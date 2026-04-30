(() => {
  'use strict';

  const doc = document.documentElement;
  const body = document.body;
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const $ = (selector, root = document) => root.querySelector(selector);
  const $$ = (selector, root = document) => Array.from(root.querySelectorAll(selector));

  body.classList.add('pds-animate');

  const initLoader = () => {
    const loader = $('[data-loader]');
    if (!loader) return;

    if (reduceMotion) {
      loader.classList.add('is-hidden');
      return;
    }

    window.setTimeout(() => loader.classList.add('is-hidden'), 700);
  };

  const initHeader = () => {
    const header = $('[data-site-header]');
    const progress = $('[data-scroll-progress]');
    const update = () => {
      const scrollTop = window.scrollY || doc.scrollTop;
      const max = Math.max(1, doc.scrollHeight - window.innerHeight);
      if (header) header.classList.toggle('is-scrolled', scrollTop > 18);
      if (progress) progress.style.width = `${Math.min(100, (scrollTop / max) * 100)}%`;
    };

    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
  };

  const initMenu = () => {
    const toggle = $('[data-menu-toggle]');
    const menu = $('[data-mobile-menu]');
    const header = $('[data-site-header]');
    if (!toggle || !menu) return;

    const setOpen = (open) => {
      toggle.setAttribute('aria-expanded', String(open));
      menu.classList.toggle('is-open', open);
      header?.classList.toggle('is-open', open);
      body.classList.toggle('menu-open', open);
    };

    toggle.addEventListener('click', () => setOpen(toggle.getAttribute('aria-expanded') !== 'true'));
    $$('a', menu).forEach((link) => link.addEventListener('click', () => setOpen(false)));
    window.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') setOpen(false);
    });
  };

  const initReveals = () => {
    const reveals = $$('.reveal');
    if (!reveals.length) return;

    if (reduceMotion || !('IntersectionObserver' in window)) {
      reveals.forEach((element) => element.classList.add('is-visible'));
      return;
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    }, { threshold: 0.14, rootMargin: '0px 0px -8% 0px' });

    reveals.forEach((element) => observer.observe(element));
    window.setTimeout(() => {
      reveals.forEach((element) => element.classList.add('is-visible'));
    }, 1400);
  };

  const initScanner = () => {
    const scanner = $('[data-scanner]');
    if (!scanner) return;

    const panel = $('[data-scanner-panel]', scanner);
    const points = $$('.scanner-point', scanner);
    if (!panel || !points.length) return;

    points.forEach((point) => {
      point.addEventListener('click', () => {
        points.forEach((item) => item.classList.remove('is-active'));
        point.classList.add('is-active');
        panel.innerHTML = `<strong>${point.dataset.title || ''}</strong><span>${point.dataset.text || ''}</span>`;
      });
    });
  };

  const initMoodboard = () => {
    const moodboard = $('[data-moodboard]');
    if (!moodboard) return;

    const image = $('[data-mood-image]', moodboard);
    const imageSource = image?.closest('picture')?.querySelector('source[type="image/webp"]');
    const title = $('[data-mood-title]', moodboard);
    const style = $('[data-mood-style]', moodboard);
    const specs = $('[data-mood-specs]', moodboard);
    const swatches = $('[data-mood-swatches]', moodboard);
    const buttons = $$('[data-mood]', moodboard);
    if (!image || !title || !style || !specs || !swatches || !buttons.length) return;

    const base = image.currentSrc || image.src;
    const dir = base.slice(0, base.lastIndexOf('/') + 1);
    const moods = {
      obsidian: {
        title: 'Obsidian Luxury',
        style: 'luxe architectural',
        image: 'ambiance-obsidian.jpg',
        specs: { Plan: 'noir veiné', Meuble: 'noyer', Métal: 'bronze', Mur: 'graphite', Lumière: 'chaude' },
        colors: ['#030303', '#5A3E2B', '#8A6A3F', '#1D1D1B', '#C9A76A']
      },
      mineral: {
        title: 'Mineral White',
        style: 'minimalisme lumineux',
        image: 'ambiance-mineral.jpg',
        specs: { Plan: 'blanc veiné', Meuble: 'beige clair', Métal: 'inox brossé', Mur: 'blanc minéral', Lumière: 'naturelle' },
        colors: ['#F4F1EA', '#B8B2A8', '#77736B', '#E8D4A2', '#8A6A3F']
      },
      urban: {
        title: 'Urban Stone',
        style: 'contemporain urbain',
        image: 'ambiance-urban.jpg',
        specs: { Plan: 'gris béton', Meuble: 'noir mat', Métal: 'acier', Mur: 'gris profond', Lumière: 'studio' },
        colors: ['#121212', '#030303', '#77736B', '#1D1D1B', '#C9A76A']
      }
    };

    const render = (key) => {
      const mood = moods[key] || moods.obsidian;
      buttons.forEach((button) => button.classList.toggle('is-active', button.dataset.mood === key));
      image.style.opacity = '0.45';
      window.setTimeout(() => {
        if (imageSource) imageSource.srcset = `${dir}${mood.image.replace(/\.(jpe?g)$/i, '.webp')}`;
        image.src = `${dir}${mood.image}`;
        image.alt = `Moodboard ${mood.title}`;
        image.style.opacity = '1';
      }, reduceMotion ? 0 : 120);
      title.textContent = mood.title;
      style.textContent = mood.style;
      specs.innerHTML = Object.entries(mood.specs).map(([term, value]) => `<div><dt>${term}</dt><dd>${value}</dd></div>`).join('');
      swatches.innerHTML = mood.colors.map((color) => `<span style="--swatch:${color}"></span>`).join('');
    };

    buttons.forEach((button) => button.addEventListener('click', () => render(button.dataset.mood)));
  };

  const initConfigurator = () => {
    const configurator = $('[data-configurator]');
    if (!configurator) return;

    const result = $('[data-config-result]', configurator);
    const state = { project: 'Cuisine', style: 'Sombre', mood: 'Luxe' };
    const recommend = () => {
      let name = 'Organic Stone';
      if (state.project === 'Cuisine' && state.style === 'Sombre' && state.mood === 'Luxe') name = 'Obsidian Luxury';
      else if (state.project === 'Salle de bain' && state.style === 'Clair' && state.mood === 'Minimaliste') name = 'Mineral White';
      else if (state.project === 'Îlot central' && state.style === 'Béton' && state.mood === 'Industrielle') name = 'Urban Stone';
      else if (state.style === 'Métal') name = 'Oxide Atelier';
      else if (state.style === 'Clair') name = 'Mineral White';
      else if (state.style === 'Sombre') name = 'Obsidian Luxury';
      else if (state.style === 'Béton') name = 'Urban Stone';

      if (result) {
        const strong = $('strong', result);
        if (strong) strong.textContent = name;
      }
    };

    $$('[data-config]', configurator).forEach((button) => {
      if (button.dataset.value === state[button.dataset.config]) button.classList.add('is-active');
      button.addEventListener('click', () => {
        const group = button.dataset.config;
        state[group] = button.dataset.value || state[group];
        $$(`[data-config="${group}"]`, configurator).forEach((item) => item.classList.remove('is-active'));
        button.classList.add('is-active');
        recommend();
      });
    });

    recommend();
  };

  const initFaq = () => {
    const faq = $('[data-faq]');
    if (!faq) return;

    $$('.faq-item button', faq).forEach((button) => {
      button.addEventListener('click', () => {
        const item = button.closest('.faq-item');
        const open = button.getAttribute('aria-expanded') === 'true';
        if (!item) return;
        item.classList.toggle('is-open', !open);
        button.setAttribute('aria-expanded', String(!open));
        const icon = $('span', button);
        if (icon) icon.textContent = open ? '+' : '−';
      });
    });
  };

  const initQuoteForm = () => {
    const wrapper = $('[data-quote-form]');
    const form = wrapper ? $('form', wrapper) : null;
    if (!form) return;

    const steps = $$('.form-step', form);
    const prev = $('[data-prev]', form);
    const next = $('[data-next]', form);
    const submit = $('[data-submit]', form);
    const label = $('[data-form-step-label]', form);
    const progress = $('[data-form-progress]', form);
    const message = $('[data-form-message]', form);
    let current = 0;
    const submitLabel = submit?.textContent || 'Envoyer la demande';

    const validateStep = () => {
      const active = steps[current];
      if (!active) return true;
      const required = $$('[required]', active);
      const radios = required.filter((field) => field.type === 'radio');
      const other = required.filter((field) => field.type !== 'radio');
      const radioValid = !radios.length || radios.some((field) => field.checked);
      const fieldsValid = other.every((field) => field.checkValidity());
      if (message) message.textContent = radioValid && fieldsValid ? '' : 'Merci de compléter cette étape avant de continuer.';
      return radioValid && fieldsValid;
    };

    const validateForm = () => {
      for (let index = 0; index < steps.length; index += 1) {
        current = index;
        render();
        if (!validateStep()) return false;
      }
      current = steps.length - 1;
      render();
      return true;
    };

    const render = () => {
      steps.forEach((step, index) => step.classList.toggle('is-active', index === current));
      if (prev) prev.style.display = current === 0 ? 'none' : 'inline-flex';
      if (next) next.style.display = current === steps.length - 1 ? 'none' : 'inline-flex';
      if (submit) submit.style.display = current === steps.length - 1 ? 'inline-flex' : 'none';
      if (label) label.textContent = `Étape ${current + 1} / ${steps.length}`;
      if (progress) progress.style.width = `${((current + 1) / steps.length) * 100}%`;
      if (message) message.textContent = '';
    };

    prev?.addEventListener('click', () => {
      current = Math.max(0, current - 1);
      render();
    });

    next?.addEventListener('click', () => {
      if (!validateStep()) return;
      current = Math.min(steps.length - 1, current + 1);
      render();
    });

    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      if (!validateForm()) return;

      const ajaxUrl = window.pdsQuote?.ajaxUrl || form.action;
      const nonce = window.pdsQuote?.nonce;
      const formData = new FormData(form);
      if (nonce && !formData.has('pds_quote_nonce')) {
        formData.append('pds_quote_nonce', nonce);
      }

      if (submit) {
        submit.disabled = true;
        submit.textContent = 'Envoi en cours...';
      }
      if (message) message.textContent = 'Envoi de votre demande...';

      try {
        const response = await fetch(ajaxUrl, {
          method: 'POST',
          body: formData,
          credentials: 'same-origin'
        });
        const payload = await response.json();
        const responseMessage = payload?.data?.message || 'Votre demande a été traitée.';

        if (!response.ok || !payload?.success) {
          throw new Error(responseMessage);
        }

        form.reset();
        current = 0;
        render();
        if (message) message.textContent = responseMessage;
      } catch (error) {
        if (message) message.textContent = error.message || 'Une erreur est survenue pendant l’envoi.';
      } finally {
        if (submit) {
          submit.disabled = false;
          submit.textContent = submitLabel;
        }
      }
    });

    render();
  };

  const initCursor = () => {
    const cursor = $('[data-cursor]');
    if (!cursor || reduceMotion || window.matchMedia('(hover: none), (pointer: coarse)').matches) return;

    let x = window.innerWidth / 2;
    let y = window.innerHeight / 2;
    let cx = x;
    let cy = y;
    const move = () => {
      cx += (x - cx) * 0.18;
      cy += (y - cy) * 0.18;
      cursor.style.transform = `translate(${cx - 14}px, ${cy - 14}px)`;
      window.requestAnimationFrame(move);
    };

    window.addEventListener('mousemove', (event) => {
      x = event.clientX;
      y = event.clientY;
      cursor.classList.add('is-visible');
    }, { passive: true });

    $$('a, button, .material-card, .application-card, .gallery-item, img').forEach((element) => {
      element.addEventListener('mouseenter', () => cursor.classList.add('is-hover'));
      element.addEventListener('mouseleave', () => cursor.classList.remove('is-hover'));
    });

    move();
  };

  const boot = () => {
    initLoader();
    initHeader();
    initMenu();
    initReveals();
    initScanner();
    initMoodboard();
    initConfigurator();
    initFaq();
    initQuoteForm();
    initCursor();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot, { once: true });
  } else {
    boot();
  }
})();
