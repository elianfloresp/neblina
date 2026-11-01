<?php
/**
 * Pedidos — envio e idioma.
 *
 * - Caixa "Envio" na tela do pedido: transportadora, código e link de rastreio.
 *   Ao marcar o pedido como Concluído, o cliente recebe o e-mail de envio com o código.
 * - E-mails ao cliente e fatura em PDF saem no idioma em que a pessoa comprou
 *   (_neblina_lang, gravado no checkout), mesmo quando o status muda pelo painel em PT.
 */

if (!defined('ABSPATH')) exit;

const NEBLINA_META_TRANSPORTADORA = '_neblina_tracking_carrier';
const NEBLINA_META_RASTREIO       = '_neblina_tracking_code';
const NEBLINA_META_RASTREIO_URL   = '_neblina_tracking_url';

/**
 * Transportadoras. Só o Correos tem link de rastreio automático;
 * nas outras, cola-se o link que a transportadora fornecer.
 */
function neblina_transportadoras() {
    return [
        'correos' => ['nome' => 'Correos', 'url' => 'https://www.correos.es/es/es/herramientas/localizador/envios/detalle?tracking-number=%s'],
        'seur'    => ['nome' => 'SEUR',    'url' => ''],
        'gls'     => ['nome' => 'GLS',     'url' => ''],
        'mrw'     => ['nome' => 'MRW',     'url' => ''],
        'outra'   => ['nome' => '',        'url' => ''],
    ];
}

/**
 * Rastreio do pedido: ['transportadora' => nome, 'codigo' => …, 'url' => …] ou null.
 */
function neblina_order_tracking($order) {
    $codigo = trim((string) $order->get_meta(NEBLINA_META_RASTREIO));
    if ($codigo === '') return null;

    $chave = $order->get_meta(NEBLINA_META_TRANSPORTADORA) ?: 'correos';
    $t     = neblina_transportadoras()[$chave] ?? neblina_transportadoras()['outra'];
    $url   = $order->get_meta(NEBLINA_META_RASTREIO_URL)
        ?: ($t['url'] ? sprintf($t['url'], rawurlencode($codigo)) : '');

    return ['transportadora' => $t['nome'], 'codigo' => $codigo, 'url' => $url];
}

// -----------------------------------------------------------------
// Caixa "Envio" no pedido (admin, HPOS e legado)
// -----------------------------------------------------------------
add_action('add_meta_boxes', function () {
    $screen = function_exists('wc_get_page_screen_id') ? wc_get_page_screen_id('shop-order') : 'shop_order';
    add_meta_box('neblina-envio', 'Envio', 'neblina_render_tracking_box', $screen, 'side', 'high');
});

function neblina_render_tracking_box($post_or_order) {
    $order = $post_or_order instanceof WC_Order ? $post_or_order : wc_get_order($post_or_order->ID);
    if (!$order) return;
    $atual = $order->get_meta(NEBLINA_META_TRANSPORTADORA) ?: 'correos';
    ?>
    <p>
        <label for="neblina_tracking_carrier"><strong>Transportadora</strong></label><br>
        <select id="neblina_tracking_carrier" name="neblina_tracking_carrier" style="width:100%">
            <?php foreach (neblina_transportadoras() as $chave => $t): ?>
                <option value="<?php echo esc_attr($chave); ?>" <?php selected($atual, $chave); ?>><?php echo esc_html($t['nome'] ?: 'Outra'); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="neblina_tracking_code"><strong>Código de rastreio</strong></label><br>
        <input type="text" id="neblina_tracking_code" name="neblina_tracking_code" style="width:100%"
               value="<?php echo esc_attr($order->get_meta(NEBLINA_META_RASTREIO)); ?>">
    </p>
    <p>
        <label for="neblina_tracking_url">Link de rastreio <span style="color:#646970">(opcional)</span></label><br>
        <input type="url" id="neblina_tracking_url" name="neblina_tracking_url" style="width:100%" placeholder="https://"
               value="<?php echo esc_attr($order->get_meta(NEBLINA_META_RASTREIO_URL)); ?>">
    </p>
    <p class="description">Preencha o código e mude o status para <strong>Concluído</strong>: o cliente recebe o e-mail de envio com o rastreio, no idioma em que comprou. No Correos o link é automático.</p>
    <?php
}

// Prioridade 10: grava antes do Woo salvar o status (40), pra o e-mail de "concluído" já ter o código.
add_action('woocommerce_process_shop_order_meta', function ($order_id) {
    $order = wc_get_order($order_id);
    if (!$order || !isset($_POST['neblina_tracking_code'])) return;   // nonce já verificado pelo Woo

    $chave = sanitize_key(wp_unslash($_POST['neblina_tracking_carrier'] ?? 'correos'));
    $order->update_meta_data(NEBLINA_META_TRANSPORTADORA, array_key_exists($chave, neblina_transportadoras()) ? $chave : 'correos');
    $order->update_meta_data(NEBLINA_META_RASTREIO, sanitize_text_field(wp_unslash($_POST['neblina_tracking_code'])));
    $order->update_meta_data(NEBLINA_META_RASTREIO_URL, esc_url_raw(wp_unslash($_POST['neblina_tracking_url'] ?? '')));
    $order->save();
}, 10);

