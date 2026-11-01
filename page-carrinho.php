<?php
/**
 * Carrinho — /es/carrito/ e /pt/carrinho/ (rota em inc/woocommerce.php).
 * Lista em woocommerce/cart/cart.php; totais e frete são os templates do Woo.
 */
get_header();
?>

<div class="shell shop-page">
    <div class="page-intro">
        <div class="eyebrow"><?php echo neblina_i('cart_kicker'); ?></div>
        <h1><?php echo neblina_i('cart_title'); ?></h1>
    </div>

    <section class="section shop-content">
        <?php echo do_shortcode('[woocommerce_cart]'); ?>
    </section>
</div>

<?php get_footer();
