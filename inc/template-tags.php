<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package    scaffold
 * @copyright  Copyright (c) 2017, Danny Cooper
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! function_exists( 'scaffold_the_custom_logo' ) ) :
	/**
	 * Output the custom logo if it exists.
	 */
	function scaffold_the_custom_logo() {

		if ( function_exists( 'the_custom_logo' ) ) {
			the_custom_logo();
		}

	}
endif;

if ( ! function_exists( 'scaffold_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function scaffold_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

			$posted_on = sprintf(
				/* translators: %s: link to date archives */
				esc_html_x( 'Posted on %s', 'post date', 'scaffold' ),
				'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
			);

			$byline = sprintf(
				/* translators: %s: link to author archives */
				esc_html_x( 'by %s', 'post author', 'scaffold' ),
				'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
			);

			echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
			edit_post_link( esc_html__( '(Edit)', 'scaffold' ), '<span class="edit-link">', '</span>' );

	}
endif;

if ( ! function_exists( 'scaffold_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function scaffold_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'scaffold' ) );
			if ( $categories_list && scaffold_categorized_blog() ) {
				/* translators: %s: cstegories list */
				printf( '<span class="cat-links">' . esc_html__( 'Categories: %1$s', 'scaffold' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'scaffold' ) );
			if ( $tags_list ) {
				/* translators: %s: tags list */
				printf( '<span class="tags-links">' . esc_html__( 'Tags: %1$s', 'scaffold' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">' . esc_html__( 'Discussion: ', 'scaffold' );
			comments_popup_link( esc_html__( 'Leave a comment', 'scaffold' ), esc_html__( '1 Comment', 'scaffold' ), esc_html_x( '% Comments', 'number of comments', 'scaffold' ), 'comments-link' );
			echo '</span>';
		}

	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function scaffold_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'scaffold_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'scaffold_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so scaffold_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so scaffold_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in scaffold_categorized_blog.
 */
function scaffold_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'scaffold_categories' );
}
add_action( 'edit_category', 'scaffold_category_transient_flusher' );
add_action( 'save_post',     'scaffold_category_transient_flusher' );

if ( ! function_exists( 'scaffold_comment' ) ) :

	/**
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @param  object $comment  The comment object.
	 * @param  array  $args Comment arguements.
	 * @param  int    $depth Comment depth.
	 */
	function scaffold_comment( $comment, $args, $depth ) {
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="post pingback">
		<p><?php esc_html_e( 'Pingback:', 'scaffold' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'scaffold' ), ' ' ); ?></p>
	<?php
			break;
			default :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<cite class="fn"><?php comment_author_link(); ?></cite>
				</div><!-- .comment-author .vcard -->
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<em><?php esc_html_e( 'Your comment is awaiting moderation.', 'scaffold' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( esc_html__( '%1$s at %2$s', 'scaffold' ), get_comment_date(), get_comment_time() ); ?>
						</time></a>
						<?php edit_comment_link( esc_html__( '(Edit)', 'scaffold' ), ' ' );
						?>
					</div><!-- .comment-meta .commentmetadata -->
				</footer>

				<div class="comment-content"><?php comment_text(); ?></div>

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array(
						'depth' => $depth,
						'max_depth' => $args['max_depth'],
						'reply_text' => 'Reply &darr;',
					) ) ); ?>
				</div><!-- .reply -->
			</article><!-- #comment-## -->

		<?php
			break;
			endswitch;
	}
endif;


if ( ! function_exists( 'scaffold_the_post_navigation' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 */
	function scaffold_the_post_navigation() {
		$args = array(
			'prev_text'                  => __( 'Previous Post: <span>%title</span>', 'scaffold' ),
			'next_text'                  => __( 'Next Post: <span>%title</span>', 'scaffold' ),
			);

		the_post_navigation( $args );
	}
endif;


if ( ! function_exists( 'scaffold_the_posts_navigation' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 */
	function scaffold_the_posts_navigation() {
		$args = array(
		'prev_text'          => __( '&larr; Older Posts', 'scaffold' ),
		'next_text'          => __( 'Newer Posts &rarr;', 'scaffold' ),
		'screen_reader_text' => __( 'Posts Navigation', 'scaffold' ),
			);

		the_posts_navigation( $args );
	}
endif;

if ( ! function_exists( 'scaffold_thumbnail' ) ) :
	/**
	 * Output the thumbnail if it exists.
	 */
	function scaffold_thumbnail() {

		if ( has_post_thumbnail() ) { ?>
			<div class="post-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail(); ?>
				</a>
			</div><!-- .post-thumbnail -->
		<?php }

	}
endif;
