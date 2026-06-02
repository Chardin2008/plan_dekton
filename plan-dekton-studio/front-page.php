<?php
/**
 * Front page template.
 *
 * @package PlanDektonStudio
 */

get_header();

if (is_page() && have_posts()) {
    the_post();

    $home_content = get_the_content();
    if (trim($home_content) !== '') {
        the_content();
        if (! str_contains($home_content, 'wp:plan-dekton-studio/friend-sites')) {
            pds_render_home_sections(array('friend-sites'));
        }
        get_footer();
        return;
    }

    rewind_posts();
}

pds_render_home_sections();

get_footer();
