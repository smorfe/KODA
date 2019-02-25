<?php get_header(); ?>


<div class="site-content" role="main">
    <div class="row">
        <div class="columns-12 loop-column">
            <?php while (have_posts()): the_post(); ?>
                <?php get_template_part('templates/loop/' . get_post_type()); ?>
            <?php endwhile; ?>

            <?php get_template_part('tempaltes/components/pagination'); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
