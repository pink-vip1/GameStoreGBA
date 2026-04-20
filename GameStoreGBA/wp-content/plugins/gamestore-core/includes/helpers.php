<?php
if (!defined('ABSPATH')) exit;

function gamestore_get_game_status($post_id = 0) {
    $post_id = $post_id ? $post_id : get_the_ID();

    $status = get_post_meta($post_id, '_gamestore_status', true);

    if (!empty($status)) {
        return $status;
    }

    $post_status = get_post_status($post_id);

    if ($post_status === 'publish') {
        return 'Approved';
    }

    if ($post_status === 'pending') {
        return 'Pending';
    }

    return 'Rejected';
}

function gamestore_is_game_approved($post_id = 0) {
    return gamestore_get_game_status($post_id) === 'Approved';
}

function gamestore_get_game_rom_path($post_id = 0) {
    $post_id = $post_id ? $post_id : get_the_ID();
    return get_post_meta($post_id, '_gamestore_rom_path', true);
}

function gamestore_get_game_reject_reason($post_id = 0) {
    $post_id = $post_id ? $post_id : get_the_ID();
    return get_post_meta($post_id, '_gamestore_reject_reason', true);
}

function gamestore_get_game_short_description($post_id = 0) {
    $post_id = $post_id ? $post_id : get_the_ID();
    return get_post_meta($post_id, '_gamestore_short_description', true);
}

function gamestore_get_play_page_url($game_id = 0) {
    $game_id = $game_id ? $game_id : get_the_ID();

    $play_page = get_page_by_path('play-game');
    if (!$play_page) {
        return '';
    }

    return add_query_arg('game_id', $game_id, get_permalink($play_page->ID));
}

function gamestore_can_view_game_public($post_id = 0) {
    $post_id = $post_id ? $post_id : get_the_ID();

    if (gamestore_is_game_approved($post_id)) {
        return true;
    }

    if (current_user_can('administrator')) {
        return true;
    }

    if (is_user_logged_in() && (int) get_post_field('post_author', $post_id) === get_current_user_id()) {
        return true;
    }

    return false;
}