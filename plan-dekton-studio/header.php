<?php
/**
 * Header template.
 *
 * @package PlanDektonStudio
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> id="top">
<?php wp_body_open(); ?>

<div class="site-loader" data-loader aria-hidden="true">
    <div class="loader-logo">
        <?php echo wp_kses_post(pds_logo_markup('loader')); ?>
        <span class="loader-line"></span>
        <span class="loader-text"><?php echo esc_html(pds_site_option('pds_loader_text', 'Surface nouvelle génération')); ?></span>
    </div>
</div>

<div class="scroll-progress" data-scroll-progress aria-hidden="true"></div>
<div class="custom-cursor" data-cursor aria-hidden="true"></div>

<header class="site-header" data-site-header>
    <div class="header-inner">
        <?php echo wp_kses_post(pds_logo_markup()); ?>

        <nav class="primary-nav" data-mobile-menu aria-label="<?php echo esc_attr(pds_site_option('pds_primary_nav_aria_label', 'Navigation principale')); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => 'pds_nav_fallback',
                'depth'          => 1,
            ));
            ?>
            <a class="btn btn-primary mobile-quote" href="<?php echo esc_url(pds_resolve_site_link((string) pds_site_option('pds_mobile_quote_url', '/#devis'))); ?>"><?php echo esc_html(pds_site_option('pds_mobile_quote_label', 'Demander un devis')); ?></a>
        </nav>

        <a class="btn btn-primary header-quote" href="<?php echo esc_url(pds_resolve_site_link((string) pds_site_option('pds_header_quote_url', '/#devis'))); ?>"><?php echo esc_html(pds_site_option('pds_header_quote_label', 'Demander un devis')); ?></a>
        <button class="menu-toggle" type="button" data-menu-toggle aria-label="<?php echo esc_attr(pds_site_option('pds_menu_toggle_label', 'Ouvrir le menu')); ?>" aria-expanded="false">
            <span></span><span></span>
        </button>
    </div>
</header>

<main id="primary" class="site-main">