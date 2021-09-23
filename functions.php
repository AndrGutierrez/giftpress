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
	// passing ajax route to scripts
	//handler (file), 'object name (gp (giftpress))'
	wp_localize_script('custom', 'gp', array(
		'ajaxurl'=> admin_url('admin-ajax.php'),
		'apiurl' => home_url('wp-json/pg/v1')
	));
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

//wordpress ajax
function gpFilterProducts(){
	$args = array(
		'post_type'=>'product',
		'posts_per_page'=>-1,
		'order'=>'ASC',
		'orderby'=>'title'
	);
	if($_POST['category']){
	/* 	//si pedimos categorías, muestralo por categorías, si no, muestra todos los productos */
		$args['tax_query'] =array(
			array(
				'taxonomy'=> 'product-category',
				'field'=> 'slug',
				'terms' => $_POST['category']
			)
		);
	}

	$products = new WP_Query($args);
	if($products->have_posts()){
	/* 		return array(); */
		while($products->have_posts()){
			$products->the_post();
			$return[] = array(
				'image' => get_the_post_thumbnail(get_the_id(), 'large'),
				'link' => get_the_permalink(),
				'title'=> get_the_title(), 
			);
		}
		wp_send_json($return);
	}
}

add_action('rest_api_init', 'newsAPI');
function newsAPI(){
	//wordpress api
	register_rest_route(
		//giftpress verison 1
		'gp/v1',
		//route news (amount is passed as $data argument in newsRequest)
		'/news/(?P<amount>\d+)',
		array(
			'methods' => 'GET',
			'callback' =>'newsRequest'
		)
	);
}

function newsRequest($data){
	$args = array(
		'post_type'=>'post',
		'posts_per_page'=>$data['amount'],
		'order'=>'ASC',
		'orderby'=>'title'
	);

	$news = new WP_Query($args);
		if($news->have_posts()){
	/* 		return array(); */
			while($news->have_posts()){
				$news->the_post();
				$return[] = array(
					'image' => get_the_post_thumbnail(get_the_id(), 'large'),
					'link' => get_the_permalink(),
					'title'=> get_the_title(), 
				);
			}
			return $return;
		}

}

add_filter('excerpt_length', 'pgCutEscerpt');
add_action('after_setup_theme', 'init_template');
add_action('widgets_init', 'sidebar');
add_action('wp_enqueue_scripts', 'assets');
add_action('init', 'products_type');
add_action('init', 'pgRegisterTax');

//for not login users
add_action('wp_ajax_nopriv_gpFilterProducts', 'gpFilterProducts');
//for login users
add_action('wp_ajax_gpFilterProducts', 'gpFilterProducts');
