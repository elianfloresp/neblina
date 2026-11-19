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
    const pt = document.documentElement.lang.startsWith('pt');
    const setMenu = function (open) {
      nav.classList.toggle('open', open);
      menuToggle.setAttribute('aria-expanded', String(open));
      menuToggle.textContent = open ? (pt ? 'Fechar ×' : 'Cerrar ×') : (pt ? 'Menu ☰' : 'Menú ☰');
    };
    menuToggle.addEventListener('click', function () {
      setMenu(!nav.classList.contains('open'));
    });
    // Esc fecha o menu e devolve o foco ao botão
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('open')) {
        setMenu(false);
        menuToggle.focus();
      }
    });
  }

  // --- Theme toggle ---
  const THEME_KEY = 'neblina-theme';
  const html = document.documentElement;
  const themeBtn = document.querySelector('.theme-toggle');

  // O tema salvo já é aplicado por um script no <head> (header.php); aqui só sincroniza o botão.
  const syncThemeBtn = function () {
    if (!themeBtn) return;
    const dark = html.getAttribute('data-theme') === 'dark';
    themeBtn.setAttribute('aria-pressed', String(dark));
    themeBtn.textContent = dark ? '◑' : '◐';
  };
  syncThemeBtn();

  if (themeBtn) {
    themeBtn.addEventListener('click', function () {
      const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      html.setAttribute('data-theme', next);
      try { localStorage.setItem(THEME_KEY, next); } catch (_) { /* localStorage pode falhar */ }
      syncThemeBtn();
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
