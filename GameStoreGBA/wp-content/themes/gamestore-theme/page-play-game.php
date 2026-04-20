<?php
/*
Template Name: Play Game
*/
if (!defined('ABSPATH')) exit;

$game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : 0;

if (!$game_id || get_post_type($game_id) !== 'game') {
    wp_die('Game không hợp lệ.');
}

if (!function_exists('gamestore_is_game_approved') || !gamestore_is_game_approved($game_id)) {
    wp_die('Game chưa được duyệt để chơi.');
}

if (!function_exists('gamestore_get_game_rom_path')) {
    wp_die('Không tìm thấy hàm lấy ROM path.');
}

$rom_path = gamestore_get_game_rom_path($game_id);

if (empty($rom_path)) {
    wp_die('Game chưa có ROM path.');
}

$rom_file = basename($rom_path);

if (empty($rom_file)) {
    wp_die('ROM path không hợp lệ.');
}

$game_title = get_the_title($game_id);

$emulator_base = defined('GAMESTORE_CORE_URL')
    ? GAMESTORE_CORE_URL . 'assets/emulatorjs/'
    : '';

if (empty($emulator_base)) {
    wp_die('Không tìm thấy đường dẫn EmulatorJS.');
}

$iframe_src = $emulator_base . 'index.html?rom=' . rawurlencode($rom_file);

get_header();
?>

<div class="container">
    <div class="page-section">
        <h1>Chơi game: <?php echo esc_html($game_title); ?></h1>

        <div class="game-player-box">
            <iframe
                src="<?php echo esc_url($iframe_src); ?>"
                width="100%"
                height="700"
                style="border:none;border-radius:12px;background:#000;"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</div>

<?php get_footer(); ?>