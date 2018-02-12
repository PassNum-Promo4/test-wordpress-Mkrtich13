<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

get_header(); ?>

	<section id="primary" class="content-area col-sm-12 col-lg-12">
		<main id="main" class="site-main row" role="main">

		<?php
		if ( have_posts() ) : ?>

			<header class="page-header">
				
			</header><!-- .page-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();
			
			
				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				

?>
	<div class="col-lg-6 mb-4">


	<?php get_template_part( 'template-parts/content', get_post_format() ); ?>
	<img class="w-75 d-flex mx-auto" src="<?php the_field('aperçu_visuel_du_projet'); ?>" alt="aperçu projet">

	</div>
	
		

<?php
			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

	

		</main><!-- #main -->
		
		 
	
	</section><!-- #primary -->


<?php

get_footer();
