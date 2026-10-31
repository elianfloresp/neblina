<?php
/**
 * Header global — estrutura idêntica ao protótipo v14.
 * Fixo no código. Cliente NÃO edita header/menu.
 */
$lang    = neblina_lang();
$is_home = get_query_var('neblina_route', 'home') === 'home';
?>
<!doctype html>
<html <?php language_attributes(); ?> data-theme="light">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $favicon_dir = NEBLINA_URI . '/assets/images/favicon'; ?>
    <link rel="icon" href="<?php echo esc_url($favicon_dir . '/favicon.ico'); ?>" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url($favicon_dir . '/favicon-32.png'); ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo esc_url($favicon_dir . '/icon-192.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo esc_url($favicon_dir . '/apple-touch-icon.png'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<a class="skip" href="#main"><?php echo neblina_i('skip_to_content'); ?></a>

<?php // Aviso do protótipo: sai quando o cliente aprovar o site definitivo (LEIA-ME da entrega v14). ?>
<div class="notice"><?php echo neblina_i('prototype_notice'); ?></div>

<header class="header shell">
    <a class="brand" href="<?php echo esc_url(neblina_home_url()); ?>" aria-label="Neblina Editorial — <?php echo esc_attr(neblina_i('home_lower')); ?>">
        <img class="brand-dark" src="<?php echo esc_url(NEBLINA_URI . '/assets/images/brand/logo-dark.png'); ?>" alt="Neblina Editorial">
        <img class="brand-light" src="<?php echo esc_url(NEBLINA_URI . '/assets/images/brand/logo-light.png'); ?>" alt="Neblina Editorial">
    </a>

    <nav class="nav" id="navigation" aria-label="<?php echo esc_attr(neblina_i('primary_nav')); ?>">
        <?php neblina_render_menu(); ?>
    </nav>

    <div class="controls">
        <a class="lang<?php echo $lang === 'es' ? ' is-active' : ''; ?>" lang="es"
           href="<?php echo esc_url(neblina_switch_lang_url('es')); ?>"
           aria-label="Español"<?php echo $lang === 'es' ? ' aria-current="true"' : ''; ?>>ES</a>
        <span aria-hidden="true">/</span>
        <a class="lang<?php echo $lang === 'pt' ? ' is-active' : ''; ?>" lang="pt"
           href="<?php echo esc_url(neblina_switch_lang_url('pt')); ?>"
           aria-label="Português"<?php echo $lang === 'pt' ? ' aria-current="true"' : ''; ?>>PT</a>
        <button class="theme-toggle" type="button" aria-label="<?php echo esc_attr(neblina_i('toggle_theme')); ?>" aria-pressed="false">◐</button>
    </div>

    <div class="mobile-toolbar">
        <a class="mobile-home" href="<?php echo esc_url(neblina_home_url()); ?>"<?php echo $is_home ? ' aria-current="page"' : ''; ?>><?php echo neblina_i('home'); ?></a>
        <button class="menu-toggle" type="button" aria-controls="navigation" aria-expanded="false"><?php echo neblina_i('menu_open'); ?></button>
    </div>
</header>

<main id="main" tabindex="-1">
