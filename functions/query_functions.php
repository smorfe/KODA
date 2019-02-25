<?php
/*
 |----------------------------------------------------------------
 | Query Functions
 |----------------------------------------------------------------
 */
/**
 * get_query functions.
 *
 * @param  string $post_type. The post type you'd like to pull fron, default: 'post'
 * @param  int $limit. How many results do you want, default: -1
 * @param  array/string $args. Overwrite wp_query's arg, default: ''
 * @return obj
 *
 */
function get_query( $post_type = 'post', $limit = null, $args = '') {
    $limit = is_null($limit)? get_option('posts_per_page') : $limit;
    $paged = get_query_var('paged')? get_query_var('paged') : 1;
    $defaults = array(
        'post_type' => $post_type,
        'posts_per_page' => $limit,
        // 'order' => 'ASC',
        // 'orderby' => 'menu_order', // they have a backend option setting now, don't need to do this
        'paged' => $paged
    );

    $args = wp_parse_args( $args, $defaults );

    return new WP_Query( $args );
}

/* return a wp_query object with random order (shortcut) */
function get_random( $post_type = 'post', $limit = null, $args = '') {
    $default = array(
        'orderby' => 'rand'
    );
    return get_query( $post_type, $limit, wp_parse_args( $args, $default ));
}

/* return a wp_query object with date order (shortcut) */
function get_latest( $post_type = 'post', $limit = null, $args = '') {
    $default = array(
        'orderby' => 'date',
        'order' => 'desc'
    );
    return get_query( $post_type, $limit, wp_parse_args( $args, $default ));
}

/* return a wp_query object with related (shortcut) */
function get_related( $limit = null, $related_by = 'category', $exclude_realted_ids = array() ) {
    $current_id = get_the_ID();
    $post_type = get_post_type();

    $the_terms = get_the_terms( get_the_ID(), $related_by );
    $term_ids = array();

    if( $the_terms ) {
        foreach( $the_terms as $tt ) {
            if( in_array( $tt->term_id, $exclude_realted_ids ) ) continue;
            $term_ids[] = $tt->term_id;
        }
    }

    $args = array(
        'post__not_in' => array( $current_id ),
        'tax_query' => array(
            array(
                'taxonomy' => $related_by,
                'field'    => 'term_id',
                'terms'    => $term_ids,
            ),
        ),
    );
    return get_random( $post_type, $limit, $args);
}

function loop($query, $template) {
    // if (! $query) {
    //     $query->query();
    // }

    while ($query->have_posts()): $query->the_post();
        get_template_part($template);
    endwhile;

    wp_reset_query();
}