<?php
/**
 * Section-based admin editor for the homepage.
 *
 * @package PlanDektonStudio
 */

if (! defined('ABSPATH')) {
    exit;
}

function pds_home_page_id(): int
{
    $front_page_id = (int) get_option('page_on_front');
    if ($front_page_id > 0) {
        return $front_page_id;
    }

    $front_page = get_page_by_path('accueil', OBJECT, 'page');

    return $front_page instanceof WP_Post ? (int) $front_page->ID : 0;
}

function pds_home_meta(string $key, $fallback = '')
{
    $page_id = pds_home_page_id();
    if ($page_id <= 0) {
        return $fallback;
    }

    $value = get_post_meta($page_id, $key, true);

    return '' !== $value && array() !== $value ? $value : $fallback;
}

function pds_site_option(string $key, $fallback = '')
{
    $value = get_option($key, null);

    return null !== $value && '' !== $value && array() !== $value ? $value : $fallback;
}

function pds_resolve_site_link(string $url): string
{
    if (str_starts_with($url, 'mailto:') || str_starts_with($url, 'tel:') || preg_match('#^https?://#', $url)) {
        return $url;
    }

    if (str_starts_with($url, '/')) {
        return home_url($url);
    }

    if (str_starts_with($url, '#')) {
        return home_url('/' . $url);
    }

    return $url;
}

function pds_site_admin_config(): array
{
    return array(
        'identity' => array(
            'title'  => 'Identite, header et navigation',
            'fields' => array(
                'pds_logo_mark' => array('label' => 'Logo marque', 'type' => 'text', 'default' => 'D'),
                'pds_logo_primary' => array('label' => 'Logo texte principal', 'type' => 'text', 'default' => 'PLAN DEKTON'),
                'pds_logo_secondary' => array('label' => 'Logo texte secondaire', 'type' => 'text', 'default' => 'STUDIO'),
                'pds_loader_text' => array('label' => 'Texte loader', 'type' => 'text', 'default' => 'Surface nouvelle génération'),
                'pds_primary_nav_aria_label' => array('label' => 'Label accessibilite navigation', 'type' => 'text', 'default' => 'Navigation principale'),
                'pds_mobile_quote_label' => array('label' => 'Bouton devis mobile', 'type' => 'text', 'default' => 'Demander un devis'),
                'pds_mobile_quote_url' => array('label' => 'Lien devis mobile', 'type' => 'text', 'default' => '/#devis'),
                'pds_header_quote_label' => array('label' => 'Bouton devis header', 'type' => 'text', 'default' => 'Demander un devis'),
                'pds_header_quote_url' => array('label' => 'Lien devis header', 'type' => 'text', 'default' => '/#devis'),
                'pds_menu_toggle_label' => array('label' => 'Label bouton menu', 'type' => 'text', 'default' => 'Ouvrir le menu'),
                'pds_fallback_menu_items' => array('label' => 'Menu par defaut', 'type' => 'repeater', 'columns' => array('label', 'url'), 'default' => array(
                    array('label' => 'Accueil', 'url' => '/#top'),
                    array('label' => 'Matières', 'url' => '/#matieres'),
                    array('label' => 'Ambiances', 'url' => '/#ambiances'),
                    array('label' => 'Applications', 'url' => '/#applications'),
                    array('label' => 'Processus', 'url' => '/#processus'),
                    array('label' => 'Avis', 'url' => '/#avis'),
                    array('label' => 'Devis', 'url' => '/#devis'),
                )),
            ),
        ),
        'footer' => array(
            'title'  => 'Footer',
            'fields' => array(
                'pds_footer_brand_text' => array('label' => 'Texte marque', 'type' => 'textarea', 'default' => 'Surfaces premium pour cuisines, intérieurs et projets architecturaux.'),
                'pds_footer_navigation_title' => array('label' => 'Titre navigation', 'type' => 'text', 'default' => 'Navigation'),
                'pds_footer_contact_title' => array('label' => 'Titre contact', 'type' => 'text', 'default' => 'Contact'),
                'pds_footer_quote_label' => array('label' => 'Lien devis', 'type' => 'text', 'default' => 'Demander un devis'),
                'pds_footer_quote_url' => array('label' => 'URL devis', 'type' => 'text', 'default' => '/#devis'),
                'pds_footer_email' => array('label' => 'Email footer', 'type' => 'email', 'default' => 'contact@plan-dekton.fr'),
                'pds_footer_phone_label' => array('label' => 'Telephone affiche', 'type' => 'text', 'default' => '+33 0 00 00 00 00'),
                'pds_footer_phone_url' => array('label' => 'Telephone lien', 'type' => 'text', 'default' => 'tel:+33000000000'),
                'pds_footer_response_text' => array('label' => 'Texte reponse', 'type' => 'text', 'default' => 'Réponse par email après étude du projet'),
                'pds_footer_area_text' => array('label' => 'Zone', 'type' => 'text', 'default' => 'Paris / Île-de-France'),
                'pds_footer_info_title' => array('label' => 'Titre informations', 'type' => 'text', 'default' => 'Informations'),
                'pds_footer_legal_label' => array('label' => 'Libelle mentions legales', 'type' => 'text', 'default' => 'Mentions légales'),
                'pds_footer_legal_url' => array('label' => 'URL mentions legales', 'type' => 'text', 'default' => '/mentions-legales/'),
                'pds_footer_privacy_label' => array('label' => 'Libelle confidentialite', 'type' => 'text', 'default' => 'Politique de confidentialité'),
                'pds_footer_privacy_url' => array('label' => 'URL confidentialite', 'type' => 'text', 'default' => '/politique-de-confidentialite/'),
                'pds_footer_copyright_name' => array('label' => 'Nom copyright', 'type' => 'text', 'default' => 'Plan Dekton Studio.'),
            ),
        ),
    );
}

