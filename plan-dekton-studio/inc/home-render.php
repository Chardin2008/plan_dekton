<?php
/**
 * Reusable homepage section rendering and Gutenberg blocks.
 *
 * @package PlanDektonStudio
 */

if (! defined('ABSPATH')) {
    exit;
}

function pds_home_should_render_section(string $section, ?array $only_sections = null): bool
{
    return null === $only_sections || in_array($section, $only_sections, true);
}

function pds_render_home_sections(?array $only_sections = null): void
{
$img = static function (string $file): string {
    return get_theme_file_uri('assets/img/' . $file);
};

$image_dimensions = array(
    'hero-dekton.jpg'              => array('1717', '916'),
    'texture-noir-veine.jpg'       => array('1536', '1024'),
    'texture-blanc-marbre.jpg'     => array('1536', '1024'),
    'texture-gris-beton.jpg'       => array('1536', '1024'),
    'texture-pierre-naturelle.jpg' => array('1536', '1024'),
    'texture-brun-terre.jpg'       => array('1536', '1024'),
    'texture-metal-oxyde.jpg'      => array('1536', '1024'),
    'ambiance-obsidian.jpg'        => array('1536', '1024'),
    'ambiance-mineral.jpg'         => array('1536', '1024'),
    'ambiance-urban.jpg'           => array('1581', '995'),
    'gallery-1.jpg'                => array('1024', '1536'),
    'gallery-2.jpg'                => array('1536', '1024'),
    'gallery-3.jpg'                => array('1024', '1536'),
    'gallery-4.jpg'                => array('1536', '1024'),
    'gallery-5.jpg'                => array('1024', '1536'),
    'gallery-6.jpg'                => array('1536', '1024'),
);

$picture = static function ($file, string $alt, string $width, string $height, array $attrs = array()) use ($img, $image_dimensions): void {
    if (is_numeric($file) && (int) $file > 0) {
        if (! isset($attrs['decoding'])) {
            $attrs['decoding'] = 'async';
        }

        $attrs['alt']    = $alt;
        $attrs['width']  = $width;
        $attrs['height'] = $height;

        echo wp_get_attachment_image((int) $file, 'full', false, $attrs); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        return;
    }

    $webp = preg_replace('/\.jpe?g$/i', '.webp', $file);
    $attr_markup = '';

    if (isset($image_dimensions[$file])) {
        $width  = $image_dimensions[$file][0];
        $height = $image_dimensions[$file][1];
    }

    if (! isset($attrs['decoding'])) {
        $attrs['decoding'] = 'async';
    }

    foreach ($attrs as $name => $value) {
        $attr_markup .= sprintf(' %s="%s"', esc_attr($name), esc_attr($value));
    }

    printf(
        '<picture><source srcset="%1$s" type="image/webp"><img src="%2$s" alt="%3$s" width="%4$s" height="%5$s"%6$s></picture>',
        esc_url($img($webp)),
        esc_url($img($file)),
        esc_attr($alt),
        esc_attr($width),
        esc_attr($height),
        $attr_markup
    );
};

$home_text = static function (string $name, string $fallback): string {
    $value = pds_home_meta($name, $fallback);

    return is_string($value) && '' !== trim($value) ? $value : $fallback;
};

$acf_text = static function (string $name, string $fallback) use ($home_text): string {
    if (function_exists('get_field')) {
        $value = get_field($name);
        if (is_string($value) && '' !== trim($value)) {
            return $value;
        }
    }

    return $home_text($name, $fallback);
};

$section_enabled = static function (string $name): bool {
    $value = pds_home_meta($name, null);
    if (null !== $value) {
        return '0' !== (string) $value;
    }

    if (function_exists('get_field')) {
        $value = get_field($name);

        return '0' !== (string) $value;
    }

    return true;
};

$acf_json = static function (string $name, array $fallback): array {
    $value = pds_home_meta($name, '');
    if (is_string($value) && '' !== trim($value)) {
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return $decoded;
        }
    }

    if (function_exists('get_field')) {
        $value = get_field($name);
        if (! is_string($value) || '' === trim($value)) {
            return $fallback;
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : $fallback;
    }

    return $fallback;
};

$home_json = static function (string $name, array $fallback): array {
    $value = pds_home_meta($name, '');
    if (! is_string($value) || '' === trim($value)) {
        return $fallback;
    }

    $decoded = json_decode($value, true);

    return is_array($decoded) ? $decoded : $fallback;
};

$acf_image = static function (string $name, string $fallback) {
    $home_value = pds_home_meta($name, '');
    if (is_numeric($home_value) && (int) $home_value > 0) {
        return (int) $home_value;
    }

    if (! function_exists('get_field')) {
        return $fallback;
    }

    $value = get_field($name);
    if (! is_numeric($value) || (int) $value <= 0) {
        return $fallback;
    }

    $file = get_attached_file((int) $value);
    if (is_string($file) && basename($file) === $fallback) {
        return $fallback;
    }

    return (int) $value;
};

$acf_file = static function (array $item, string $fallback = '') {
    if (isset($item['image_id']) && (int) $item['image_id'] > 0) {
        $file = get_attached_file((int) $item['image_id']);
        if (! is_string($file) || basename($file) !== ($item['image'] ?? '')) {
            return (int) $item['image_id'];
        }
    }

    return $item['image'] ?? $fallback;
};

$hero = array(
    'eyebrow'         => $acf_text('pds_hero_eyebrow', 'Studio de surfaces premium'),
    'title_line_1'    => $acf_text('pds_hero_title_line_1', 'Surface'),
    'title_line_2'    => $acf_text('pds_hero_title_line_2', 'nouvelle'),
    'title_line_3'    => $acf_text('pds_hero_title_line_3', 'génération'),
    'lead'            => $acf_text('pds_hero_lead', 'Des surfaces premium pour cuisines, îlots, salles de bain et projets architecturaux.'),
    'primary_label'   => $acf_text('pds_hero_primary_button_label', 'Demander un devis'),
    'primary_url'     => $acf_text('pds_hero_primary_button_url', '#devis'),
    'secondary_label' => $acf_text('pds_hero_secondary_button_label', 'Explorer les matières'),
    'secondary_url'   => $acf_text('pds_hero_secondary_button_url', '#matieres'),
    'image'           => $acf_image('pds_hero_visual_image', 'hero-dekton.jpg'),
    'material_tag'    => $acf_text('pds_hero_material_tag', 'Dekton · plan de travail · îlot · crédence'),
    'proof_items'     => $acf_json('pds_hero_proof_items', array('Étude du projet', 'Choix de finition', 'Devis accompagné')),
    'floating_cards'  => $home_json('pds_hero_floating_cards', array(
        array('label' => 'Résistance', 'value' => 'Chaleur'),
        array('label' => 'Finition', 'value' => 'Minérale'),
        array('label' => 'Usage', 'value' => 'Intérieur / extérieur'),
    )),
    'keywords'        => $home_json('pds_hero_keywords', array('Cuisine', 'Îlot central', 'Salle de bain', 'Crédence', 'Extérieur')),
);

$surface = array(
    'eyebrow' => $acf_text('pds_surface_intelligence_eyebrow', 'Surface Intelligence'),
    'title'   => $acf_text('pds_surface_intelligence_title', 'Une surface pensée pour les espaces exigeants.'),
    'intro'   => $acf_text('pds_surface_intelligence_intro', 'Dekton permet de concevoir un plan esthétique et technique : dimensions, découpes, chants, crédence et intégration de l’évier doivent être anticipés dès le brief.'),
    'badges'  => $acf_json('pds_surface_intelligence_badges', array('Résistance quotidienne', 'Finition architecturale', 'Entretien facilité')),
);

$features = array(
    array('01', 'Chaleur', 'Une surface pensée pour les casseroles, plaques chaudes et usages intensifs du quotidien.'),
    array('02', 'Rayures', 'Une matière adaptée aux plans sollicités, à condition de respecter les bons gestes de coupe.'),
    array('03', 'Taches', 'Un entretien simple avec une éponge douce, de l’eau chaude et un produit adapté.'),
    array('04', 'UV', 'Une option pertinente pour certains projets lumineux ou extérieurs selon l’exposition et la pose.'),
    array('05', 'Usage intensif', 'Un choix durable pour cuisines familiales, îlots centraux, crédences et espaces très utilisés.'),
);
$features = array_map(
    static fn (array $item): array => array($item['number'] ?? '', $item['title'] ?? '', $item['text'] ?? ''),
    $acf_json('pds_surface_intelligence_cards', $features)
);

$materials = array(
    array('Noir veiné', 'luxe sombre', 'Une présence forte pour les cuisines architecturales.', 'texture-noir-veine.jpg'),
    array('Blanc marbré', 'lumière minérale', 'Un rendu clair, élégant et intemporel.', 'texture-blanc-marbre.jpg'),
    array('Gris béton', 'contemporain urbain', 'Un style brut, moderne et structuré.', 'texture-gris-beton.jpg'),
    array('Pierre naturelle', 'organique premium', 'Une texture chaleureuse inspirée de la matière.', 'texture-pierre-naturelle.jpg'),
    array('Brun terre', 'bois, argile et chaleur', 'Une teinte profonde pour des intérieurs enveloppants.', 'texture-brun-terre.jpg'),
    array('Métal oxydé', 'industriel chic', 'Une finition expressive pour les projets audacieux.', 'texture-metal-oxyde.jpg'),
);
$materials = array_map(
    static fn (array $item): array => array($item['title'] ?? '', $item['subtitle'] ?? '', $item['text'] ?? '', $acf_file($item, '')),
    $acf_json('pds_material_lab_materials', $materials)
);

$applications = array(
    array('Plan de travail cuisine', 'Une surface sur mesure avec découpes pour évier, plaque de cuisson et prises selon le projet.', 'gallery-1.jpg'),
    array('Îlot central', 'Une pièce centrale à dimensionner avec précision pour les repas, la préparation et la circulation.', 'gallery-2.jpg'),
    array('Crédence', 'Une continuité visuelle entre le plan et le mur, pratique pour protéger les zones exposées.', 'gallery-4.jpg'),
    array('Salle de bain', 'Un rendu minéral adapté aux plans vasque, habillages et espaces d’eau contemporains.', 'gallery-3.jpg'),
    array('Table sur mesure', 'Une pièce forte à penser selon le piètement, l’épaisseur, les chants et l’usage quotidien.', 'gallery-5.jpg'),
    array('Extérieur', 'Une solution à étudier selon l’exposition, le support, les joints et les contraintes de pose.', 'gallery-6.jpg'),
);
$applications = array_map(
    static fn (array $item): array => array($item['title'] ?? '', $item['text'] ?? '', $acf_file($item, '')),
    $acf_json('pds_applications_items', $applications)
);

$commitments = array(
    array('Conseil matière', 'Nous guidons le choix du coloris, du veinage et de la finition selon la lumière, l’usage et le style de votre pièce.'),
    array('Projet sur mesure', 'Chaque demande est étudiée selon les dimensions, les découpes, le type de chant, l’évier, la crédence et les contraintes de pose.'),
    array('Devis qualifié', 'Le formulaire sert de brief projet : vous recevez une réponse plus précise, avec les informations utiles pour avancer sereinement.'),
);
$commitments = array_map(
    static fn (array $item): array => array($item['title'] ?? '', $item['text'] ?? ''),
    $acf_json('pds_commercial_proof_cards', $commitments)
);

$commercial = array(
    'eyebrow'         => $acf_text('pds_commercial_proof_eyebrow', 'Accompagnement projet'),
    'title'           => $acf_text('pds_commercial_proof_title', 'Un plan Dekton se choisit avec précision.'),
    'intro'           => $acf_text('pds_commercial_proof_intro', 'Au-delà de l’image, nous aidons à cadrer les éléments qui font la différence : usage, dimensions, finitions, contraintes techniques et rendu final.'),
    'primary_label'   => $acf_text('pds_commercial_proof_primary_button_label', 'Préparer mon devis'),
    'primary_url'     => $acf_text('pds_commercial_proof_primary_button_url', '#devis'),
    'secondary_label' => $acf_text('pds_commercial_proof_secondary_button_label', 'Comparer les matières'),
    'secondary_url'   => $acf_text('pds_commercial_proof_secondary_button_url', '#matieres'),
);

$scanner = array(
    'eyebrow'     => $acf_text('pds_material_scanner_eyebrow', 'Material Scanner'),
    'title'       => $acf_text('pds_material_scanner_title', 'Analysez la surface idéale pour votre projet.'),
    'intro'       => $acf_text('pds_material_scanner_intro', 'Couleur, veinage, finition, épaisseur et usage quotidien : chaque choix influence le rendu, l’entretien et le budget final.'),
    'image'       => $acf_image('pds_material_scanner_image', 'texture-noir-veine.jpg'),
    'panel_title' => $acf_text('pds_material_scanner_default_panel_title', 'Veinage'),
    'panel_text'  => $acf_text('pds_material_scanner_default_panel_text', 'Un effet minéral profond pour donner du caractère au plan de travail.'),
    'points'      => $acf_json('pds_material_scanner_points', array(
        array('title' => 'Veinage', 'x' => '28%', 'y' => '38%', 'text' => 'Un effet minéral profond pour donner du caractère au plan de travail.'),
        array('title' => 'Finition', 'x' => '58%', 'y' => '25%', 'text' => 'Une surface élégante pensée pour un rendu contemporain.'),
        array('title' => 'Résistance', 'x' => '70%', 'y' => '58%', 'text' => 'Une matière adaptée aux usages exigeants du quotidien.'),
        array('title' => 'Ambiance', 'x' => '36%', 'y' => '70%', 'text' => 'Une présence visuelle forte pour les cuisines premium.'),
        array('title' => 'Usage conseillé', 'x' => '78%', 'y' => '78%', 'text' => 'Idéal pour plan de travail, îlot central, crédence ou projet sur mesure.'),
    )),
);

$material_lab = array(
    'eyebrow' => $acf_text('pds_material_lab_eyebrow', 'Le laboratoire des matières'),
    'title'   => $acf_text('pds_material_lab_title', 'Un showroom digital pour comparer les signatures.'),
    'intro'   => $acf_text('pds_material_lab_intro', 'Chaque finition raconte une ambiance : profondeur sombre, lumière minérale, béton urbain ou chaleur organique.'),
    'link_label' => $home_text('pds_material_lab_card_link_label', 'Voir l’ambiance'),
    'link_url'   => $home_text('pds_material_lab_card_link_url', '#ambiances'),
);

$moodboard = array(
    'eyebrow' => $acf_text('pds_moodboard_eyebrow', 'Moodboard dynamique'),
    'title'   => $acf_text('pds_moodboard_title', 'Composez une ambiance, pas seulement un plan.'),
    'tabs'    => $acf_json('pds_moodboard_tabs', array(
        array('key' => 'obsidian', 'title' => 'Obsidian Luxury', 'style' => 'luxe architectural', 'image' => 'ambiance-obsidian.jpg'),
        array('key' => 'mineral', 'title' => 'Mineral White', 'style' => 'minimalisme lumineux', 'image' => 'ambiance-mineral.jpg'),
        array('key' => 'urban', 'title' => 'Urban Stone', 'style' => 'contemporain architectural', 'image' => 'ambiance-urban.jpg'),
    )),
);
$active_mood = $moodboard['tabs'][0] ?? array();
$active_mood_image = $acf_file($active_mood, 'ambiance-obsidian.jpg');

$configurator = array(
    'eyebrow'       => $acf_text('pds_project_configurator_eyebrow', 'Configurateur de projet'),
    'title'         => $acf_text('pds_project_configurator_title', 'Quel plan Dekton correspond à votre espace ?'),
    'questions'     => $acf_json('pds_project_configurator_questions', array(
        array('key' => 'project', 'question' => 'Quel est votre projet ?', 'choices' => array('Cuisine', 'Îlot central', 'Salle de bain', 'Extérieur')),
        array('key' => 'style', 'question' => 'Quel style préférez-vous ?', 'choices' => array('Sombre', 'Clair', 'Pierre', 'Béton', 'Métal')),
        array('key' => 'mood', 'question' => 'Quelle ambiance recherchez-vous ?', 'choices' => array('Luxe', 'Minimaliste', 'Naturelle', 'Industrielle')),
    )),
    'default_result' => $acf_text('pds_project_configurator_default_result', 'Votre ambiance recommandée : Obsidian Luxury'),
    'button_label'   => $acf_text('pds_project_configurator_button_label', 'Demander un devis pour cette ambiance'),
    'button_url'     => $acf_text('pds_project_configurator_button_url', '#devis'),
);

$before_choice = array(
    'eyebrow'      => $acf_text('pds_before_choice_eyebrow', 'Avant de choisir votre plan Dekton'),
    'title'        => $acf_text('pds_before_choice_title', 'Quatre questions pour préparer un devis précis.'),
    'questions'    => $acf_json('pds_before_choice_questions', array('Quelles dimensions approximatives ?', 'Évier, plaque ou prises à intégrer ?', 'Crédence assortie ou plan seul ?', 'Effet marbre, béton, pierre ou métal ?')),
    'button_label' => $acf_text('pds_before_choice_button_label', 'Préparer mon devis'),
    'button_url'   => $acf_text('pds_before_choice_button_url', '#devis'),
);

$applications_meta = array(
    'eyebrow' => $acf_text('pds_applications_eyebrow', 'Une matière, plusieurs espaces'),
    'title'   => $acf_text('pds_applications_title', 'Des usages pensés comme des pièces de design.'),
    'link_label' => $home_text('pds_applications_card_link_label', 'Demander un devis'),
    'link_url'   => $home_text('pds_applications_card_link_url', '#devis'),
);

$signatures = array_map(
    static fn (array $item): array => array($item['number'] ?? '', $item['title'] ?? '', $item['text'] ?? '', $acf_file($item, '')),
    $acf_json('pds_signatures_items', array(
        array('number' => '01', 'title' => 'Obsidian Luxury', 'text' => 'Noir profond, veines dorées, bois noyer et lumière chaude pour une cuisine au caractère affirmé.', 'image' => 'ambiance-obsidian.jpg'),
        array('number' => '02', 'title' => 'Mineral White', 'text' => 'Blanc veiné, meubles clairs et lumière naturelle pour un espace lumineux, propre et intemporel.', 'image' => 'ambiance-mineral.jpg'),
        array('number' => '03', 'title' => 'Urban Stone', 'text' => 'Gris béton, noir mat et lignes tendues pour une atmosphère contemporaine et architecturale.', 'image' => 'ambiance-urban.jpg'),
    ))
);
$signatures_meta = array(
    'eyebrow' => $acf_text('pds_signatures_eyebrow', 'Signatures visuelles'),
    'title'   => $acf_text('pds_signatures_title', 'Trois directions artistiques fortes.'),
    'link_label' => $home_text('pds_signatures_card_link_label', 'Demander ce style'),
    'link_url'   => $home_text('pds_signatures_card_link_url', '#devis'),
);

$comparator = array(
    'eyebrow'  => $acf_text('pds_comparator_eyebrow', 'Dekton vs surface classique'),
    'title'    => $acf_text('pds_comparator_title', 'Les bons critères avant de choisir.'),
    'criteria' => $acf_json('pds_comparator_criteria', array('chaleur', 'rayures', 'taches', 'extérieur', 'rendu esthétique', 'entretien')),
    'dekton_label'  => $home_text('pds_comparator_dekton_label', 'Dekton'),
    'dekton_text'   => $home_text('pds_comparator_dekton_text', 'Très performant si la pose, les découpes et l’entretien sont adaptés au projet.'),
    'classic_label' => $home_text('pds_comparator_classic_label', 'Surface classique'),
    'classic_text'  => $home_text('pds_comparator_classic_text', 'Résultat variable selon la matière, l’épaisseur, la finition et l’usage quotidien.'),
);

$process = array(
    'eyebrow' => $acf_text('pds_process_eyebrow', 'De l’idée à la surface finale'),
    'title'   => $acf_text('pds_process_title', 'Un déroulé clair, du brief à la pose.'),
    'steps'   => $acf_json('pds_process_steps', array('Brief et dimensions', 'Choix du coloris', 'Validation des découpes', 'Prise de cotes précise', 'Fabrication du plan', 'Pose et finitions')),
);

$details = array(
    'eyebrow'         => $acf_text('pds_details_eyebrow', 'Les détails invisibles font le luxe visible'),
    'title'           => $acf_text('pds_details_title', 'Les choix techniques qui changent le résultat.'),
    'items'           => $acf_json('pds_details_items', array('Épaisseur du plan', 'Type de chant', 'Finition mate ou satinée', 'Évier sous plan ou posé', 'Crédence assortie', 'Arrondis et découpes spéciales')),
    'primary_label'   => $acf_text('pds_details_primary_button_label', 'Valider ces détails'),
    'primary_url'     => $acf_text('pds_details_primary_button_url', '#devis'),
    'secondary_label' => $acf_text('pds_details_secondary_button_label', 'Voir le processus'),
    'secondary_url'   => $acf_text('pds_details_secondary_button_url', '#processus'),
    'card_text'     => $home_text('pds_details_card_text', 'Un point à valider avant devis pour éviter les approximations et préciser la fabrication.'),
);

$gallery = array_map(
    static fn (array $item): array => array($item['label'] ?? '', $acf_file($item, '')),
    $acf_json('pds_gallery_items', array(
        array('label' => 'Cuisine', 'image' => 'gallery-1.jpg'),
        array('label' => 'Îlot', 'image' => 'gallery-2.jpg'),
        array('label' => 'Salle de bain', 'image' => 'gallery-3.jpg'),
        array('label' => 'Crédence', 'image' => 'gallery-4.jpg'),
        array('label' => 'Détail matière', 'image' => 'gallery-5.jpg'),
        array('label' => 'Extérieur', 'image' => 'gallery-6.jpg'),
    ))
);
$gallery_meta = array(
    'eyebrow' => $acf_text('pds_gallery_eyebrow', 'Galerie d’inspirations'),
    'title'   => $acf_text('pds_gallery_title', 'Des cadrages pour se projeter.'),
);

$faqs = array();
foreach ($acf_json('pds_faq_questions', array(
    array('question' => 'Quelles informations faut-il pour un devis ?', 'answer' => 'Les dimensions approximatives, le type de projet, le style souhaité, les découpes prévues, la présence d’une crédence et vos contraintes de pose.'),
    array('question' => 'Dekton résiste-t-il à la chaleur ?', 'answer' => 'Dekton offre une très bonne résistance à la chaleur dans les usages courants, mais le projet doit toujours être validé selon la pose et les contraintes réelles.'),
    array('question' => 'Peut-on intégrer un évier ou une plaque ?', 'answer' => 'Oui, les découpes peuvent être prévues pour évier, plaque de cuisson, robinetterie ou prises, avec une validation technique avant fabrication.'),
    array('question' => 'Est-ce facile à entretenir ?', 'answer' => 'Oui, l’entretien courant se fait avec de l’eau chaude, une éponge douce et un produit adapté, en évitant les gestes abrasifs inutiles.'),
    array('question' => 'Peut-on avoir un plan Dekton sur mesure ?', 'answer' => 'Oui, le plan peut être étudié selon les dimensions, l’épaisseur, le type de chant, la finition, les découpes et la crédence souhaitée.'),
)) as $faq) {
    if (isset($faq['question'], $faq['answer'])) {
        $faqs[$faq['question']] = $faq['answer'];
    }
}
$faq_meta = array(
    'eyebrow' => $acf_text('pds_faq_eyebrow', 'Questions fréquentes'),
    'title'   => $acf_text('pds_faq_title', 'Les réponses utiles avant de demander un devis.'),
);

$reviews = array_map(
    static fn (array $item): array => array($item['name'] ?? '', $item['project'] ?? '', $item['text'] ?? ''),
    $acf_json('pds_testimonials_reviews', array(
        array('name' => 'Nadia M.', 'project' => 'Cuisine avec îlot central', 'text' => 'Le rendu est élégant, solide et vraiment haut de gamme. Le plan a changé toute l’ambiance de la cuisine.'),
        array('name' => 'Thomas R.', 'project' => 'Rénovation cuisine', 'text' => 'Le plan Dekton apporte un style beaucoup plus moderne. La pièce paraît plus structurée et plus premium.'),
        array('name' => 'Sarah L.', 'project' => 'Salle de bain', 'text' => 'Le résultat est propre, contemporain et facile à entretenir. C’est exactement l’ambiance que je voulais.'),
    ))
);
$testimonials_meta = array(
    'eyebrow' => $acf_text('pds_testimonials_eyebrow', '4.9/5 · témoignages clients'),
    'title'   => $acf_text('pds_testimonials_title', 'Ils ont transformé leur espace'),
    'stars' => $home_text('pds_testimonials_stars', html_entity_decode('&#9733;&#9733;&#9733;&#9733;&#9733;', ENT_QUOTES, 'UTF-8')),
    'stars_label' => $home_text('pds_testimonials_stars_label', '5 étoiles'),
);

$final_cta = array(
    'eyebrow'         => $acf_text('pds_final_cta_eyebrow', 'Studio de projet'),
    'title'           => $acf_text('pds_final_cta_title', 'Votre projet mérite une surface bien préparée.'),
    'text'            => $acf_text('pds_final_cta_text', 'Envoyez les premières informations : type de pièce, dimensions, style, découpes, crédence et contraintes. Nous vous aidons à cadrer un plan Dekton cohérent, esthétique et réalisable.'),
    'primary_label'   => $acf_text('pds_final_cta_primary_button_label', 'Demander un devis'),
    'primary_url'     => $acf_text('pds_final_cta_primary_button_url', '#devis'),
    'secondary_label' => $acf_text('pds_final_cta_secondary_button_label', 'Explorer les matières'),
    'secondary_url'   => $acf_text('pds_final_cta_secondary_button_url', '#matieres'),
);

$quote_form = array(
    'eyebrow' => $acf_text('pds_quote_form_eyebrow', 'Demander un devis'),
    'title'   => $acf_text('pds_quote_form_title', 'Un formulaire court pour préparer un devis utile.'),
    'honeypot_label' => $home_text('pds_quote_form_honeypot_label', 'Site web'),
    'progress_label' => $home_text('pds_quote_form_progress_label', 'Étape 1 / 3'),
    'project_legend' => $home_text('pds_quote_form_project_legend', 'Type de projet'),
    'project_options' => $home_json('pds_quote_form_project_options', array('Cuisine', 'Îlot central', 'Salle de bain', 'Extérieur', 'Autre')),
    'style_legend' => $home_text('pds_quote_form_style_legend', 'Style souhaité'),
    'style_options' => $home_json('pds_quote_form_style_options', array('Noir veiné', 'Blanc marbré', 'Gris béton', 'Pierre naturelle', 'Métal oxydé')),
    'info_legend' => $home_text('pds_quote_form_info_legend', 'Informations'),
    'name_label' => $home_text('pds_quote_form_name_label', 'Nom'),
    'email_label' => $home_text('pds_quote_form_email_label', 'Email'),
    'phone_label' => $home_text('pds_quote_form_phone_label', 'Téléphone'),
    'dimensions_label' => $home_text('pds_quote_form_dimensions_label', 'Dimensions approximatives'),
    'message_label' => $home_text('pds_quote_form_message_label', 'Message'),
    'prev_label' => $home_text('pds_quote_form_prev_label', 'Précédent'),
    'next_label' => $home_text('pds_quote_form_next_label', 'Suivant'),
    'submit_label' => $home_text('pds_quote_form_submit_label', 'Préparer la demande'),
);

$friend_sites = array(
    'eyebrow' => $home_text('pds_friend_sites_eyebrow', 'Sites amis'),
    'title'   => $home_text('pds_friend_sites_title', 'Nos sites partenaires'),
    'intro'   => $home_text('pds_friend_sites_intro', 'Retrouvez aussi nos ressources et sites specialises autour des plans de travail, de la cuisine et de l habitat.'),
    'links'   => $home_json('pds_friend_sites_links', array(
        array('label' => 'plan-travail-ceramique.fr', 'url' => 'https://plan-travail-ceramique.fr/'),
        array('label' => 'plan-cuisine-granit.com', 'url' => 'https://plan-cuisine-granit.com/'),
        array('label' => 'plan-travail-quartz.fr', 'url' => 'https://plan-travail-quartz.fr/'),
        array('label' => 'vectonemobile.fr', 'url' => 'https://vectonemobile.fr/'),
        array('label' => 'education-actu.fr', 'url' => 'https://education-actu.fr/'),
        array('label' => 'Plan travail en Dekton', 'url' => 'https://meilleur-plan-cuisine.fr/plan-de-travail-et-cuisine/ceramique/plan-de-travail-en-dekton/'),
    )),
);
?>

<?php if ($section_enabled('pds_hero_enabled') && pds_home_should_render_section('hero', $only_sections)) : ?>
<section class="hero section-dark" aria-labelledby="hero-title">
    <div class="hero-copy reveal">
        <p class="eyebrow"><?php echo esc_html($hero['eyebrow']); ?></p>
        <h1 id="hero-title"><span><?php echo esc_html($hero['title_line_1']); ?></span><span><?php echo esc_html($hero['title_line_2']); ?></span><span><?php echo esc_html($hero['title_line_3']); ?></span></h1>
        <p class="hero-lead"><?php echo esc_html($hero['lead']); ?></p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?php echo esc_url($hero['primary_url']); ?>"><?php echo esc_html($hero['primary_label']); ?></a>
            <a class="btn btn-secondary" href="<?php echo esc_url($hero['secondary_url']); ?>"><?php echo esc_html($hero['secondary_label']); ?></a>
        </div>
        <dl class="hero-proof" aria-label="Points forts">
            <?php foreach ($hero['proof_items'] as $index => $proof_item) : ?>
                <div><dt><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></dt><dd><?php echo esc_html((string) $proof_item); ?></dd></div>
            <?php endforeach; ?>
        </dl>
    </div>
    <div class="hero-visual reveal slide-right">
        <?php $picture($hero['image'], 'Plan de travail Dekton sombre avec lumière chaude', '1920', '1280', array('fetchpriority' => 'high')); ?>
        <div class="hero-material-tag"><?php echo esc_html($hero['material_tag']); ?></div>
        <?php
        $floating_card_classes = array('card-one', 'card-two', 'card-three');
        foreach ($hero['floating_cards'] as $index => $card) :
            $card_class = $floating_card_classes[$index] ?? 'card-three';
            ?>
            <div class="floating-card <?php echo esc_attr($card_class); ?>"><span><?php echo esc_html($card['label'] ?? ''); ?></span> <?php echo esc_html($card['value'] ?? ''); ?></div>
        <?php endforeach; ?>
    </div>
    <div class="hero-keywords" aria-label="Applications principales">
        <?php foreach ($hero['keywords'] as $keyword) : ?><span><?php echo esc_html((string) $keyword); ?></span><?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_surface_intelligence_enabled') && pds_home_should_render_section('surface-intelligence', $only_sections)) : ?>
<section class="surface-intelligence pds-section" id="surface-intelligence" aria-labelledby="surface-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($surface['eyebrow']); ?></p>
        <h2 id="surface-title"><?php echo esc_html($surface['title']); ?></h2>
        <p><?php echo esc_html($surface['intro']); ?></p>
    </div>
    <div class="surface-intro reveal" aria-label="Indicateurs matière">
        <?php foreach ($surface['badges'] as $badge) : ?>
            <span><?php echo esc_html((string) $badge); ?></span>
        <?php endforeach; ?>
    </div>
    <div class="tech-grid">
        <?php foreach ($features as $feature) : ?>
            <article class="tech-card reveal">
                <span class="tech-index"><?php echo esc_html($feature[0]); ?></span>
                <h3><?php echo esc_html($feature[1]); ?></h3>
                <p><?php echo esc_html($feature[2]); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_commercial_proof_enabled') && pds_home_should_render_section('commercial-proof', $only_sections)) : ?>
<section class="commercial-proof pds-section" aria-labelledby="proof-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($commercial['eyebrow']); ?></p>
        <h2 id="proof-title"><?php echo esc_html($commercial['title']); ?></h2>
        <p><?php echo esc_html($commercial['intro']); ?></p>
    </div>
    <div class="detail-grid">
        <?php foreach ($commitments as $commitment) : ?>
            <article class="detail-card reveal">
                <span aria-hidden="true">◇</span>
                <h3><?php echo esc_html($commitment[0]); ?></h3>
                <p><?php echo esc_html($commitment[1]); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
    <div class="hero-actions reveal">
        <a class="btn btn-primary" href="<?php echo esc_url($commercial['primary_url']); ?>"><?php echo esc_html($commercial['primary_label']); ?></a>
        <a class="btn btn-secondary" href="<?php echo esc_url($commercial['secondary_url']); ?>"><?php echo esc_html($commercial['secondary_label']); ?></a>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_material_scanner_enabled') && pds_home_should_render_section('material-scanner', $only_sections)) : ?>
<section class="material-scanner pds-section section-band" id="matieres" aria-labelledby="scanner-title" data-scanner>
    <div class="scanner-copy reveal">
        <p class="eyebrow"><?php echo esc_html($scanner['eyebrow']); ?></p>
        <h2 id="scanner-title"><?php echo esc_html($scanner['title']); ?></h2>
        <p><?php echo esc_html($scanner['intro']); ?></p>
        <div class="scanner-note" data-scanner-panel>
            <strong><?php echo esc_html($scanner['panel_title']); ?></strong>
            <span><?php echo esc_html($scanner['panel_text']); ?></span>
        </div>
    </div>
    <div class="scanner-stage reveal">
        <?php $picture($scanner['image'], 'Texture Dekton noire veinée analysée par points interactifs', '1200', '900', array('loading' => 'lazy')); ?>
        <?php
        foreach ($scanner['points'] as $index => $point) :
            ?>
            <button class="scanner-point<?php echo 0 === $index ? ' is-active' : ''; ?>" style="--x: <?php echo esc_attr($point['x'] ?? ''); ?>; --y: <?php echo esc_attr($point['y'] ?? ''); ?>;" data-title="<?php echo esc_attr($point['title'] ?? ''); ?>" data-text="<?php echo esc_attr($point['text'] ?? ''); ?>">
                <span><?php echo esc_html($point['title'] ?? ''); ?></span>
            </button>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_material_lab_enabled') && pds_home_should_render_section('material-lab', $only_sections)) : ?>
<section class="material-lab pds-section" aria-labelledby="lab-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($material_lab['eyebrow']); ?></p>
        <h2 id="lab-title"><?php echo esc_html($material_lab['title']); ?></h2>
        <p><?php echo esc_html($material_lab['intro']); ?></p>
    </div>
    <div class="material-grid">
        <?php foreach ($materials as $material) : ?>
            <article class="material-card reveal">
                <?php $picture($material[3], 'Texture ' . $material[0], '1200', '900', array('loading' => 'lazy')); ?>
                <div>
                    <span><?php echo esc_html($material[1]); ?></span>
                    <h3><?php echo esc_html($material[0]); ?></h3>
                    <p><?php echo esc_html($material[2]); ?></p>
                    <a href="<?php echo esc_url($material_lab['link_url']); ?>"><?php echo esc_html($material_lab['link_label']); ?></a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_moodboard_enabled') && pds_home_should_render_section('moodboard', $only_sections)) : ?>
<section class="moodboard pds-section section-band" id="ambiances" aria-labelledby="mood-title" data-moodboard>
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($moodboard['eyebrow']); ?></p>
        <h2 id="mood-title"><?php echo esc_html($moodboard['title']); ?></h2>
    </div>
    <div class="mood-layout reveal">
        <div class="mood-image">
            <?php $picture($active_mood_image, 'Moodboard Obsidian Luxury', '1600', '1100', array('data-mood-image' => '', 'loading' => 'lazy')); ?>
        </div>
        <div class="mood-panel">
            <div class="tab-list" role="tablist" aria-label="Moodboards">
                <?php foreach ($moodboard['tabs'] as $index => $tab) : ?>
                    <button<?php echo 0 === $index ? ' class="is-active"' : ''; ?> type="button" data-mood="<?php echo esc_attr($tab['key'] ?? ''); ?>"><?php echo esc_html($tab['title'] ?? ''); ?></button>
                <?php endforeach; ?>
            </div>
            <h3 data-mood-title><?php echo esc_html($active_mood['title'] ?? 'Obsidian Luxury'); ?></h3>
            <p data-mood-style><?php echo esc_html($active_mood['style'] ?? 'luxe architectural'); ?></p>
            <dl class="mood-specs" data-mood-specs>
                <?php foreach (($active_mood['specs'] ?? array('Plan' => 'noir veiné', 'Meuble' => 'noyer', 'Métal' => 'bronze', 'Mur' => 'graphite', 'Lumière' => 'chaude')) as $label => $value) : ?>
                    <div><dt><?php echo esc_html($label); ?></dt><dd><?php echo esc_html($value); ?></dd></div>
                <?php endforeach; ?>
            </dl>
            <div class="swatches" data-mood-swatches><?php foreach (($active_mood['swatches'] ?? array('#030303', '#5A3E2B', '#8A6A3F', '#1D1D1B')) as $swatch) : ?><span style="--swatch:<?php echo esc_attr($swatch); ?>"></span><?php endforeach; ?></div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_project_configurator_enabled') && pds_home_should_render_section('project-configurator', $only_sections)) : ?>
<section class="project-configurator pds-section" aria-labelledby="config-title" data-configurator>
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($configurator['eyebrow']); ?></p>
        <h2 id="config-title"><?php echo esc_html($configurator['title']); ?></h2>
    </div>
    <div class="configurator-grid reveal">
        <?php
        foreach ($configurator['questions'] as $step) :
            $key = $step['key'] ?? '';
            ?>
            <fieldset class="choice-group">
                <legend><?php echo esc_html($step['question'] ?? ''); ?></legend>
                <?php foreach (($step['choices'] ?? array()) as $choice) : ?>
                    <button type="button" data-config="<?php echo esc_attr($key); ?>" data-value="<?php echo esc_attr($choice); ?>"><?php echo esc_html($choice); ?></button>
                <?php endforeach; ?>
            </fieldset>
        <?php endforeach; ?>
    </div>
    <div class="recommendation reveal" data-config-result>
        <?php echo esc_html(str_replace('Obsidian Luxury', '', $configurator['default_result'])); ?><strong>Obsidian Luxury</strong>
        <a class="btn btn-primary" href="<?php echo esc_url($configurator['button_url']); ?>"><?php echo esc_html($configurator['button_label']); ?></a>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_before_choice_enabled') && pds_home_should_render_section('before-choice', $only_sections)) : ?>
<section class="before-choice pds-section section-calm" aria-labelledby="before-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($before_choice['eyebrow']); ?></p>
        <h2 id="before-title"><?php echo esc_html($before_choice['title']); ?></h2>
    </div>
    <div class="question-grid">
        <?php foreach ($before_choice['questions'] as $question) : ?>
            <article class="question-card reveal"><span aria-hidden="true">✦</span><h3><?php echo esc_html($question); ?></h3></article>
        <?php endforeach; ?>
    </div>
    <a class="btn btn-secondary reveal" href="<?php echo esc_url($before_choice['button_url']); ?>"><?php echo esc_html($before_choice['button_label']); ?></a>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_applications_enabled') && pds_home_should_render_section('applications', $only_sections)) : ?>
<section class="applications pds-section" id="applications" aria-labelledby="apps-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($applications_meta['eyebrow']); ?></p>
        <h2 id="apps-title"><?php echo esc_html($applications_meta['title']); ?></h2>
    </div>
    <div class="application-grid">
        <?php foreach ($applications as $i => $app) : ?>
            <article class="application-card reveal <?php echo 0 === $i || 3 === $i ? 'is-large' : ''; ?>">
                <?php $picture($app[2], $app[0], '1200', '900', array('loading' => 'lazy')); ?>
                <div><h3><?php echo esc_html($app[0]); ?></h3><p><?php echo esc_html($app[1]); ?></p><a href="<?php echo esc_url($applications_meta['link_url']); ?>"><?php echo esc_html($applications_meta['link_label']); ?></a></div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_signatures_enabled') && pds_home_should_render_section('signatures', $only_sections)) : ?>
<section class="signatures pds-section section-band" aria-labelledby="signatures-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($signatures_meta['eyebrow']); ?></p>
        <h2 id="signatures-title"><?php echo esc_html($signatures_meta['title']); ?></h2>
    </div>
    <div class="signature-stack">
        <?php
        foreach ($signatures as $signature) :
            ?>
            <article class="signature-card reveal">
                <?php $picture($signature[3], $signature[1], '1600', '1100', array('loading' => 'lazy')); ?>
                <div><span><?php echo esc_html($signature[0]); ?></span><h3><?php echo esc_html($signature[1]); ?></h3><p><?php echo esc_html($signature[2]); ?></p><a href="<?php echo esc_url($signatures_meta['link_url']); ?>"><?php echo esc_html($signatures_meta['link_label']); ?></a></div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_comparator_enabled') && pds_home_should_render_section('comparator', $only_sections)) : ?>
<section class="comparator pds-section" aria-labelledby="compare-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($comparator['eyebrow']); ?></p>
        <h2 id="compare-title"><?php echo esc_html($comparator['title']); ?></h2>
    </div>
    <div class="compare-grid reveal">
        <?php foreach ($comparator['criteria'] as $criterion) : ?>
            <article class="compare-row">
                <h3><?php echo esc_html(ucfirst($criterion)); ?></h3>
                <p><strong><?php echo esc_html($comparator['dekton_label']); ?></strong><span><?php echo esc_html($comparator['dekton_text']); ?></span></p>
                <p><strong><?php echo esc_html($comparator['classic_label']); ?></strong><span><?php echo esc_html($comparator['classic_text']); ?></span></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_process_enabled') && pds_home_should_render_section('process', $only_sections)) : ?>
<section class="process pds-section section-band" id="processus" aria-labelledby="process-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($process['eyebrow']); ?></p>
        <h2 id="process-title"><?php echo esc_html($process['title']); ?></h2>
    </div>
    <ol class="timeline">
        <?php foreach ($process['steps'] as $i => $step) : ?>
            <li class="reveal"><span><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span><?php echo esc_html($step); ?></li>
        <?php endforeach; ?>
    </ol>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_details_enabled') && pds_home_should_render_section('details', $only_sections)) : ?>
<section class="details pds-section" aria-labelledby="details-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($details['eyebrow']); ?></p>
        <h2 id="details-title"><?php echo esc_html($details['title']); ?></h2>
    </div>
    <div class="detail-grid">
        <?php foreach ($details['items'] as $detail) : ?>
            <article class="detail-card reveal"><span aria-hidden="true">◇</span><h3><?php echo esc_html($detail); ?></h3><p><?php echo esc_html($details['card_text']); ?></p></article>
        <?php endforeach; ?>
    </div>
    <div class="hero-actions reveal">
        <a class="btn btn-primary" href="<?php echo esc_url($details['primary_url']); ?>"><?php echo esc_html($details['primary_label']); ?></a>
        <a class="btn btn-secondary" href="<?php echo esc_url($details['secondary_url']); ?>"><?php echo esc_html($details['secondary_label']); ?></a>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_gallery_enabled') && pds_home_should_render_section('gallery', $only_sections)) : ?>
<section class="gallery pds-section section-band" aria-labelledby="gallery-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($gallery_meta['eyebrow']); ?></p>
        <h2 id="gallery-title"><?php echo esc_html($gallery_meta['title']); ?></h2>
    </div>
    <div class="gallery-grid">
        <?php foreach ($gallery as $i => $gallery_item) : ?>
            <?php $label = $gallery_item[0]; ?>
            <figure class="gallery-item reveal">
                <?php $picture($gallery_item[1], $label . ' avec surface Dekton', '1200', 1 === $i || 3 === $i || 5 === $i ? '900' : '1500', array('loading' => 'lazy')); ?>
                <figcaption><?php echo esc_html($label); ?></figcaption>
            </figure>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_faq_enabled') && pds_home_should_render_section('faq', $only_sections)) : ?>
<section class="faq pds-section" aria-labelledby="faq-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($faq_meta['eyebrow']); ?></p>
        <h2 id="faq-title"><?php echo esc_html($faq_meta['title']); ?></h2>
    </div>
    <div class="faq-list reveal" data-faq>
        <?php
        $faq_index = 0;
        foreach ($faqs as $question => $answer) :
            $faq_index++;
            ?>
            <article class="faq-item">
                <button type="button" aria-expanded="false" aria-controls="faq-<?php echo esc_attr($faq_index); ?>"><?php echo esc_html($question); ?><span>+</span></button>
                <div id="faq-<?php echo esc_attr($faq_index); ?>" class="faq-panel"><p><?php echo esc_html($answer); ?></p></div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_testimonials_enabled') && pds_home_should_render_section('testimonials', $only_sections)) : ?>
<section class="testimonials pds-section section-band" id="avis" aria-labelledby="reviews-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($testimonials_meta['eyebrow']); ?></p>
        <h2 id="reviews-title"><?php echo esc_html($testimonials_meta['title']); ?></h2>
    </div>
    <div class="testimonial-grid">
        <?php
        foreach ($reviews as $review) :
            ?>
            <article class="testimonial-card reveal">
                <span class="avatar"><?php echo esc_html(substr($review[0], 0, 1)); ?></span>
                <span class="stars" aria-label="<?php echo esc_attr($testimonials_meta['stars_label']); ?>"><?php echo esc_html($testimonials_meta['stars']); ?></span>
                <p>“<?php echo esc_html($review[2]); ?>”</p>
                <strong><?php echo esc_html($review[0]); ?></strong>
                <em><?php echo esc_html($review[1]); ?></em>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_final_cta_enabled') && pds_home_should_render_section('final-cta', $only_sections)) : ?>
<section class="final-cta pds-section" aria-labelledby="cta-title">
    <div class="final-cta-inner reveal">
        <p class="eyebrow"><?php echo esc_html($final_cta['eyebrow']); ?></p>
        <h2 id="cta-title"><?php echo esc_html($final_cta['title']); ?></h2>
        <p><?php echo esc_html($final_cta['text']); ?></p>
        <div class="hero-actions"><a class="btn btn-primary" href="<?php echo esc_url($final_cta['primary_url']); ?>"><?php echo esc_html($final_cta['primary_label']); ?></a><a class="btn btn-secondary" href="<?php echo esc_url($final_cta['secondary_url']); ?>"><?php echo esc_html($final_cta['secondary_label']); ?></a></div>
    </div>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_quote_form_enabled') && pds_home_should_render_section('quote-form', $only_sections)) : ?>
<section class="quote-form pds-section section-band" id="devis" aria-labelledby="quote-title" data-quote-form>
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($quote_form['eyebrow']); ?></p>
        <h2 id="quote-title"><?php echo esc_html($quote_form['title']); ?></h2>
    </div>
    <form class="multi-form reveal" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" novalidate>
        <input type="hidden" name="action" value="pds_quote_request">
        <?php wp_nonce_field('pds_quote_request', 'pds_quote_nonce'); ?>
        <label class="form-honeypot" aria-hidden="true" tabindex="-1"><?php echo esc_html($quote_form['honeypot_label']); ?><input type="text" name="site_web" autocomplete="off" tabindex="-1"></label>
        <div class="form-progress"><span data-form-step-label><?php echo esc_html($quote_form['progress_label']); ?></span><i data-form-progress></i></div>
        <fieldset class="form-step is-active" data-step="0">
            <legend><?php echo esc_html($quote_form['project_legend']); ?></legend>
            <?php foreach ($quote_form['project_options'] as $option) : ?>
                <label><input type="radio" name="type_projet" value="<?php echo esc_attr($option); ?>" required> <?php echo esc_html($option); ?></label>
            <?php endforeach; ?>
        </fieldset>
        <fieldset class="form-step" data-step="1">
            <legend><?php echo esc_html($quote_form['style_legend']); ?></legend>
            <?php foreach ($quote_form['style_options'] as $option) : ?>
                <label><input type="radio" name="style_souhaite" value="<?php echo esc_attr($option); ?>" required> <?php echo esc_html($option); ?></label>
            <?php endforeach; ?>
        </fieldset>
        <fieldset class="form-step" data-step="2">
            <legend><?php echo esc_html($quote_form['info_legend']); ?></legend>
            <div class="field-grid">
                <label><?php echo esc_html($quote_form['name_label']); ?><input type="text" name="nom" autocomplete="name" required></label>
                <label><?php echo esc_html($quote_form['email_label']); ?><input type="email" name="email" autocomplete="email" required></label>
                <label><?php echo esc_html($quote_form['phone_label']); ?><input type="tel" name="telephone" autocomplete="tel"></label>
                <label><?php echo esc_html($quote_form['dimensions_label']); ?><input type="text" name="dimensions"></label>
                <label class="wide"><?php echo esc_html($quote_form['message_label']); ?><textarea name="message" rows="5"></textarea></label>
            </div>
        </fieldset>
        <p class="form-message" data-form-message aria-live="polite"></p>
        <div class="form-actions">
            <button class="btn btn-secondary" type="button" data-prev><?php echo esc_html($quote_form['prev_label']); ?></button>
            <button class="btn btn-primary" type="button" data-next><?php echo esc_html($quote_form['next_label']); ?></button>
            <button class="btn btn-primary" type="submit" data-submit><?php echo esc_html($quote_form['submit_label']); ?></button>
        </div>
    </form>
</section>
<?php endif; ?>

<?php if ($section_enabled('pds_friend_sites_enabled') && pds_home_should_render_section('friend-sites', $only_sections)) : ?>
<section class="friend-sites pds-section" aria-labelledby="friend-sites-title">
    <div class="section-heading reveal">
        <p class="eyebrow"><?php echo esc_html($friend_sites['eyebrow']); ?></p>
        <h2 id="friend-sites-title"><?php echo esc_html($friend_sites['title']); ?></h2>
        <p><?php echo esc_html($friend_sites['intro']); ?></p>
    </div>
    <ul class="friend-sites-list reveal">
        <?php foreach ($friend_sites['links'] as $link) : ?>
            <?php
            $label = trim((string) ($link['label'] ?? ''));
            $url   = trim((string) ($link['url'] ?? ''));
            if ('' === $label || '' === $url) {
                continue;
            }
            ?>
            <li><a href="<?php echo esc_url(pds_resolve_site_link($url)); ?>"><?php echo esc_html($label); ?></a></li>
        <?php endforeach; ?>
    </ul>
</section>
<?php endif; ?>

<?php
}

