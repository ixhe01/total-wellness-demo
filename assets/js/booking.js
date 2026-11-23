/* Total Wellness — asistente de reserva online */
(() => {
  const $ = (s, el = document) => el.querySelector(s);
  const api = (action, params = {}, body) => {
    const qs = new URLSearchParams({ action, ...params });
    return fetch('api.php?' + qs, body ? { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) } : {})
      .then(async r => { const j = await r.json(); if (!r.ok) throw new Error(j.error || 'Error'); return j; });
  };
  const url = new URLSearchParams(location.search);
  const st = { step: 1, location: null, service: null, provider: null, date: null, time: null };
  let M = null; // meta
  const B = window.TW_BT || {}, LOC = B.locale || 'en-US';
  const money = p => +p > 0 ? `${B.from} $${(+p).toFixed(0)}` : B.free;
  const fmtDay = d => new Date(d + 'T12:00:00');
  const fmtTime = t => { const [h, m] = t.split(':').map(Number); return LOC === 'es-MX' ? `${h}:${String(m).padStart(2, '0')}` : `${(h % 12) || 12}:${String(m).padStart(2, '0')} ${h < 12 ? 'AM' : 'PM'}`; };

  function go(n) {
    st.step = n;
    document.querySelectorAll('.panel').forEach(p => p.classList.toggle('is-on', +p.dataset.panel === n));
    document.querySelectorAll('#steps li').forEach(li => { const s = +li.dataset.s; li.classList.toggle('is-on', s === n); li.classList.toggle('is-done', s < n); });
    $('#backBtn').hidden = n === 1 || n === 6;
    if (n === 6) $('#summary').classList.add('is-done');
    if (n > 1) { const y = document.querySelector('.booking__main').getBoundingClientRect().top + scrollY - (innerWidth < 920 ? 80 : 110); scrollTo({ top: y, behavior: 'smooth' }); }
    summary();
  }
  function summary() {
    const L = M.locations.find(l => l.id == st.location), S = M.services.find(s => s.id == st.service);
    const P = st.provider === 0 ? { name: B.any } : M.providers.find(p => p.id == st.provider);
    const set = (k, v) => $(`#summary [data-k="${k}"]`).textContent = v || '—';
    set('location', L && L.name); set('service', S && `${S.name} · ${S.duration} min`); set('provider', P && P.name);
    set('when', st.date && st.time ? `${fmtDay(st.date).toLocaleDateString(LOC, { weekday: 'short', month: 'short', day: 'numeric' })} · ${fmtTime(st.time)}` : '');
  }

  function renderLocations() {
    $('#locList').innerHTML = M.locations.map(l => `<button class="opt" data-id="${l.id}"><strong>${l.name}</strong><span>${l.address}</span></button>`).join('');
    $('#locList').onclick = e => { const b = e.target.closest('.opt'); if (!b) return; st.location = +b.dataset.id; renderServices();
      const S = +url.get('service'); if (S && M.providers.some(p => p.services.includes(S) && p.locations.includes(st.location))) { st.service = S; renderProviders(); go(3); } else go(2); };
  }
  function renderServices(cat) {
    const avail = M.services.filter(s => M.providers.some(p => p.services.includes(+s.id) && p.locations.includes(st.location)));
    const cats = [...new Set(avail.map(s => s.category))];
    cat = cat || cats[0];
    $('#catTabs').innerHTML = cats.map(c => `<button class="${c === cat ? 'is-on' : ''}" data-c="${c}">${c}</button>`).join('');
    $('#catTabs').onclick = e => { const b = e.target.closest('button'); if (b) renderServices(b.dataset.c); };
    $('#svcList').innerHTML = avail.filter(s => s.category === cat).map(s => `
      <button class="svc" data-id="${s.id}"><span class="svc__main"><strong>${s.name}</strong><small>${s.description || ''}</small></span>
      <span class="svc__meta"><b>${s.duration} min</b><small>${money(s.price)}</small></span></button>`).join('');
    $('#svcList').onclick = e => { const b = e.target.closest('.svc'); if (!b) return; st.service = +b.dataset.id; renderProviders(); go(3); };
  }
  function renderProviders() {
    const ps = M.providers.filter(p => p.services.includes(st.service) && p.locations.includes(st.location));
    $('#provList').innerHTML = `<button class="opt opt--any" data-id="0"><span class="avatar avatar--any">✦</span><strong>${B.any}</strong><span>${B.earliest}</span></button>` +
      ps.map(p => `<button class="opt" data-id="${p.id}"><span class="avatar" style="background-image:url('${p.photo}')"></span><strong>${p.name}</strong><span>${p.title}</span></button>`).join('');
    $('#provList').onclick = e => { const b = e.target.closest('.opt'); if (!b) return; st.provider = +b.dataset.id; st.date = st.time = null; renderDays(); go(4); };
  }
  async function renderDays() {
    $('#dayList').innerHTML = `<p class="muted">${B.loadingDays}</p>`; $('#slotList').innerHTML = '';
    const days = await api('days', { service_id: st.service, location_id: st.location, provider_id: st.provider || '' });
    $('#dayList').innerHTML = days.map(d => { const dt = fmtDay(d.date);
      return `<button class="day" data-d="${d.date}" ${d.count ? '' : 'disabled'}><small>${dt.toLocaleDateString(LOC, { weekday: 'short' })}</small><b>${dt.getDate()}</b><small>${dt.toLocaleDateString(LOC, { month: 'short' })}</small></button>`; }).join('');
    $('#dayList').onclick = e => { const b = e.target.closest('.day'); if (!b || b.disabled) return;
      document.querySelectorAll('.day').forEach(x => x.classList.toggle('is-on', x === b)); st.date = b.dataset.d; st.time = null; summary(); renderSlots(); };
    const first = $('.day:not([disabled])'); if (first) first.click(); else $('#slotList').innerHTML = `<p class="muted">${B.noDays}</p>`;
  }
  async function renderSlots() {
    $('#slotList').innerHTML = `<p class="muted">${B.loadingTimes}</p>`;
    const slots = await api('slots', { service_id: st.service, location_id: st.location, provider_id: st.provider || '', date: st.date });
    const groups = { [B.morning]: slots.filter(t => t < '12:00'), [B.afternoon]: slots.filter(t => t >= '12:00') };
    $('#slotList').innerHTML = Object.entries(groups).filter(([, v]) => v.length).map(([k, v]) =>
      `<h4>${k}</h4><div class="slots__grid">${v.map(t => `<button class="slot" data-t="${t}">${fmtTime(t)}</button>`).join('')}</div>`).join('') || `<p class="muted">${B.noTimes}</p>`;
    $('#slotList').onclick = e => { const b = e.target.closest('.slot'); if (!b) return; st.time = b.dataset.t; summary(); go(5); };
  }

  $('#bookForm').addEventListener('submit', async e => {
    e.preventDefault();
    const f = e.target, msg = $('.form__msg', f);
    if (!f.reportValidity()) return;
    const data = Object.fromEntries(new FormData(f));
    const btn = $('button[type=submit]', f); btn.disabled = true; msg.textContent = B.booking;
    try {
      const r = await api('book', {}, { ...data, notes: (window.TW_PROMO ? `[${B.promo}: ${window.TW_PROMO}] ` : '') + (data.notes || ''), service_id: st.service, location_id: st.location, provider_id: st.provider || '', date: st.date, time: st.time });
      const a = r.appointment;
      $('#doneName').textContent = a.name;
      $('#doneDl').innerHTML = `<dt>${B.code}</dt><dd>${a.code}</dd><dt>${B.treatment}</dt><dd>${a.service}</dd><dt>${B.with}</dt><dd>${a.provider}</dd><dt>${B.when}</dt><dd>${a.when}</dd><dt>${B.where}</dt><dd>${a.location} — ${a.address}</dd>${window.TW_PROMO ? `<dt>${B.promo}</dt><dd>${window.TW_PROMO}</dd>` : ''}`;
      msg.textContent = ''; go(6);
    } catch (err) { msg.textContent = err.message; if (/taken|ocup/.test(err.message)) { go(4); renderSlots(); } }
    btn.disabled = false;
  });
  $('#backBtn').onclick = () => go(Math.max(1, st.step - 1));

  api('meta').then(m => {
    M = m; renderLocations();
    const L = +url.get('location'), S = +url.get('service');
    if (M.locations.length === 1 || L) $(`#locList .opt[data-id="${L || M.locations[0].id}"]`).click();
  }).catch(() => $('#locList').innerHTML = `<p>${B.loadErr}</p>`);
})();
