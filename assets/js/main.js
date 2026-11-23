/* Total Wellness — interacciones del sitio */
(() => {
  // Menú móvil
  const burger = document.querySelector('.burger'), nav = document.getElementById('nav');
  if (burger) burger.addEventListener('click', () => {
    const open = document.body.classList.toggle('menu-open');
    burger.setAttribute('aria-expanded', open);
  });
  nav && nav.addEventListener('click', e => { if (e.target.tagName === 'A') document.body.classList.remove('menu-open'); });

  // Header compacto al hacer scroll
  const onScroll = () => document.body.classList.toggle('scrolled', scrollY > 40);
  addEventListener('scroll', onScroll, { passive: true }); onScroll();

  // Animación de entrada
  const io = 'IntersectionObserver' in window ? new IntersectionObserver(es => es.forEach(en => {
    if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
  }), { rootMargin: '0px 0px -8% 0px' }) : null;
  document.querySelectorAll('.reveal').forEach(el => io ? io.observe(el) : el.classList.add('in'));

  // Imagen que no carga → fondo neutro elegante en lugar de ícono roto
  document.querySelectorAll('img').forEach(img => img.addEventListener('error', () => img.classList.add('img-fail'), { once: true }));

  // Formulario de consulta (leads)
  const lead = document.getElementById('leadForm');
  if (lead) lead.addEventListener('submit', async e => {
    e.preventDefault();
    const msg = lead.querySelector('.form__msg'), btn = lead.querySelector('button');
    const T = window.TW_T || {}; btn.disabled = true; msg.textContent = T.sending;
    try {
      const r = await fetch((window.TW_API||'api.php')+'?action=lead', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(Object.fromEntries(new FormData(lead))) });
      const j = await r.json(); if (!r.ok) throw new Error(j.error);
      lead.reset(); msg.textContent = T.thanks; msg.classList.add('ok');
    } catch (err) { msg.textContent = err.message || T.error; }
    btn.disabled = false;
  });

  // Pop-up de promoción: aparece una vez (a los 7 s o al 35% de scroll); no vuelve en 7 días si se cierra
  const promo = document.getElementById('promo');
  if (promo) {
    const KEY = 'twc_promo_closed', now = Date.now();
    let closedAt = 0; try { closedAt = +localStorage.getItem(KEY) || 0; } catch (e) {}
    const force = /[?&]promo=show/.test(location.search);
    if (force || now - closedAt > 7 * 864e5) {
      let shown = false;
      const show = () => {
        if (shown || document.body.classList.contains('menu-open')) return; shown = true;
        promo.hidden = false; requestAnimationFrame(() => promo.classList.add('open'));
        document.body.classList.add('promo-open');
        removeEventListener('scroll', onSc);
        setTimeout(() => promo.querySelector('.promo__x').focus({ preventScroll: true }), 300);
      };
      const hide = () => {
        promo.classList.remove('open'); document.body.classList.remove('promo-open');
        setTimeout(() => promo.hidden = true, 350);
        try { localStorage.setItem(KEY, String(Date.now())); } catch (e) {}
      };
      const onSc = () => { if (scrollY > (document.body.scrollHeight - innerHeight) * .35) show(); };
      addEventListener('scroll', onSc, { passive: true });
      setTimeout(show, force ? 300 : 7000);
      promo.addEventListener('click', e => { if (e.target.closest('[data-close]')) hide(); });
      promo.querySelector('.btn').addEventListener('click', () => { try { localStorage.setItem(KEY, String(Date.now())); } catch (e) {} });
      addEventListener('keydown', e => { if (e.key === 'Escape' && promo.classList.contains('open')) hide(); });
    }
  }
})();
