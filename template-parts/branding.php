<?php
/**
 * Template part for displaying the logo and site title.
 *
 * @package    scaffold
 * @copyright  Copyright (c) 2019, Danny Cooper
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

?>

<div class="site-branding">

	<?php scaffold_the_custom_logo(); ?>

	<?php if ( is_front_page() && is_home() ) : ?>

		<h1 class="site-title">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php bloginfo( 'name' ); ?>
			</a>
		</h1>

	<?php else : ?>

		<p class="site-title">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php bloginfo( 'name' ); ?>
			</a>
		</p>

	<?php endif; ?>

	<?php

	$description = get_bloginfo( 'description', 'display' );

	if ( $description || is_customize_preview() ) :
		?>

		<p class="site-description">
			<?php echo $description; /* WPCS: xss ok. */ ?>
		</p>

	<?php endif; ?>

</div><!-- .site-branding -->
