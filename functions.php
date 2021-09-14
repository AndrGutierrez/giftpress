<?php
function init_template(){
	/**
	 * RENDER
	 */
	//in all our page posts we are gonna see thumbnail
	add_theme_support('post-thumbnails');
	add_theme_support('title-tag');

	//registering a tab menu
	// (Appearance>Menus option will appear in our admin dashboard )
	register_nav_menus(
		array(
			'top_menu'=>'Main Menu'
		)
	);
}
// this means wordpress chooses the theme when someone opens the site
function assets(){
	/*
	* REGISTER DEPENDENCIES
	*/
	//this recieves name, source and dependencies, version and media
	//(in which resolutions and dispositives is this gonna be executated)
	// load css
	wp_register_style('bootstrap',
			          'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css',
					  '',
					  '5.0',
					  'all');

	wp_register_style('roboto',
	                  'https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap',
	       		  	  '',
			  		  '1.0',
					  'all');
	//load styles
	wp_enqueue_style('styles', get_stylesheet_uri(), array('bootstrap', 'roboto'), '1.0', 'all');

	// register javascript dependencies
	wp_register_script('popper',
			   '',
			   'https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js',
			   '1.16.0',
			   //this asks us if we want this to be installed in the footer or in the header,
			   // so we type true for footer
			   true
			   );
	//making dependencies work
	wp_enqueue_script('bootstrap',
			  'https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js',
			  // these are bootstrap js popper was installed in the lines above, and
			  // wordpress has jquery already installed
			  array('jquery', 'popper'),
			  '5.0',
			  true
	);
	//loading our custom javascript files
	wp_enqueue_script('custom', get_template_directory_uri().'/assets/js/custom.js', '', '1.0', true);
}

function sidebar(){
	/**
	 * Widgets
	 */
	// (appearance>widgets will appear in our admin dashboard)
	 register_sidebar(
		 array(
			 'name' => 'Footer',
			 //html id
			 'id' => 'footer',
			 'description' => 'footer widget zone',
			 // tags that contain the title
			 'before_title' => '<p>',
			 'after_title' => '</p>',
			 // this will take the 'id' value
			 'before_widget' => '<div id="%1$s" class="%2$s"',
			 'after_widget' => '</div>'
		 )
		 );
}

function products_type(){
	/**
	 * Custom post type for products
	 */
	$labels = array(
		'name' => 'Products',
		'silgular_name' => 'Product',
		'menu_name' => 'Products',
	);
	$args = array(
		'label' => 'Products',
		'description' =>'products registered on the page',
		'labels' => $labels,
		// post type features
		// revisions is the history of the post changes
		'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
		'public' => true,
		// this is for being able to show in our admin menu our custom post type
		'show_in_menu' =>true,
		'menu_position' => 5,
		// we can see more of these icons at:
		// http://developer.wordpress.org/resource/dashicons
		'menu_icon' => 'dashicons-cart',
		'can_export' => true,
		// this is for making custom hooks (always should be true)
		'publicy_queryable' => true,
		// asign a custom url
		'rewrite' => true,
		// making public for wordpress API and shows a better editor
		'show_in_rest' => true,

	);
	register_post_type('product', $args);
}

function pgCutEscerpt( $length ) {
	// Excerpt length es de 20 palabras
	return 20;
}

function pgRegisterTax(){
	/*
	 * CREATE NEW TAXONOMY (product categories)
	 */
	$args = array(
		'hierarchical' => true,
		'labels' => array(
			'name'=> 'Product Categories',
			'singular_name'=> 'Product Category',
		),
		'show_in_nav_menu' => true,
		'show_admin_column' => true,
		// routing
		'rewrite' => array('slug'=>'product-category')
	);
	// name, post types
	register_taxonomy('product-category', array('product'), $args);

}

add_filter('excerpt_length', 'pgCutEscerpt');
add_action('after_setup_theme', 'init_template');
add_action('widgets_init', 'sidebar');
add_action('wp_enqueue_scripts', 'assets');
add_action('init', 'products_type');
add_action('init', 'pgRegisterTax');