function pds_home_block_sections(): array
{
    return array(
        'hero'                 => __('Hero', 'plan-dekton-studio'),
        'surface-intelligence' => __('Surface Intelligence', 'plan-dekton-studio'),
        'commercial-proof'     => __('Accompagnement projet', 'plan-dekton-studio'),
        'material-scanner'     => __('Material Scanner', 'plan-dekton-studio'),
        'material-lab'         => __('Laboratoire matieres', 'plan-dekton-studio'),
        'moodboard'            => __('Moodboard', 'plan-dekton-studio'),
        'project-configurator' => __('Configurateur', 'plan-dekton-studio'),
        'before-choice'        => __('Avant de choisir', 'plan-dekton-studio'),
        'applications'         => __('Applications', 'plan-dekton-studio'),
        'signatures'           => __('Signatures', 'plan-dekton-studio'),
        'comparator'           => __('Comparateur', 'plan-dekton-studio'),
        'process'              => __('Processus', 'plan-dekton-studio'),
        'details'              => __('Details', 'plan-dekton-studio'),
        'gallery'              => __('Galerie', 'plan-dekton-studio'),
        'faq'                  => __('FAQ', 'plan-dekton-studio'),
        'testimonials'         => __('Temoignages', 'plan-dekton-studio'),
        'final-cta'            => __('CTA final', 'plan-dekton-studio'),
        'quote-form'           => __('Formulaire devis', 'plan-dekton-studio'),
        'friend-sites'         => __('Sites amis', 'plan-dekton-studio'),
    );
}

