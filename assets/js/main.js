/**
 * Neblina — JS principal.
 * Carregado em todas as páginas.
 *
 * - Toggle menu mobile
 * - Toggle tema claro/escuro
 * - Carrinho flutuante (aparece quando o header sai da tela)
 */
(function () {
  'use strict';

  // --- Menu mobile ---
  const menuToggle = document.querySelector('.menu-toggle');
  const nav = document.getElementById('navigation');
  if (menuToggle && nav) {
    menuToggle.addEventListener('click', function () {
      const open = nav.classList.toggle('open');
      menuToggle.setAttribute('aria-expanded', String(open));
      menuToggle.textContent = open
        ? (document.documentElement.lang.startsWith('pt') ? 'Fechar ×' : 'Cerrar ×')
        : (document.documentElement.lang.startsWith('pt') ? 'Menu ☰' : 'Menú ☰');
    });
  }

  // --- Theme toggle ---
  const THEME_KEY = 'neblina-theme';
  const html = document.documentElement;
  const themeBtn = document.querySelector('.theme-toggle');

  // Restaura preferência salva
  try {
    const saved = localStorage.getItem(THEME_KEY);
    if (saved === 'light' || saved === 'dark') {
      html.setAttribute('data-theme', saved);
    }
  } catch (_) { /* localStorage pode falhar em alguns navegadores */ }

  if (themeBtn) {
    themeBtn.addEventListener('click', function () {
      const current = html.getAttribute('data-theme') || 'light';
      const next = current === 'light' ? 'dark' : 'light';
      html.setAttribute('data-theme', next);
      try { localStorage.setItem(THEME_KEY, next); } catch (_) {}
      themeBtn.setAttribute('aria-pressed', String(next === 'dark'));
      themeBtn.textContent = next === 'light' ? '◐' : '◑';
    });
  }

  // --- Carrinho flutuante ---
  // Só existe no HTML com livro no carrinho (header.php). Fica "inert" enquanto escondido.
  const cartFloat = document.querySelector('.cart-float');
  const header = document.querySelector('.header');
  if (cartFloat && header && 'IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      const show = !entries[0].isIntersecting;
      cartFloat.classList.toggle('is-visible', show);
      cartFloat.inert = !show;
    }).observe(header);
  }

  // Livro recém-adicionado (aviso de sucesso na ficha): o número do carrinho pula.
  if (document.querySelector('.shop-notice.is-success')) {
    document.querySelectorAll('.cart-link, .cart-float').forEach(function (el) {
      el.classList.add('is-bump');
      el.addEventListener('animationend', function () { el.classList.remove('is-bump'); }, { once: true });
    });
  }
})();
