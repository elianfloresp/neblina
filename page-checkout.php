<?php
/**
 * Finalizar compra e "pedido recebido" — /{lang}/finalizar-compra/ (rota em inc/woocommerce.php).
 * Formulário, resumo e pagamento são os templates do Woo, com o visual de assets/css/loja.css.
 */
get_header();

$recebido = is_order_received_page();
?>

<div class="shell shop-page<?php echo $recebido ? ' is-order-received' : ''; ?>">
    <div class="page-intro">
        <div class="eyebrow"><?php echo neblina_i($recebido ? 'order_kicker' : 'checkout_kicker'); ?></div>
        <h1><?php echo neblina_i($recebido ? 'order_title' : 'checkout_title'); ?></h1>
    </div>

    <section class="section shop-content">
        <?php echo do_shortcode('[woocommerce_checkout]'); ?>
    </section>
</div>

<?php get_footer();
