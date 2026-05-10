<?php
/**
 * Footer template.
 *
 * @package PlanDektonStudio
 */
?>
</main>

<footer class="site-footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <?php echo wp_kses_post(pds_logo_markup('footer')); ?>
            <p><?php echo esc_html(pds_site_option('pds_footer_brand_text', 'Surfaces premium pour cuisines, intérieurs et projets architecturaux.')); ?></p>
        </div>
        <div>
            <h2><?php echo esc_html(pds_site_option('pds_footer_navigation_title', 'Navigation')); ?></h2>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'container'      => false,
                'fallback_cb'    => 'pds_nav_fallback',
                'depth'          => 1,
            ));
            ?>
        </div>
        <div>
            <h2><?php echo esc_html(pds_site_option('pds_footer_contact_title', 'Contact')); ?></h2>
            <ul class="footer-list">
                <li><a href="<?php echo esc_url(pds_resolve_site_link((string) pds_site_option('pds_footer_quote_url', '/#devis'))); ?>"><?php echo esc_html(pds_site_option('pds_footer_quote_label', 'Demander un devis')); ?></a></li>
                <?php $footer_email = (string) pds_site_option('pds_footer_email', 'contact@plan-dekton.fr'); ?>
                <li><a href="mailto:<?php echo esc_attr($footer_email); ?>"><?php echo esc_html($footer_email); ?></a></li>
                <li><a href="<?php echo esc_url(pds_site_option('pds_footer_phone_url', 'tel:+33000000000')); ?>"><?php echo esc_html(pds_site_option('pds_footer_phone_label', '+33 0 00 00 00 00')); ?></a></li>
                <li><?php echo esc_html(pds_site_option('pds_footer_response_text', 'Réponse par email après étude du projet')); ?></li>
                <li><?php echo esc_html(pds_site_option('pds_footer_area_text', 'Paris / Île-de-France')); ?></li>
            </ul>
        </div>
        <div>
            <h2><?php echo esc_html(pds_site_option('pds_footer_info_title', 'Informations')); ?></h2>
            <ul class="footer-list">
                <li><a href="<?php echo esc_url(pds_resolve_site_link((string) pds_site_option('pds_footer_legal_url', '/mentions-legales/'))); ?>"><?php echo esc_html(pds_site_option('pds_footer_legal_label', 'Mentions légales')); ?></a></li>
                <li><a href="<?php echo esc_url(pds_resolve_site_link((string) pds_site_option('pds_footer_privacy_url', '/politique-de-confidentialite/'))); ?>"><?php echo esc_html(pds_site_option('pds_footer_privacy_label', 'Politique de confidentialité')); ?></a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; <?php echo esc_html(gmdate('Y')); ?> <?php echo esc_html(pds_site_option('pds_footer_copyright_name', 'Plan Dekton Studio.')); ?></span>
        <div class="footer-legal">
            <a href="<?php echo esc_url(pds_resolve_site_link((string) pds_site_option('pds_footer_legal_url', '/mentions-legales/'))); ?>"><?php echo esc_html(pds_site_option('pds_footer_legal_label', 'Mentions légales')); ?></a>
            <a href="<?php echo esc_url(pds_resolve_site_link((string) pds_site_option('pds_footer_privacy_url', '/politique-de-confidentialite/'))); ?>"><?php echo esc_html(pds_site_option('pds_footer_privacy_label', 'Politique de confidentialité')); ?></a>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>