<?php
/**
 * Front page template.
 *
 * Les contenus ci-dessous peuvent être remplacés par des champs ACF,
 * des blocs Gutenberg/patterns ou une page éditée dans l'admin si le site évolue.
 *
 * @package PlanDektonStudio
 */

get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();

        if (trim(get_the_content()) !== '') {
            the_content();
        } else {
            pds_render_front_page_fallback();
        }
    endwhile;
else :
    pds_render_front_page_fallback();
endif;

get_footer();
