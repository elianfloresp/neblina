<?php
/**
 * Configuração base do WooCommerce da Neblina. Idempotente: pode rodar de novo.
 *
 * Uso (só linha de comando):
 *   php wp-content/themes/neblina/tools/setup-loja.php
 *
 * ⚠️ FICTÍCIO até o cliente confirmar: endereço da loja, valores de frete,
 *    alíquota de IVA e dados bancários. Estão marcados com "FICTÍCIO" abaixo.
 */

if (PHP_SAPI !== 'cli') exit;

require dirname(__DIR__, 4) . '/wp-load.php';

if (!class_exists('WooCommerce')) {
    exit("WooCommerce não está ativo.\n");
}

// -----------------------------------------------------------------
// Loja, moeda e países
// -----------------------------------------------------------------
$eu = WC()->countries->get_european_union_countries();

$options = [
    // Endereço da loja — FICTÍCIO (base em Madri)
    'woocommerce_store_address'           => 'Calle de Ejemplo, 1',
    'woocommerce_store_city'              => 'Madrid',
    'woocommerce_store_postcode'          => '28001',
    'woocommerce_default_country'         => 'ES:M',

    // Vende e entrega só na União Europeia
    'woocommerce_allowed_countries'       => 'specific',
    'woocommerce_specific_allowed_countries' => $eu,
    'woocommerce_ship_to_countries'       => '',
    'woocommerce_default_customer_address'=> 'base',

    // Euro no formato espanhol: 18,90 €
    'woocommerce_currency'                => 'EUR',
    'woocommerce_currency_pos'            => 'right_space',
    'woocommerce_price_thousand_sep'      => '.',
    'woocommerce_price_decimal_sep'       => ',',
    'woocommerce_price_num_decimals'      => 2,

    // Unidades (peso em gramas, usado no frete)
    'woocommerce_weight_unit'             => 'g',
    'woocommerce_dimension_unit'          => 'cm',

    // Preço com IVA incluído (mesmo preço final em toda a UE: filtro em inc/woocommerce.php)
    'woocommerce_calc_taxes'              => 'yes',
    'woocommerce_prices_include_tax'      => 'yes',
    'woocommerce_tax_based_on'            => 'shipping',
    'woocommerce_tax_display_shop'        => 'incl',
    'woocommerce_tax_display_cart'        => 'incl',
    'woocommerce_tax_total_display'       => 'single',

    // Estoque
    'woocommerce_manage_stock'            => 'yes',
    'woocommerce_notify_low_stock_amount' => 3,
    'woocommerce_notify_no_stock_amount'  => 0,
    'woocommerce_hide_out_of_stock_items' => 'no',

    // Checkout sem conta: compra como convidado
    'woocommerce_enable_guest_checkout'   => 'yes',
    'woocommerce_enable_checkout_login_reminder' => 'no',
    'woocommerce_enable_signup_and_login_from_checkout' => 'no',
    'woocommerce_enable_myaccount_registration' => 'no',

    // Desliga o que não vamos usar
    'woocommerce_enable_reviews'          => 'no',
    'woocommerce_enable_coupons'          => 'no',
    'woocommerce_allow_tracking'          => 'no',
    'woocommerce_show_marketplace_suggestions' => 'no',
    'woocommerce_coming_soon'             => 'no',
    'woocommerce_onboarding_profile'      => ['skipped' => true],
    'woocommerce_task_list_hidden'        => 'yes',
    'woocommerce_extended_task_list_hidden' => 'yes',
];

foreach ($options as $name => $value) {
    update_option($name, $value);
}
echo "opções: " . count($options) . "\n";

// Pedidos nas tabelas novas (HPOS), padrão atual do WooCommerce. Só com loja vazia.
if (get_option('woocommerce_custom_orders_table_created') === 'yes'
    && empty(wc_get_orders(['limit' => 1, 'return' => 'ids']))) {
    update_option('woocommerce_custom_orders_table_enabled', 'yes');
    update_option('woocommerce_custom_orders_table_data_sync_enabled', 'no');
    echo "HPOS: ativo\n";
}