function pds_home_block_content(): string
{
    $blocks = array();

    foreach (array_keys(pds_home_block_sections()) as $section) {
        $blocks[] = sprintf('<!-- wp:plan-dekton-studio/%s /-->', $section);
    }

    return implode("\n\n", $blocks);
}

function pds_register_home_blocks(): void
{
    foreach (pds_home_block_sections() as $section => $title) {
        register_block_type(
            'plan-dekton-studio/' . $section,
            array(
                'api_version'     => 2,
                'title'           => $title,
                'category'        => 'plan-dekton-studio',
                'icon'            => 'layout',
                'render_callback' => static function () use ($section): string {
                    ob_start();
                    pds_render_home_sections(array($section));

                    return (string) ob_get_clean();
                },
                'supports'        => array(
                    'customClassName' => false,
                    'html'            => false,
                    'reusable'        => false,
                ),
            )
        );
    }
}
add_action('init', 'pds_register_home_blocks');

function pds_home_block_categories(array $categories): array
{
    $categories[] = array(
        'slug'  => 'plan-dekton-studio',
        'title' => __('Plan Dekton Studio', 'plan-dekton-studio'),
    );

    return $categories;
}
add_filter('block_categories_all', 'pds_home_block_categories');

function pds_enqueue_home_block_editor_assets(): void
{
    wp_enqueue_script(
        'pds-home-blocks',
        get_theme_file_uri('assets/js/home-blocks.js'),
        array('wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-server-side-render'),
        PDS_THEME_VERSION,
        true
    );

    wp_localize_script(
        'pds-home-blocks',
        'pdsHomeBlocks',
        array(
            'sections' => pds_home_block_sections(),
        )
    );
}
add_action('enqueue_block_editor_assets', 'pds_enqueue_home_block_editor_assets');

