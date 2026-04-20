<?php
if (!defined('ABSPATH')) exit;

function gamestore_filter_public_game_queries($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if (
        $query->is_home() ||
        $query->is_front_page() ||
        $query->is_post_type_archive('game') ||
        $query->is_tax('game_category') ||
        $query->is_search()
    ) {
        $post_type = $query->get('post_type');

        if (
            $query->is_post_type_archive('game') ||
            $query->is_tax('game_category') ||
            $post_type === 'game' ||
            (is_array($post_type) && in_array('game', $post_type))
        ) {
            $meta_query = (array) $query->get('meta_query');

            $meta_query[] = [
                'key' => '_gamestore_status',
                'value' => 'Approved',
                'compare' => '=',
            ];

            $query->set('post_status', 'publish');
            $query->set('meta_query', $meta_query);
        }
    }
}
add_action('pre_get_posts', 'gamestore_filter_public_game_queries');

function gamestore_block_unapproved_single_game() {
    if (!is_singular('game')) {
        return;
    }

    $post_id = get_queried_object_id();

    if (!$post_id) {
        return;
    }

    if (!function_exists('gamestore_can_view_game_public')) {
        return;
    }

    if (!gamestore_can_view_game_public($post_id)) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
        include get_query_template('404');
        exit;
    }
}
add_action('template_redirect', 'gamestore_block_unapproved_single_game');