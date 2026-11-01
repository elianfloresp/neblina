<?php
/**
 * Carrinho — lista de livros no visual do site (substitui a tabela padrão do Woo).
 *
 * Mantém hooks, nomes de campo e as classes que o cart.js do Woo usa
 * (.woocommerce-cart-form, .product-remove > a, input.qty, button[name=update_cart]).
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 11.2.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
    <?php do_action('woocommerce_before_cart_table'); ?>

    <ul class="cart-items woocommerce-cart-form__contents">
        <?php do_action('woocommerce_before_cart_contents'); ?>

        <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item):
            $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

            if (!$_product instanceof WC_Product || !$_product->exists() || $cart_item['quantity'] <= 0
                || !apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                continue;
            }

            $nome      = apply_filters('woocommerce_cart_item_name', WC()->cart->get_item_product_name($cart_item, $_product), $cart_item, $cart_item_key);
            $link      = apply_filters('woocommerce_cart_item_permalink', $_product->get_permalink($cart_item), $cart_item, $cart_item_key);
            $livro_id  = neblina_product_book_id($_product);
            $autor     = $livro_id ? neblina_book_author($livro_id) : null;
            $max       = $_product->is_sold_individually() ? 1 : $_product->get_max_purchase_quantity();
            ?>
            <li class="cart-item woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                <a class="cart-item-cover" href="<?php echo esc_url($link); ?>" tabindex="-1" aria-hidden="true">
                    <?php echo apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('neblina-book-cover'), $cart_item, $cart_item_key); // phpcs:ignore ?>
                </a>

                <div class="cart-item-info">
                    <h2><a href="<?php echo esc_url($link); ?>"><?php echo wp_kses_post($nome); ?></a></h2>
                    <?php if ($autor): ?><p class="cart-item-author"><?php echo esc_html($autor['name']); ?></p><?php endif; ?>
                    <?php do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key); ?>
                    <?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore ?>
                    <p class="cart-item-price">
                        <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // phpcs:ignore ?>
                    </p>
                </div>

                <div class="cart-item-qty product-quantity">
                    <?php
                    $qty = woocommerce_quantity_input([
                        'input_name'   => "cart[{$cart_item_key}][qty]",
                        'input_value'  => $cart_item['quantity'],
                        'max_value'    => $max,
                        'min_value'    => $_product->is_sold_individually() ? 1 : 0,
                        'product_name' => wp_strip_all_tags($nome),
                    ], $_product, false);
                    echo apply_filters('woocommerce_cart_item_quantity', $qty, $cart_item_key, $cart_item); // phpcs:ignore
                    ?>
                </div>

                <div class="cart-item-subtotal">
                    <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore ?>
                </div>

                <div class="cart-item-remove product-remove">
                    <?php
                    echo apply_filters('woocommerce_cart_item_remove_link', sprintf( // phpcs:ignore
                        '<a role="button" href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
                        esc_url(wc_get_cart_remove_url($cart_item_key)),
                        /* translators: %s is the product name */
                        esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($nome))),
                        esc_attr($product_id),
                        esc_attr($_product->get_sku()),
                        esc_html(neblina_i('remove'))
                    ), $cart_item_key);
                    ?>
                </div>
            </li>
        <?php endforeach; ?>

        <?php do_action('woocommerce_cart_contents'); ?>
    </ul>

    <div class="cart-actions actions">
        <a class="text-link" href="<?php echo esc_url(neblina_url('livros')); ?>"><?php echo neblina_i('keep_shopping'); ?></a>
        <button type="submit" class="text-link cart-update" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>
        <?php do_action('woocommerce_cart_actions'); ?>
        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
    </div>

    <?php do_action('woocommerce_after_cart_contents'); ?>
    <?php do_action('woocommerce_after_cart_table'); ?>
</form>

<?php do_action('woocommerce_before_cart_collaterals'); ?>

<div class="cart-collaterals">
    <?php do_action('woocommerce_cart_collaterals'); ?>
</div>

<?php do_action('woocommerce_after_cart'); ?>
