(function () {
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-logly-toggle]');
    if (!btn) return;
    var form = document.getElementById('logly-settings-form');
    if (!form) return;
    var open = form.style.display !== 'none' && form.style.display !== '';
    form.style.display = open ? 'none' : 'block';
    btn.textContent = open ? btn.dataset.labelShow : btn.dataset.labelHide;
  });
})();
