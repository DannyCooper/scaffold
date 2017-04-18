<?php
/*
Template Name: Full-width Template
Template Post Type: post, page
*/

get_header(); ?>

	<div class="content-area">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</div><!-- .content-area -->

<?php
get_footer();
