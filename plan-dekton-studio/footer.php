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
            <p><?php esc_html_e('Surfaces premium pour cuisines, intérieurs et projets architecturaux.', 'plan-dekton-studio'); ?></p>
        </div>
        <div>
            <h2><?php esc_html_e('Navigation', 'plan-dekton-studio'); ?></h2>
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
            <h2><?php esc_html_e('Contact', 'plan-dekton-studio'); ?></h2>
            <ul class="footer-list">
                <li><a href="<?php echo esc_url(home_url('/#devis')); ?>"><?php esc_html_e('Demander un devis', 'plan-dekton-studio'); ?></a></li>
                <li><a href="mailto:contact@plan-dekton.fr">contact@plan-dekton.fr</a></li>
                <li><a href="tel:+33000000000">+33 0 00 00 00 00</a></li>
                <li><?php esc_html_e('Réponse par email après étude du projet', 'plan-dekton-studio'); ?></li>
                <li><?php esc_html_e('Paris / Île-de-France', 'plan-dekton-studio'); ?></li>
            </ul>
        </div>
        <div>
            <h2><?php esc_html_e('Informations', 'plan-dekton-studio'); ?></h2>
            <ul class="footer-list">
                <li><a href="<?php echo esc_url(home_url('/mentions-legales/')); ?>"><?php esc_html_e('Mentions légales', 'plan-dekton-studio'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/politique-de-confidentialite/')); ?>"><?php esc_html_e('Politique de confidentialité', 'plan-dekton-studio'); ?></a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; <?php echo esc_html(gmdate('Y')); ?> Plan Dekton Studio.</span>
        <div class="footer-legal">
            <a href="<?php echo esc_url(home_url('/mentions-legales/')); ?>"><?php esc_html_e('Mentions légales', 'plan-dekton-studio'); ?></a>
            <a href="<?php echo esc_url(home_url('/politique-de-confidentialite/')); ?>"><?php esc_html_e('Politique de confidentialité', 'plan-dekton-studio'); ?></a>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
