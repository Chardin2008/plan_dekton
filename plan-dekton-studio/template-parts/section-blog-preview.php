<?php
/**
 * Template part: Section blog preview.
 *
 * @package PlanDektonStudio
 */

$latest_posts = new WP_Query(
    array(
        'posts_per_page'      => 3,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
    )
);
?>

<section class="blog-preview pds-section" id="blog" aria-labelledby="blog-title">
    <div class="section-heading reveal">
        <p class="eyebrow">Conseils & inspirations</p>
        <h2 id="blog-title">Les derniers conseils Plan Dekton.</h2>
        <p>Des reperes simples pour preparer un plan de travail, choisir une finition et cadrer un devis.</p>
    </div>
    <div class="detail-grid">
        <?php if ($latest_posts->have_posts()) : ?>
            <?php while ($latest_posts->have_posts()) : ?>
                <?php $latest_posts->the_post(); ?>
                <article class="detail-card reveal">
                    <span aria-hidden="true">&loz;</span>
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
                    <a href="<?php the_permalink(); ?>"><?php esc_html_e('Lire le conseil', 'plan-dekton-studio'); ?></a>
                </article>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <article class="detail-card reveal">
                <span aria-hidden="true">&loz;</span>
                <h3><?php esc_html_e('Guide des matieres', 'plan-dekton-studio'); ?></h3>
                <p><?php esc_html_e('Ajoutez vos articles depuis WordPress pour alimenter cette section.', 'plan-dekton-studio'); ?></p>
            </article>
        <?php endif; ?>
    </div>
</section>