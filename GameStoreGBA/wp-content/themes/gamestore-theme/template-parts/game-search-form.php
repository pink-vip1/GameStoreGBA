<form method="get" action="<?php echo esc_url(get_post_type_archive_link('game')); ?>" class="game-search-form">
    <input type="text" name="s" placeholder="Tìm game..." value="<?php echo get_search_query(); ?>">
    <input type="hidden" name="post_type" value="game">

    <?php
    $terms = get_terms([
        'taxonomy' => 'game_category',
        'hide_empty' => false
    ]);
    ?>

    <select name="game_category">
        <option value="">Tất cả thể loại</option>
        <?php foreach ($terms as $term) : ?>
            <option value="<?php echo esc_attr($term->slug); ?>">
                <?php echo esc_html($term->name); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Tìm</button>
</form>