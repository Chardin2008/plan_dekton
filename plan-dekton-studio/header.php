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
        <span class="loader-text"><?php esc_html_e('Surface nouvelle génération', 'plan-dekton-studio'); ?></span>
    </div>
</div>

<div class="scroll-progress" data-scroll-progress aria-hidden="true"></div>
<div class="custom-cursor" data-cursor aria-hidden="true"></div>

<header class="site-header" data-site-header>
    <div class="header-inner">
        <?php echo wp_kses_post(pds_logo_markup()); ?>

        <nav class="primary-nav" data-mobile-menu aria-label="<?php esc_attr_e('Navigation principale', 'plan-dekton-studio'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => 'pds_nav_fallback',
                'depth'          => 1,
            ));
            ?>
            <a class="btn btn-primary mobile-quote" href="<?php echo esc_url(home_url('/#devis')); ?>"><?php esc_html_e('Demander un devis', 'plan-dekton-studio'); ?></a>
        </nav>

        <a class="btn btn-primary header-quote" href="<?php echo esc_url(home_url('/#devis')); ?>"><?php esc_html_e('Demander un devis', 'plan-dekton-studio'); ?></a>
        <button class="menu-toggle" type="button" data-menu-toggle aria-label="<?php esc_attr_e('Ouvrir le menu', 'plan-dekton-studio'); ?>" aria-expanded="false">
            <span></span><span></span>
        </button>
    </div>
</header>

<main id="primary" class="site-main">
