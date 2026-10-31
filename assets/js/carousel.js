/**
 * Carrossel de banners da home. Clica no número, troca o slide.
 */
(function () {
  'use strict';

  const slides = document.querySelectorAll('[data-slide]');
  const pages  = document.querySelectorAll('[data-banner-page]');
  const posEl  = document.querySelector('.banner-position');
  if (slides.length === 0 || pages.length === 0) return;

  pages.forEach(function (btn) {
    btn.addEventListener('click', function () {
      const index = Number(btn.dataset.bannerPage);
      slides.forEach(function (s) { s.hidden = Number(s.dataset.slide) !== index; });
      pages.forEach(function (b) { b.setAttribute('aria-current', String(Number(b.dataset.bannerPage) === index)); });
      if (posEl) posEl.textContent = (index + 1) + ' / ' + slides.length;
    });
  });
})();
