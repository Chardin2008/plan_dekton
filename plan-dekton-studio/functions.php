<?php
/**
 * Theme setup and integrations for Plan Dekton Studio.
 *
 * @package PlanDektonStudio
 */

if (! defined('ABSPATH')) {
    exit;
}

define('PDS_THEME_VERSION', '1.0.21');
define('PDS_QUOTE_RECIPIENT', 'chardinpoutcheu@gmail.com');

function pds_setup(): void
{
    load_theme_textdomain('plan-dekton-studio', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('custom-logo', array(
        'height'      => 90,
        'width'       => 320,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets'));

    add_editor_style('assets/css/editor.css');

    register_nav_menus(array(
        'primary' => __('Menu principal', 'plan-dekton-studio'),
        'footer'  => __('Menu footer', 'plan-dekton-studio'),
    ));
}
add_action('after_setup_theme', 'pds_setup');

function pds_enqueue_assets(): void
{
    wp_enqueue_style(
        'pds-main',
        get_theme_file_uri('assets/css/main.css'),
        array(),
        PDS_THEME_VERSION
    );

    wp_enqueue_script(
        'pds-main',
        get_theme_file_uri('assets/js/main.js'),
        array(),
        PDS_THEME_VERSION,
        true
    );

    wp_localize_script(
        'pds-main',
        'pdsQuote',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('pds_quote_request'),
        )
    );
}
add_action('wp_enqueue_scripts', 'pds_enqueue_assets');

function pds_defer_script(string $tag, string $handle): string
{
    if ('pds-main' !== $handle) {
        return $tag;
    }

    return str_replace(' src=', ' defer src=', $tag);
}
add_filter('script_loader_tag', 'pds_defer_script', 10, 2);

function pds_optimize_front_assets(): void
{
    if (is_admin()) {
        return;
    }

    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('template_redirect', 'rest_output_link_header', 11);
}
add_action('init', 'pds_optimize_front_assets');

function pds_dequeue_unused_front_styles(): void
{
    if (is_admin()) {
        return;
    }

    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');
}
add_action('wp_enqueue_scripts', 'pds_dequeue_unused_front_styles', 100);

function pds_register_patterns(): void
{
    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category(
            'plan-dekton-studio',
            array('label' => __('Plan Dekton Studio', 'plan-dekton-studio'))
        );
    }
}
add_action('init', 'pds_register_patterns');

function pds_language_attributes(string $output): string
{
    return preg_replace('/lang="[^"]*"/', 'lang="fr-FR"', $output) ?: $output;
}
add_filter('language_attributes', 'pds_language_attributes');

function pds_head_meta(): void
{
    $theme_color = '#070707';
    $og_image    = get_theme_file_uri('assets/img/og-plan-dekton.jpg');
    $site_url    = home_url('/');
    $is_front    = is_front_page() || is_home();
    $yoast_active = defined('WPSEO_VERSION');
    $site_name   = get_bloginfo('name');
    $description = __('Plans de travail Dekton sur mesure pour cuisines, îlots, salles de bain et projets architecturaux premium.', 'plan-dekton-studio');
    $title       = __('Plan Dekton Studio — Plans de travail Dekton sur mesure', 'plan-dekton-studio');
    $schema      = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'LocalBusiness',
        'name'        => $site_name,
        'url'         => $site_url,
        'image'       => $og_image,
        'description' => $description,
        'areaServed'  => array(
            '@type' => 'AdministrativeArea',
            'name'  => 'Île-de-France',
        ),
        'makesOffer'  => array(
            '@type'       => 'Offer',
            'itemOffered' => array(
                '@type' => 'Service',
                'name'  => __('Plan de travail Dekton sur mesure', 'plan-dekton-studio'),
            ),
        ),
    );
    ?>
    <meta name="theme-color" content="<?php echo esc_attr($theme_color); ?>">
    <?php if ($is_front) : ?>
        <?php if (! $yoast_active) : ?>
            <meta name="description" content="<?php echo esc_attr($description); ?>">
            <link rel="canonical" href="<?php echo esc_url($site_url); ?>">
        <?php endif; ?>
        <link rel="preload" as="image" href="<?php echo esc_url(get_theme_file_uri('assets/img/hero-dekton.webp')); ?>" type="image/webp" fetchpriority="high">
    <?php endif; ?>
    <link rel="manifest" href="<?php echo esc_url(get_theme_file_uri('site.webmanifest')); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php echo esc_url(get_theme_file_uri('assets/img/favicon.svg')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(get_theme_file_uri('assets/img/favicon-32x32.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo esc_url(get_theme_file_uri('assets/img/apple-touch-icon.png')); ?>">
    <?php
    /*
     * L'icône du site peut aussi être modifiée depuis WordPress :
     * Apparence > Personnaliser > Identité du site.
     */
    ?>
    <?php if ($is_front && ! $yoast_active) : ?>
        <meta property="og:type" content="website">
        <meta property="og:locale" content="fr_FR">
        <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
        <meta property="og:title" content="<?php echo esc_attr($title); ?>">
        <meta property="og:description" content="<?php echo esc_attr($description); ?>">
        <meta property="og:image" content="<?php echo esc_url($og_image); ?>">
        <meta property="og:url" content="<?php echo esc_url($site_url); ?>">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
        <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
        <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>">
        <script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <?php endif; ?>
    <?php
}
add_action('wp_head', 'pds_head_meta', 5);

