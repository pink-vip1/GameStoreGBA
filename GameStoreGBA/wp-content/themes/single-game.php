<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article class="single-game">
        <h2><?php the_title(); ?></h2>

        <?php if (has_post_thumbnail()) : ?>
            <div class="single-game-thumb">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php endif; ?>

        <div class="single-game-content">
            <?php the_content(); ?>
        </div>

        <?php get_template_part('template-parts/game', 'meta'); ?>

        <div class="single-game-actions">
            <a class="btn" href="<?php echo esc_url(home_url('/play')); ?>">Chơi game</a>
        </div>
    </article>
<?php endwhile; endif; ?>

<?php get_footer(); ?>