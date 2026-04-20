<div class="game-meta">
    <p>
        <strong>Thể loại:</strong>
        <?php
        $terms = get_the_terms(get_the_ID(), 'game_category');
        if ($terms && !is_wp_error($terms)) {
            echo esc_html(implode(', ', wp_list_pluck($terms, 'name')));
        } else {
            echo 'Chưa có';
        }
        ?>
    </p>

    <p>
        <strong>Mô tả ngắn:</strong>
        <?php
        $short_description = function_exists('gamestore_get_game_short_description')
            ? gamestore_get_game_short_description(get_the_ID())
            : '';

        echo !empty($short_description) ? esc_html($short_description) : 'Chưa có';
        ?>
    </p>
</div>