<?php global $cbt_options; 
	
	if(isset($post) && isset($post->ID))
	{
		$page_layout = get_post_meta( $post->ID, 'cbt_layout', true );
		//echo '<pre>'.print_r($page_layout, TRUE).'</pre>';
		if($page_layout != 'default')
		{
			switch($page_layout)
			{
				case 'boxed':
					$cbt_options['boxed_layout'] = '1';
				break;
				
				case 'wide':
					$cbt_options['boxed_layout'] = '0';
				break;
			}
		}
	}
?>
<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie10 lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie10 lt-ie9"><![endif]-->
<!--[if (IE 9)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie10"><![endif]-->
<!--[if gt IE 9]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<?php // Google Chrome Frame for IE ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">


<?php // mobile meta (hooray!) ?>
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<!--Shortcut icon-->
<?php if(!empty($cbt_options['apple-touch-icon']['url'])) { ?>
	<link rel="apple-touch-icon" href="<?php echo $cbt_options['apple-touch-icon']['url']; ?>" />
<?php } ?>
<?php if(!empty($cbt_options['favicon']['url'])) { ?>
	<link rel="shortcut icon" href="<?php echo $cbt_options['favicon']['url']; ?>" />
<?php } ?>

<!--[if IE]>
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
<![endif]-->
<?php // or, set /favicon.ico for IE10 win ?>
<meta name="msapplication-TileColor" content="<?php echo $cbt_options['header-color']; ?>">
<?php if(!empty($cbt_options['favicon']['url'])) { ?>
<meta name="msapplication-TileImage" content="<?php echo $cbt_options['favicon']['url']; ?>">
<?php } ?>

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

<?php // wordpress head functions ?>
<?php wp_head(); ?>
<?php // end of wordpress head ?>

<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/library/js/libs/respond.min.js"></script>
<![endif]-->
</head>

<body <?php body_class(); ?>>
	
<?php
// ------------------------------------------------------
//  Add video background if enabled
// ------------------------------------------------------
cbt_video_background();


?>

<div id="full-wrapper">

<?php if($cbt_options['boxed_layout'] == '1') echo '<div id="boxed">'; ?>


<?php if($cbt_options['header-style']) get_template_part('header-styles/style'.$cbt_options['header-style']); ?>

<!-- <div id="main_bg"></div> -->

<div id="main_area">

