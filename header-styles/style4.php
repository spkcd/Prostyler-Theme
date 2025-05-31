<?php 
global $cbt_options; 
$nav_align = 'right';

global $theme_shape;
$theme_shape = array('header', 'heading');
switch($cbt_options['header4-shape'])
{
	case 'arrow':
		$theme_shape['header'] = '<svg id="bigTriangle" xmlns="https://www.w3.org/2000/svg" version="1.1" width="100%" height="100" viewBox="0 0 100 100" preserveAspectRatio="none">
			<path id="trianglePath1" d="M0 0 L50 100 L100 0 Z" />
		</svg>';
		$theme_shape['heading'] = '<svg version="1.1" id="bigTriangle" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px"
			 y="0px" viewBox="0 0 612 100" enable-background="new 0 0 612 100" xml:space="preserve"  width="100%" height="100" preserveAspectRatio="none">
		<polygon points="0,101.5 0,0 306,99.7 612,0 612,101.5 "/>
		</svg>';
	break;
	
	
	case 'curve':
		$theme_shape['header'] = '<svg version="1.1" id="Layer_1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 611.9 9.6" enable-background="new 0 0 611.9 9.6" xml:space="preserve" width="100%" height="100" preserveAspectRatio="none">
<path fill-rule="evenodd" clip-rule="evenodd" d="M0,0h611.9v1c0,0-152.9,8.6-306,8.6C153,9.6,0,1,0,1V0L0,0z"/>
</svg>';
		$theme_shape['heading'] = '<svg version="1.1" id="Layer_1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 611.9 9.6" enable-background="new 0 0 611.9 9.6" xml:space="preserve" width="100%" height="100" preserveAspectRatio="none">

	<path fill-rule="evenodd" clip-rule="evenodd" d="M611.9,9.6V1c0,0-152.9,8.6-306,8.6H611.9z"/>
	<path fill-rule="evenodd" clip-rule="evenodd" d="M0,9.6h306C153,9.6,0,1,0,1V9.6z"/>

</svg>';
	break;
}
?>
<!-- header style4 -->
<?php if($cbt_options['disable-top-bar'] != 1): ?>
<div id="top-nav" class="<?php if($cbt_options['top-bar-no-mobile-hide'] != 1): ?>hidden-sm hidden-xs<?php endif;?>">
	<div class="container">
		<ul id="contact_info" class="pull-left">
			<?php if($cbt_options['social-phone']){?>
				<li><i class="fa fa-phone"></i> <a href="tel:<?php echo $cbt_options['social-phone']?>"><?php echo $cbt_options['social-phone']?></a></li>
			<?php } ?>
			<?php if($cbt_options['social-email']){?>
				<li><i class="fa fa-envelope"></i> <a target="_blank" href="mailto:<?php echo $cbt_options['social-email']?>"><?php echo $cbt_options['social-email']?></a></li>
			<?php } ?>
		</ul>
		
		<ul id="social_buttons" class="pull-right">
			<?php
				foreach ($cbt_options['social-icons'] as $idx => $arr) 
				{
				    if (! $arr['enabled']) continue;

				    echo '<li><a title="'.$arr['name'].'" target="_blank" href="'.$arr['url'].'"><i class="fa '.$arr['icon'].'"></i></a></li>';
				}	
			?>
			<?php if($cbt_options['social-rss']){?>
				<li><a href="<?php bloginfo('rss2_url'); ?>"><i class="fa fa-rss"></i></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<?php endif;?>

<header id="header" class="header <?php echo ($cbt_options['nav-sticky']=='1')?'do-sticky':''?> <?php echo ($cbt_options['disable-top-bar']=='1')?'no-topbar':''?>">

	<nav role="navigation" class="header-bg header-bg2 header-inner no_slide_height_zone">
		<div class="navbar">
			<div class="container">
				<!-- .navbar-toggle is used as the toggle for collapsed navbar content -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<?php apply_filters( 'st_woo_cart_mobile', '', 'echo' ); ?>
					
					<?php if(isset($cbt_options['logo']) && $cbt_options['logo']['url']):?>
						<a id="logo" class="navbar-brand" href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>" rel="homepage">
							<img src="<?php echo $cbt_options['logo']['url']; ?>" />
						</a>
					<?php else:?>
						<a id="logo" class="navbar-brand no-logo" href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>" rel="homepage"><?php bloginfo('name'); ?></a>
					<?php endif;?>
				</div>
				
				<div class="navbar-collapse collapse navbar-responsive-collapse yamm">
					<a href="#" class="navbar-toggle" data-target=".navbar-responsive-collapse"><i class="fa fa-times-circle-o"></i></a>
					<?php bones_main_nav($nav_align); ?>
				</div>
			</div>
		</div> 
		
		<?php echo $theme_shape['header']; ?>

		
	</nav>

</header> <?php // end header ?>