/* Total Wellness — panel interno */
(() => {
  const T = window.TW, $ = (s, el = document) => el.querySelector(s), $$ = (s, el = document) => [...el.querySelectorAll(s)];
  const api = (action, params = {}, body) => fetch('api.php?' + new URLSearchParams({ action, ...params }), body
      ? { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF': T.csrf }, body: JSON.stringify(body) } : {})
    .then(async r => { const j = await r.json(); if (r.status === 401) location.href = 'login.php'; if (!r.ok) throw new Error(j.error || 'Error'); return j; });
  const fmtT = t => { const [h, m] = t.slice(-8, -3).split(':').map(Number); return `${(h % 12) || 12}:${String(m).padStart(2, '0')}${h < 12 ? 'a' : 'p'}`; };
  const t12 = t => { const [h, m] = t.split(':').map(Number); return `${(h % 12) || 12}:${String(m).padStart(2, '0')} ${h < 12 ? 'AM' : 'PM'}`; };
  const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const toast = (m, bad) => { const d = document.createElement('div'); d.className = 'toast' + (bad ? ' toast--bad' : ''); d.textContent = m; document.body.append(d); setTimeout(() => d.remove(), 2600); };
  const prov = id => T.providers.find(p => p.id == id) || {};

  /* ---------------- AGENDA ---------------- */
  const cal = $('#cal');
  let curDate = T.today;
  const START = 8, END = 19, PX = 1.6; // 1.6px por minuto
  async function loadDay() {
    const loc = +$('#locSel').value;
    const d = new Date(curDate + 'T12:00:00');
    $('#dateLabel').textContent = d.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }) + (curDate === T.today ? ' · Today' : '');
    $('#dateSel').value = curDate;
    const { appointments, blocks } = await api('day', { date: curDate, location_id: loc });
    const provs = T.providers.filter(p => +p.active && (!loc || T.pl.some(x => x.provider_id == p.id && x.location_id == loc)));
    const hours = [...Array(END - START).keys()].map(i => START + i);
    let html = `<div class="cal__head"><div class="cal__gut"></div>${provs.map(p => `<div class="cal__ph"><span class="ava" style="background-image:url('${p.photo_url}')"></span><div><b>${esc(p.name)}</b><small>${appointments.filter(a => a.provider_id == p.id && a.status !== 'cancelled').length} appts</small></div></div>`).join('')}</div>`;
    html += `<div class="cal__body" style="height:${(END - START) * 60 * PX}px"><div class="cal__gut">${hours.map(h => `<span style="top:${(h - START) * 60 * PX}px">${(h % 12) || 12} ${h < 12 ? 'AM' : 'PM'}</span>`).join('')}</div>`;
    html += provs.map(p => {
      const col = appointments.filter(a => a.provider_id == p.id).map(a => {
        const s = new Date(a.start_at.replace(' ', 'T')), e = new Date(a.end_at.replace(' ', 'T'));
        const top = ((s.getHours() - START) * 60 + s.getMinutes()) * PX, h = Math.max(26, (e - s) / 60000 * PX - 3);
        return `<button class="ev ev--${a.status}" data-id="${a.id}" style="top:${top}px;height:${h}px;--c:${a.color}">
          <b>${esc(a.first_name)} ${esc(a.last_name[0] || '')}.</b> <span>${fmtT(a.start_at)}</span><small>${esc(a.service)}</small></button>`;
      }).join('');
      const bl = blocks.filter(b => b.provider_id == p.id).map(b => {
        const s = new Date(Math.max(new Date(b.start_at.replace(' ', 'T')), new Date(curDate + 'T0' + START + ':00'))), e = new Date(b.end_at.replace(' ', 'T'));
        const top = Math.max(0, ((s.getHours() - START) * 60 + s.getMinutes()) * PX), h = Math.min((END - START) * 60 * PX - top, (e - s) / 60000 * PX);
        return `<div class="blk" style="top:${top}px;height:${h}px">${esc(b.reason || 'Blocked')}</div>`;
      }).join('');
      return `<div class="cal__col">${hours.map(h => `<i style="top:${(h - START) * 60 * PX}px"></i>`).join('')}${bl}${col}</div>`;
    }).join('');
    // línea de "ahora"
    const now = new Date();
    if (curDate === T.today && now.getHours() >= START && now.getHours() < END) html += `<div class="now" style="top:${((now.getHours() - START) * 60 + now.getMinutes()) * PX}px"></div>`;
    html += '</div>';
    cal.style.setProperty('--cols', provs.length);
    cal.innerHTML = provs.length ? html : '<p class="empty">No providers at this location.</p>';
  }
  if (cal) {
    const shift = n => { const d = new Date(curDate + 'T12:00:00'); d.setDate(d.getDate() + n); curDate = d.toISOString().slice(0, 10); loadDay(); };
    $('#prevD').onclick = () => shift(-1); $('#nextD').onclick = () => shift(1);
    $('#todayD').onclick = () => { curDate = T.today; loadDay(); };
    $('#dateSel').onchange = e => { curDate = e.target.value; loadDay(); };
    $('#locSel').onchange = loadDay;
    cal.addEventListener('click', e => { const b = e.target.closest('.ev'); if (b) openAppt(b.dataset.id); });
    loadDay();
    setInterval(loadDay, 60000); // refresco automático cada minuto
  }

  /* ---------------- DRAWER DE CITA ---------------- */
  const drawer = $('#drawer');
  drawer.addEventListener('click', e => { if (e.target.closest('[data-close]')) closeDrawer(); });
  addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });
  function closeDrawer() { drawer.classList.remove('open'); drawer.setAttribute('aria-hidden', 'true'); }
  $$('[data-open]').forEach(tr => tr.addEventListener('click', () => openAppt(tr.dataset.open)));

  async function openAppt(id) {
    const a = await api('get', { id });
    const s = new Date(a.start_at.replace(' ', 'T'));
    const actions = { confirmed: 'Confirm', checked_in: 'Check in', completed: 'Complete', no_show: 'No-show', cancelled: 'Cancel' };
    $('#drawerBody').innerHTML = `
      <p class="eyebrow">#${esc(a.code)} · ${esc(a.source)}</p>
      <h2>${esc(a.first_name)} ${esc(a.last_name)} ${+a.is_new ? '<i class="tag">New patient</i>' : ''}</h2>
      <span class="st st--${a.status}">${T.statuses[a.status]}</span>
      <dl class="dl">
        <dt>Treatment</dt><dd><i class="dot" style="background:${a.color}"></i>${esc(a.service)} · ${a.duration} min</dd>
        <dt>When</dt><dd>${s.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric' })} · ${t12(a.start_at.slice(11, 16))}</dd>
        <dt>Provider</dt><dd>${esc(a.provider)}</dd><dt>Location</dt><dd>${esc(a.location)}</dd>
        <dt>Phone</dt><dd><a href="tel:${esc(a.phone)}">${esc(a.phone)}</a></dd><dt>Email</dt><dd>${a.email ? `<a href="mailto:${esc(a.email)}">${esc(a.email)}</a>` : '—'}</dd>
        ${a.dob ? `<dt>DOB</dt><dd>${esc(a.dob)}</dd>` : ''}
        <dt>Patient notes</dt><dd>${esc(a.notes) || '<span class="muted">—</span>'}</dd>
      </dl>
      <div class="acts">${Object.entries(actions).filter(([k]) => k !== a.status).map(([k, l]) => `<button class="b b--sm ${k === 'cancelled' || k === 'no_show' ? 'b--ghost' : ''}" data-st="${k}">${l}</button>`).join('')}</div>
      <h3>Internal notes</h3>
      <textarea id="inNotes" rows="3" placeholder="Visible to staff only">${esc(a.internal_notes)}</textarea>
      <button class="b b--sm" id="saveNotes">Save notes</button>
      <h3>Reschedule</h3>
      <div class="resch"><input type="date" id="rsDate" value="${a.start_at.slice(0, 10)}"><select id="rsProv">${T.providers.filter(p => T.ps.some(x => x.provider_id == p.id && x.service_id == a.service_id)).map(p => `<option value="${p.id}" ${p.id == a.provider_id ? 'selected' : ''}>${esc(p.name)}</option>`).join('')}</select></div>
      <div class="slots" id="rsSlots"></div>`;
    drawer.classList.add('open'); drawer.setAttribute('aria-hidden', 'false');
    $$('[data-st]', drawer).forEach(b => b.onclick = async () => { await api('status', {}, { id: a.id, status: b.dataset.st }); toast('Status updated'); refresh(); openAppt(a.id); });
    $('#saveNotes').onclick = async () => { await api('notes', {}, { id: a.id, notes: $('#inNotes').value }); toast('Notes saved'); };
    const loadRs = async () => {
      const sl = await api('slots', { service_id: a.service_id, location_id: a.location_id, provider_id: $('#rsProv').value, date: $('#rsDate').value, ignore: a.id });
      $('#rsSlots').innerHTML = sl.length ? sl.map(t => `<button class="slot" data-t="${t}">${t12(t)}</button>`).join('') : '<p class="muted small">No open times that day.</p>';
    };
    $('#rsDate').onchange = loadRs; $('#rsProv').onchange = loadRs; loadRs();
    $('#rsSlots').onclick = async e => { const b = e.target.closest('.slot'); if (!b) return;
      try { await api('reschedule', {}, { id: a.id, date: $('#rsDate').value, time: b.dataset.t, provider_id: $('#rsProv').value }); toast('Appointment moved'); refresh(); openAppt(a.id); } catch (er) { toast(er.message, 1); } };
  }
  const refresh = () => { if (cal) loadDay(); else if ($('.v-list')) setTimeout(() => location.reload(), 600); };

  /* ---------------- NUEVA CITA ---------------- */
  const nf = $('#newForm');
  if (nf) {
    const fillProv = () => {
      const sid = nf.service_id.value, loc = nf.location_id.value;
      const ps = T.providers.filter(p => +p.active && T.ps.some(x => x.provider_id == p.id && x.service_id == sid) && T.pl.some(x => x.provider_id == p.id && x.location_id == loc));
      nf.provider_id.innerHTML = '<option value="">Any available</option>' + ps.map(p => `<option value="${p.id}">${esc(p.name)}</option>`).join('');
    };
    const loadSlots = async () => {
      nf.time.value = '';
      const sl = await api('slots', { service_id: nf.service_id.value, location_id: nf.location_id.value, provider_id: nf.provider_id.value, date: nf.date.value });
      $('#newSlots').innerHTML = sl.length ? sl.map(t => `<button type="button" class="slot" data-t="${t}">${t12(t)}</button>`).join('') : '<p class="muted">No open times — try another day or provider.</p>';
    };
    nf.service_id.onchange = nf.location_id.onchange = () => { fillProv(); loadSlots(); };
    nf.provider_id.onchange = nf.date.onchange = loadSlots;
    $('#newSlots').onclick = e => { const b = e.target.closest('.slot'); if (!b) return; $$('.slot', nf).forEach(x => x.classList.toggle('on', x === b)); nf.time.value = b.dataset.t; };
    nf.onsubmit = async e => {
      e.preventDefault();
      if (!nf.time.value) return toast('Pick a time first', 1);
      try { const r = await api('create', {}, Object.fromEntries(new FormData(nf))); toast('Created ' + r.code); $('#newMsg').textContent = `✓ Appointment ${r.code} created.`; nf.reset(); fillProv(); loadSlots(); }
      catch (er) { toast(er.message, 1); }
    };
    fillProv(); loadSlots();
  }

  /* ---------------- LEADS / SETTINGS ---------------- */
  $$('.lead-st').forEach(s => s.onchange = async () => { await api('lead_status', {}, { id: s.dataset.id, status: s.value }); toast('Updated'); });
  $$('.save-svc').forEach(b => b.onclick = async () => {
    const tr = b.closest('tr'), g = n => $(`[name=${n}]`, tr);
    if (!g('name').value.trim()) return toast('Name required', 1);
    await api('save_service', {}, { id: tr.dataset.id, category: g('category').value, name: g('name').value, duration: g('duration').value, price: g('price').value, description: g('description').value, active: g('active').checked });
    toast('Saved'); if (!tr.dataset.id) location.reload();
  });
  $$('form.prov').forEach(f => f.onsubmit = async e => { e.preventDefault();
    await api('save_provider', {}, { id: f.dataset.id, name: f.name.value, title: f.title.value, active: f.active.checked, services: $$('[name=services]:checked', f).map(c => c.value) }); toast('Saved'); });
  $$('form.hours').forEach(f => f.onsubmit = async e => { e.preventDefault();
    const days = {}; $$('.hrow', f).forEach(r => days[r.dataset.wd] = { open: $('[name=open]', r).value, close: $('[name=close]', r).value });
    await api('save_hours', {}, { location_id: f.dataset.loc, days }); toast('Hours saved'); });
  const bf = $('#blockForm');
  if (bf) bf.onsubmit = async e => { e.preventDefault(); await api('add_block', {}, Object.fromEntries(new FormData(bf))); location.reload(); };
  $$('.del-block').forEach(b => b.onclick = async () => { await api('del_block', {}, { id: b.dataset.id }); location.reload(); });
})();
