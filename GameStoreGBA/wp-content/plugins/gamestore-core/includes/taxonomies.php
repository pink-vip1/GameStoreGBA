<?php
if (!defined('ABSPATH')) exit;

function gamestore_register_taxonomy_game_category() {
    $labels = [
        'name' => 'Game Categories',
        'singular_name' => 'Game Category',
        'search_items' => 'Search Game Categories',
        'all_items' => 'All Game Categories',
        'edit_item' => 'Edit Game Category',
        'update_item' => 'Update Game Category',
        'add_new_item' => 'Add New Game Category',
        'new_item_name' => 'New Game Category Name',
        'menu_name' => 'Game Categories',
    ];

    $args = [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => ['slug' => 'game-category'],
        'show_in_rest' => true,
    ];

    register_taxonomy('game_category', ['game'], $args);
}
add_action('init', 'gamestore_register_taxonomy_game_category');