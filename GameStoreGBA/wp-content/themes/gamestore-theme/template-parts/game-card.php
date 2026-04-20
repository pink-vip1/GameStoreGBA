<article class="game-card">
    <a href="<?php the_permalink(); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <div class="game-card-thumb">
                <?php the_post_thumbnail('medium'); ?>
            </div>
        <?php endif; ?>

        <h3 class="game-card-title"><?php the_title(); ?></h3>
    </a>
</article>