function pds_section_admin_config(): array
{
    return array(
        'hero' => array(
            'title'  => 'Hero',
            'fields' => array(
                'pds_hero_enabled' => array('label' => 'Section active', 'type' => 'checkbox'),
                'pds_hero_eyebrow' => array('label' => 'Eyebrow', 'type' => 'text'),
                'pds_hero_title_line_1' => array('label' => 'Titre ligne 1', 'type' => 'text'),
                'pds_hero_title_line_2' => array('label' => 'Titre ligne 2', 'type' => 'text'),
                'pds_hero_title_line_3' => array('label' => 'Titre ligne 3', 'type' => 'text'),
                'pds_hero_lead' => array('label' => 'Texte intro', 'type' => 'textarea'),
                'pds_hero_primary_button_label' => array('label' => 'Bouton principal', 'type' => 'text'),
                'pds_hero_primary_button_url' => array('label' => 'Lien bouton principal', 'type' => 'text'),
                'pds_hero_secondary_button_label' => array('label' => 'Bouton secondaire', 'type' => 'text'),
                'pds_hero_secondary_button_url' => array('label' => 'Lien bouton secondaire', 'type' => 'text'),
                'pds_hero_visual_image' => array('label' => 'Image principale ID', 'type' => 'image'),
                'pds_hero_material_tag' => array('label' => 'Tag matiere', 'type' => 'text'),
                'pds_hero_proof_items' => array('label' => 'Preuves', 'type' => 'list'),
                'pds_hero_floating_cards' => array('label' => 'Cartes flottantes', 'type' => 'repeater', 'columns' => array('label', 'value'), 'default' => array(
                    array('label' => 'Résistance', 'value' => 'Chaleur'),
                    array('label' => 'Finition', 'value' => 'Minérale'),
                    array('label' => 'Usage', 'value' => 'Intérieur / extérieur'),
                )),
                'pds_hero_keywords' => array('label' => 'Mots cles hero', 'type' => 'list', 'default' => array('Cuisine', 'Îlot central', 'Salle de bain', 'Crédence', 'Extérieur')),
            ),
        ),
        'surface' => array(
            'title'  => 'Surface Intelligence',
            'fields' => array(
                'pds_surface_intelligence_enabled' => array('label' => 'Section active', 'type' => 'checkbox'),
                'pds_surface_intelligence_eyebrow' => array('label' => 'Eyebrow', 'type' => 'text'),
                'pds_surface_intelligence_title' => array('label' => 'Titre', 'type' => 'text'),
                'pds_surface_intelligence_intro' => array('label' => 'Intro', 'type' => 'textarea'),
                'pds_surface_intelligence_badges' => array('label' => 'Badges', 'type' => 'list'),
                'pds_surface_intelligence_cards' => array('label' => 'Cartes', 'type' => 'repeater', 'columns' => array('number', 'title', 'text')),
            ),
        ),
        'commercial' => array(
            'title'  => 'Accompagnement projet',
            'fields' => array(
                'pds_commercial_proof_enabled' => array('label' => 'Section active', 'type' => 'checkbox'),
                'pds_commercial_proof_eyebrow' => array('label' => 'Eyebrow', 'type' => 'text'),
                'pds_commercial_proof_title' => array('label' => 'Titre', 'type' => 'text'),
                'pds_commercial_proof_intro' => array('label' => 'Intro', 'type' => 'textarea'),
                'pds_commercial_proof_cards' => array('label' => 'Cartes', 'type' => 'repeater', 'columns' => array('title', 'text')),
                'pds_commercial_proof_primary_button_label' => array('label' => 'Bouton principal', 'type' => 'text'),
                'pds_commercial_proof_primary_button_url' => array('label' => 'Lien bouton principal', 'type' => 'text'),
                'pds_commercial_proof_secondary_button_label' => array('label' => 'Bouton secondaire', 'type' => 'text'),
                'pds_commercial_proof_secondary_button_url' => array('label' => 'Lien bouton secondaire', 'type' => 'text'),
            ),
        ),
        'scanner' => array(
            'title'  => 'Material Scanner',
            'fields' => array(
                'pds_material_scanner_enabled' => array('label' => 'Section active', 'type' => 'checkbox'),
                'pds_material_scanner_eyebrow' => array('label' => 'Eyebrow', 'type' => 'text'),
                'pds_material_scanner_title' => array('label' => 'Titre', 'type' => 'text'),
                'pds_material_scanner_intro' => array('label' => 'Intro', 'type' => 'textarea'),
                'pds_material_scanner_image' => array('label' => 'Image ID', 'type' => 'image'),
                'pds_material_scanner_default_panel_title' => array('label' => 'Titre panneau', 'type' => 'text'),
                'pds_material_scanner_default_panel_text' => array('label' => 'Texte panneau', 'type' => 'textarea'),
                'pds_material_scanner_points' => array('label' => 'Points interactifs', 'type' => 'repeater', 'columns' => array('title', 'x', 'y', 'text')),
            ),
        ),
        'material_lab' => array(
            'title'  => 'Laboratoire matieres',
            'fields' => array(
                'pds_material_lab_enabled' => array('label' => 'Section active', 'type' => 'checkbox'),
                'pds_material_lab_eyebrow' => array('label' => 'Eyebrow', 'type' => 'text'),
                'pds_material_lab_title' => array('label' => 'Titre', 'type' => 'text'),
                'pds_material_lab_intro' => array('label' => 'Intro', 'type' => 'textarea'),
                'pds_material_lab_materials' => array('label' => 'Matieres', 'type' => 'repeater', 'columns' => array('title', 'subtitle', 'text', 'image', 'image_id')),
                'pds_material_lab_card_link_label' => array('label' => 'Libelle lien carte', 'type' => 'text', 'default' => 'Voir l’ambiance'),
                'pds_material_lab_card_link_url' => array('label' => 'Lien carte', 'type' => 'text', 'default' => '#ambiances'),
            ),
        ),
        'moodboard' => array(
            'title'  => 'Moodboard',
            'fields' => array(
                'pds_moodboard_enabled' => array('label' => 'Section active', 'type' => 'checkbox'),
                'pds_moodboard_eyebrow' => array('label' => 'Eyebrow', 'type' => 'text'),
                'pds_moodboard_title' => array('label' => 'Titre', 'type' => 'text'),
                'pds_moodboard_tabs' => array('label' => 'Onglets moodboard', 'type' => 'moodboard_repeater'),
            ),
        ),
        'configurator' => array(
            'title'  => 'Configurateur',
            'fields' => array(
                'pds_project_configurator_enabled' => array('label' => 'Section active', 'type' => 'checkbox'),
                'pds_project_configurator_eyebrow' => array('label' => 'Eyebrow', 'type' => 'text'),
                'pds_project_configurator_title' => array('label' => 'Titre', 'type' => 'text'),
                'pds_project_configurator_questions' => array('label' => 'Questions', 'type' => 'choice_repeater'),
                'pds_project_configurator_default_result' => array('label' => 'Resultat par defaut', 'type' => 'text'),
                'pds_project_configurator_button_label' => array('label' => 'Bouton', 'type' => 'text'),
                'pds_project_configurator_button_url' => array('label' => 'Lien bouton', 'type' => 'text'),
            ),
        ),
        'before_choice' => array(
            'title'  => 'Avant de choisir',
            'fields' => array(
                'pds_before_choice_enabled' => array('label' => 'Section active', 'type' => 'checkbox'),
                'pds_before_choice_eyebrow' => array('label' => 'Eyebrow', 'type' => 'text'),
                'pds_before_choice_title' => array('label' => 'Titre', 'type' => 'text'),
                'pds_before_choice_questions' => array('label' => 'Questions', 'type' => 'list'),
                'pds_before_choice_button_label' => array('label' => 'Bouton', 'type' => 'text'),
                'pds_before_choice_button_url' => array('label' => 'Lien bouton', 'type' => 'text'),
            ),
        ),
        'applications' => array(
            'title'  => 'Applications',
            'fields' => array(
                'pds_applications_enabled' => array('label' => 'Section active', 'type' => 'checkbox'),
                'pds_applications_eyebrow' => array('label' => 'Eyebrow', 'type' => 'text'),
                'pds_applications_title' => array('label' => 'Titre', 'type' => 'text'),
                'pds_applications_items' => array('label' => 'Applications', 'type' => 'repeater', 'columns' => array('title', 'text', 'image', 'image_id')),
                'pds_applications_card_link_label' => array('label' => 'Libelle lien carte', 'type' => 'text', 'default' => 'Demander un devis'),
                'pds_applications_card_link_url' => array('label' => 'Lien carte', 'type' => 'text', 'default' => '#devis'),
            ),
        ),
        'signatures' => array(
            'title'  => 'Signatures',
            'fields' => array(
                'pds_signatures_enabled' => array('label' => 'Section active', 'type' => 'checkbox'),
                'pds_signatures_eyebrow' => array('label' => 'Eyebrow', 'type' => 'text'),
                'pds_signatures_title' => array('label' => 'Titre', 'type' => 'text'),
                'pds_signatures_items' => array('label' => 'Signatures', 'type' => 'repeater', 'columns' => array('number', 'title', 'text', 'image', 'image_id')),
                'pds_signatures_card_link_label' => array('label' => 'Libelle lien carte', 'type' => 'text', 'default' => 'Demander ce style'),
                'pds_signatures_card_link_url' => array('label' => 'Lien carte', 'type' => 'text', 'default' => '#devis'),
            ),
        ),
        'other_sections' => array(
            'title'  => 'Comparateur, processus, details, galerie',
            'fields' => array(
                'pds_comparator_enabled' => array('label' => 'Comparateur actif', 'type' => 'checkbox'),
                'pds_comparator_eyebrow' => array('label' => 'Comparateur eyebrow', 'type' => 'text'),
                'pds_comparator_title' => array('label' => 'Comparateur titre', 'type' => 'text'),
                'pds_comparator_criteria' => array('label' => 'Criteres comparateur', 'type' => 'list'),
                'pds_comparator_dekton_label' => array('label' => 'Libelle Dekton', 'type' => 'text', 'default' => 'Dekton'),
                'pds_comparator_dekton_text' => array('label' => 'Texte Dekton', 'type' => 'textarea', 'default' => 'Très performant si la pose, les découpes et l’entretien sont adaptés au projet.'),
                'pds_comparator_classic_label' => array('label' => 'Libelle surface classique', 'type' => 'text', 'default' => 'Surface classique'),
                'pds_comparator_classic_text' => array('label' => 'Texte surface classique', 'type' => 'textarea', 'default' => 'Résultat variable selon la matière, l’épaisseur, la finition et l’usage quotidien.'),
                'pds_process_enabled' => array('label' => 'Processus actif', 'type' => 'checkbox'),
                'pds_process_eyebrow' => array('label' => 'Processus eyebrow', 'type' => 'text'),
                'pds_process_title' => array('label' => 'Processus titre', 'type' => 'text'),
                'pds_process_steps' => array('label' => 'Etapes', 'type' => 'list'),
                'pds_details_enabled' => array('label' => 'Details actif', 'type' => 'checkbox'),
                'pds_details_eyebrow' => array('label' => 'Details eyebrow', 'type' => 'text'),
                'pds_details_title' => array('label' => 'Details titre', 'type' => 'text'),
                'pds_details_items' => array('label' => 'Items details', 'type' => 'list'),
                'pds_details_card_text' => array('label' => 'Texte cartes details', 'type' => 'textarea', 'default' => 'Un point à valider avant devis pour éviter les approximations et préciser la fabrication.'),
                'pds_details_primary_button_label' => array('label' => 'Details bouton principal', 'type' => 'text'),
                'pds_details_primary_button_url' => array('label' => 'Details lien principal', 'type' => 'text'),
                'pds_details_secondary_button_label' => array('label' => 'Details bouton secondaire', 'type' => 'text'),
                'pds_details_secondary_button_url' => array('label' => 'Details lien secondaire', 'type' => 'text'),
                'pds_gallery_enabled' => array('label' => 'Galerie active', 'type' => 'checkbox'),
                'pds_gallery_eyebrow' => array('label' => 'Galerie eyebrow', 'type' => 'text'),
                'pds_gallery_title' => array('label' => 'Galerie titre', 'type' => 'text'),
                'pds_gallery_items' => array('label' => 'Images galerie', 'type' => 'repeater', 'columns' => array('label', 'image', 'image_id')),
            ),
        ),
        'faq_reviews_cta_form' => array(
            'title'  => 'FAQ, avis, CTA, formulaire',
            'fields' => array(
                'pds_faq_enabled' => array('label' => 'FAQ active', 'type' => 'checkbox'),
                'pds_faq_eyebrow' => array('label' => 'FAQ eyebrow', 'type' => 'text'),
                'pds_faq_title' => array('label' => 'FAQ titre', 'type' => 'text'),
                'pds_faq_questions' => array('label' => 'Questions FAQ', 'type' => 'repeater', 'columns' => array('question', 'answer')),
                'pds_testimonials_enabled' => array('label' => 'Avis actifs', 'type' => 'checkbox'),
                'pds_testimonials_eyebrow' => array('label' => 'Avis eyebrow', 'type' => 'text'),
                'pds_testimonials_title' => array('label' => 'Avis titre', 'type' => 'text'),
                'pds_testimonials_reviews' => array('label' => 'Avis', 'type' => 'repeater', 'columns' => array('name', 'project', 'text')),
                'pds_testimonials_stars' => array('label' => 'Etoiles', 'type' => 'text', 'default' => '★★★★★'),
                'pds_testimonials_stars_label' => array('label' => 'Label etoiles', 'type' => 'text', 'default' => '5 étoiles'),
                'pds_final_cta_enabled' => array('label' => 'CTA final actif', 'type' => 'checkbox'),
                'pds_final_cta_eyebrow' => array('label' => 'CTA eyebrow', 'type' => 'text'),
                'pds_final_cta_title' => array('label' => 'CTA titre', 'type' => 'text'),
                'pds_final_cta_text' => array('label' => 'CTA texte', 'type' => 'textarea'),
                'pds_final_cta_primary_button_label' => array('label' => 'CTA bouton principal', 'type' => 'text'),
                'pds_final_cta_primary_button_url' => array('label' => 'CTA lien principal', 'type' => 'text'),
                'pds_final_cta_secondary_button_label' => array('label' => 'CTA bouton secondaire', 'type' => 'text'),
                'pds_final_cta_secondary_button_url' => array('label' => 'CTA lien secondaire', 'type' => 'text'),
                'pds_quote_form_enabled' => array('label' => 'Formulaire actif', 'type' => 'checkbox'),
                'pds_quote_form_eyebrow' => array('label' => 'Formulaire eyebrow', 'type' => 'text'),
                'pds_quote_form_title' => array('label' => 'Formulaire titre', 'type' => 'text'),
                'pds_quote_form_recipient_email' => array('label' => 'Email destinataire', 'type' => 'email'),
                'pds_quote_form_success_message' => array('label' => 'Message succes', 'type' => 'textarea'),
                'pds_quote_form_error_message' => array('label' => 'Message erreur', 'type' => 'textarea'),
                'pds_quote_form_honeypot_label' => array('label' => 'Label anti-spam', 'type' => 'text', 'default' => 'Site web'),
                'pds_quote_form_progress_label' => array('label' => 'Progression', 'type' => 'text', 'default' => 'Étape 1 / 3'),
                'pds_quote_form_project_legend' => array('label' => 'Legende type projet', 'type' => 'text', 'default' => 'Type de projet'),
                'pds_quote_form_project_options' => array('label' => 'Options type projet', 'type' => 'list', 'default' => array('Cuisine', 'Îlot central', 'Salle de bain', 'Extérieur', 'Autre')),
                'pds_quote_form_style_legend' => array('label' => 'Legende style', 'type' => 'text', 'default' => 'Style souhaité'),
                'pds_quote_form_style_options' => array('label' => 'Options style', 'type' => 'list', 'default' => array('Noir veiné', 'Blanc marbré', 'Gris béton', 'Pierre naturelle', 'Métal oxydé')),
                'pds_quote_form_info_legend' => array('label' => 'Legende informations', 'type' => 'text', 'default' => 'Informations'),
                'pds_quote_form_name_label' => array('label' => 'Label nom', 'type' => 'text', 'default' => 'Nom'),
                'pds_quote_form_email_label' => array('label' => 'Label email', 'type' => 'text', 'default' => 'Email'),
                'pds_quote_form_phone_label' => array('label' => 'Label telephone', 'type' => 'text', 'default' => 'Téléphone'),
                'pds_quote_form_dimensions_label' => array('label' => 'Label dimensions', 'type' => 'text', 'default' => 'Dimensions approximatives'),
                'pds_quote_form_message_label' => array('label' => 'Label message', 'type' => 'text', 'default' => 'Message'),
                'pds_quote_form_prev_label' => array('label' => 'Bouton precedent', 'type' => 'text', 'default' => 'Précédent'),
                'pds_quote_form_next_label' => array('label' => 'Bouton suivant', 'type' => 'text', 'default' => 'Suivant'),
                'pds_quote_form_submit_label' => array('label' => 'Bouton envoyer', 'type' => 'text', 'default' => 'Préparer la demande'),
            ),
        ),
    );
}

