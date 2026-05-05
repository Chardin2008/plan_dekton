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
define('PDS_QUOTE_RECIPIENT', 'hello@mpc.contact');
define('PDS_CF7_FORM_TITLE', 'Demande de devis Plan Dekton');
define('PDS_HOME_FOCUS_KEYPHRASE', 'plan de travail dekton');
define('PDS_HOME_SEO_TITLE', 'Plan de travail Dekton sur mesure | Plan Dekton Studio');
define('PDS_HOME_META_DESCRIPTION', 'Découvrez nos plans de travail Dekton sur mesure pour cuisine, îlot central, crédence, salle de bain et projets premium. Demandez un devis personnalisé.');

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

    add_editor_style(array('assets/css/main.css', 'assets/css/editor.css'));

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

add_filter('wpcf7_autop_or_not', '__return_false');

function pds_get_cf7_form_id(): int
{
    if (! post_type_exists('wpcf7_contact_form')) {
        return 0;
    }

    $stored_form_id = (int) get_option('pds_cf7_form_id');
    if ($stored_form_id > 0 && 'wpcf7_contact_form' === get_post_type($stored_form_id)) {
        return $stored_form_id;
    }

    $existing = get_page_by_title(PDS_CF7_FORM_TITLE, OBJECT, 'wpcf7_contact_form');
    if ($existing instanceof WP_Post) {
        if (! get_option('pds_cf7_form_configured')) {
            pds_configure_cf7_form((int) $existing->ID);
            update_option('pds_cf7_form_configured', '1', false);
        }

        update_option('pds_cf7_form_id', (int) $existing->ID, false);

        return (int) $existing->ID;
    }

    $form_id = wp_insert_post(
        array(
            'post_title'   => PDS_CF7_FORM_TITLE,
            'post_name'    => 'demande-de-devis-plan-dekton',
            'post_status'  => 'publish',
            'post_type'    => 'wpcf7_contact_form',
            'post_content' => '',
        ),
        true
    );

    if (is_wp_error($form_id)) {
        return 0;
    }

    pds_configure_cf7_form((int) $form_id);
    update_option('pds_cf7_form_id', (int) $form_id, false);
    update_option('pds_cf7_form_configured', '1', false);

    return (int) $form_id;
}

