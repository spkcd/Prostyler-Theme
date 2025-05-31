<?php
global $cbt_options, $ptitle;
	
get_header(); ?>

	<?php $heading_type = ''; $page_title = (isset($ptitle))?$ptitle:''; include(locate_template('template_parts/heading_area.php')); ?>

    <div class="container">

			<div id="content" class="row clearfix">

						<div id="main" class="col-md-8 clearfix" role="main">

							<?php get_template_part( 'template_parts/posts' ); ?>

						</div> <?php // end #main ?>


						<?php get_sidebar(); ?>


			</div> <?php // end #content ?>

    </div> <!-- end ./container -->

<?php get_footer(); ?>
