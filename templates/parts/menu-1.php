<?php
/**
 * Template part for displaying the primary navigation menu.
 *
 * @package scaffold
 */

?>

<nav class="menu-1 site-navigation" role="navigation">
	<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'scaffold' ); ?></button>
	<?php wp_nav_menu( array(
		'theme_location' => 'menu-1',
		'menu_id' => 'site-menu',
	) ); ?>
</nav><!-- .site-navigation -->
