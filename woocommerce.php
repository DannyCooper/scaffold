<?php
/**
 * The template for displaying WooCommerce pages.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility
 *
 * @package scaffold
 */

get_header(); ?>

	<div class="content-area">

		<?php woocommerce_content(); ?>

	</div><!-- .content-area -->

<?php
get_footer();
