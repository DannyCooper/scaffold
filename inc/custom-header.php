<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 * <?php the_header_image_tag(); ?>
 *
 * @link       https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package    scaffold
 * @copyright  Copyright (c) 2018, Danny Cooper
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses scaffold_header_style()
 */
function scaffold_custom_header_setup() {
	add_theme_support(
		'custom-header',
		apply_filters(
			'scaffold_custom_header_args',
			array(
				'default-image'      => '',
				'default-text-color' => '000000',
				'width'              => 1000,
				'height'             => 250,
				'flex-height'        => true,
				'flex-width'         => true,
				'wp-head-callback'   => 'scaffold_header_style',
			)
		)
	);
}
add_action( 'after_setup_theme', 'scaffold_custom_header_setup' );

if ( ! function_exists( 'scaffold_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see scaffold_custom_header_setup().
	 */
	function scaffold_header_style() {

		$header_text_color = get_header_textcolor();
		$height            = get_theme_mod( 'header-background-height', '173' );
		$repeat            = get_theme_mod( 'header-background-repeat', 'no-repeat' );
		$size              = get_theme_mod( 'header-background-size', 'initial' );
		$position          = get_theme_mod( 'header-background-position', 'center' );
		$attachment        = get_theme_mod( 'header-background-attachment', false ) ? 'fixed' : 'scroll';

		?>
		<style type="text/css">
			<?php if ( ! display_header_text() ) : ?>
				.site-title,
				.site-description {
					position: absolute;
					clip: rect(1px, 1px, 1px, 1px);
				}
			<?php else : ?>
				.site-title a {
					color: #<?php echo esc_attr( $header_text_color ); ?>;
				}
			<?php endif; ?>

			.site-header {
				min-height: <?php echo absint( $height ) . 'px'; ?>;
			}

			<?php if ( has_header_image() ) : ?>
				.site-header {
					background-image: url( <?php header_image(); ?> );
					background-repeat: <?php echo esc_attr( $repeat ); ?>;
					background-size: <?php echo esc_attr( $size ); ?>;
					background-position: <?php echo esc_attr( $position ); ?>;
					background-attachment: <?php echo esc_attr( $attachment ); ?>;
				}
			<?php endif; ?>
		</style>
		<?php
	}
endif;
