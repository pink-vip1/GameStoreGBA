<?php if (!defined('ABSPATH')) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header">
    <div class="container header-inner">

        <h1 class="site-title">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <?php bloginfo('name'); ?>
            </a>
        </h1>

        <nav class="site-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary_menu',
                'container' => false,
                'fallback_cb' => false
            ]);
            ?>
        </nav>

        <div class="header-search">
            <?php get_template_part('template-parts/game-search-form'); ?>
        </div>

        <?php
        $submit_page = get_page_by_path('submit-game');
        $my_games_page = get_page_by_path('my-games');
        $dashboard_page = get_page_by_path('dashboard');
        ?>

        <div class="auth-links">
            <?php if (is_user_logged_in()): ?>
                <?php $current_user = wp_get_current_user(); ?>

                <span class="user-name">
                    👤 <?php echo esc_html($current_user->user_login); ?>
                </span>
                    <a href="<?php echo esc_url(admin_url('profile.php')); ?>">
                        Profile
                    </a>

                <?php if ($submit_page): ?>
                    <a href="<?php echo esc_url(get_permalink($submit_page->ID)); ?>">
                        Submit
                    </a>
                <?php endif; ?>

                <?php if ($my_games_page): ?>
                    <a href="<?php echo esc_url(get_permalink($my_games_page->ID)); ?>">
                        My Games
                    </a>
                <?php endif; ?>

                <?php if (in_array('administrator', (array) $current_user->roles) && $dashboard_page): ?>
                    <a href="<?php echo esc_url(get_permalink($dashboard_page->ID)); ?>">
                        Dashboard
                    </a>
                <?php endif; ?>

                <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>">
                    Logout
                </a>

            <?php else: ?>

                <a href="<?php echo esc_url(wp_login_url($my_games_page ? get_permalink($my_games_page->ID) : home_url('/'))); ?>">
                    Login
                </a>

                <a href="<?php echo esc_url(wp_registration_url()); ?>">
                    Register
                </a>

            <?php endif; ?>
        </div>

    </div>
</header>

<main class="site-main container">