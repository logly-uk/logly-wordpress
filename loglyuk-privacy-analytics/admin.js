(function () {
  // Settings bar: show/hide the Change form
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-loglyuk-toggle]');
    if (!btn) return;
    var form = document.getElementById('loglyuk-settings-form');
    if (!form) return;
    var open = form.style.display !== 'none' && form.style.display !== '';
    form.style.display = open ? 'none' : 'block';
    btn.textContent = open ? btn.dataset.labelShow : btn.dataset.labelHide;
  });

  // Native dashboard: fetch the public Logly API and render in wp-admin (no iframe)
  var root = document.getElementById('loglyuk-dashboard');
  if (!root) return;

  var site = root.dataset.site;
  var api = root.dataset.api;

  function esc(s) {
    var d = document.createElement('div');
    d.textContent = s == null ? '' : String(s);
    return d.innerHTML;
  }

  function fmtNum(n) {
    return (n || 0).toLocaleString();
  }

  function fmtDuration(sec) {
    sec = sec || 0;
    var m = Math.floor(sec / 60), s = sec % 60;
    return m + 'm ' + s + 's';
  }

  function stat(label, value) {
    return '<div style="flex:1;min-width:120px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:0.75rem 0.875rem">' +
      '<div style="font-size:0.75rem;color:#64748b;margin-bottom:0.25rem">' + esc(label) + '</div>' +
      '<div style="font-size:1.375rem;font-weight:700;color:#0f172a">' + esc(value) + '</div>' +
      '</div>';
  }

  function list(label, rows) {
    var items = (rows || []).map(function (r) {
      return '<li style="display:flex;justify-content:space-between;gap:1rem;padding:0.3rem 0;border-bottom:1px solid #f1f5f9;font-size:0.8125rem">' +
        '<span style="color:#334155;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">' + esc(r.value) + '</span>' +
        '<span style="color:#64748b;font-variant-numeric:tabular-nums">' + fmtNum(r.count) + '</span>' +
        '</li>';
    }).join('');
    if (!items) items = '<li style="color:#94a3b8;font-size:0.8125rem;padding:0.3rem 0">&mdash;</li>';
    return '<div style="flex:1;min-width:240px">' +
      '<div style="font-size:0.8125rem;font-weight:600;color:#475569;margin-bottom:0.4rem">' + esc(label) + '</div>' +
      '<ul style="margin:0;padding:0;list-style:none">' + items + '</ul>' +
      '</div>';
  }

  // Pageviews-over-time bars, built from the daily series already in /stats (no extra query)
  function chart(label, daily) {
    daily = daily || [];
    var max = daily.reduce(function (m, d) { return Math.max(m, d.pageviews || 0); }, 0) || 1;
    var bars = daily.map(function (d) {
      var h = Math.round(((d.pageviews || 0) / max) * 100);
      var title = (d.date || '') + ': ' + fmtNum(d.pageviews) + ' ' + label.toLowerCase();
      return '<div title="' + esc(title) + '" style="flex:1;min-width:2px;height:' + h + '%;min-height:1px;background:#6366f1;border-radius:2px 2px 0 0;opacity:0.85"></div>';
    }).join('');
    return '<div style="margin-bottom:1rem">' +
      '<div style="font-size:0.8125rem;font-weight:600;color:#475569;margin-bottom:0.4rem">' + esc(label) + '</div>' +
      '<div style="display:flex;align-items:flex-end;gap:2px;height:80px;border-bottom:1px solid #e2e8f0;padding-bottom:1px">' + bars + '</div>' +
      '</div>';
  }

  // Donut chart + legend for share-type breakdowns (countries, devices, browsers)
  var PIE_COLORS = ['#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#06b6d4', '#a855f7', '#94a3b8'];

  function pie(label, rows) {
    rows = (rows || []).slice();
    var total = rows.reduce(function (s, r) { return s + (r.count || 0); }, 0);
    var head = '<div style="font-size:0.8125rem;font-weight:600;color:#475569;margin-bottom:0.5rem">' + esc(label) + '</div>';
    if (!total) {
      return '<div style="flex:1;min-width:200px">' + head +
        '<p style="color:#94a3b8;font-size:0.8125rem;margin:0">&mdash;</p></div>';
    }
    var top = rows.slice(0, 6);
    var rest = rows.slice(6).reduce(function (s, r) { return s + (r.count || 0); }, 0);
    if (rest > 0) top.push({ value: 'Other', count: rest });

    var stops = [], acc = 0, legend = '';
    top.forEach(function (r, i) {
      var pct = (r.count / total) * 100;
      var c = PIE_COLORS[i % PIE_COLORS.length];
      stops.push(c + ' ' + acc.toFixed(2) + '% ' + (acc + pct).toFixed(2) + '%');
      acc += pct;
      legend += '<li style="display:flex;align-items:center;gap:0.4rem;font-size:0.8125rem;padding:0.15rem 0">' +
        '<span style="width:9px;height:9px;border-radius:2px;flex:none;background:' + c + '"></span>' +
        '<span style="color:#334155;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1">' + esc(r.value) + '</span>' +
        '<span style="color:#64748b;font-variant-numeric:tabular-nums">' + Math.round(pct) + '%</span>' +
        '</li>';
    });

    var donut = '<div style="width:92px;height:92px;flex:none;border-radius:50%;' +
      'background:conic-gradient(' + stops.join(',') + ');' +
      '-webkit-mask:radial-gradient(circle 27px at center, transparent 98%, #000 100%);' +
      'mask:radial-gradient(circle 27px at center, transparent 98%, #000 100%)"></div>';

    return '<div style="flex:1;min-width:240px">' + head +
      '<div style="display:flex;align-items:center;gap:1rem">' + donut +
      '<ul style="margin:0;padding:0;list-style:none;flex:1;min-width:0">' + legend + '</ul></div></div>';
  }

  function message(text) {
    root.innerHTML = '<p style="color:#64748b;font-size:0.875rem;margin:0.5rem 0">' + esc(text) + '</p>';
  }

  function render(stats, breakdown) {
    var t = (stats && stats.totals) || {};
    var b = breakdown || {};
    var cards =
      stat(root.dataset.lPageviews, fmtNum(t.pageviews)) +
      stat(root.dataset.lVisitors, fmtNum(t.visitors)) +
      stat(root.dataset.lSessions, fmtNum(t.sessions)) +
      stat(root.dataset.lBounce, (t.bounce_rate || 0) + '%') +
      stat(root.dataset.lDuration, fmtDuration(t.avg_duration_s));

    var lists =
      list(root.dataset.lPages, b.pages) +
      list(root.dataset.lReferrers, b.referrers);

    var pies =
      pie(root.dataset.lCountries, b.countries) +
      pie(root.dataset.lDevices, b.devices) +
      pie(root.dataset.lBrowsers, b.browsers);

    root.innerHTML =
      '<div style="display:flex;gap:0.625rem;flex-wrap:wrap;margin-bottom:1rem">' + cards + '</div>' +
      chart(root.dataset.lTrend, stats && stats.daily) +
      '<div style="display:flex;gap:2rem;flex-wrap:wrap;margin-bottom:1.25rem">' + lists + '</div>' +
      '<div style="display:flex;gap:2rem;flex-wrap:wrap">' + pies + '</div>';
  }

  function load() {
    if (!site || !api) return;
    var days = (document.getElementById('loglyuk-days') || {}).value || '30';
    var base = api + '/api/public/' + encodeURIComponent(site);
    var qs = '?days=' + encodeURIComponent(days);

    message('Loading…');

    fetch(base + '/stats' + qs)
      .then(function (r) {
        if (r.status === 404) { var e = new Error('private'); e.code = 'private'; throw e; }
        if (!r.ok) throw new Error('http');
        return r.json();
      })
      .then(function (stats) {
        return fetch(base + '/breakdown' + qs)
          .then(function (r) { return r.ok ? r.json() : {}; })
          .catch(function () { return {}; })
          .then(function (breakdown) { render(stats, breakdown); });
      })
      .catch(function (err) {
        message(err && err.code === 'private' ? root.dataset.msgPrivate : root.dataset.msgError);
      });
  }

  var picker = document.getElementById('loglyuk-days');
  if (picker) picker.addEventListener('change', load);
  load();
})();
