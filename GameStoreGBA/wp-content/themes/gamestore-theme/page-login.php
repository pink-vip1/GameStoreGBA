<?php
/*
Template Name: Login
*/
if (!defined('ABSPATH')) exit;

if (is_user_logged_in()) {
    wp_redirect(home_url('/my-games'));
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gamestore_login'])) {
    if (!isset($_POST['gamestore_login_nonce']) || !wp_verify_nonce($_POST['gamestore_login_nonce'], 'gamestore_login_action')) {
        $error_message = 'Nonce không hợp lệ.';
    } else {
        $creds = [
            'user_login'    => sanitize_text_field($_POST['username']),
            'user_password' => $_POST['password'],
            'remember'      => !empty($_POST['remember_me']),
        ];

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            $error_message = 'Sai tài khoản hoặc mật khẩu.';
        } else {
            wp_redirect(home_url('/my-games'));
            exit;
        }
    }
}

get_header();
?>

<div class="container">
    <div class="page-section">
        <h1>Đăng nhập</h1>

        <?php if (!empty($error_message)): ?>
            <p><?php echo esc_html($error_message); ?></p>
        <?php endif; ?>

        <form method="post" class="gamestore-form">
            <?php wp_nonce_field('gamestore_login_action', 'gamestore_login_nonce'); ?>

            <p>
                <label for="username">Tên đăng nhập hoặc email</label><br>
                <input type="text" name="username" id="username" required>
            </p>

            <p>
                <label for="password">Mật khẩu</label><br>
                <input type="password" name="password" id="password" required>
            </p>

            <p>
                <label>
                    <input type="checkbox" name="remember_me" value="1">
                    Ghi nhớ đăng nhập
                </label>
            </p>

            <p>
                <button type="submit" name="gamestore_login" value="1">Đăng nhập</button>
            </p>
        </form>

        <p>Chưa có tài khoản? <a href="<?php echo esc_url(home_url('/register')); ?>">Đăng ký</a></p>
    </div>
</div>

<?php get_footer(); ?>