(function (blocks, element, i18n) {
  const el = element.createElement;
  const __ = i18n.__;

  blocks.registerBlockType('plan-dekton/quote-form', {
    title: __('Plan Dekton - Zone formulaire devis', 'plan-dekton-studio'),
    description: __('Affiche le formulaire de devis cote visiteur et un repere propre dans Gutenberg.', 'plan-dekton-studio'),
    icon: 'email',
    category: 'widgets',
    supports: {
      html: false,
      reusable: true
    },
    example: {},
    edit: function () {
      return el(
        'div',
        { className: 'pds-editor-form-placeholder' },
        el('strong', null, __('Zone formulaire devis', 'plan-dekton-studio')),
        el(
          'p',
          null,
          __('Le formulaire est rendu automatiquement cote visiteur. Dans Gutenberg, ce bloc sert de repere propre.', 'plan-dekton-studio')
        )
      );
    },
    save: function () {
      return null;
    },
    transforms: {
      from: [
        {
          type: 'shortcode',
          tag: 'pds_quote_form'
        },
        {
          type: 'shortcode',
          tag: 'contact-form-7'
        }
      ]
    }
  });
})(window.wp.blocks, window.wp.element, window.wp.i18n);
