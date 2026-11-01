/**
 * Neblina — loja (ficha do livro e carrinho).
 *
 * - Botões − / + do seletor de quantidade (limites = min/max do campo, que vêm do estoque)
 * - No carrinho, mudar a quantidade atualiza o carrinho sozinho (usa o AJAX do próprio Woo)
 */
(function () {
  'use strict';

  function limits(input) {
    return {
      min: input.min === '' ? 0 : Number(input.min),
      max: input.max === '' ? Infinity : Number(input.max),
      step: Number(input.step) || 1,
    };
  }

  // − desativado no mínimo, + desativado no máximo (estoque).
  function syncButtons(input) {
    const { min, max } = limits(input);
    const value = Number(input.value) || 0;
    input.parentElement.querySelectorAll('.qty-btn').forEach(function (btn) {
      btn.disabled = Number(btn.dataset.step) < 0 ? value <= min : value >= max;
    });
  }

  // Número do link "Carrinho" no header (fica fora do HTML que o Woo atualiza).
  function syncHeaderCount() {
    const link = document.querySelector('.cart-link');
    const form = document.querySelector('.woocommerce-cart-form');
    if (!link || !document.querySelector('.shop-page')) return;
    let total = 0;
    if (form) {
      form.querySelectorAll('input.qty').forEach(function (input) { total += Number(input.value) || 0; });
    }
    let badge = link.querySelector('.cart-count');
    if (!total) {
      if (badge) badge.remove();
      return;
    }
    if (!badge) {
      badge = document.createElement('span');
      badge.className = 'cart-count';
      link.append(' ', badge);
    }
    badge.textContent = total;
  }

  function syncAll() {
    document.querySelectorAll('.qty-stepper input.qty').forEach(syncButtons);
    syncHeaderCount();
  }

  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.qty-btn');
    if (!btn) return;
    const input = btn.parentElement.querySelector('input.qty');
    const { min, max, step } = limits(input);
    const current = Number(input.value) || 0;
    const next = Math.min(max, Math.max(min, current + Number(btn.dataset.step) * step));
    if (next === current) return;
    input.value = next;
    input.dispatchEvent(new Event('change', { bubbles: true }));
  });

  // Carrinho: atualiza meio segundo depois da última mudança.
  let timer;
  document.addEventListener('change', function (e) {
    const input = e.target;
    if (!input.matches || !input.matches('input.qty')) return;
    syncButtons(input);

    const form = input.closest('.woocommerce-cart-form');
    if (!form) return;
    clearTimeout(timer);
    timer = setTimeout(function () {
      const update = form.querySelector('[name="update_cart"]');
      if (update) {
        update.disabled = false;
        update.click();
      }
    }, 500);
  });

  document.body.classList.add('cart-auto-update');
  syncAll();

  // O Woo troca o HTML do carrinho depois de atualizar.
  if (window.jQuery) {
    window.jQuery(document.body).on('updated_wc_div updated_cart_totals', syncAll);
  }
})();
