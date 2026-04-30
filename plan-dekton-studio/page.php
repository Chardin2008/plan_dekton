<?php
/**
 * Default page template.
 *
 * @package PlanDektonStudio
 */

get_header();
?>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : ?>
        <?php the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('legal-page pds-section'); ?>>
            <header class="legal-page__header reveal">
                <p class="eyebrow"><?php esc_html_e('Informations', 'plan-dekton-studio'); ?></p>
                <h1><?php the_title(); ?></h1>
            </header>

            <div class="legal-page__content reveal">
                <?php the_content(); ?>
            </div>

            <a class="btn btn-secondary legal-page__back" href="<?php echo esc_url(home_url('/')); ?>">
                <?php esc_html_e('Retour au site', 'plan-dekton-studio'); ?>
            </a>
        </article>
    <?php endwhile; ?>
<?php endif; ?>

<?php
get_footer();
