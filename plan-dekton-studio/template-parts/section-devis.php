<?php
/**
 * Template part: Section devis.
 *
 * @package PlanDektonStudio
 */
?>

<section class="quote-form pds-section section-band" id="devis" aria-labelledby="quote-title" data-quote-form>
    <div class="section-heading reveal">
        <p class="eyebrow">Demander un devis</p>
        <h2 id="quote-title">Un formulaire court pour préparer un devis utile.</h2>
    </div>
    <?php echo pds_get_quote_form_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</section>