function pds_configure_cf7_form(int $form_id): void
{
    if ($form_id <= 0) {
        return;
    }

    $form = <<<'FORM'
<label class="form-honeypot" aria-hidden="true" tabindex="-1">Site web [text site_web autocomplete:off]</label>
<div class="form-progress"><span data-form-step-label>Étape 1 / 3</span><i data-form-progress></i></div>
<fieldset class="form-step is-active" data-step="0">
    <legend>Type de projet</legend>
    [radio* type_projet use_label_element "Cuisine" "Îlot central" "Salle de bain" "Extérieur" "Autre"]
</fieldset>
<fieldset class="form-step" data-step="1">
    <legend>Style souhaité</legend>
    [radio* style_souhaite use_label_element "Noir veiné" "Blanc marbré" "Gris béton" "Pierre naturelle" "Métal oxydé"]
</fieldset>
<fieldset class="form-step" data-step="2">
    <legend>Informations</legend>
    <div class="field-grid">
        <label>Nom [text* nom autocomplete:name]</label>
        <label>Email [email* email autocomplete:email]</label>
        <label>Téléphone [tel telephone autocomplete:tel]</label>
        <label>Dimensions approximatives [text dimensions]</label>
        <label class="wide">Message [textarea message rows:5]</label>
    </div>
</fieldset>
<p class="form-message" data-form-message aria-live="polite"></p>
<div class="form-actions">
    <button class="btn btn-secondary" type="button" data-prev>Précédent</button>
    <button class="btn btn-primary" type="button" data-next>Suivant</button>
    [submit class:btn class:btn-primary "Préparer la demande"]
</div>
FORM;

    $mail = array(
        'subject'            => 'Nouvelle demande de devis Plan Dekton - [nom]',
        'sender'             => '[_site_title] <integration@top-one-position.fr>',
        'body'               => "Nouvelle demande de devis Plan Dekton Studio\n\nType de projet: [type_projet]\nStyle souhaité: [style_souhaite]\nNom: [nom]\nEmail: [email]\nTéléphone: [telephone]\nDimensions: [dimensions]\n\nMessage:\n[message]\n\nPage source: [_site_url]",
        'recipient'          => PDS_QUOTE_RECIPIENT,
        'additional_headers' => 'Reply-To: [nom] <[email]>',
        'attachments'        => '',
        'use_html'           => 0,
        'exclude_blank'      => 0,
    );

    $messages = array(
        'mail_sent_ok'             => __('Merci, votre demande a bien été envoyée. Nous revenons vers vous rapidement.', 'plan-dekton-studio'),
        'mail_sent_ng'             => __('L’envoi email a échoué. Merci de réessayer ou de nous contacter directement.', 'plan-dekton-studio'),
        'validation_error'         => __('Merci de compléter les champs obligatoires avant l’envoi.', 'plan-dekton-studio'),
        'spam'                     => __('Votre demande n’a pas pu être envoyée.', 'plan-dekton-studio'),
        'accept_terms'             => __('Vous devez accepter les conditions avant l’envoi.', 'plan-dekton-studio'),
        'invalid_required'         => __('Merci de compléter ce champ.', 'plan-dekton-studio'),
        'invalid_too_long'         => __('Ce champ est trop long.', 'plan-dekton-studio'),
        'invalid_too_short'        => __('Ce champ est trop court.', 'plan-dekton-studio'),
        'upload_failed'            => __('Erreur inconnue pendant l’envoi du fichier.', 'plan-dekton-studio'),
        'upload_file_type_invalid' => __('Ce type de fichier n’est pas autorisé.', 'plan-dekton-studio'),
        'upload_file_too_large'    => __('Le fichier est trop lourd.', 'plan-dekton-studio'),
        'upload_failed_php_error'  => __('Erreur pendant l’envoi du fichier.', 'plan-dekton-studio'),
    );

    wp_update_post(
        array(
            'ID'           => $form_id,
            'post_title'   => PDS_CF7_FORM_TITLE,
            'post_content' => $form,
            'post_status'  => 'publish',
        )
    );

    update_post_meta($form_id, '_form', $form);
    update_post_meta($form_id, '_mail', $mail);
    update_post_meta(
        $form_id,
        '_mail_2',
        array(
            'active'             => false,
            'subject'            => '',
            'sender'             => '',
            'body'               => '',
            'recipient'          => '',
            'additional_headers' => '',
            'attachments'        => '',
            'use_html'           => 0,
            'exclude_blank'      => 0,
        )
    );
    update_post_meta($form_id, '_messages', $messages);
    update_post_meta($form_id, '_additional_settings', '');
    update_post_meta($form_id, '_locale', 'fr_FR');
}

function pds_prepare_homepage_content(string $html, int $cf7_form_id): string
{
    if (preg_match('/<main[^>]*class="[^"]*\bsite-main\b[^"]*"[^>]*>(.*)<\/main>/is', $html, $matches)) {
        $html = trim($matches[1]);
    }

    $html = str_replace(home_url(), '', $html);

    if ($cf7_form_id > 0) {
        $html = preg_replace('/<form class="multi-form reveal".*?<\/form>/is', pds_quote_shortcode_markup($cf7_form_id), $html, 1) ?: $html;
    }

    return trim($html);
}

function pds_quote_shortcode_markup(int $cf7_form_id): string
{
    if ($cf7_form_id <= 0) {
        return '';
    }

    return "<!-- wp:shortcode -->\n[pds_quote_form]\n<!-- /wp:shortcode -->";
}

function pds_quote_form_shortcode(): string
{
    $form_id = pds_get_cf7_form_id();
    if ($form_id <= 0) {
        return '';
    }

    return do_shortcode(
        sprintf(
            '[contact-form-7 id="%d" title="%s" html_class="multi-form reveal"]',
            $form_id,
            esc_attr(PDS_CF7_FORM_TITLE)
        )
    );
}
add_shortcode('pds_quote_form', 'pds_quote_form_shortcode');

