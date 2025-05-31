<?php
global $cbt_options;
$custom_template_slug = $cbt_options['404_template'];

if($custom_template_slug)
{
	cbt_apply_custom_template($custom_template_slug);

	get_template_part( $template );
}
else
{
?>
	<?php get_header(); ?>

		<?php $heading_type = 'empty'; include(locate_template('template_parts/heading_area.php')); ?>

	      <div class="container">

	  			<div id="content">

						<div id="main" class="col-md-8 clearfix" role="main">

							<article id="post-not-found" class="hentry clearfix">

								<header class="article-header">

									<h1>404</h1>

								</header> <?php // end article header ?>

								<section class="entry-content">

									<p><?php _e( 'The page you were looking for was not found.', 'cbt' ); ?></p>

								</section> <?php // end article section ?>

								<section class="search">

										<p><?php get_search_form(); ?></p>

								</section> <?php // end search section ?>

								<footer class="article-footer">


								</footer> <?php // end article footer ?>

							</article> <?php // end article ?>

						</div> <?php // end #main ?>

	  			</div> <?php // end #content ?>

	      </div> <?php // end ./container ?>


	<?php get_footer(); ?>
<?php
}
