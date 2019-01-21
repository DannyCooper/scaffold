<?php
/**
 * Scaffold WooCommerce functions and definitions
 *
 * @package    scaffold
 * @copyright  Copyright (c) 2019, Danny Cooper
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! function_exists( 'scaffold_wc_checkout_link' ) ) {
	/**
	 * If there are products in the cart, show a checkout link.
	 */
	function scaffold_wc_checkout_link() {
		global $woocommerce;

		if ( count( $woocommerce->cart->cart_contents ) > 0 ) :

			echo '<a href="' . esc_url( $woocommerce->cart->get_checkout_url() ) . '" title="' . esc_attr__( 'Checkout', 'scaffold' ) . '">' . esc_html__( 'Checkout', 'scaffold' ) . '</a>';

		endif;
	}
}