function pds_logo_markup(string $context = 'header'): string
{
    if (has_custom_logo()) {
        return get_custom_logo();
    }

    $label = 'footer' === $context ? __('Retour à l’accueil', 'plan-dekton-studio') : get_bloginfo('name');

    return sprintf(
        '<a class="site-logo" href="%1$s" aria-label="%2$s"><span class="logo-mark">D</span><span class="logo-text"><strong>PLAN DEKTON</strong><small>STUDIO</small></span></a>',
        esc_url(home_url('/')),
        esc_attr($label)
    );
}

function pds_nav_fallback(): void
{
    $items = array(
        home_url('/#top')          => __('Accueil', 'plan-dekton-studio'),
        '#matieres'     => __('Matières', 'plan-dekton-studio'),
        home_url('/#ambiances')    => __('Ambiances', 'plan-dekton-studio'),
        home_url('/#applications') => __('Applications', 'plan-dekton-studio'),
        home_url('/#processus')    => __('Processus', 'plan-dekton-studio'),
        home_url('/#avis')         => __('Avis', 'plan-dekton-studio'),
        home_url('/#devis')        => __('Devis', 'plan-dekton-studio'),
    );

    echo '<ul class="menu">';
    foreach ($items as $url => $label) {
        if (str_starts_with($url, '#')) {
            $url = home_url('/' . $url);
        }

        printf('<li><a href="%s">%s</a></li>', esc_url($url), esc_html($label));
    }
    echo '</ul>';
}

function pds_normalize_section_menu_links(array $atts): array
{
    if (! is_front_page() && ! empty($atts['href']) && str_starts_with($atts['href'], '#')) {
        $atts['href'] = home_url('/' . $atts['href']);
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'pds_normalize_section_menu_links');

function pds_handle_quote_request(): void
{
    if (! check_ajax_referer('pds_quote_request', 'pds_quote_nonce', false)) {
        wp_send_json_error(
            array('message' => __('La session a expiré. Merci de recharger la page puis de réessayer.', 'plan-dekton-studio')),
            403
        );
    }

    if (! empty($_POST['site_web'])) {
        wp_send_json_error(
            array('message' => __('Votre demande n’a pas pu être envoyée.', 'plan-dekton-studio')),
            400
        );
    }

    $type_projet     = isset($_POST['type_projet']) ? sanitize_text_field(wp_unslash($_POST['type_projet'])) : '';
    $style_souhaite  = isset($_POST['style_souhaite']) ? sanitize_text_field(wp_unslash($_POST['style_souhaite'])) : '';
    $nom             = isset($_POST['nom']) ? sanitize_text_field(wp_unslash($_POST['nom'])) : '';
    $email           = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $telephone       = isset($_POST['telephone']) ? sanitize_text_field(wp_unslash($_POST['telephone'])) : '';
    $dimensions      = isset($_POST['dimensions']) ? sanitize_text_field(wp_unslash($_POST['dimensions'])) : '';
    $message_client  = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';

    if ('' === $type_projet || '' === $style_souhaite || '' === $nom || '' === $email || ! is_email($email)) {
        wp_send_json_error(
            array('message' => __('Merci de compléter les champs obligatoires avant l’envoi.', 'plan-dekton-studio')),
            422
        );
    }

    $subject = sprintf(
        /* translators: %s: customer name */
        __('Nouvelle demande de devis Plan Dekton - %s', 'plan-dekton-studio'),
        $nom
    );

    $body = sprintf(
        "Nouvelle demande de devis Plan Dekton Studio\n\nType de projet: %s\nStyle souhaité: %s\nNom: %s\nEmail: %s\nTéléphone: %s\nDimensions: %s\n\nMessage:\n%s\n\nPage source: %s",
        $type_projet,
        $style_souhaite,
        $nom,
        $email,
        '' !== $telephone ? $telephone : 'Non renseigné',
        '' !== $dimensions ? $dimensions : 'Non renseigné',
        '' !== $message_client ? $message_client : 'Non renseigné',
        home_url('/')
    );

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        sprintf('Reply-To: %s <%s>', $nom, $email),
    );

    $sent = wp_mail(PDS_QUOTE_RECIPIENT, $subject, $body, $headers);

    if (! $sent) {
        wp_send_json_error(
            array('message' => __('L’envoi email a échoué. Merci de réessayer ou de nous contacter directement.', 'plan-dekton-studio')),
            500
        );
    }

    wp_send_json_success(
        array('message' => __('Merci, votre demande a bien été envoyée. Nous revenons vers vous rapidement.', 'plan-dekton-studio'))
    );
}
add_action('wp_ajax_pds_quote_request', 'pds_handle_quote_request');
add_action('wp_ajax_nopriv_pds_quote_request', 'pds_handle_quote_request');

function pds_configure_smtp(PHPMailer\PHPMailer\PHPMailer $phpmailer): void
{
    $host = getenv('PDS_SMTP_HOST');
    if (! $host) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host       = $host;
    $phpmailer->Port       = (int) (getenv('PDS_SMTP_PORT') ?: 587);
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = getenv('PDS_SMTP_USER') ?: '';
    $phpmailer->Password   = getenv('PDS_SMTP_PASS') ?: '';
    $phpmailer->SMTPSecure = getenv('PDS_SMTP_SECURE') ?: 'tls';

    $from = getenv('PDS_SMTP_FROM') ?: $phpmailer->Username;
    if ($from && is_email($from)) {
        $phpmailer->setFrom($from, get_bloginfo('name'));
    }
}
add_action('phpmailer_init', 'pds_configure_smtp');
