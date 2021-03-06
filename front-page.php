<?php get_header(); ?>
<main class="container">
    <?php if(have_posts()){
        while(have_posts()){
            the_post();?>
            <h1 class="my-3">
                <?php the_title(); ?>
            </h1>
            <?php the_content(); ?>
        <?php }
        }?>
        <div class="product-list row">
            <h2 class="text-center">
                PRODUCTS
            </h2>
                <div class="row">
                    <div class="col-12">
                        <select id="product-category" name="prouct-category" class="form-control">
                        <?php
                            // we pass a taxonomy as argument
                            // hide if there is not products
                            $terms= get_terms('product-category', array('hide-empty'=>true));
                            foreach($terms as $term){
                                echo '<option value= "'.$term->slug.'">'.$term->name.'</option>';
                            }
                        ?>
                        </select>
                    </div>
                </div>
                <?php
                $args = array(
                    // the post type we created (check functions.php)
                    'post_type' => 'product',
                    // we can set the posts we want to get, -1 brings them all
                    'post_per_page' => -1,
                    // this is ordered by date options: DESC/ASC
                    'order' => 'ASC'
                );
                //making a request to get the posts
                $products = new WP_Query($args);
                if($products->have_posts()){
                    while($products->have_posts()){
                        $products->the_post();?>

                        <div class="col-4 border border rounded product-item">
                            <div class="thumbnail-container">

                            <?php the_post_thumbnail('medium') ?>
                            </div>
                            <div class="row product-info">
                                <h4 class="my-3">
                                    <a href="<?php the_permalink();?>">
                                        <?php the_title()?>
                                    </a>
                               
                                </h4>
                                <div class="btn-primary rounded-circle add">
                                    <div class="plus">
                                        +
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                }
                ?>
        </div>
</main>
<?php get_footer(); ?>
