<?php
/** Socho Sons: existing work-log entry points and shared contact footer.
 * Install as a PHP WPCode snippet, Everywhere, without this opening PHP tag.
 * Uses existing pages 222 / 154 / 218 and the existing work-log permission check.
 * Disabling the snippet restores the theme output; no records or roles are changed.
 */
if (!defined('ABSPATH')) { exit; }
if (defined('SSPF_VERSION')) { return; }
define('SSPF_VERSION', '1.0.0');

function sspf_manager() {
    return is_user_logged_in() && function_exists('socho_munkanaplo_is_manager')
        && socho_munkanaplo_is_manager();
}

function sspf_login($kind, $target) {
    wp_login_form(array(
        'redirect' => get_permalink($target), 'remember' => true,
        'form_id' => 'sspf-login-' . $kind,
        'id_username' => 'sspf-user-' . $kind,
        'id_password' => 'sspf-pass-' . $kind,
        'id_remember' => 'sspf-remember-' . $kind,
        'id_submit' => 'sspf-submit-' . $kind,
        'label_username' => 'Username or email', 'label_password' => 'Password',
        'label_remember' => 'Remember me', 'label_log_in' => 'Sign in',
        'required_username' => true, 'required_password' => true,
    ));
    echo '<a class="sspf-small" href="' . esc_url(wp_lostpassword_url(get_permalink(222))) . '">Forgot your password?</a>';
}

