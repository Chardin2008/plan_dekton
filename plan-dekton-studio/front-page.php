<?php
/**
 * Front page template.
 *
 * @package PlanDektonStudio
 */

get_header();

if (is_page() && have_posts()) {
    the_post();

    if (trim(get_the_content()) !== '') {
        the_content();
        get_footer();
        return;
    }

    rewind_posts();
}

pds_render_home_sections();

get_footer();
