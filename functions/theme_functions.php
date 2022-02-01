<?php
/*
 |----------------------------------------------------------------
 | Site Logo
 |----------------------------------------------------------------
 */
function koda_site_logo($logo = '', $class = 'site-logo') {
    printf(
        '<a href="%1$s" title="%2$s" rel="home"><img src="%3$s" alt="%2$s" class="%4$s" width="200" height="51" /></a>',
        home_url(),
        esc_attr(get_bloginfo('name')),
        trailingslashit(get_stylesheet_directory_uri()). KodaTheme::load_instance()->config($logo),
        $class
    );
}

/*
 |----------------------------------------------------------------
 | Adding a class into the menu link
 |----------------------------------------------------------------
 */
function add_class_on_menu_link( $class, $item, $args ) {
    if( $args->theme_location == 'primary' ) {
        $class['class'] = 'smooth-scroll';
    }
    return $class;
}
add_filter( 'nav_menu_link_attributes', 'add_class_on_menu_link', 10, 3 );

/*
 |----------------------------------------------------------------
 | Generate Social Media
 |----------------------------------------------------------------
 */
function socials($field_name, $class = '') {
    if (is_string($field_name)) $field_name = array($field_name);
    $socials = '';

    $socials .= sprintf('<ul class="social-lists"><li><p class="sub-heading">Connect with me</p></li>', $class);
    while (call_user_func_array('have_rows', $field_name)) : the_row();
        $platform = get_sub_field('platform');
        $link = get_sub_field('link');
        $socials .= sprintf(
            '<li><a href="%s" target="_blank"><i class="fa fa-%s" aria-hidden="true"></i></a></li>',
            get_sub_field('link'),
            get_sub_field('platform')
        );
    endwhile;

    $socials .= '</ul>';
    return $socials;
}