function pds_ensure_homepage_uses_blocks(): void
{
    if (! is_admin() || wp_doing_ajax() || ! current_user_can('edit_pages')) {
        return;
    }

    $page_id = pds_home_page_id();
    if ($page_id <= 0) {
        return;
    }

    $front_page = get_post($page_id);
    if ($front_page instanceof WP_Post && '' === trim((string) $front_page->post_content)) {
        wp_update_post(
            array(
                'ID'           => $page_id,
                'post_content' => pds_home_block_content(),
            )
        );
    }
}
add_action('admin_init', 'pds_ensure_homepage_uses_blocks', 20);

function pds_ensure_friend_sites_block_on_homepage(): void
{
    if (! is_admin() || wp_doing_ajax() || ! current_user_can('edit_pages')) {
        return;
    }

    $page_id = pds_home_page_id();
    if ($page_id <= 0) {
        return;
    }

    $front_page = get_post($page_id);
    if (! $front_page instanceof WP_Post) {
        return;
    }

    $content = (string) $front_page->post_content;
    if (str_contains($content, 'wp:plan-dekton-studio/friend-sites')) {
        return;
    }

    $friend_sites_block = '<!-- wp:plan-dekton-studio/friend-sites /-->';
    $content = '' === trim($content) ? pds_home_block_content() : trim($content) . "\n\n" . $friend_sites_block;

    wp_update_post(
        array(
            'ID'           => $page_id,
            'post_content' => $content,
        )
    );
}
add_action('admin_init', 'pds_ensure_friend_sites_block_on_homepage', 36);

function pds_keep_homepage_editor_visible(): void
{
    if (! is_admin() || wp_doing_ajax() || ! current_user_can('edit_pages')) {
        return;
    }

    $group = get_page_by_path('group_pds_home_sections', OBJECT, 'acf-field-group');
    if (! $group instanceof WP_Post) {
        return;
    }

    $settings = maybe_unserialize($group->post_content);
    if (! is_array($settings)) {
        return;
    }

    $hidden = isset($settings['hide_on_screen']) && is_array($settings['hide_on_screen']) ? $settings['hide_on_screen'] : array();
    if (! in_array('the_content', $hidden, true)) {
        return;
    }

    $settings['hide_on_screen'] = array_values(array_diff($hidden, array('the_content')));

    wp_update_post(
        array(
            'ID'           => (int) $group->ID,
            'post_content' => maybe_serialize($settings),
        )
    );
}
add_action('admin_init', 'pds_keep_homepage_editor_visible', 20);
