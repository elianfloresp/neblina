<?php
/**
 * Header global — rendered em todas as páginas.
 * Fixo no código. Cliente NÃO edita header/menu.
 */
$lang = neblina_lang();
$other_lang = $lang === 'pt' ? 'es' : 'pt';
$current_url_other = neblina_switch_lang_url($other_lang);
?>
<!doctype html>
<html <?php language_attributes(); ?> data-theme="light">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo esc_url(NEBLINA_URI . '/assets/images/brand/symbol.png'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<a class="skip" href="#main"><?php echo neblina_i('skip_to_content'); ?></a>

<header class="header">
    <div class="shell">
        <a class="brand" href="<?php echo esc_url(neblina_home_url()); ?>" aria-label="Neblina Editorial">
            <img class="brand-dark" src="<?php echo esc_url(NEBLINA_URI . '/assets/images/brand/logo-dark.png'); ?>" alt="Neblina Editorial">
            <img class="brand-light" src="<?php echo esc_url(NEBLINA_URI . '/assets/images/brand/logo-light.png'); ?>" alt="Neblina Editorial">
        </a>

        <nav class="nav" id="navigation" aria-label="<?php echo esc_attr(neblina_i('primary_nav')); ?>">
            <?php neblina_render_menu(); ?>
        </nav>

        <div class="controls">
            <a class="lang <?php echo $lang === 'es' ? 'is-active' : ''; ?>"
               href="<?php echo esc_url(neblina_switch_lang_url('es')); ?>"
               aria-label="Español" <?php echo $lang === 'es' ? 'aria-current="true"' : ''; ?>>ES</a>
            <span aria-hidden="true">/</span>
            <a class="lang <?php echo $lang === 'pt' ? 'is-active' : ''; ?>"
               href="<?php echo esc_url(neblina_switch_lang_url('pt')); ?>"
               aria-label="Português" <?php echo $lang === 'pt' ? 'aria-current="true"' : ''; ?>>PT</a>
            <button class="theme-toggle" type="button" aria-label="<?php echo esc_attr(neblina_i('toggle_theme')); ?>" aria-pressed="false">◐</button>
        </div>

        <div class="mobile-toolbar">
            <a class="mobile-home" href="<?php echo esc_url(neblina_home_url()); ?>"><?php echo neblina_i('home'); ?></a>
            <button class="menu-toggle" type="button" aria-controls="navigation" aria-expanded="false">
                <?php echo neblina_i('menu_open'); ?>
            </button>
        </div>
    </div>
</header>

<main id="main" tabindex="-1">
