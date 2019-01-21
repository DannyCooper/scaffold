<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package    scaffold
 * @copyright  Copyright (c) 2019, Danny Cooper
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function scaffold_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( ! is_post_type_archive() && ! comments_open() ) {
		$classes[] = 'comments-closed';
	}

	return $classes;
}
add_filter( 'body_class', 'scaffold_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function scaffold_pingback_header() {

	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}

}
add_action( 'wp_head', 'scaffold_pingback_header' );

/**
 * Replaces the excerpt "Read More" text by a link.
 */
function new_excerpt_more() {
	global $post;
	return '&hellip; <p><a class="moretag" href="' . get_permalink( $post->ID ) . '">' . esc_html__( 'Read the full article', 'scaffold' ) . '</a></p>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );
