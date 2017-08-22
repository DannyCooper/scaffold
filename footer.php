<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link       https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package    scaffold
 * @copyright  Copyright (c) 2017, Danny Cooper
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

?>

	</div><!-- .site-content -->

	<footer class="site-footer" role="contentinfo">
		<div class="site-info">

			<?php
			 // translators: %1$s: theme name.
			 // translators: %2$s: theme author.
			 printf( esc_html__( 'Theme: %1$s by %2$s.', 'scaffold' ), 'Scaffold', '<a href="https://dannycooper.com/" rel="designer">Danny Cooper</a>' ); ?>

		</div><!-- .site-info -->
	</footer><!-- .site-footer -->

<?php wp_footer(); ?>
</div><!-- .site-wrapper -->

</body>
</html>
