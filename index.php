<?php
$template = 'default_template';

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
else get_template_part( $template );

/*
$template = 'default_template';
get_template_part( $template );
*/