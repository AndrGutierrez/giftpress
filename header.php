<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php wp_head(); ?>
</head>
<body>
<header>
	<div class="container header">
		<div class="row align-items-center">
			<div class="col-2 "><img class="icon" src="<?php echo get_template_directory_uri()?>/assets/img/logo.png" alt=""></div>
			<div class="col-10 menu-title">
				<nav>
					<?php wp_nav_menu(
						// we create the menu in wordpress admin and here we are telling it
						// where to put it (also check functions.php)
						array(
							'theme_location' => 'top_menu',
							'menu_class' => 'main-menu',
							'container_class' => 'container-menu'
						)
					);
					?>
				</nav>
			</div>
		</div>
	</div>

</header>
	
