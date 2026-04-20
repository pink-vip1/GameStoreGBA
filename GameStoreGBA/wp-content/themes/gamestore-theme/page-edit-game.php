<?php
/*
Template Name: Edit Game
*/
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

$game_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$game_id || get_post_type($game_id) !== 'game') {
    wp_die('Game không hợp lệ');
}

$current_user = wp_get_current_user();
$is_owner = (int) get_post_field('post_author', $game_id) === get_current_user_id();
$is_admin = current_user_can('administrator');

if (!$is_owner && !$is_admin) {
    wp_die('Bạn không có quyền sửa game này');
}

$title = get_the_title($game_id);
$content = get_post_field('post_content', $game_id);
$short_desc = get_post_meta($game_id, '_gamestore_short_description', true);
$current_category_ids = wp_get_object_terms($game_id, 'game_category', ['fields' => 'ids']);

$categories = get_terms([
    'taxonomy'   => 'game_category',
    'hide_empty' => false,
]);

$current_rom = get_post_meta($game_id, '_gamestore_rom_path', true);

get_header();
?>

<div class="container">
    <div class="page-section">
        <h1>Sửa game</h1>

        <?php if (isset($_GET['update_success']) && $_GET['update_success'] === '1'): ?>
            <p>Cập nhật game thành công.</p>
        <?php endif; ?>

        <?php if (isset($_GET['update_error']) && $_GET['update_error'] === 'invalid_rom'): ?>
            <p>Chỉ cho phép upload ROM có đuôi .gba</p>
        <?php endif; ?>

        <?php if (isset($_GET['update_error']) && $_GET['update_error'] === 'rom_upload_failed'): ?>
            <p>Upload ROM thất bại.</p>
        <?php endif; ?>

        <?php if (isset($_GET['update_error']) && $_GET['update_error'] === 'invalid_cover'): ?>
            <p>Upload ảnh bìa thất bại.</p>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="gamestore-form">
            <?php wp_nonce_field('gamestore_edit_game', 'gamestore_edit_nonce'); ?>

            <input type="hidden" name="game_id" value="<?php echo esc_attr($game_id); ?>">

            <p>
                <label for="game_title">Tên game</label><br>
                <input type="text" name="game_title" id="game_title" value="<?php echo esc_attr($title); ?>" required>
            </p>

            <p>
                <label for="game_short_description">Mô tả ngắn</label><br>
                <textarea name="game_short_description" id="game_short_description" rows="3"><?php echo esc_textarea($short_desc); ?></textarea>
            </p>

            <p>
                <label for="game_description">Mô tả chi tiết</label><br>
                <textarea name="game_description" id="game_description" rows="8" required><?php echo esc_textarea($content); ?></textarea>
            </p>

            <p>
                <label for="game_category">Thể loại</label><br>
                <select name="game_category" id="game_category">
                    <option value="">-- Chọn thể loại --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo esc_attr($category->term_id); ?>"
                            <?php selected(in_array($category->term_id, $current_category_ids)); ?>>
                            <?php echo esc_html($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>

            <p>
                <strong>ROM hiện tại:</strong>
                <?php echo !empty($current_rom) ? esc_html($current_rom) : 'Chưa có'; ?>
            </p>

            <p>
                // Các định dạng ROM khả dụng
                <label for="game_rom_file">Thay ROM mới (.gba, .gbc, .gb)</label><br>
                <input type="file" name="game_rom_file" id="game_rom_file" accept=".gba,.gbc,.gb">
            </p>

            <?php if (has_post_thumbnail($game_id)): ?>
                <p><strong>Ảnh bìa hiện tại:</strong></p>
                <p><?php echo get_the_post_thumbnail($game_id, 'medium'); ?></p>
            <?php endif; ?>

            <p>
                <label for="game_cover">Thay ảnh bìa mới</label><br>
                <input type="file" name="game_cover" id="game_cover" accept="image/*">
            </p>

            <p>
                <button type="submit" name="gamestore_update_game" value="1">Cập nhật</button>
            </p>
        </form>
    </div>
</div>

<?php get_footer(); ?>