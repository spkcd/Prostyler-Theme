<?php 
global $cbt_options; 
$nav_align = ' nav-justified';
?>
<!-- header style3 -->
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

<header id="header" class="header">

	<div class="header-bg header-inner">
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
			</div>
		</div> 	
		
		<div id="nav-holder" class="sticky-holder <?php echo ($cbt_options['nav-sticky']=='1')?'do-sticky':''?>">
			<nav class="header-bg header-bg2 no_slide_height_zone" role="navigation">
				<div class="navbar">
					<div class="container">
						<div class="navbar-collapse collapse navbar-responsive-collapse">
							<a href="#" class="navbar-toggle" data-target=".navbar-responsive-collapse"><i class="fa fa-times-circle-o"></i></a>
							<?php bones_main_nav($nav_align); ?>
						</div>
					</div>
				</div> 	
			</nav>
		</div>	
	</div>
	


</header> <?php // end header ?>