add_action('wp_head', function () {
    if (is_admin()) { return; }
    ?>
    <style id="sspf-styles">
    .sspf{--gold:#e4bc63;--muted:#c1b8a7;box-sizing:border-box;background:#050505;color:#f5eddd;font-family:Verdana,sans-serif;line-height:1.6}
    .sspf *{box-sizing:border-box}.sspf a{color:var(--gold)}.sspf a:hover{color:#fff1c4}
    .sspf a:focus-visible,.sspf input:focus-visible,.sspf textarea:focus-visible,.sspf button:focus-visible{outline:3px solid #f5d383;outline-offset:4px}
    .sspf-wrap{max-width:1120px;padding:40px 24px;margin:auto}.sspf-hero{margin:0 auto 30px;max-width:1040px}.sspf-hero img{width:100%;height:auto;display:block}
    .sspf-intro{text-align:center;max-width:660px;margin:0 auto 32px;color:var(--muted)}
    .sspf-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:24px}.sspf-card{background:#0c0b09;border:1px solid #786031;border-radius:12px;padding:30px}
    .sspf h1,.sspf h2{color:#f0cf88;font-family:Georgia,serif;font-weight:400;line-height:1.2}.sspf h1{font-size:36px}.sspf h2{font-size:30px;margin:0 0 12px}
    .sspf-eyebrow{font-size:11px;letter-spacing:.18em;text-transform:uppercase;color:var(--gold)}.sspf-card>p{color:var(--muted);font-size:14px}
    .sspf label{display:block;font-size:13px;color:#efe4cf}.sspf input:not([type=checkbox]):not([type=submit]),.sspf textarea{display:block;width:100%;background:#080808;color:#fff;border:1px solid #7f704e;border-radius:5px;padding:12px;font:inherit;margin-top:6px;min-height:46px}
    .sspf input[type=checkbox]{margin-right:8px}.sspf textarea{resize:vertical;min-height:112px}.sspf input[type=submit],.sspf button,.sspf-button{display:inline-block;padding:13px 22px;background:#e4bc63;color:#171107!important;border:1px solid #e4bc63;border-radius:5px;font:700 14px Verdana,sans-serif;cursor:pointer;text-decoration:none}
    .sspf .login-submit input{width:100%}.sspf-small{font-size:12px}.sspf-session{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:24px}
    .sspf-footer{border-top:1px solid #6f5629;margin-top:0}.sspf-footer .sspf-wrap{max-width:1120px}.sspf-footer-logo{max-width:350px;width:100%;height:auto;display:block;margin:0 auto 16px}
    .sspf-footer-brand{text-align:center}.sspf-contact-links{display:flex;justify-content:center;gap:12px 26px;flex-wrap:wrap;margin:18px 0 32px;font-size:14px}.sspf-contact-links a{text-decoration:none}
    .sspf-message{max-width:680px;margin:auto}.sspf-message h2{text-align:center}.sspf-message>p{text-align:center;color:var(--muted);font-size:13px}.sspf-form-fields{display:grid;grid-template-columns:1fr 1fr;gap:16px}.sspf-wide{grid-column:1/-1}.sspf-form-fields p{margin:0}.sspf-hp{position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden}
    .sspf-social{display:flex;justify-content:center;gap:22px;margin:26px 0 18px;font-size:12px}.sspf-social span{color:#aaa18f}.sspf-bottom{text-align:center;border-top:1px solid #3f3420;padding-top:20px;font-size:11px;color:#bfb19a}.sspf-notice{border:1px solid #ad8e4a;padding:14px;margin:16px 0;color:#fff4d7}
    body.page-id-222 .sons-wl{display:none!important}
    @media(max-width:700px){.sspf-wrap{padding:28px 16px}.sspf-grid,.sspf-form-fields{grid-template-columns:1fr}.sspf-card{padding:23px}.sspf-wide{grid-column:auto}.sspf h1{font-size:29px}.sspf h2{font-size:27px}}
    </style>
    <?php
}, 99);

// Existing work-log pages have their own secure server-rendered entry point.
add_action('template_redirect', function () {
    if (!is_page(array(222,154,218)) || is_feed() || is_preview()) { return; }
    if (!defined('DONOTCACHEPAGE')) { define('DONOTCACHEPAGE', true); }
    nocache_headers();
    $id = get_queried_object_id();
    $denied = $id === 218 && is_user_logged_in() && !sspf_manager();
    if ($denied) { status_header(403); }
    get_header();
    echo '<main id="sspf-main" class="sspf"><div class="sspf-wrap">';
    if ($id === 222) {
        echo '<h1 class="screen-reader-text">Socho Sons Work Log</h1><figure class="sspf-hero">';
        echo wp_get_attachment_image(317, 'full', false, array('alt'=>'Work Log — Socho Sons LLC. internal company system','loading'=>'eager','fetchpriority'=>'high'));
        echo '</figure><p class="sspf-intro">Welcome to the Socho Sons team workspace. Sign in to record your work, track your hours, or manage your team’s work records.</p><div class="sspf-grid">';
        foreach (array('employee'=>array('Employee login',154,'Record your time and view your own work entries.'),'management'=>array('Management login',218,'Review work records and manage your team.')) as $kind=>$card) {
            echo '<section class="sspf-card"><p class="sspf-eyebrow">Socho Sons LLC.</p><h2>' . esc_html($card[0]) . '</h2><p>' . esc_html($card[2]) . '</p>';
            if (!is_user_logged_in()) { sspf_login($kind,$card[1]); }
            elseif ($kind === 'employee' || sspf_manager()) {
                echo '<a class="sspf-button" href="' . esc_url(get_permalink($card[1])) . '">Open ' . ($kind === 'employee' ? 'work log' : 'management') . '</a>';
            } else { echo '<p>Management permission is required for this area.</p>'; }
            echo '</section>';
        }
        echo '</div>';
        if (is_user_logged_in()) { echo '<p><a href="' . esc_url(wp_logout_url(get_permalink(222))) . '">Sign out</a></p>'; }
    } elseif (!is_user_logged_in()) {
        echo '<section class="sspf-card"><h1>' . ($id === 218 ? 'Management login' : 'Employee login') . '</h1>';
        sspf_login($id === 218 ? 'management' : 'employee', $id);
        echo '</section>';
    } elseif ($denied) {
        echo '<section class="sspf-card"><h1>Management access required</h1><p>Your account does not have access to this area.</p><a class="sspf-button" href="' . esc_url(get_permalink(154)) . '">Open your work log</a></section>';
    } else {
        echo '<div class="sspf-session"><a href="' . esc_url(get_permalink(222)) . '">Back to Work Log</a><a href="' . esc_url(wp_logout_url(get_permalink(222))) . '">Sign out</a></div><h1>' . ($id === 218 ? 'Management work records' : 'Employee work log') . '</h1>';
        if (shortcode_exists('socho_munkanaplo')) { echo do_shortcode('[socho_munkanaplo]'); }
        else { echo '<p class="sspf-notice">The work log is temporarily unavailable. Please contact your manager.</p>'; }
        if ($id === 218 && sspf_manager() && shortcode_exists('socho_worker_register')) { echo do_shortcode('[socho_worker_register]'); }
    }
    echo '</div></main>';
    get_footer();
    exit;
}, 8);

function sspf_footer() {
    ob_start(); ?>
    <footer id="ss-contact" class="sspf sspf-footer"><div class="sspf-wrap">
        <div class="sspf-footer-brand"><a href="<?php echo esc_url(home_url('/')); ?>" aria-label="Socho Sons LLC. home"><?php echo wp_get_attachment_image(306,'large',false,array('class'=>'sspf-footer-logo','alt'=>'Socho Sons LLC.')); ?></a></div>
        <div class="sspf-contact-links"><a href="tel:+17329021274">+1 732 902 1274</a><a href="mailto:info@sochosons.com">info@sochosons.com</a><span>New Jersey, USA</span></div>
        <section class="sspf-message" aria-labelledby="sspf-contact-title"><h2 id="sspf-contact-title">Get in touch</h2><p>Tell us about your project. We would be happy to hear from you.</p>
        <?php
        $notice = isset($_GET['sspf_message']) && is_string($_GET['sspf_message']) ? sanitize_key(wp_unslash($_GET['sspf_message'])) : '';
        $messages = array('sent'=>'Thank you. Your message has been submitted.','invalid'=>'Please check your name, email and message, then try again.','retry'=>'Your form has expired. Reload the page and try again.','wait'=>'Please wait a few minutes before sending another message.','failed'=>'We could not submit your message. Please call or email us.');
        if (isset($messages[$notice])) { echo '<p role="status" class="sspf-notice">' . esc_html($messages[$notice]) . '</p>'; }
        ?>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <input type="hidden" name="action" value="sspf_contact">
            <?php wp_nonce_field('sspf_contact','sspf_nonce'); ?>
            <div class="sspf-hp" aria-hidden="true"><label>Leave this empty<input name="sspf_website" tabindex="-1" autocomplete="off"></label></div>
            <div class="sspf-form-fields"><p><label for="sspf-name">Name</label><input id="sspf-name" name="sspf_name" autocomplete="name" maxlength="120" required></p><p><label for="sspf-email">Email</label><input type="email" id="sspf-email" name="sspf_email" autocomplete="email" maxlength="254" required></p><p class="sspf-wide"><label for="sspf-message">Message</label><textarea id="sspf-message" name="sspf_message_text" maxlength="5000" required></textarea></p><p class="sspf-wide"><button type="submit">Send message</button></p></div>
        </form></section>
        <nav class="sspf-social" aria-label="Social media">
        <?php
        // Only verified, site-owner-supplied profile URLs become links.
        foreach (array('facebook'=>'Facebook','linkedin'=>'LinkedIn','youtube'=>'YouTube') as $key=>$label) {
            $url = get_option('sspf_social_' . $key, '');
            if ($url && wp_http_validate_url($url)) { echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">' . esc_html($label) . '</a>'; }
            else { echo '<span title="Company profile link is not configured">' . esc_html($label) . '</span>'; }
        }
        ?></nav>
        <div class="sspf-bottom">&copy; <?php echo esc_html(wp_date('Y')); ?> Socho Sons LLC. All rights reserved. &nbsp; <a href="<?php echo esc_url(get_permalink(44)); ?>">Cookie Policy</a></div>
    </div></footer>
    <?php return ob_get_clean();
}

// Replace only the existing shared footer, not arbitrary page or plugin footers.
add_action('template_redirect', function () {
    if (is_admin() || is_feed() || is_robots() || is_trackback()) { return; }
    $footer = sspf_footer();
    ob_start(function ($html) use ($footer) {
        if (strpos($html, '<html') === false) { return $html; }
        $pattern = '~<footer\b(?=[^>]*\bid=["\']ss-contact["\'])[^>]*>.*?</footer>~s';
        $count = 0;
        $updated = preg_replace_callback($pattern, function () use ($footer) { return $footer; }, $html, 1, $count);
        return $count && is_string($updated) ? $updated : $html;
    });
}, 1);

function sspf_contact_result($result) {
    wp_safe_redirect(add_query_arg('sspf_message', $result, home_url('/contact/')) . '#ss-contact', 303);
    exit;
}
function sspf_contact_submit() {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { sspf_contact_result('invalid'); }
    foreach (array('sspf_nonce','sspf_name','sspf_email','sspf_message_text','sspf_website') as $field) {
        if (isset($_POST[$field]) && !is_string($_POST[$field])) { sspf_contact_result('invalid'); }
    }
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sspf_nonce'] ?? '')), 'sspf_contact')) { sspf_contact_result('retry'); }
    if (!empty($_POST['sspf_website'])) { sspf_contact_result('sent'); }
    $name = sanitize_text_field(wp_unslash($_POST['sspf_name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['sspf_email'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['sspf_message_text'] ?? ''));
    if (!$name || strlen($name)>480 || !is_email($email) || strlen($email)>254 || !$message || strlen($message)>20000) { sspf_contact_result('invalid'); }
    $ip = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $key = 'sspf_rate_' . hash_hmac('sha256', $ip, wp_salt('nonce'));
    $count = (int)get_transient($key);
    if ($count >= 3) { sspf_contact_result('wait'); }
    set_transient($key, $count + 1, 10 * MINUTE_IN_SECONDS);
    // Confirmed shared company address; mailbox sharing is configured by the mail provider.
    $recipient = 'info@sochosons.com';
    $ok = wp_mail($recipient, 'Socho Sons website enquiry', "Name: $name\nEmail: $email\n\n$message", array('Content-Type: text/plain; charset=UTF-8','Reply-To: ' . $email));
    sspf_contact_result($ok ? 'sent' : 'failed');
}
add_action('admin_post_nopriv_sspf_contact','sspf_contact_submit');
add_action('admin_post_sspf_contact','sspf_contact_submit');