function pds_configure_homepage_seo(int $page_id): void
{
    if ($page_id <= 0) {
        return;
    }

    $meta_defaults = array(
        '_yoast_wpseo_focuskw' => PDS_HOME_FOCUS_KEYPHRASE,
        '_yoast_wpseo_title'   => PDS_HOME_SEO_TITLE,
        '_yoast_wpseo_metadesc' => PDS_HOME_META_DESCRIPTION,
    );

    foreach ($meta_defaults as $meta_key => $value) {
        if ('' === (string) get_post_meta($page_id, $meta_key, true)) {
            update_post_meta($page_id, $meta_key, $value);
        }
    }
}

function pds_seed_homepage_if_missing(): void
{
    if (! is_admin() || wp_doing_ajax() || ! current_user_can('edit_pages')) {
        return;
    }

    $front_page = get_page_by_path('accueil', OBJECT, 'page');
    if ($front_page instanceof WP_Post) {
        $cf7_form_id = pds_get_cf7_form_id();
        if ($cf7_form_id > 0 && str_contains($front_page->post_content, '<form class="multi-form reveal"')) {
            $updated_content = preg_replace('/<form class="multi-form reveal".*?<\/form>/is', pds_quote_shortcode_markup($cf7_form_id), $front_page->post_content, 1);

            if (is_string($updated_content) && $updated_content !== $front_page->post_content) {
                wp_update_post(
                    array(
                        'ID'           => (int) $front_page->ID,
                        'post_content' => $updated_content,
                    )
                );
            }
        }

        if ('page' !== get_option('show_on_front') || (int) get_option('page_on_front') !== (int) $front_page->ID) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', (int) $front_page->ID);
        }

        pds_configure_homepage_seo((int) $front_page->ID);

        return;
    }

    $cf7_form_id = pds_get_cf7_form_id();
    $response    = wp_remote_get(
        home_url('/'),
        array(
            'timeout'   => 20,
            'sslverify' => false,
        )
    );

    if (is_wp_error($response)) {
        update_option('pds_homepage_seed_error', $response->get_error_message(), false);
        return;
    }

    $content = pds_prepare_homepage_content((string) wp_remote_retrieve_body($response), $cf7_form_id);
    if ('' === $content) {
        update_option('pds_homepage_seed_error', 'Impossible de générer le contenu de la page Accueil.', false);
        return;
    }

    $page_id = wp_insert_post(
        array(
            'post_title'   => 'Accueil',
            'post_name'    => 'accueil',
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ),
        true
    );

    if (is_wp_error($page_id)) {
        update_option('pds_homepage_seed_error', $page_id->get_error_message(), false);
        return;
    }

    update_option('show_on_front', 'page');
    update_option('page_on_front', (int) $page_id);
    pds_configure_homepage_seo((int) $page_id);
    delete_option('pds_homepage_seed_error');
}
add_action('admin_init', 'pds_seed_homepage_if_missing');

function pds_admin_setup_notices(): void
{
    if (! current_user_can('activate_plugins')) {
        return;
    }

    $missing = array();
    if (! defined('WPCF7_VERSION')) {
        $missing[] = 'Contact Form 7';
    }
    if (! defined('CFDB7_VERSION') && ! class_exists('CFDB7_Wp_Main_Page')) {
        $missing[] = 'Database Addon for Contact Form 7 - CFDB7';
    }

    if ($missing) {
        printf(
            '<div class="notice notice-warning"><p>%s</p></div>',
            esc_html(sprintf('Plan Dekton Studio : installez/activez %s pour gérer le formulaire devis et stocker les demandes.', implode(' + ', $missing)))
        );
    }

    $seed_error = get_option('pds_homepage_seed_error');
    if ($seed_error) {
        printf(
            '<div class="notice notice-error"><p>%s</p></div>',
            esc_html(sprintf('Plan Dekton Studio : la page Accueil n’a pas pu être créée automatiquement. Détail : %s', $seed_error))
        );
    }
}
add_action('admin_notices', 'pds_admin_setup_notices');

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
