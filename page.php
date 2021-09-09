<?php get_header();?>
<main class='container'>
    <?php if(have_posts()){
        //checks if there is content to show 
        while(have_posts()){?>
                <?php the_post(); ?>
                <h1 class="my-3"><?php the_title();?></h1>
                <?php the_content(); ?>
            </div>
        <?php }
    }?>
</main>
<?php get_footer(); ?>