<?php
/**
 * Scaffold Theme Customizer
 *
 * @package    scaffold
 * @copyright  Copyright (c) 2018, Danny Cooper
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function scaffold_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'scaffold_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function scaffold_customize_preview_js() {
	wp_enqueue_script( 'scaffold_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'scaffold_customize_preview_js' );

/**
 * Get lighter/darker color when given a hex value.
 *
 * @param string $hex The hex value to darken.
 * @param int    $steps Steps should be between -255 and 255. Negative = darker, positive = lighter.
 */
function scaffold_brightness( $hex, $steps ) {

	$steps = max( -255, min( 255, $steps ) );

	// Normalize into a six character long hex string.
	$hex = str_replace( '#', '', $hex );
	if ( strlen( $hex ) === 3 ) {
		$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
	}

	// Split into three parts: R, G and B.
	$color_parts = str_split( $hex, 2 );
	$return      = '#';

	foreach ( $color_parts as $color ) {
		$color   = hexdec( $color ); // Convert to decimal.
		$color   = max( 0, min( 255, $color + $steps ) ); // Adjust color.
		$return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT ); // Make two char hex code.
	}

	return sanitize_hex_color( $return );
}

/**
 * Output the Customizer CSS to wp_head
 */
function scaffold_customizer_css() {

	$bg_color = get_theme_mod( 'navigation-bg-color' );

	?>
	<style>
	.menu-1 {
		background-color: <?php echo sanitize_hex_color( $bg_color ); // WPCS: XSS ok. ?>;
	}
	.menu-1 li:hover, .menu-1 li.focus {
		background-color: <?php echo scaffold_brightness( $bg_color, -25 ); // WPCS: XSS ok. ?>;
	}
	.menu-1 ul ul li {
		background-color: <?php echo scaffold_brightness( $bg_color, -50 ); // WPCS: XSS ok. ?>;
	}
	.menu-1 .sub-menu li:hover {
		background-color: <?php echo scaffold_brightness( $bg_color, -75 ); // WPCS: XSS ok. ?>;
	}
	</style>
	<?php
}
add_action( 'wp_head', 'scaffold_customizer_css' );
