<?php
if (!defined('ABSPATH')) exit;

function gamestore_register_post_type_game() {
    $labels = [
        'name' => 'Games',
        'singular_name' => 'Game',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Game',
        'edit_item' => 'Edit Game',
        'new_item' => 'New Game',
        'view_item' => 'View Game',
        'search_items' => 'Search Games',
        'not_found' => 'No games found',
        'menu_name' => 'Games',
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'games'],
        'supports' => ['title', 'editor', 'thumbnail', 'comments'],
        'menu_icon' => 'dashicons-games',
        'show_in_rest' => true,
    ];

    register_post_type('game', $args);
}
add_action('init', 'gamestore_register_post_type_game');