// -----------------------------------------------------------------
// IVA — FICTÍCIO: 4% (tipo superreducido de livros na Espanha).
// Confirmar com o contador, inclusive vendas para outros países da UE (OSS).
// -----------------------------------------------------------------
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->prefix}woocommerce_tax_rates WHERE tax_rate_name = 'IVA'");
WC_Tax::_insert_tax_rate([
    'tax_rate_country'  => 'ES',
    'tax_rate'          => '4.0000', // = $iva_es no frete, abaixo
    'tax_rate_name'     => 'IVA',
    'tax_rate_priority' => 1,
    'tax_rate_compound' => 0,
    'tax_rate_shipping' => 1,
    'tax_rate_order'    => 0,
    'tax_rate_class'    => '',
]);
echo "IVA: 4% ES\n";

// -----------------------------------------------------------------
// Frete — FICTÍCIO. A ordem importa: o WooCommerce usa a primeira zona que bate.
// Valores abaixo são o preço final pro cliente. O WooCommerce soma IVA ao frete,
// então na Espanha o custo gravado é o valor sem o IVA (4,90 € → 4,7115 + 4%).
// -----------------------------------------------------------------
$iva_es = 4;
foreach (WC_Shipping_Zones::get_zones() as $z) {
    WC_Shipping_Zones::delete_zone($z['zone_id']);
}

$zonas = [
    // Canárias, Ceuta e Melilla: fora no lançamento (alfândega e IGIC). Zona sem método = sem entrega.
    ['nome' => 'Canarias, Ceuta y Melilla (sin envío)', 'paises' => ['ES'], 'ceps' => ['35*', '38*', '51*', '52*'], 'metodos' => []],
    ['nome' => 'Islas Baleares', 'paises' => ['ES'], 'ceps' => ['07*'], 'metodos' => [
        ['flat_rate', 'Envío estándar', '8.90'],
    ]],
    ['nome' => 'España peninsular', 'paises' => ['ES'], 'ceps' => [], 'metodos' => [
        ['flat_rate', 'Envío estándar', '4.90'],
        ['free_shipping', 'Envío gratuito', '40'],
    ]],
    ['nome' => 'Unión Europea', 'paises' => array_values(array_diff($eu, ['ES'])), 'ceps' => [], 'metodos' => [
        ['flat_rate', 'Envío internacional', '14.90'],
    ]],
];

foreach ($zonas as $ordem => $z) {
    $zone = new WC_Shipping_Zone();
    $zone->set_zone_name($z['nome']);
    $zone->set_zone_order($ordem);
    foreach ($z['paises'] as $pais) {
        $zone->add_location($pais, 'country');
    }
    foreach ($z['ceps'] as $cep) {
        $zone->add_location($cep, 'postcode');
    }
    $zone->save();

    foreach ($z['metodos'] as [$tipo, $titulo, $valor]) {
        $instance = $zone->add_shipping_method($tipo);
        $key      = "woocommerce_{$tipo}_{$instance}_settings";
        $custo    = $z['paises'] === ['ES'] ? round($valor / (1 + $iva_es / 100), 4) : $valor;
        $settings = $tipo === 'free_shipping'
            ? ['title' => $titulo, 'requires' => 'min_amount', 'min_amount' => $valor, 'ignore_discounts' => 'no']
            : ['title' => $titulo, 'tax_status' => 'taxable', 'cost' => (string) $custo];
        update_option($key, $settings);
    }
    echo "frete: {$z['nome']}\n";
}

// -----------------------------------------------------------------
// Pagamento — por enquanto só transferência bancária, pra testar pedidos.
// Gateway real (Stripe etc.) entra quando o cliente tiver a conta.
// -----------------------------------------------------------------
update_option('woocommerce_bacs_settings', [
    'enabled'      => 'yes',
    'title'        => 'Transferencia bancaria',
    'description'  => 'Realiza el pago por transferencia. Tu pedido se enviará cuando recibamos el importe.',
    'instructions' => 'Usa el número de pedido como concepto de la transferencia.',
]);
update_option('woocommerce_bacs_accounts', [[
    'account_name'   => 'Neblina Editorial (FICTÍCIO)',
    'account_number' => '',
    'bank_name'      => 'Banco de ejemplo',
    'sort_code'      => '',
    'iban'           => 'ES00 0000 0000 0000 0000 0000',
    'bic'            => '',
]]);
foreach (['cheque', 'cod', 'paypal'] as $gw) {
    $s = get_option("woocommerce_{$gw}_settings", []);
    update_option("woocommerce_{$gw}_settings", array_merge((array) $s, ['enabled' => 'no']));
}
echo "pagamento: transferência (fictícia)\n";

wc_delete_shop_order_transients();
WC_Cache_Helper::get_transient_version('shipping', true);
echo "ok\n";
