<?php
/*
 *  Template Name: Home Page
 */
get_header(); the_post();?>

    <?php get_template_part('templates/homepage/banner'); ?>
    <?php get_template_part('templates/homepage/cta'); ?>
    <?php get_template_part('templates/homepage/survey'); ?>
    <?php get_template_part('templates/homepage/carriers'); ?>
    <?php get_template_part('templates/homepage/benefits'); ?>
    <?php get_template_part('templates/homepage/talk'); ?>
    <?php get_template_part('templates/homepage/form'); ?>

<?php get_footer();?>
