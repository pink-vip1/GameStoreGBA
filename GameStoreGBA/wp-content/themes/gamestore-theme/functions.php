<?php
if (!defined('ABSPATH')) exit;

function gamestore_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    register_nav_menus([
        'primary_menu' => 'Primary Menu'
    ]);
}
add_action('after_setup_theme', 'gamestore_theme_setup');

function gamestore_theme_assets() {
    wp_enqueue_style(
        'gamestore-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        '1.0'
    );

    wp_enqueue_script(
        'gamestore-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'gamestore_theme_assets');
add_action('admin_head-profile.php', function () {
    echo '<style>
        body.wp-admin {
            background: #111 !important;
            color: #fff !important;
        }

        .wrap {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 12px;
        }

        /* TEXT */
        .wrap h1,
        .wrap h2,
        .wrap h3,
        .wrap label,
        .wrap p,
        .wrap td,
        .wrap th {
            color: #fff !important;
        }

        /* LINK */
        a {
            color: #fdfcfc !important;
        }

        /* INPUT */
        input, textarea, select {
            background: #222 !important;
            color: #fff !important;
            border: 1px solid #444 !important;
        }

        /* PLACEHOLDER */
        ::placeholder {
            color: #aaa !important;
        }

        /* BUTTON */
        .button, .button-primary {
            background: #141313 !important;
            border-color: #ff5a5f !important;
            color: #fff !important;
        }

        .button:hover {
            background: #e0484d !important;
        }

        /* TABLE BORDER */
        .form-table th {
            color: #ccc !important;
        }

        /* CHECKBOX */
        input[type="checkbox"] {
            accent-color: #ff5a5f;
        }

    </style>';
});
// Custom login page styles
add_action('login_enqueue_scripts', function () {
    ?>
    <style>
        body.login {
            background: #0f0f0f;
        }

        .login #login {
            width: 420px;
        }

        .login form {
            background: #f3f3f3;
            border-radius: 18px;
            border: none;
            box-shadow: none;
            padding: 30px;
        }

        .login label {
            color: #111 !important;
            font-size: 16px;
        }

        .login input[type="text"],
        .login input[type="password"] {
            background: #dfe8f6;
            border: 1px solid #666;
            border-radius: 6px;
            min-height: 48px;
            font-size: 18px;
        }

        .login .forgetmenot {
            float: left;
            margin-top: 12px;
        }

        .login .forgetmenot label {
            color: #111 !important;
            font-size: 15px !important;
            display: inline-block !important;
            opacity: 1 !important;
            visibility: visible !important;
            margin-left: 6px;
        }

        .login .button-primary {
            background: #2f74b5 !important;
            border-color: #2f74b5 !important;
            border-radius: 6px;
            padding: 6px 18px;
            box-shadow: none !important;
        }
    </style>
    <?php
});
// Custom login page URL and title
add_filter('login_headerurl', function () {
    return home_url('/');
});

add_filter('login_headertext', function () {
    return get_bloginfo('name');
});
// Custom login page logo
add_action('login_enqueue_scripts', function () {
    ?>
    <style>
        .login h1 a {
            background-image: url('http://localhost/GameStoreGBA/wp-content/uploads/logo.png') !important;
            background-size: contain !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            width: 220px !important;
            height: 90px !important;
        }
    </style>
    <?php
});
// Redirect to homepage after login
add_filter('login_redirect', function ($redirect_to, $request, $user) {
    return home_url('/');
}, 10, 3);