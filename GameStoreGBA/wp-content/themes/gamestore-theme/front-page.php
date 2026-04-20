<?php get_header(); ?>

<section class="home-hero">
    <h2>Chào mừng đến với GameStore GBA</h2>
    <p>Khám phá và chơi các game GBA trực tiếp trên website.</p>
</section>

<section class="home-games">
    <h3>Game mới</h3>

    <div class="game-grid">
        <?php
        $games = new WP_Query([
            'post_type'      => 'game',
            'post_status'    => 'publish',
            'posts_per_page' => 8,
            'meta_query'     => [
                [
                    'key'     => '_gamestore_status',
                    'value'   => 'Approved',
                    'compare' => '='
                ]
            ]
        ]);

        if ($games->have_posts()) :
            while ($games->have_posts()) : $games->the_post();
                get_template_part('template-parts/game', 'card');
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>Chưa có game nào.</p>';
        endif;
        ?>
    </div>
</section>

<?php get_footer(); ?>