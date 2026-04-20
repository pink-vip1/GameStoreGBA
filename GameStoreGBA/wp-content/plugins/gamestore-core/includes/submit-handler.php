<?php
if (!defined('ABSPATH')) exit;

function gamestore_handle_frontend_submit_game() {
    if (!isset($_POST['gamestore_submit_game'])) {
        return;
    }

    if (!is_user_logged_in()) {
        wp_die('Bạn cần đăng nhập để gửi game.');
    }

    if (
        !isset($_POST['gamestore_submit_nonce']) ||
        !wp_verify_nonce($_POST['gamestore_submit_nonce'], 'gamestore_submit_game_action')
    ) {
        wp_die('Nonce không hợp lệ.');
    }

    $current_user = wp_get_current_user();

    if (
        !in_array('game_developer', (array) $current_user->roles) &&
        !in_array('administrator', (array) $current_user->roles)
    ) {
        wp_die('Bạn không có quyền gửi game.');
    }

    $title = isset($_POST['game_title']) ? sanitize_text_field($_POST['game_title']) : '';
    $content = isset($_POST['game_description']) ? wp_kses_post($_POST['game_description']) : '';
    $short_description = isset($_POST['game_short_description']) ? sanitize_textarea_field($_POST['game_short_description']) : '';
    $category_id = isset($_POST['game_category']) ? intval($_POST['game_category']) : 0;

    if (empty($title) || empty($content)) {
        wp_safe_redirect(add_query_arg('submit_error', 'empty_required', wp_get_referer()));
        exit;
    }

    if (empty($_FILES['game_rom_file']['name'])) {
        wp_safe_redirect(add_query_arg('submit_error', 'invalid_rom', wp_get_referer()));
        exit;
    }

    $rom_file = $_FILES['game_rom_file'];
    $rom_ext = strtolower(pathinfo($rom_file['name'], PATHINFO_EXTENSION));

    if ($rom_ext !== 'gba') {
        wp_safe_redirect(add_query_arg('submit_error', 'invalid_rom', wp_get_referer()));
        exit;
    }

    $post_data = [
        'post_type'    => 'game',
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => 'pending',
        'post_author'  => get_current_user_id(),
    ];

    $post_id = wp_insert_post($post_data, true);

    if (is_wp_error($post_id)) {
        wp_safe_redirect(add_query_arg('submit_error', 'insert_failed', wp_get_referer()));
        exit;
    }

    update_post_meta($post_id, '_gamestore_short_description', $short_description);
    update_post_meta($post_id, '_gamestore_status', 'Pending');
    update_post_meta($post_id, '_gamestore_reject_reason', '');

    if ($category_id > 0) {
        wp_set_object_terms($post_id, [$category_id], 'game_category');
    }

    $roms_dir = GAMESTORE_CORE_PATH . 'assets/emulatorjs/roms/';

    if (!file_exists($roms_dir)) {
        wp_mkdir_p($roms_dir);
    }

    $safe_rom_filename = time() . '_' . sanitize_file_name($rom_file['name']);
    $rom_target_path = $roms_dir . $safe_rom_filename;

    if (!move_uploaded_file($rom_file['tmp_name'], $rom_target_path)) {
        wp_delete_post($post_id, true);
        wp_safe_redirect(add_query_arg('submit_error', 'rom_upload_failed', wp_get_referer()));
        exit;
    }

    update_post_meta($post_id, '_gamestore_rom_path', $safe_rom_filename);

    if (!empty($_FILES['game_cover']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $attachment_id = media_handle_upload('game_cover', $post_id);

        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($post_id, $attachment_id);
        }
    }

    $my_games_page = get_page_by_path('my-games');
    $redirect_url = $my_games_page
        ? add_query_arg('submit_success', '1', get_permalink($my_games_page->ID))
        : home_url('/');

    wp_safe_redirect($redirect_url);
    exit;
}
add_action('init', 'gamestore_handle_frontend_submit_game');
// xử lý form edit game
function gamestore_handle_update_game() {
    if (!isset($_POST['gamestore_update_game'])) {
        return;
    }

    if (
        !isset($_POST['gamestore_edit_nonce']) ||
        !wp_verify_nonce($_POST['gamestore_edit_nonce'], 'gamestore_edit_game')
    ) {
        wp_die('Nonce không hợp lệ');
    }

    if (!is_user_logged_in()) {
        wp_die('Bạn cần đăng nhập.');
    }

    $game_id = isset($_POST['game_id']) ? intval($_POST['game_id']) : 0;

    if (!$game_id || get_post_type($game_id) !== 'game') {
        wp_die('Game không hợp lệ');
    }

    $is_owner = (int) get_post_field('post_author', $game_id) === get_current_user_id();
    $is_admin = current_user_can('administrator');

    if (!$is_owner && !$is_admin) {
        wp_die('Bạn không có quyền sửa game này');
    }

    $title = isset($_POST['game_title']) ? sanitize_text_field($_POST['game_title']) : '';
    $content = isset($_POST['game_description']) ? wp_kses_post($_POST['game_description']) : '';
    $short_desc = isset($_POST['game_short_description']) ? sanitize_textarea_field($_POST['game_short_description']) : '';
    $category_id = isset($_POST['game_category']) ? intval($_POST['game_category']) : 0;

    if (empty($title) || empty($content)) {
        wp_die('Vui lòng nhập đầy đủ tên game và mô tả.');
    }

    wp_update_post([
        'ID'           => $game_id,
        'post_title'   => $title,
        'post_content' => $content,
    ]);

    update_post_meta($game_id, '_gamestore_short_description', $short_desc);

    if ($category_id > 0) {
        wp_set_object_terms($game_id, [$category_id], 'game_category');
    }

    // Thay ROM mới nếu có
    if (!empty($_FILES['game_rom_file']['name'])) {
        $rom_file = $_FILES['game_rom_file'];
        $rom_ext = strtolower(pathinfo($rom_file['name'], PATHINFO_EXTENSION));
        //Các định dạng ROM khả dụng
        $allowed_exts = ['gba', 'gb', 'gbc'];

        if (!in_array($rom_ext, $allowed_exts)) {
            $edit_page = get_page_by_path('edit-game');
            $redirect_url = $edit_page
                ? add_query_arg(['id' => $game_id, 'update_error' => 'invalid_rom'], get_permalink($edit_page->ID))
                : wp_get_referer();
            wp_safe_redirect($redirect_url);
            exit;
        }

        $roms_dir = GAMESTORE_CORE_PATH . 'assets/emulatorjs/roms/';

        if (!file_exists($roms_dir)) {
            wp_mkdir_p($roms_dir);
        }

        $old_rom = get_post_meta($game_id, '_gamestore_rom_path', true);
        $safe_rom_filename = time() . '_' . sanitize_file_name($rom_file['name']);
        $rom_target_path = $roms_dir . $safe_rom_filename;

        if (!move_uploaded_file($rom_file['tmp_name'], $rom_target_path)) {
            $edit_page = get_page_by_path('edit-game');
            $redirect_url = $edit_page
                ? add_query_arg(['id' => $game_id, 'update_error' => 'rom_upload_failed'], get_permalink($edit_page->ID))
                : wp_get_referer();
            wp_safe_redirect($redirect_url);
            exit;
        }

        update_post_meta($game_id, '_gamestore_rom_path', $safe_rom_filename);

        // Xóa ROM cũ nếu có và nếu file tồn tại
        if (!empty($old_rom)) {
            $old_rom_path = $roms_dir . basename($old_rom);
            if (file_exists($old_rom_path) && is_file($old_rom_path)) {
                @unlink($old_rom_path);
            }
        }
    }

    // Thay ảnh bìa mới nếu có
    if (!empty($_FILES['game_cover']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $attachment_id = media_handle_upload('game_cover', $game_id);

        if (is_wp_error($attachment_id)) {
            $edit_page = get_page_by_path('edit-game');
            $redirect_url = $edit_page
                ? add_query_arg(['id' => $game_id, 'update_error' => 'invalid_cover'], get_permalink($edit_page->ID))
                : wp_get_referer();
            wp_safe_redirect($redirect_url);
            exit;
        }

        set_post_thumbnail($game_id, $attachment_id);
    }

    // Nếu developer sửa thì quay lại Pending
    if (!$is_admin) {
        wp_update_post([
            'ID'          => $game_id,
            'post_status' => 'pending',
        ]);

        update_post_meta($game_id, '_gamestore_status', 'Pending');
        update_post_meta($game_id, '_gamestore_reject_reason', '');
    }

    $edit_page = get_page_by_path('edit-game');
    $redirect_url = $edit_page
        ? add_query_arg(['id' => $game_id, 'update_success' => '1'], get_permalink($edit_page->ID))
        : get_permalink($game_id);

    wp_safe_redirect($redirect_url);
    exit;
}
add_action('init', 'gamestore_handle_update_game');