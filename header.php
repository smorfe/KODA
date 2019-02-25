<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri().'/images/favicon.ico'; ?>">
    <?php wp_head();?>
</head>

<body <?php body_class(); ?>>

<header class="site-header" role="header">
    <div class="row row-center row-middle row-wrap">
        <div class="columns-3 logo-column">
            <?php koda_site_logo('site_logo'); ?>

            <a class="menu-trigger" href="#">
                    <span class="burger-menu">
                        <span class="burger-line"></span>
                    </span>
            </a>
        </div>

        <nav class="columns-9 navigation-column" role="navigation">
            <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'menu_class'     => 'nav-menu main-menu'
                ]);
            ?>
        </nav>
    </div>
</header>

<section class="site-main" id="main">