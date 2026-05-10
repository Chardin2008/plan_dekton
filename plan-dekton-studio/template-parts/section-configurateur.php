<?php
/**
 * Template part: Section configurateur.
 *
 * @package PlanDektonStudio
 */
?>

<section class="project-configurator pds-section" aria-labelledby="config-title" data-configurator>
    <div class="section-heading reveal">
        <p class="eyebrow">Configurateur de projet</p>
        <h2 id="config-title">Quel plan Dekton correspond à votre espace ?</h2>
    </div>
    <div class="configurator-grid reveal">
                    <fieldset class="choice-group">
                <legend>Quel est votre projet ?</legend>
                                    <button type="button" data-config="project" data-value="Cuisine">Cuisine</button>
                                    <button type="button" data-config="project" data-value="Îlot central">Îlot central</button>
                                    <button type="button" data-config="project" data-value="Salle de bain">Salle de bain</button>
                                    <button type="button" data-config="project" data-value="Extérieur">Extérieur</button>
                            </fieldset>
                    <fieldset class="choice-group">
                <legend>Quel style préférez-vous ?</legend>
                                    <button type="button" data-config="style" data-value="Sombre">Sombre</button>
                                    <button type="button" data-config="style" data-value="Clair">Clair</button>
                                    <button type="button" data-config="style" data-value="Pierre">Pierre</button>
                                    <button type="button" data-config="style" data-value="Béton">Béton</button>
                                    <button type="button" data-config="style" data-value="Métal">Métal</button>
                            </fieldset>
                    <fieldset class="choice-group">
                <legend>Quelle ambiance recherchez-vous ?</legend>
                                    <button type="button" data-config="mood" data-value="Luxe">Luxe</button>
                                    <button type="button" data-config="mood" data-value="Minimaliste">Minimaliste</button>
                                    <button type="button" data-config="mood" data-value="Naturelle">Naturelle</button>
                                    <button type="button" data-config="mood" data-value="Industrielle">Industrielle</button>
                            </fieldset>
            </div>
    <div class="recommendation reveal" data-config-result>
        Votre ambiance recommandée : <strong>Obsidian Luxury</strong>
        <a class="btn btn-primary" href="#devis">Demander un devis pour cette ambiance</a>
    </div>
</section>
