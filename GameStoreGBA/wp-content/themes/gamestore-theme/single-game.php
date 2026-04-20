<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php
    $game_id = get_the_ID();
    $status = function_exists('gamestore_get_game_status') ? gamestore_get_game_status($game_id) : 'Pending';
    $reject_reason = function_exists('gamestore_get_game_reject_reason') ? gamestore_get_game_reject_reason($game_id) : '';
    $play_url = function_exists('gamestore_get_play_page_url') ? gamestore_get_play_page_url($game_id) : '';

    $is_admin = current_user_can('administrator');
    $is_owner = is_user_logged_in() && ((int) get_post_field('post_author', $game_id) === get_current_user_id());

    $edit_page = get_page_by_path('edit-game');
    $edit_url = $edit_page ? add_query_arg('id', $game_id, get_permalink($edit_page->ID)) : '';
    ?>

    <article class="single-game">
        <div class="game-detail">
            <div class="game-left">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="single-game-thumb">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="game-right">
                <h1><?php the_title(); ?></h1>

                <?php get_template_part('template-parts/game', 'meta'); ?>

                <p><strong>Trạng thái:</strong> <?php echo esc_html($status); ?></p>

                <?php if (!empty($reject_reason) && ($is_admin || $is_owner)): ?>
                    <p><strong>Lý do từ chối:</strong> <?php echo esc_html($reject_reason); ?></p>
                <?php endif; ?>

                <div class="game-desc">
                    <?php the_content(); ?>
                </div>

                <div class="single-game-actions" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:20px;">
                    <?php if ($status === 'Approved' && !empty($play_url)): ?>
                        <a class="btn-play" href="<?php echo esc_url($play_url); ?>">Chơi game</a>
                    <?php else: ?>
                        <span class="btn-play" style="opacity:0.6; pointer-events:none;">Chưa thể chơi</span>
                    <?php endif; ?>

                    <?php if (($is_owner || $is_admin) && !empty($edit_url)): ?>
                        <a class="btn" href="<?php echo esc_url($edit_url); ?>">Sửa game</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="single-game-comments" style="margin-top: 32px;">
            <?php comments_template(); ?>
        </div>
    </article>
<?php endwhile; endif; ?>

<?php get_footer(); ?>