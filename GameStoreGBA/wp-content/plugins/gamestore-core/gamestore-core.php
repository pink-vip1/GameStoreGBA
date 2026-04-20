<?php
/**
 * Plugin Name: GameStore Core
 * Description: Plugin xử lý logic chính cho GameStore GBA
 * Version: 1.0
 * Author: Phuc Ha
 */

if (!defined('ABSPATH')) exit;

define('GAMESTORE_CORE_PATH', plugin_dir_path(__FILE__));
define('GAMESTORE_CORE_URL', plugin_dir_url(__FILE__));

require_once GAMESTORE_CORE_PATH . 'includes/post-types.php';
require_once GAMESTORE_CORE_PATH . 'includes/taxonomies.php';
require_once GAMESTORE_CORE_PATH . 'includes/roles.php';
require_once GAMESTORE_CORE_PATH . 'includes/meta-fields.php';
require_once GAMESTORE_CORE_PATH . 'includes/submit-handler.php';
require_once GAMESTORE_CORE_PATH . 'includes/moderation.php';
require_once GAMESTORE_CORE_PATH . 'includes/query-filters.php';
require_once GAMESTORE_CORE_PATH . 'includes/shortcodes.php';
require_once GAMESTORE_CORE_PATH . 'includes/play-render.php';
require_once GAMESTORE_CORE_PATH . 'includes/helpers.php';

function gamestore_core_assets() {
    wp_enqueue_style(
        'gamestore-core',
        GAMESTORE_CORE_URL . 'assets/css/core.css',
        [],
        '1.0'
    );

    wp_enqueue_script(
        'gamestore-core',
        GAMESTORE_CORE_URL . 'assets/js/core.js',
        [],
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'gamestore_core_assets');

function gamestore_core_activate() {
    gamestore_register_post_type_game();
    gamestore_register_taxonomy_game_category();
    gamestore_add_roles();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'gamestore_core_activate');

function gamestore_core_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'gamestore_core_deactivate');