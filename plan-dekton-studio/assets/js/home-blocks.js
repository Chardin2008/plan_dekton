(function (wp) {
  if (!wp || !wp.blocks || !wp.element || !window.pdsHomeBlocks) {
    return;
  }

  const el = wp.element.createElement;
  const ServerSideRender = wp.serverSideRender || (wp.components && wp.components.ServerSideRender);
  const sections = window.pdsHomeBlocks.sections || {};

  Object.keys(sections).forEach((section) => {
    wp.blocks.registerBlockType(`plan-dekton-studio/${section}`, {
      title: sections[section],
      icon: 'layout',
      category: 'plan-dekton-studio',
      supports: {
        customClassName: false,
        html: false,
        reusable: false,
      },
      edit() {
        if (!ServerSideRender) {
          return el('div', {}, sections[section]);
        }

        return el(ServerSideRender, {
          block: `plan-dekton-studio/${section}`,
        });
      },
      save() {
        return null;
      },
    });
  });
})(window.wp);
