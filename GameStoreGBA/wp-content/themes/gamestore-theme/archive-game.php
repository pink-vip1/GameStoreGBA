<?php get_header(); ?>

<section class="archive-header">
    <h2>Danh sách game</h2>
    <?php get_template_part('template-parts/game', 'search-form'); ?>
</section>

<div class="game-grid">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/game', 'card'); ?>
        <?php endwhile; ?>
    <?php else : ?>
        <p>Không có game nào.</p>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php the_posts_pagination(); ?>
</div>

<?php get_footer(); ?>