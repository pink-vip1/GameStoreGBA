<?php
/*
Template Name: Register
*/
if (!defined('ABSPATH')) exit;

if (is_user_logged_in()) {
    wp_redirect(home_url('/my-games'));
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gamestore_register'])) {
    if (!isset($_POST['gamestore_register_nonce']) || !wp_verify_nonce($_POST['gamestore_register_nonce'], 'gamestore_register_action')) {
        $error_message = 'Nonce không hợp lệ.';
    } else {
        $username = isset($_POST['username']) ? sanitize_user($_POST['username']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $error_message = 'Vui lòng nhập đầy đủ thông tin.';
        } elseif (!is_email($email)) {
            $error_message = 'Email không hợp lệ.';
        } elseif ($password !== $confirm_password) {
            $error_message = 'Mật khẩu xác nhận không khớp.';
        } elseif (username_exists($username)) {
            $error_message = 'Tên đăng nhập đã tồn tại.';
        } elseif (email_exists($email)) {
            $error_message = 'Email đã tồn tại.';
        } else {
            $user_id = wp_create_user($username, $password, $email);

            if (is_wp_error($user_id)) {
                $error_message = $user_id->get_error_message();
            } else {
                $user = new WP_User($user_id);
                $user->set_role('game_developer');

                $success_message = 'Đăng ký thành công. Bạn có thể đăng nhập ngay.';
            }
        }
    }
}

get_header();
?>

<div class="container">
    <div class="page-section">
        <h1>Đăng ký tài khoản Developer</h1>

        <?php if (!empty($error_message)): ?>
            <p><?php echo esc_html($error_message); ?></p>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <p><?php echo esc_html($success_message); ?></p>
            <p><a href="<?php echo esc_url(home_url('/login')); ?>">Đi tới đăng nhập</a></p>
        <?php else: ?>
            <form method="post" class="gamestore-form">
                <?php wp_nonce_field('gamestore_register_action', 'gamestore_register_nonce'); ?>

                <p>
                    <label for="username">Tên đăng nhập</label><br>
                    <input type="text" name="username" id="username" required>
                </p>

                <p>
                    <label for="email">Email</label><br>
                    <input type="email" name="email" id="email" required>
                </p>

                <p>
                    <label for="password">Mật khẩu</label><br>
                    <input type="password" name="password" id="password" required>
                </p>

                <p>
                    <label for="confirm_password">Xác nhận mật khẩu</label><br>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </p>

                <p>
                    <button type="submit" name="gamestore_register" value="1">Đăng ký</button>
                </p>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>