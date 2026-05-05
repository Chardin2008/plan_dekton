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

if (is_page() && have_posts()) {
    the_post();

    if (trim(get_the_content()) !== '') {
        echo do_shortcode(get_the_content(null, false)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        get_footer();
        return;
    }

    rewind_posts();
}

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

$picture = static function (string $file, string $alt, string $width, string $height, array $attrs = array()) use ($img, $image_dimensions): void {
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

$features = array(
    array('01', 'Chaleur', 'Une surface pensée pour les casseroles, plaques chaudes et usages intensifs du quotidien.'),
    array('02', 'Rayures', 'Une matière adaptée aux plans sollicités, à condition de respecter les bons gestes de coupe.'),
    array('03', 'Taches', 'Un entretien simple avec une éponge douce, de l’eau chaude et un produit adapté.'),
    array('04', 'UV', 'Une option pertinente pour certains projets lumineux ou extérieurs selon l’exposition et la pose.'),
    array('05', 'Usage intensif', 'Un choix durable pour cuisines familiales, îlots centraux, crédences et espaces très utilisés.'),
);

$materials = array(
    array('Noir veiné', 'luxe sombre', 'Une présence forte pour les cuisines architecturales.', 'texture-noir-veine.jpg'),
    array('Blanc marbré', 'lumière minérale', 'Un rendu clair, élégant et intemporel.', 'texture-blanc-marbre.jpg'),
    array('Gris béton', 'contemporain urbain', 'Un style brut, moderne et structuré.', 'texture-gris-beton.jpg'),
    array('Pierre naturelle', 'organique premium', 'Une texture chaleureuse inspirée de la matière.', 'texture-pierre-naturelle.jpg'),
    array('Brun terre', 'bois, argile et chaleur', 'Une teinte profonde pour des intérieurs enveloppants.', 'texture-brun-terre.jpg'),
    array('Métal oxydé', 'industriel chic', 'Une finition expressive pour les projets audacieux.', 'texture-metal-oxyde.jpg'),
);

$applications = array(
    array('Plan de travail cuisine', 'Une surface sur mesure avec découpes pour évier, plaque de cuisson et prises selon le projet.', 'gallery-1.jpg'),
    array('Îlot central', 'Une pièce centrale à dimensionner avec précision pour les repas, la préparation et la circulation.', 'gallery-2.jpg'),
    array('Crédence', 'Une continuité visuelle entre le plan et le mur, pratique pour protéger les zones exposées.', 'gallery-4.jpg'),
    array('Salle de bain', 'Un rendu minéral adapté aux plans vasque, habillages et espaces d’eau contemporains.', 'gallery-3.jpg'),
    array('Table sur mesure', 'Une pièce forte à penser selon le piètement, l’épaisseur, les chants et l’usage quotidien.', 'gallery-5.jpg'),
    array('Extérieur', 'Une solution à étudier selon l’exposition, le support, les joints et les contraintes de pose.', 'gallery-6.jpg'),
);

$commitments = array(
    array('Conseil matière', 'Nous guidons le choix du coloris, du veinage et de la finition selon la lumière, l’usage et le style de votre pièce.'),
    array('Projet sur mesure', 'Chaque demande est étudiée selon les dimensions, les découpes, le type de chant, l’évier, la crédence et les contraintes de pose.'),
    array('Devis qualifié', 'Le formulaire sert de brief projet : vous recevez une réponse plus précise, avec les informations utiles pour avancer sereinement.'),
);
?>

<section class="hero section-dark" aria-labelledby="hero-title">
    <div class="hero-copy reveal">
        <p class="eyebrow">Studio de surfaces premium</p>
        <h1 id="hero-title"><span>Surface</span><span>nouvelle</span><span>génération</span></h1>
        <p class="hero-lead">Des surfaces premium pour cuisines, îlots, salles de bain et projets architecturaux.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="#devis">Demander un devis</a>
            <a class="btn btn-secondary" href="#matieres">Explorer les matières</a>
        </div>
        <dl class="hero-proof" aria-label="Points forts">
            <div><dt>01</dt><dd>Étude du projet</dd></div>
            <div><dt>02</dt><dd>Choix de finition</dd></div>
            <div><dt>03</dt><dd>Devis accompagné</dd></div>
        </dl>
    </div>
    <div class="hero-visual reveal slide-right">
        <?php $picture('hero-dekton.jpg', 'Plan de travail Dekton sombre avec lumière chaude', '1920', '1280', array('fetchpriority' => 'high')); ?>
        <div class="hero-material-tag">Dekton · plan de travail · îlot · crédence</div>
        <div class="floating-card card-one"><span>Résistance</span> Chaleur</div>
        <div class="floating-card card-two"><span>Finition</span> Minérale</div>
        <div class="floating-card card-three"><span>Usage</span> Intérieur / extérieur</div>
    </div>
    <div class="hero-keywords" aria-label="Applications principales">
        <span>Cuisine</span><span>Îlot central</span><span>Salle de bain</span><span>Crédence</span><span>Extérieur</span>
    </div>
</section>

<section class="surface-intelligence pds-section" id="surface-intelligence" aria-labelledby="surface-title">
    <div class="section-heading reveal">
        <p class="eyebrow">Surface Intelligence</p>
        <h2 id="surface-title">Une surface pensée pour les espaces exigeants.</h2>
        <p>Dekton permet de concevoir un plan esthétique et technique : dimensions, découpes, chants, crédence et intégration de l’évier doivent être anticipés dès le brief.</p>
    </div>
    <div class="surface-intro reveal" aria-label="Indicateurs matière">
        <span>Résistance quotidienne</span>
        <span>Finition architecturale</span>
        <span>Entretien facilité</span>
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

<section class="commercial-proof pds-section" aria-labelledby="proof-title">
    <div class="section-heading reveal">
        <p class="eyebrow">Accompagnement projet</p>
        <h2 id="proof-title">Un plan Dekton se choisit avec précision.</h2>
        <p>Au-delà de l’image, nous aidons à cadrer les éléments qui font la différence : usage, dimensions, finitions, contraintes techniques et rendu final.</p>
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
        <a class="btn btn-primary" href="#devis">Préparer mon devis</a>
        <a class="btn btn-secondary" href="#matieres">Comparer les matières</a>
    </div>
</section>

<section class="material-scanner pds-section section-band" id="matieres" aria-labelledby="scanner-title" data-scanner>
    <div class="scanner-copy reveal">
        <p class="eyebrow">Material Scanner</p>
        <h2 id="scanner-title">Analysez la surface idéale pour votre projet.</h2>
        <p>Couleur, veinage, finition, épaisseur et usage quotidien : chaque choix influence le rendu, l’entretien et le budget final.</p>
        <div class="scanner-note" data-scanner-panel>
            <strong>Veinage</strong>
            <span>Un effet minéral profond pour donner du caractère au plan de travail.</span>
        </div>
    </div>
    <div class="scanner-stage reveal">
        <?php $picture('texture-noir-veine.jpg', 'Texture Dekton noire veinée analysée par points interactifs', '1200', '900', array('loading' => 'lazy')); ?>
        <?php
        $points = array(
            array('Veinage', '28%', '38%', 'Un effet minéral profond pour donner du caractère au plan de travail.'),
            array('Finition', '58%', '25%', 'Une surface élégante pensée pour un rendu contemporain.'),
            array('Résistance', '70%', '58%', 'Une matière adaptée aux usages exigeants du quotidien.'),
            array('Ambiance', '36%', '70%', 'Une présence visuelle forte pour les cuisines premium.'),
            array('Usage conseillé', '78%', '78%', 'Idéal pour plan de travail, îlot central, crédence ou projet sur mesure.'),
        );
        foreach ($points as $index => $point) :
            ?>
            <button class="scanner-point<?php echo 0 === $index ? ' is-active' : ''; ?>" style="--x: <?php echo esc_attr($point[1]); ?>; --y: <?php echo esc_attr($point[2]); ?>;" data-title="<?php echo esc_attr($point[0]); ?>" data-text="<?php echo esc_attr($point[3]); ?>">
                <span><?php echo esc_html($point[0]); ?></span>
            </button>
        <?php endforeach; ?>
    </div>
</section>

<section class="material-lab pds-section" aria-labelledby="lab-title">
    <div class="section-heading reveal">
        <p class="eyebrow">Le laboratoire des matières</p>
        <h2 id="lab-title">Un showroom digital pour comparer les signatures.</h2>
        <p>Chaque finition raconte une ambiance : profondeur sombre, lumière minérale, béton urbain ou chaleur organique.</p>
    </div>
    <div class="material-grid">
        <?php foreach ($materials as $material) : ?>
            <article class="material-card reveal">
                <?php $picture($material[3], 'Texture ' . $material[0], '1200', '900', array('loading' => 'lazy')); ?>
                <div>
                    <span><?php echo esc_html($material[1]); ?></span>
                    <h3><?php echo esc_html($material[0]); ?></h3>
                    <p><?php echo esc_html($material[2]); ?></p>
                    <a href="#ambiances">Voir l’ambiance</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="moodboard pds-section section-band" id="ambiances" aria-labelledby="mood-title" data-moodboard>
    <div class="section-heading reveal">
        <p class="eyebrow">Moodboard dynamique</p>
        <h2 id="mood-title">Composez une ambiance, pas seulement un plan.</h2>
    </div>
    <div class="mood-layout reveal">
        <div class="mood-image">
            <?php $picture('ambiance-obsidian.jpg', 'Moodboard Obsidian Luxury', '1600', '1100', array('data-mood-image' => '', 'loading' => 'lazy')); ?>
        </div>
        <div class="mood-panel">
            <div class="tab-list" role="tablist" aria-label="Moodboards">
                <button class="is-active" type="button" data-mood="obsidian">Obsidian Luxury</button>
                <button type="button" data-mood="mineral">Mineral White</button>
                <button type="button" data-mood="urban">Urban Stone</button>
            </div>
            <h3 data-mood-title>Obsidian Luxury</h3>
            <p data-mood-style>luxe architectural</p>
            <dl class="mood-specs" data-mood-specs>
                <div><dt>Plan</dt><dd>noir veiné</dd></div><div><dt>Meuble</dt><dd>noyer</dd></div><div><dt>Métal</dt><dd>bronze</dd></div><div><dt>Mur</dt><dd>graphite</dd></div><div><dt>Lumière</dt><dd>chaude</dd></div>
            </dl>
            <div class="swatches" data-mood-swatches><span style="--swatch:#030303"></span><span style="--swatch:#5A3E2B"></span><span style="--swatch:#8A6A3F"></span><span style="--swatch:#1D1D1B"></span></div>
        </div>
    </div>
</section>

<section class="project-configurator pds-section" aria-labelledby="config-title" data-configurator>
    <div class="section-heading reveal">
        <p class="eyebrow">Configurateur de projet</p>
        <h2 id="config-title">Quel plan Dekton correspond à votre espace ?</h2>
    </div>
    <div class="configurator-grid reveal">
        <?php
        $steps = array(
            'project' => array('Quel est votre projet ?', array('Cuisine', 'Îlot central', 'Salle de bain', 'Extérieur')),
            'style'   => array('Quel style préférez-vous ?', array('Sombre', 'Clair', 'Pierre', 'Béton', 'Métal')),
            'mood'    => array('Quelle ambiance recherchez-vous ?', array('Luxe', 'Minimaliste', 'Naturelle', 'Industrielle')),
        );
        foreach ($steps as $key => $step) :
            ?>
            <fieldset class="choice-group">
                <legend><?php echo esc_html($step[0]); ?></legend>
                <?php foreach ($step[1] as $choice) : ?>
                    <button type="button" data-config="<?php echo esc_attr($key); ?>" data-value="<?php echo esc_attr($choice); ?>"><?php echo esc_html($choice); ?></button>
                <?php endforeach; ?>
            </fieldset>
        <?php endforeach; ?>
    </div>
    <div class="recommendation reveal" data-config-result>
        Votre ambiance recommandée : <strong>Obsidian Luxury</strong>
        <a class="btn btn-primary" href="#devis">Demander un devis pour cette ambiance</a>
    </div>
</section>

<section class="before-choice pds-section section-calm" aria-labelledby="before-title">
    <div class="section-heading reveal">
        <p class="eyebrow">Avant de choisir votre plan Dekton</p>
        <h2 id="before-title">Quatre questions pour préparer un devis précis.</h2>
    </div>
    <div class="question-grid">
        <?php foreach (array('Quelles dimensions approximatives ?', 'Évier, plaque ou prises à intégrer ?', 'Crédence assortie ou plan seul ?', 'Effet marbre, béton, pierre ou métal ?') as $question) : ?>
            <article class="question-card reveal"><span aria-hidden="true">✦</span><h3><?php echo esc_html($question); ?></h3></article>
        <?php endforeach; ?>
    </div>
    <a class="btn btn-secondary reveal" href="#devis">Préparer mon devis</a>
</section>

<section class="applications pds-section" id="applications" aria-labelledby="apps-title">
    <div class="section-heading reveal">
        <p class="eyebrow">Une matière, plusieurs espaces</p>
        <h2 id="apps-title">Des usages pensés comme des pièces de design.</h2>
    </div>
    <div class="application-grid">
        <?php foreach ($applications as $i => $app) : ?>
            <article class="application-card reveal <?php echo 0 === $i || 3 === $i ? 'is-large' : ''; ?>">
                <?php $picture($app[2], $app[0], '1200', '900', array('loading' => 'lazy')); ?>
                <div><h3><?php echo esc_html($app[0]); ?></h3><p><?php echo esc_html($app[1]); ?></p><a href="#devis">Demander un devis</a></div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="signatures pds-section section-band" aria-labelledby="signatures-title">
    <div class="section-heading reveal">
        <p class="eyebrow">Signatures visuelles</p>
        <h2 id="signatures-title">Trois directions artistiques fortes.</h2>
    </div>
    <div class="signature-stack">
        <?php
        $signatures = array(
            array('01', 'Obsidian Luxury', 'Noir profond, veines dorées, bois noyer et lumière chaude pour une cuisine au caractère affirmé.', 'ambiance-obsidian.jpg'),
            array('02', 'Mineral White', 'Blanc veiné, meubles clairs et lumière naturelle pour un espace lumineux, propre et intemporel.', 'ambiance-mineral.jpg'),
            array('03', 'Urban Stone', 'Gris béton, noir mat et lignes tendues pour une atmosphère contemporaine et architecturale.', 'ambiance-urban.jpg'),
        );
        foreach ($signatures as $signature) :
            ?>
            <article class="signature-card reveal">
                <?php $picture($signature[3], $signature[1], '1600', '1100', array('loading' => 'lazy')); ?>
                <div><span><?php echo esc_html($signature[0]); ?></span><h3><?php echo esc_html($signature[1]); ?></h3><p><?php echo esc_html($signature[2]); ?></p><a href="#devis">Demander ce style</a></div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="comparator pds-section" aria-labelledby="compare-title">
    <div class="section-heading reveal">
        <p class="eyebrow">Dekton vs surface classique</p>
        <h2 id="compare-title">Les bons critères avant de choisir.</h2>
    </div>
    <div class="compare-grid reveal">
        <?php foreach (array('chaleur', 'rayures', 'taches', 'extérieur', 'rendu esthétique', 'entretien') as $criterion) : ?>
            <article class="compare-row">
                <h3><?php echo esc_html(ucfirst($criterion)); ?></h3>
                <p><strong>Dekton</strong><span>Très performant si la pose, les découpes et l’entretien sont adaptés au projet.</span></p>
                <p><strong>Surface classique</strong><span>Résultat variable selon la matière, l’épaisseur, la finition et l’usage quotidien.</span></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="process pds-section section-band" id="processus" aria-labelledby="process-title">
    <div class="section-heading reveal">
        <p class="eyebrow">De l’idée à la surface finale</p>
        <h2 id="process-title">Un déroulé clair, du brief à la pose.</h2>
    </div>
    <ol class="timeline">
        <?php foreach (array('Brief et dimensions', 'Choix du coloris', 'Validation des découpes', 'Prise de cotes précise', 'Fabrication du plan', 'Pose et finitions') as $i => $step) : ?>
            <li class="reveal"><span><?php echo esc_html(str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT)); ?></span><?php echo esc_html($step); ?></li>
        <?php endforeach; ?>
    </ol>
</section>

<section class="details pds-section" aria-labelledby="details-title">
    <div class="section-heading reveal">
        <p class="eyebrow">Les détails invisibles font le luxe visible</p>
        <h2 id="details-title">Les choix techniques qui changent le résultat.</h2>
    </div>
    <div class="detail-grid">
        <?php foreach (array('Épaisseur du plan', 'Type de chant', 'Finition mate ou satinée', 'Évier sous plan ou posé', 'Crédence assortie', 'Arrondis et découpes spéciales') as $detail) : ?>
            <article class="detail-card reveal"><span aria-hidden="true">◇</span><h3><?php echo esc_html($detail); ?></h3><p>Un point à valider avant devis pour éviter les approximations et préciser la fabrication.</p></article>
        <?php endforeach; ?>
    </div>
    <div class="hero-actions reveal">
        <a class="btn btn-primary" href="#devis">Valider ces détails</a>
        <a class="btn btn-secondary" href="#processus">Voir le processus</a>
    </div>
</section>

<section class="gallery pds-section section-band" aria-labelledby="gallery-title">
    <div class="section-heading reveal">
        <p class="eyebrow">Galerie d’inspirations</p>
        <h2 id="gallery-title">Des cadrages pour se projeter.</h2>
    </div>
    <div class="gallery-grid">
        <?php foreach (array('Cuisine', 'Îlot', 'Salle de bain', 'Crédence', 'Détail matière', 'Extérieur') as $i => $label) : ?>
            <figure class="gallery-item reveal">
                <?php $picture('gallery-' . ($i + 1) . '.jpg', $label . ' avec surface Dekton', '1200', 1 === $i || 3 === $i || 5 === $i ? '900' : '1500', array('loading' => 'lazy')); ?>
                <figcaption><?php echo esc_html($label); ?></figcaption>
            </figure>
        <?php endforeach; ?>
    </div>
</section>

<section class="faq pds-section" aria-labelledby="faq-title">
    <div class="section-heading reveal">
        <p class="eyebrow">Questions fréquentes</p>
        <h2 id="faq-title">Les réponses utiles avant de demander un devis.</h2>
    </div>
    <div class="faq-list reveal" data-faq>
        <?php
        $faqs = array(
            'Quelles informations faut-il pour un devis ?' => 'Les dimensions approximatives, le type de projet, le style souhaité, les découpes prévues, la présence d’une crédence et vos contraintes de pose.',
            'Dekton résiste-t-il à la chaleur ?' => 'Dekton offre une très bonne résistance à la chaleur dans les usages courants, mais le projet doit toujours être validé selon la pose et les contraintes réelles.',
            'Peut-on intégrer un évier ou une plaque ?' => 'Oui, les découpes peuvent être prévues pour évier, plaque de cuisson, robinetterie ou prises, avec une validation technique avant fabrication.',
            'Est-ce facile à entretenir ?' => 'Oui, l’entretien courant se fait avec de l’eau chaude, une éponge douce et un produit adapté, en évitant les gestes abrasifs inutiles.',
            'Peut-on avoir un plan Dekton sur mesure ?' => 'Oui, le plan peut être étudié selon les dimensions, l’épaisseur, le type de chant, la finition, les découpes et la crédence souhaitée.',
        );
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

<section class="testimonials pds-section section-band" id="avis" aria-labelledby="reviews-title">
    <div class="section-heading reveal">
        <p class="eyebrow">4.9/5 · témoignages clients</p>
        <h2 id="reviews-title">Ils ont transformé leur espace</h2>
    </div>
    <div class="testimonial-grid">
        <?php
        $reviews = array(
            array('Nadia M.', 'Cuisine avec îlot central', 'Le rendu est élégant, solide et vraiment haut de gamme. Le plan a changé toute l’ambiance de la cuisine.'),
            array('Thomas R.', 'Rénovation cuisine', 'Le plan Dekton apporte un style beaucoup plus moderne. La pièce paraît plus structurée et plus premium.'),
            array('Sarah L.', 'Salle de bain', 'Le résultat est propre, contemporain et facile à entretenir. C’est exactement l’ambiance que je voulais.'),
        );
        foreach ($reviews as $review) :
            ?>
            <article class="testimonial-card reveal">
                <span class="avatar"><?php echo esc_html(substr($review[0], 0, 1)); ?></span>
                <span class="stars" aria-label="5 étoiles">★★★★★</span>
                <p>“<?php echo esc_html($review[2]); ?>”</p>
                <strong><?php echo esc_html($review[0]); ?></strong>
                <em><?php echo esc_html($review[1]); ?></em>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="final-cta pds-section" aria-labelledby="cta-title">
    <div class="final-cta-inner reveal">
        <p class="eyebrow">Studio de projet</p>
        <h2 id="cta-title">Votre projet mérite une surface bien préparée.</h2>
        <p>Envoyez les premières informations : type de pièce, dimensions, style, découpes, crédence et contraintes. Nous vous aidons à cadrer un plan Dekton cohérent, esthétique et réalisable.</p>
        <div class="hero-actions"><a class="btn btn-primary" href="#devis">Demander un devis</a><a class="btn btn-secondary" href="#matieres">Explorer les matières</a></div>
    </div>
</section>

<section class="quote-form pds-section section-band" id="devis" aria-labelledby="quote-title" data-quote-form>
    <div class="section-heading reveal">
        <p class="eyebrow">Demander un devis</p>
        <h2 id="quote-title">Un formulaire court pour préparer un devis utile.</h2>
    </div>
    <form class="multi-form reveal" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post" novalidate>
        <input type="hidden" name="action" value="pds_quote_request">
        <?php wp_nonce_field('pds_quote_request', 'pds_quote_nonce'); ?>
        <label class="form-honeypot" aria-hidden="true" tabindex="-1">Site web<input type="text" name="site_web" autocomplete="off" tabindex="-1"></label>
        <div class="form-progress"><span data-form-step-label>Étape 1 / 3</span><i data-form-progress></i></div>
        <fieldset class="form-step is-active" data-step="0">
            <legend>Type de projet</legend>
            <?php foreach (array('Cuisine', 'Îlot central', 'Salle de bain', 'Extérieur', 'Autre') as $option) : ?>
                <label><input type="radio" name="type_projet" value="<?php echo esc_attr($option); ?>" required> <?php echo esc_html($option); ?></label>
            <?php endforeach; ?>
        </fieldset>
        <fieldset class="form-step" data-step="1">
            <legend>Style souhaité</legend>
            <?php foreach (array('Noir veiné', 'Blanc marbré', 'Gris béton', 'Pierre naturelle', 'Métal oxydé') as $option) : ?>
                <label><input type="radio" name="style_souhaite" value="<?php echo esc_attr($option); ?>" required> <?php echo esc_html($option); ?></label>
            <?php endforeach; ?>
        </fieldset>
        <fieldset class="form-step" data-step="2">
            <legend>Informations</legend>
            <div class="field-grid">
                <label>Nom<input type="text" name="nom" autocomplete="name" required></label>
                <label>Email<input type="email" name="email" autocomplete="email" required></label>
                <label>Téléphone<input type="tel" name="telephone" autocomplete="tel"></label>
                <label>Dimensions approximatives<input type="text" name="dimensions"></label>
                <label class="wide">Message<textarea name="message" rows="5"></textarea></label>
            </div>
        </fieldset>
        <p class="form-message" data-form-message aria-live="polite"></p>
        <div class="form-actions">
            <button class="btn btn-secondary" type="button" data-prev>Précédent</button>
            <button class="btn btn-primary" type="button" data-next>Suivant</button>
            <button class="btn btn-primary" type="submit" data-submit>Préparer la demande</button>
        </div>
    </form>
</section>

<?php
get_footer();
