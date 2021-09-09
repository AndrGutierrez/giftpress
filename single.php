<?php get_header();
// this is how our posts are gonna be showed
?>
<main class="container my-3">
    <?php if ( have_posts()){
        while ( have_posts()){?>
            <?php the_post();?>
            <div class="post col-10 px-3 ">
                <div class="row">
                    <div class="col-6 my-2 border p-0 thumbnail-container">
                        <?php the_post_thumbnail('medium');
                        // when we save an image in wordpress it divides it in 4 images:
                        // original, medium, large, thumbnail
                        ?>
                    </div>
                    <div class="col-6 product-description px-4">
                        <h1 class="my-3">
                            <?php the_title();?>
                        </h1>
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        <?php }
    }
    ?>
</main>
<?php get_footer()?>