function pds_section_admin_add_page(): void
{
    $hook = add_menu_page(
        'Plan Dekton',
        'Plan Dekton',
        'edit_pages',
        'pds-sections',
        'pds_section_admin_render_page',
        'dashicons-layout',
        30
    );

    add_submenu_page(
        'pds-sections',
        'Sections accueil',
        'Sections accueil',
        'edit_pages',
        'pds-sections',
        'pds_section_admin_render_page'
    );

    add_submenu_page(
        'pds-sections',
        'Reglages globaux',
        'Reglages globaux',
        'edit_pages',
        'pds-site-options',
        'pds_site_admin_render_page'
    );
}
add_action('admin_menu', 'pds_section_admin_add_page');

function pds_site_admin_render_page(): void
{
    if (! current_user_can('edit_pages')) {
        wp_die(esc_html__('Acces refuse.', 'plan-dekton-studio'));
    }

    if (isset($_POST['pds_site_options_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pds_site_options_nonce'])), 'pds_save_site_options')) {
        pds_site_admin_save();
        echo '<div class="notice notice-success is-dismissible"><p>Reglages enregistres.</p></div>';
    }

    ?>
    <div class="wrap pds-section-admin">
        <h1>Plan Dekton - reglages globaux</h1>
        <p>Ces champs pilotent les textes et liens du header, du footer, du logo texte et du menu par defaut sans modifier le design.</p>
        <form method="post">
            <?php wp_nonce_field('pds_save_site_options', 'pds_site_options_nonce'); ?>
            <?php foreach (pds_site_admin_config() as $section) : ?>
                <details class="pds-admin-section" open>
                    <summary><strong><?php echo esc_html($section['title']); ?></strong></summary>
                    <table class="form-table" role="presentation">
                        <tbody>
                        <?php foreach ($section['fields'] as $key => $field) : ?>
                            <tr>
                                <th scope="row"><?php echo esc_html($field['label']); ?></th>
                                <td><?php pds_site_admin_render_field($key, $field); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </details>
            <?php endforeach; ?>
            <?php submit_button('Enregistrer les reglages'); ?>
        </form>
    </div>
    <style>
        .pds-admin-section{background:#fff;border:1px solid #dcdcde;margin:14px 0;padding:0 18px}
        .pds-admin-section summary{cursor:pointer;font-size:16px;padding:16px 0}
        .pds-repeat-row{border:1px solid #dcdcde;margin:0 0 10px;padding:12px;background:#f6f7f7}
        .pds-repeat-row label{display:block;margin:0 0 8px;font-weight:600}
        .pds-repeat-row input,.pds-repeat-row textarea,.pds-section-admin textarea.regular-text{width:100%;max-width:820px}
        .pds-help{color:#646970;margin-top:4px}
    </style>
    <?php
}

function pds_section_admin_render_page(): void
{
    if (! current_user_can('edit_pages')) {
        wp_die(esc_html__('Acces refuse.', 'plan-dekton-studio'));
    }

    $page_id = pds_home_page_id();
    if ($page_id <= 0) {
        echo '<div class="wrap"><h1>Plan Dekton</h1><p>Page Accueil introuvable.</p></div>';
        return;
    }

    if (isset($_POST['pds_sections_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pds_sections_nonce'])), 'pds_save_sections')) {
        pds_section_admin_save($page_id);
        echo '<div class="notice notice-success is-dismissible"><p>Sections enregistrees.</p></div>';
    }

    ?>
    <div class="wrap pds-section-admin">
        <h1>Plan Dekton - edition section par section</h1>
        <p>Cette page modifie uniquement les contenus WordPress de la page Accueil. Le design et les fonctionnalites du front restent portes par le theme.</p>
        <form method="post">
            <?php wp_nonce_field('pds_save_sections', 'pds_sections_nonce'); ?>
            <?php foreach (pds_section_admin_config() as $section) : ?>
                <details class="pds-admin-section" open>
                    <summary><strong><?php echo esc_html($section['title']); ?></strong></summary>
                    <table class="form-table" role="presentation">
                        <tbody>
                        <?php foreach ($section['fields'] as $key => $field) : ?>
                            <tr>
                                <th scope="row"><?php echo esc_html($field['label']); ?></th>
                                <td><?php pds_section_admin_render_field($key, $field, $page_id); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </details>
            <?php endforeach; ?>
            <?php submit_button('Enregistrer les sections'); ?>
        </form>
    </div>
    <style>
        .pds-admin-section{background:#fff;border:1px solid #dcdcde;margin:14px 0;padding:0 18px}
        .pds-admin-section summary{cursor:pointer;font-size:16px;padding:16px 0}
        .pds-repeat-row{border:1px solid #dcdcde;margin:0 0 10px;padding:12px;background:#f6f7f7}
        .pds-repeat-row label{display:block;margin:0 0 8px;font-weight:600}
        .pds-repeat-row input,.pds-repeat-row textarea,.pds-section-admin textarea.regular-text{width:100%;max-width:820px}
        .pds-help{color:#646970;margin-top:4px}
    </style>
    <?php
}

function pds_section_admin_render_field(string $key, array $field, int $page_id): void
{
    $type  = $field['type'] ?? 'text';
    $value = get_post_meta($page_id, $key, true);
    if (('' === $value || array() === $value) && array_key_exists('default', $field)) {
        $value = $field['default'];
    }
    $name  = sprintf('pds_sections[%s]', esc_attr($key));

    if ('checkbox' === $type) {
        printf('<label><input type="checkbox" name="%s" value="1" %s> Active</label>', $name, checked('1', (string) $value, false));
        return;
    }

    if ('textarea' === $type) {
        printf('<textarea class="regular-text" rows="4" name="%s">%s</textarea>', $name, esc_textarea((string) $value));
        return;
    }

    if ('email' === $type) {
        printf('<input type="email" class="regular-text" name="%s" value="%s">', $name, esc_attr((string) $value));
        return;
    }

    if ('image' === $type) {
        printf('<input type="number" class="small-text" name="%s" value="%s">', $name, esc_attr((string) $value));
        echo '<p class="pds-help">ID du media WordPress. Les images actuelles restent en place si ce champ n est pas modifie.</p>';
        return;
    }

    if ('list' === $type) {
        $items = is_array($value) ? $value : pds_decode_json_array((string) $value);
        printf('<textarea class="regular-text" rows="6" name="%s">%s</textarea>', $name, esc_textarea(implode("\n", array_map('strval', $items))));
        echo '<p class="pds-help">Une ligne par item.</p>';
        return;
    }

    if ('repeater' === $type) {
        $rows = is_array($value) ? $value : pds_decode_json_array((string) $value);
        pds_section_admin_render_repeater($key, $field['columns'] ?? array(), $rows);
        return;
    }

    if ('choice_repeater' === $type) {
        $rows = is_array($value) ? $value : pds_decode_json_array((string) $value);
        pds_section_admin_render_choice_repeater($key, $rows);
        return;
    }

    if ('moodboard_repeater' === $type) {
        $rows = is_array($value) ? $value : pds_decode_json_array((string) $value);
        pds_section_admin_render_moodboard_repeater($key, $rows);
        return;
    }

    if ('json' === $type) {
        $decoded = pds_decode_json_array((string) $value);
        $pretty  = $decoded ? wp_json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : (string) $value;
        printf('<textarea class="regular-text" rows="10" name="%s">%s</textarea>', $name, esc_textarea((string) $pretty));
        echo '<p class="pds-help">Structure avancee conservee en JSON pour garder les specifications, couleurs et images.</p>';
        return;
    }

    printf('<input type="text" class="regular-text" name="%s" value="%s">', $name, esc_attr((string) $value));
}

function pds_site_admin_render_field(string $key, array $field): void
{
    $type    = $field['type'] ?? 'text';
    $default = $field['default'] ?? '';
    $value   = get_option($key, null);
    if (null === $value || '' === $value || array() === $value) {
        $value = $default;
    }
    $name = sprintf('pds_site_options[%s]', esc_attr($key));

    if ('textarea' === $type) {
        printf('<textarea class="regular-text" rows="4" name="%s">%s</textarea>', $name, esc_textarea((string) $value));
        return;
    }

    if ('email' === $type) {
        printf('<input type="email" class="regular-text" name="%s" value="%s">', $name, esc_attr((string) $value));
        return;
    }

    if ('list' === $type) {
        $items = is_array($value) ? $value : pds_decode_json_array((string) $value);
        printf('<textarea class="regular-text" rows="6" name="%s">%s</textarea>', $name, esc_textarea(implode("\n", array_map('strval', $items))));
        echo '<p class="pds-help">Une ligne par item.</p>';
        return;
    }

    if ('repeater' === $type) {
        $rows = is_array($value) ? $value : pds_decode_json_array((string) $value);
        pds_site_admin_render_repeater($key, $field['columns'] ?? array(), $rows);
        return;
    }

    printf('<input type="text" class="regular-text" name="%s" value="%s">', $name, esc_attr((string) $value));
}

function pds_site_admin_render_repeater(string $key, array $columns, array $rows): void
{
    $rows[] = array();
    foreach ($rows as $index => $row) {
        echo '<div class="pds-repeat-row">';
        foreach ($columns as $column) {
            $value = is_array($row) ? ($row[$column] ?? '') : '';
            printf('<label>%s</label>', esc_html(ucfirst(str_replace('_', ' ', $column))));
            printf('<input type="text" name="pds_site_repeaters[%s][%d][%s]" value="%s">', esc_attr($key), (int) $index, esc_attr($column), esc_attr((string) $value));
        }
        echo '</div>';
    }
    echo '<p class="pds-help">La derniere ligne vide sert a ajouter un nouvel element.</p>';
}

function pds_section_admin_render_repeater(string $key, array $columns, array $rows): void
{
    $rows[] = array();
    foreach ($rows as $index => $row) {
        echo '<div class="pds-repeat-row">';
        foreach ($columns as $column) {
            $value = is_array($row) ? ($row[$column] ?? '') : '';
            printf('<label>%s</label>', esc_html(ucfirst(str_replace('_', ' ', $column))));
            if ('text' === $column || 'answer' === $column) {
                printf('<textarea rows="3" name="pds_repeaters[%s][%d][%s]">%s</textarea>', esc_attr($key), (int) $index, esc_attr($column), esc_textarea((string) $value));
            } else {
                printf('<input type="text" name="pds_repeaters[%s][%d][%s]" value="%s">', esc_attr($key), (int) $index, esc_attr($column), esc_attr((string) $value));
            }
        }
        echo '</div>';
    }
    echo '<p class="pds-help">La derniere ligne vide sert a ajouter un nouvel element.</p>';
}

function pds_section_admin_render_choice_repeater(string $key, array $rows): void
{
    $rows[] = array();
    foreach ($rows as $index => $row) {
        echo '<div class="pds-repeat-row">';
        printf('<label>Key</label><input type="text" name="pds_repeaters[%s][%d][key]" value="%s">', esc_attr($key), (int) $index, esc_attr((string) ($row['key'] ?? '')));
        printf('<label>Question</label><input type="text" name="pds_repeaters[%s][%d][question]" value="%s">', esc_attr($key), (int) $index, esc_attr((string) ($row['question'] ?? '')));
        $choices = isset($row['choices']) && is_array($row['choices']) ? implode("\n", array_map('strval', $row['choices'])) : '';
        printf('<label>Choix, un par ligne</label><textarea rows="4" name="pds_repeaters[%s][%d][choices]">%s</textarea>', esc_attr($key), (int) $index, esc_textarea($choices));
        echo '</div>';
    }
}

function pds_section_admin_render_moodboard_repeater(string $key, array $rows): void
{
    $rows[] = array();
    foreach ($rows as $index => $row) {
        $specs = '';
        if (isset($row['specs']) && is_array($row['specs'])) {
            foreach ($row['specs'] as $label => $value) {
                $specs .= $label . ': ' . $value . "\n";
            }
        }

        $swatches = isset($row['swatches']) && is_array($row['swatches']) ? implode("\n", array_map('strval', $row['swatches'])) : '';

        echo '<div class="pds-repeat-row">';
        printf('<label>Key</label><input type="text" name="pds_repeaters[%s][%d][key]" value="%s">', esc_attr($key), (int) $index, esc_attr((string) ($row['key'] ?? '')));
        printf('<label>Titre</label><input type="text" name="pds_repeaters[%s][%d][title]" value="%s">', esc_attr($key), (int) $index, esc_attr((string) ($row['title'] ?? '')));
        printf('<label>Style</label><input type="text" name="pds_repeaters[%s][%d][style]" value="%s">', esc_attr($key), (int) $index, esc_attr((string) ($row['style'] ?? '')));
        printf('<label>Image fichier</label><input type="text" name="pds_repeaters[%s][%d][image]" value="%s">', esc_attr($key), (int) $index, esc_attr((string) ($row['image'] ?? '')));
        printf('<label>Image ID</label><input type="text" name="pds_repeaters[%s][%d][image_id]" value="%s">', esc_attr($key), (int) $index, esc_attr((string) ($row['image_id'] ?? '')));
        printf('<label>Specs, format Label: valeur</label><textarea rows="5" name="pds_repeaters[%s][%d][specs]">%s</textarea>', esc_attr($key), (int) $index, esc_textarea(trim($specs)));
        printf('<label>Couleurs, une par ligne</label><textarea rows="4" name="pds_repeaters[%s][%d][swatches]">%s</textarea>', esc_attr($key), (int) $index, esc_textarea($swatches));
        echo '</div>';
    }
}

function pds_section_admin_save(int $page_id): void
{
    $config = pds_section_admin_config();
    $fields = array();
    foreach ($config as $section) {
        $fields = array_merge($fields, $section['fields']);
    }

    $posted = isset($_POST['pds_sections']) && is_array($_POST['pds_sections']) ? wp_unslash($_POST['pds_sections']) : array();
    $repeaters = isset($_POST['pds_repeaters']) && is_array($_POST['pds_repeaters']) ? wp_unslash($_POST['pds_repeaters']) : array();

    foreach ($fields as $key => $field) {
        $type = $field['type'] ?? 'text';
        if ('checkbox' === $type) {
            update_post_meta($page_id, $key, isset($posted[$key]) ? '1' : '0');
            continue;
        }

        if ('list' === $type) {
            $lines = isset($posted[$key]) ? preg_split('/\R/', (string) $posted[$key]) : array();
            update_post_meta($page_id, $key, pds_json_encode_clean_list((array) $lines));
            continue;
        }

        if ('repeater' === $type || 'choice_repeater' === $type) {
            update_post_meta($page_id, $key, pds_json_encode_clean_repeater($repeaters[$key] ?? array(), $field['columns'] ?? array(), 'choice_repeater' === $type));
            continue;
        }

        if ('moodboard_repeater' === $type) {
            update_post_meta($page_id, $key, pds_json_encode_clean_moodboards($repeaters[$key] ?? array()));
            continue;
        }

        if ('json' === $type) {
            $raw = isset($posted[$key]) ? (string) $posted[$key] : '';
            $decoded = json_decode($raw, true);
            update_post_meta($page_id, $key, is_array($decoded) ? wp_json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : wp_kses_post($raw));
            continue;
        }

        $value = isset($posted[$key]) ? (string) $posted[$key] : '';
        if ('email' === $type) {
            $value = sanitize_email($value);
        } elseif ('image' === $type) {
            $value = (string) absint($value);
        } elseif ('textarea' === $type) {
            $value = sanitize_textarea_field($value);
        } else {
            $value = sanitize_text_field($value);
        }
        update_post_meta($page_id, $key, $value);
    }

    if (function_exists('pds_get_cf7_form_id') && function_exists('pds_configure_cf7_form')) {
        $form_id = pds_get_cf7_form_id();
        if ($form_id > 0) {
            pds_configure_cf7_form($form_id);
        }
    }
}

function pds_site_admin_save(): void
{
    $config = pds_site_admin_config();
    $fields = array();
    foreach ($config as $section) {
        $fields = array_merge($fields, $section['fields']);
    }

    $posted = isset($_POST['pds_site_options']) && is_array($_POST['pds_site_options']) ? wp_unslash($_POST['pds_site_options']) : array();
    $repeaters = isset($_POST['pds_site_repeaters']) && is_array($_POST['pds_site_repeaters']) ? wp_unslash($_POST['pds_site_repeaters']) : array();

    foreach ($fields as $key => $field) {
        $type = $field['type'] ?? 'text';

        if ('list' === $type) {
            $lines = isset($posted[$key]) ? preg_split('/\R/', (string) $posted[$key]) : array();
            update_option($key, pds_json_encode_clean_list((array) $lines), false);
            continue;
        }

        if ('repeater' === $type) {
            update_option($key, pds_json_encode_clean_repeater($repeaters[$key] ?? array(), $field['columns'] ?? array()), false);
            continue;
        }

        $value = isset($posted[$key]) ? (string) $posted[$key] : '';
        if ('email' === $type) {
            $value = sanitize_email($value);
        } elseif ('textarea' === $type) {
            $value = sanitize_textarea_field($value);
        } else {
            $value = sanitize_text_field($value);
        }

        update_option($key, $value, false);
    }
}

function pds_decode_json_array(string $value): array
{
    $decoded = json_decode($value, true);

    return is_array($decoded) ? $decoded : array();
}

function pds_json_encode_clean_list(array $lines): string
{
    $items = array();
    foreach ($lines as $line) {
        $line = trim(sanitize_text_field((string) $line));
        if ('' !== $line) {
            $items[] = $line;
        }
    }

    return wp_json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function pds_json_encode_clean_repeater(array $rows, array $columns, bool $choices = false): string
{
    $clean = array();
    foreach ($rows as $row) {
        if (! is_array($row)) {
            continue;
        }

        $item = array();
        $has_content = false;
        $field_names = $choices ? array('key', 'question', 'choices') : $columns;
        foreach ($field_names as $column) {
            $value = $row[$column] ?? '';
            if ('choices' === $column) {
                $value = array_filter(array_map('trim', preg_split('/\R/', (string) $value) ?: array()));
                $value = array_map('sanitize_text_field', $value);
            } elseif ('image_id' === $column) {
                $value = absint($value);
            } elseif ('text' === $column || 'answer' === $column) {
                $value = sanitize_textarea_field((string) $value);
            } else {
                $value = sanitize_text_field((string) $value);
            }

            if ((is_array($value) && $value) || (! is_array($value) && '' !== (string) $value && '0' !== (string) $value)) {
                $has_content = true;
            }
            $item[$column] = $value;
        }

        if ($has_content) {
            $clean[] = $item;
        }
    }

    return wp_json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function pds_json_encode_clean_moodboards(array $rows): string
{
    $clean = array();
    foreach ($rows as $row) {
        if (! is_array($row)) {
            continue;
        }

        $item = array(
            'key'      => sanitize_key((string) ($row['key'] ?? '')),
            'title'    => sanitize_text_field((string) ($row['title'] ?? '')),
            'style'    => sanitize_text_field((string) ($row['style'] ?? '')),
            'image'    => sanitize_file_name((string) ($row['image'] ?? '')),
            'image_id' => absint($row['image_id'] ?? 0),
            'specs'    => array(),
            'swatches' => array(),
        );

        $spec_lines = preg_split('/\R/', (string) ($row['specs'] ?? '')) ?: array();
        foreach ($spec_lines as $line) {
            if (! str_contains($line, ':')) {
                continue;
            }
            [$label, $value] = array_map('trim', explode(':', $line, 2));
            if ('' !== $label && '' !== $value) {
                $item['specs'][sanitize_text_field($label)] = sanitize_text_field($value);
            }
        }

        $swatch_lines = preg_split('/\R/', (string) ($row['swatches'] ?? '')) ?: array();
        foreach ($swatch_lines as $swatch) {
            $swatch = sanitize_hex_color(trim($swatch));
            if ($swatch) {
                $item['swatches'][] = $swatch;
            }
        }

        if ('' !== $item['key'] || '' !== $item['title']) {
            $clean[] = $item;
        }
    }

    return wp_json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
