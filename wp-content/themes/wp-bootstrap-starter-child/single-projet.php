<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WP_Bootstrap_Starter
 */

get_header(); ?>

	<section id="primary" class="content-area col-sm-12 col-lg-12">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>
				<strong><h2 class="text-center"><?php the_field('nom_du_projet'); ?></h2></strong>
				<div class="d-flex mt-5">
				<img class="col-lg-8" src="<?php the_field('aperçu_visuel_du_projet'); ?>" alt="aperçu projet">
				<div class="col-lg-4 mt-5">
					<p><?php the_field('description_du_projet'); ?></p>
					<a class="text-info" href="<?php the_field('lien_vers_le_projet'); ?>">Lien vers le projet</a><br />
					<img class="col-lg-3 mt-3" src="<?php the_field('technologies_utilisees_dans_le_projet'); ?>" alt="logo technologie">
					<img class="col-lg-3 mt-3" src="<?php the_field('technologies_utilisees_dans_le_projet_(copie)'); ?>" alt="logo technologie">
				</div>
				</div>
		</main><!-- #main -->

	</section><!-- #primary -->

<?php

get_footer();
