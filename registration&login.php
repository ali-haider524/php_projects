<?php
/*
Plugin Name: TrueBid AJAX Registration & Login
Description: Custom AJAX-based registration and login for TrueBid.
Version: 1.3
Author: Noble Contractors Inc.
*/

// Helper: Start session if not started
function truebid_start_session_if_needed() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('truebid-auth-style', plugin_dir_url(__FILE__) . 'assets/style.css');
    wp_enqueue_script('truebid-auth-script', plugin_dir_url(__FILE__) . 'assets/script.js', ['jquery'], null, true);
    wp_localize_script('truebid-auth-script', 'truebid_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'redirect_url' => home_url('/account')
    ]);
});

// Save referrer when visiting /account
add_action('template_redirect', function() {
    if (is_page('account') && !is_user_logged_in()) {
        truebid_start_session_if_needed();

        $ref = $_SERVER['HTTP_REFERER'] ?? '';
        $parsed = wp_parse_url($ref);
        error_log('HTTP REFERER: ' . $ref);
        error_log('PARSED PATH: ' . ($parsed['path'] ?? 'not set'));

        if (!isset($_SESSION['truebid_redirect_back']) && !empty($parsed['host']) && $parsed['host'] === $_SERVER['HTTP_HOST']) {
            $path = $parsed['path'] ?? '';
            if ($path !== '/account' && $path !== '/register') {
                $_SESSION['truebid_redirect_back'] = home_url($path); // Store full URL
            }
            error_log('REFERRER STORED IN SESSION: ' . $_SESSION['truebid_redirect_back']);
        }

        // Do NOT close the session here — login may need it
    }
});

// Shortcodes
add_shortcode('truebid_register_form', 'truebid_register_form_shortcode');
add_shortcode('truebid_login_form', 'truebid_login_form_shortcode');

// Register form HTML
function truebid_register_form_shortcode() {
    if (is_user_logged_in()) return '<p>You are already logged in.</p>';
    ob_start(); ?>
    <form id="truebid-register-form" class="truebid-form">
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="phone" placeholder="Phone Number">
        <button type="submit">Register</button>
        <p class="truebid-message"></p>
    </form>
    <?php echo '';
    echo '<div class="truebid-helper">Already registered? <a href="/account">Log in here</a></div>';
    return ob_get_clean();
}

// Login form HTML
function truebid_login_form_shortcode() {
    if (is_user_logged_in()) return '<p>You are already logged in.</p>';
    ob_start(); ?>
    <form id="truebid-login-form" class="truebid-form">
        <input type="text" name="username" placeholder="Username or Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Log In</button>
        <p class="truebid-message"></p>
    </form>
    <?php echo '<div class="truebid-helper">Don\'t have an Account? <a href="/register">Register Now</a></div>';

    if (!is_user_logged_in() && isset($_GET['login']) && $_GET['login'] === 'failed') {
        echo '<div class="truebid-error" style="color: red; margin-top: 10px;">❌ Invalid login. Please check your credentials.</div>';
    }
    return ob_get_clean();
}

// Handle registration
add_action('wp_ajax_nopriv_truebid_register_user', 'truebid_register_user');
function truebid_register_user() {
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $fullname = sanitize_text_field($_POST['fullname']);
    $phone = sanitize_text_field($_POST['phone']);
    if (!is_email($email) || email_exists($email)) wp_send_json_error('Invalid or already used email.');
    $username = current(explode('@', $email));
    $i = 1; while (username_exists($username)) $username = $username . $i++;
    $user_id = wp_create_user($username, $password, $email);
    if (is_wp_error($user_id)) wp_send_json_error('Registration failed.');
    wp_update_user(['ID' => $user_id, 'display_name' => $fullname]);
    update_user_meta($user_id, 'phone_number', $phone);
    wp_send_json_success('Registration successful. You can now log in.');
}

// Handle login
add_action('wp_ajax_nopriv_truebid_login_user', 'truebid_login_user');
function truebid_login_user() {
    truebid_start_session_if_needed();

    $creds = [
        'user_login' => $_POST['username'],
        'user_password' => $_POST['password'],
        'remember' => true
    ];
    $user = wp_signon($creds, false);
    if (is_wp_error($user)) wp_send_json_error('Login failed. Check credentials.');

    $redirect = $_SESSION['truebid_redirect_back'] ?? '';
    $redirect = filter_var($redirect, FILTER_VALIDATE_URL) ? $redirect : home_url('/account');
    unset($_SESSION['truebid_redirect_back']);

    session_write_close(); // Safe to close now

    error_log('LOGIN REDIRECT TO: ' . $redirect);
    wp_send_json_success($redirect);
}

// Auto logout after inactivity
add_action('init', function () {
    truebid_start_session_if_needed();

    $timeout = 600;

    if (is_user_logged_in()) {
        if (isset($_SESSION['last_activity'])) {
            $inactive_duration = time() - $_SESSION['last_activity'];
            if ($inactive_duration > $timeout) {
                session_unset();
                session_destroy();
                wp_logout();
                wp_redirect(home_url('/account?session_expired=true'));
                exit;
            }
        }
        $_SESSION['last_activity'] = time();
    }

    session_write_close();
});

// Session expired notice in footer
add_action('wp_footer', function () {
    if (isset($_GET['session_expired']) && $_GET['session_expired'] === 'true') {
        echo '<div style="text-align:center;color:red;padding:10px;">Your session has expired due to inactivity. Please log in again.</div>';
    }
});
