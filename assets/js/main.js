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
    btn.disabled = true; msg.textContent = 'Sending…';
    try {
      const r = await fetch((window.TW_API||'api.php')+'?action=lead', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(Object.fromEntries(new FormData(lead))) });
      const j = await r.json(); if (!r.ok) throw new Error(j.error);
      lead.reset(); msg.textContent = 'Thank you! We’ll be in touch within one business day.'; msg.classList.add('ok');
    } catch (err) { msg.textContent = err.message || 'Something went wrong. Please call us.'; }
    btn.disabled = false;
  });
})();
