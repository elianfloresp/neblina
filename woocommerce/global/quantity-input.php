<?php
/**
 * Seletor de quantidade − 1 + (ficha do livro e carrinho).
 *
 * Mesmo campo do Woo (input.qty com min/max do estoque), com botões − e +
 * ao redor. Os botões funcionam via assets/js/loja.js; sem JS, o campo
 * numérico continua funcionando sozinho.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 *
 * @var bool   $readonly If the input should be set to readonly mode.
 * @var string $type     The input type attribute.
 */

defined('ABSPATH') || exit;

/* translators: %s: Quantity. */
$label   = !empty($args['product_name']) ? sprintf(esc_html__('%s quantity', 'woocommerce'), wp_strip_all_tags($args['product_name'])) : esc_html__('Quantity', 'woocommerce');
$stepper = !$readonly && $type === 'number';
?>
<div class="quantity<?php echo $stepper ? ' qty-stepper' : ''; ?>">
    <?php do_action('woocommerce_before_quantity_input_field'); ?>
    <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo esc_attr($label); ?></label>

    <?php if ($stepper): ?>
        <button type="button" class="qty-btn" data-step="-1" aria-controls="<?php echo esc_attr($input_id); ?>" aria-label="<?php echo esc_attr(neblina_i('qty_less')); ?>">−</button>
    <?php endif; ?>

    <input
        type="<?php echo esc_attr($type); ?>"
        <?php echo $readonly ? 'readonly="readonly"' : ''; ?>
        id="<?php echo esc_attr($input_id); ?>"
        class="<?php echo esc_attr(join(' ', (array) $classes)); ?>"
        name="<?php echo esc_attr($input_name); ?>"
        value="<?php echo esc_attr($input_value); ?>"
        aria-label="<?php esc_attr_e('Product quantity', 'woocommerce'); ?>"
        min="<?php echo esc_attr($min_value); ?>"
        <?php if (0 < $max_value): ?>
            max="<?php echo esc_attr($max_value); ?>"
        <?php endif; ?>
        <?php if (!$readonly): ?>
            step="<?php echo esc_attr($step); ?>"
            placeholder="<?php echo esc_attr($placeholder); ?>"
            inputmode="<?php echo esc_attr($inputmode); ?>"
            autocomplete="<?php echo esc_attr($autocomplete ?? 'on'); ?>"
        <?php endif; ?>
    />

    <?php if ($stepper): ?>
        <button type="button" class="qty-btn" data-step="1" aria-controls="<?php echo esc_attr($input_id); ?>" aria-label="<?php echo esc_attr(neblina_i('qty_more')); ?>">+</button>
    <?php endif; ?>

    <?php do_action('woocommerce_after_quantity_input_field'); ?>
</div>
