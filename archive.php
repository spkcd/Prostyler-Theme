<?php
global $cbt_options;
$custom_template_slug = $cbt_options['index_template'];
if ( post_password_required() ) { 
	$custom_template_slug = $cbt_options['password_template'];
}

if($custom_template_slug)
{
	cbt_apply_custom_template($custom_template_slug);
	
	ob_start();
	get_template_part( $template );
	$html = ob_get_contents();
	ob_end_clean();
	
	echo cbt_text_shortcodes_process($html);
}
else
{
?>
	<?php 
		get_header(); 
	?>
		
		<?php $heading_type = 'template_part'; $heading_template = 'template_parts/archive_titles'; include(locate_template('template_parts/heading_area.php')); ?>
	
	    <div class="container">
	
				<div id="content" class="clearfix row">
	
					<div id="main" class="col-md-8 clearfix" role="main">
	
						<?php get_template_part( 'template_parts/archive' ); ?>
	
					</div> <?php // end #main ?>
	
					<?php get_sidebar(); ?>
	
	
				</div> <?php // end #content ?>
	
	    </div> <?php // end ./container ?>
	
	<?php get_footer(); ?>
<?php
}