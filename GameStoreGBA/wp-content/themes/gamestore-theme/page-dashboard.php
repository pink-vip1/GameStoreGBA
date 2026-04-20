<?php
/*
Template Name: Dashboard
*/
if (!defined('ABSPATH')) exit;

if (!current_user_can('administrator')) {
    wp_die('Bạn không có quyền truy cập trang này.');
}

get_header();

$pending_games = new WP_Query([
    'post_type'      => 'game',
    'post_status'    => 'pending',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
?>

<div class="container">
    <div class="page-section">
        <h1>Admin Dashboard - Game chờ duyệt</h1>

        <?php if (isset($_GET['moderation_success']) && $_GET['moderation_success'] === '1'): ?>
            <p>Đã xử lý game thành công.</p>
        <?php endif; ?>

        <?php if ($pending_games->have_posts()): ?>
            <div class="game-grid">
                <?php while ($pending_games->have_posts()): $pending_games->the_post(); ?>
                    <article class="game-card">
                        <?php if (has_post_thumbnail()): ?>
                            <div class="game-card-thumb">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>

                        <h3 class="game-card-title"><?php the_title(); ?></h3>

                        <p><strong>Developer:</strong> <?php echo esc_html(get_the_author()); ?></p>
                        <p><?php echo esc_html(gamestore_get_game_short_description(get_the_ID())); ?></p>

                        <p>
                            <a class="btn" href="<?php the_permalink(); ?>">Xem chi tiết</a>
                        </p>

                        <form method="post" class="moderation-form" style="margin-bottom: 12px;">
                            <?php wp_nonce_field('gamestore_moderation_action', 'gamestore_moderation_nonce'); ?>
                            <input type="hidden" name="game_id" value="<?php echo esc_attr(get_the_ID()); ?>">
                            <input type="hidden" name="moderation_action" value="approve">
                            <button type="submit" name="gamestore_moderate_game" value="1" class="btn">
                                Approve
                            </button>
                        </form>

                        <form method="post" class="moderation-form">
                            <?php wp_nonce_field('gamestore_moderation_action', 'gamestore_moderation_nonce'); ?>
                            <input type="hidden" name="game_id" value="<?php echo esc_attr(get_the_ID()); ?>">
                            <input type="hidden" name="moderation_action" value="reject">

                            <p>
                                <textarea name="reject_reason" rows="3" placeholder="Lý do từ chối"></textarea>
                            </p>

                            <button type="submit" name="gamestore_moderate_game" value="1" class="btn">
                                Reject
                            </button>
                        </form>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <p>Không có game nào đang chờ duyệt.</p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>