// Rastreio no e-mail de pedido concluído (= enviado).
add_action('woocommerce_email_before_order_table', function ($order, $sent_to_admin, $plain_text, $email) {
    if ($sent_to_admin || !$email || $email->id !== 'customer_completed_order') return;
    $r = neblina_order_tracking($order);
    if (!$r) return;

    $linha = trim($r['transportadora'] . ' · ' . $r['codigo'], ' ·');
    if ($plain_text) {
        echo "\n" . neblina_i('tracking_title') . "\n" . $linha . ($r['url'] ? "\n" . $r['url'] : '') . "\n\n";
        return;
    }
    ?>
    <div style="margin:0 0 24px;padding:16px 18px;border-left:3px solid #324483;background:#f9f1ea;">
        <p style="margin:0 0 6px;font-weight:600;"><?php echo esc_html(neblina_i('tracking_title')); ?></p>
        <p style="margin:0;"><?php echo esc_html($linha); ?></p>
        <?php if ($r['url']): ?>
            <p style="margin:8px 0 0;"><a href="<?php echo esc_url($r['url']); ?>" style="color:#324483;"><?php echo esc_html(neblina_i('tracking_link')); ?> →</a></p>
        <?php endif; ?>
    </div>
    <?php
}, 5, 4);

// Logo e rodapé dos e-mails da loja vêm do tema (rodapé no idioma do pedido).
add_filter('option_woocommerce_email_header_image', fn() => NEBLINA_URI . '/assets/images/brand/logo-dark.png');
add_filter('woocommerce_email_footer_text', fn() => neblina_i('email_footer'));

// -----------------------------------------------------------------
// Idioma do pedido nos e-mails ao cliente e na fatura
// -----------------------------------------------------------------

/**
 * Idioma em que o pedido foi feito (ou o padrão do site, pra pedidos antigos).
 */
function neblina_order_lang($order) {
    $lang = $order instanceof WC_Order ? $order->get_meta('_neblina_lang') : '';
    return in_array($lang, NEBLINA_LANGS, true) ? $lang : NEBLINA_DEFAULT_LANG;
}

/**
 * Entra no idioma do pedido (textos do WP/Woo, neblina_i() e instrução da transferência).
 * Sempre em par com neblina_order_locale_pop().
 */
function neblina_order_locale_push($order) {
    $lang = neblina_order_lang($order);
    $GLOBALS['neblina_lang_stack'][] = [
        $GLOBALS['neblina_lang_override'] ?? null,
        switch_to_locale(neblina_locale($lang)),   // false se já estava nesse idioma
    ];
    $GLOBALS['neblina_lang_override'] = $lang;
    neblina_localize_bacs();
}

function neblina_order_locale_pop() {
    if (empty($GLOBALS['neblina_lang_stack'])) return;
    [$anterior, $trocou] = array_pop($GLOBALS['neblina_lang_stack']);
    if ($trocou) restore_previous_locale();
    $GLOBALS['neblina_lang_override'] = $anterior;
    neblina_localize_bacs();
}

// O Woo troca o idioma no início do e-mail, quando ainda não sabe o pedido.
// Aqui ele não troca; o idioma do pedido entra no primeiro get_recipient(),
// que roda com o pedido carregado e antes de montar assunto e conteúdo.
$GLOBALS['neblina_email_locale'] = [];

add_filter('woocommerce_allow_switching_email_locale', function ($allow, $email) {
    if (!$email->is_customer_email()) return $allow;
    $GLOBALS['neblina_email_locale'][spl_object_id($email)] = 'pendente';
    return false;
}, 10, 2);

add_action('woocommerce_email', function ($mailer) {
    foreach ($mailer->get_emails() as $email) {
        if (!$email->is_customer_email()) continue;
        add_filter('woocommerce_email_recipient_' . $email->id, function ($recipient, $order, $email) {
            $id = spl_object_id($email);
            if (($GLOBALS['neblina_email_locale'][$id] ?? '') === 'pendente' && $order instanceof WC_Order) {
                neblina_order_locale_push($order);
                // O Woo guarda o assunto/título padrão na 1ª leitura (WC_Settings_API::get_option);
                // recarrega pra não reaproveitar o texto de outro idioma (ex.: vários pedidos de uma vez).
                $email->init_settings();
                $GLOBALS['neblina_email_locale'][$id] = 'trocado';
            }
            return $recipient;
        }, 10, 3);
    }
});

add_filter('woocommerce_allow_restoring_email_locale', function ($allow, $email) {
    if (!$email->is_customer_email()) return $allow;
    $id = spl_object_id($email);
    if (($GLOBALS['neblina_email_locale'][$id] ?? '') === 'trocado') {
        neblina_order_locale_pop();
    }
    unset($GLOBALS['neblina_email_locale'][$id]);
    return false;
}, 10, 2);

// Fatura e guia de remessa (PDF Invoices & Packing Slips) no idioma do pedido.
$GLOBALS['neblina_pdf_locale'] = [];

add_action('wpo_wcpdf_before_html', function ($type, $document) {
    if (!empty($document->order) && $document->order instanceof WC_Order) {
        neblina_order_locale_push($document->order);
        $GLOBALS['neblina_pdf_locale'][spl_object_id($document)] = true;
    }
}, 10, 2);
add_action('wpo_wcpdf_after_html', function ($type, $document) {
    $id = spl_object_id($document);
    if (!empty($GLOBALS['neblina_pdf_locale'][$id])) {
        neblina_order_locale_pop();
        unset($GLOBALS['neblina_pdf_locale'][$id]);
    }
}, 10, 2);
