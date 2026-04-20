<?php
if (!defined('ABSPATH')) exit;

function gamestore_handle_game_moderation() {
    if (!isset($_POST['gamestore_moderate_game'])) {
        return;
    }

    if (!current_user_can('administrator')) {
        wp_die('Bạn không có quyền thực hiện thao tác này.');
    }

    if (
        !isset($_POST['gamestore_moderation_nonce']) ||
        !wp_verify_nonce($_POST['gamestore_moderation_nonce'], 'gamestore_moderation_action')
    ) {
        wp_die('Nonce không hợp lệ.');
    }

    $game_id = isset($_POST['game_id']) ? intval($_POST['game_id']) : 0;
    $action_type = isset($_POST['moderation_action']) ? sanitize_text_field($_POST['moderation_action']) : '';
    $reject_reason = isset($_POST['reject_reason']) ? sanitize_textarea_field($_POST['reject_reason']) : '';

    if (!$game_id || get_post_type($game_id) !== 'game') {
        wp_die('Game không hợp lệ.');
    }

    if ($action_type === 'approve') {
        wp_update_post([
            'ID' => $game_id,
            'post_status' => 'publish',
        ]);

        update_post_meta($game_id, '_gamestore_status', 'Approved');
        update_post_meta($game_id, '_gamestore_reject_reason', '');
    } elseif ($action_type === 'reject') {
        wp_update_post([
            'ID' => $game_id,
            'post_status' => 'draft',
        ]);

        update_post_meta($game_id, '_gamestore_status', 'Rejected');
        update_post_meta($game_id, '_gamestore_reject_reason', $reject_reason);
    }

    $dashboard_page = get_page_by_path('dashboard');
    $redirect_url = $dashboard_page ? get_permalink($dashboard_page->ID) : admin_url();

    wp_safe_redirect(add_query_arg('moderation_success', '1', $redirect_url));
    exit;
}
add_action('init', 'gamestore_handle_game_moderation');