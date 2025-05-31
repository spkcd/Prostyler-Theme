<?php global $cbt_options; ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); 

get_header(); ?>



	
	<?php $heading_type = 'breadcrumb'; include(locate_template('template_parts/heading_area.php')); ?>

      
    <div class="container">  

			<div id="content" class="clearfix row">

				<div id="main" class="col-md-8 clearfix" role="main">

						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="https://schema.org/BlogPosting">

							<?php get_template_part( 'template_parts/single' ); ?>

						</article> <?php // end article ?>



          <?php comments_template(); ?>



				</div> <?php // end #main ?>

				<?php get_sidebar(); ?>

			</div> <?php // end #content ?>

    </div> <?php // end ./container ?>

<?php get_footer(); ?>

<?php endwhile; ?> 

<?php else : ?>

<?php get_template_part('not-found'); ?>

<?php endif; ?>