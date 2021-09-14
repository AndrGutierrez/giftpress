<?php 
//get taxonomy
$taxonomy = get_the_terms(get_the_ID(), 'product-category');
//gets 6 products, that have the same 'product category' taxonomy
$args= array(
            'post_type'=>'product',
            'posts_per_page'=>6,
            'order' => 'ASC',
            'orderby' => '',
            'post__not_in' => array($ID_current_product),
            // taxonomy filter
            'tax_query'=>array(
                array(
                    'taxonomy' => 'product-category',
                    'field' => 'slug',
                    'terms' => $taxonomy[0]
                )
            )
);
$products = new WP_Query($args);
            /* custom hook that recommends posts*/
?>
<?php get_header();
// this is how our posts are gonna be showed
?>
<main class="container my-3">
    <?php if ( have_posts()){
        while ( have_posts()){ ?>
            <?php the_post(); ?>
            <div class="post col-12 px-3">
                <div class="row col-10">
                    <div class="col-6 my-2 border p-0 thumbnail-container">
                        <?php the_post_thumbnail('medium');
                        // when we save an image in wordpress it divides it in 4 images:
                        // original, medium, large, thumbnail
                        ?>
                    </div>
                    <div class="col-6 product-description px-4">
                        <h1 class="my-3">
                            Product Name: <?php the_title(); ?>
                        </h1>
                        <?php the_content(); ?>
                    </div>
                </div>
                <?php if ($products->have_posts()){ ?>
                    <div class="row justify-content-center related-products col-12">
                        <?php while ($products->have_posts()){
                            $products->the_post(); ?>
                             <div class="col-3 my-3">
                                <div class="related-product p-3">
                                    <div class="thumbnail">
                                        <?php the_post_thumbnail('thumbnail'); ?>
                                    </div>
                                    <a href="<?php the_permalink() ?>" >
                                        <h4><?php the_title() ?></h4>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <!-- show next post or previous post --!>
                <?php get_template_part('template-parts/post', 'navigation')?>
            </div>
        <?php }
        }
    } ?>
</main>
<?php get_footer()?>
