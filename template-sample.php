<?php 
//Template Name: Sample Page
/*this is required*/
get_header();
// THIS IS FOR ACF FIELDS
$fields = get_fields();
?>
<main class='container'>
    <?php if(have_posts()){
        //checks if there is content to show 
        while(have_posts()){?>
                <?php the_post(); ?>
                <h1 class="my-3"><?php echo $fields['title']?></h1>
                <?php the_content(); ?>
                <img src="<?php echo $fields['image'] ?>" alt="">
            </div>
        <?php }
    }?>
</main>
<?php get_footer(); ?>
