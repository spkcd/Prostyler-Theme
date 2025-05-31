</div> <!-- #main_area -->

	<?php 
	global $cbt_options; 
	
	$footerColumns = (!empty($cbt_options['footer_columns'])) ? $cbt_options['footer_columns'] : '2'; 
	
	if($footerColumns == '2'){
		$footerColumnClass = 'col-sm-6 col-md-6';
	} else if($footerColumns == '3'){
		$footerColumnClass = 'col-sm-4 col-md-4';
	} else {
		$footerColumnClass = 'col-sm-3 col-md-3';
	}
	?>

    <footer id="footer" class="clearfix footer-columns-<?php echo $footerColumns;?>">
	    <div id="footer-wrap">
	      <div id="footer-widgets">
	
	        <div class="container">
	
	        <div id="footer-wrapper">
	
	          <div class="row">
	          
	            <div class="<?php echo $footerColumnClass?> footer-sidebar">
	              <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-1') ) : ?>
	              <?php endif; ?>
	            </div> <!-- end widget1 -->
	
	            <div class="<?php echo $footerColumnClass?> footer-sidebar">
	              <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-2') ) : ?>
	              <?php endif; ?>
	            </div> <!-- end widget1 -->
	            
	            <?php if($footerColumns == '3' || $footerColumns == '4'): ?>
		            <div class="<?php echo $footerColumnClass?> footer-sidebar">
		              <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-3') ) : ?>
		              <?php endif; ?>
		            </div> <!-- end widget1 -->
	            <?php endif; ?>
	            
	            <?php if($footerColumns == '4'): ?>
		            <div class="<?php echo $footerColumnClass?> footer-sidebar">
		              <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-4') ) : ?>
		              <?php endif; ?>
		            </div> <!-- end widget1 -->
	            <?php endif; ?>
	
	          </div> <!-- end .row -->
	
	        </div> <!-- end #footer-wrapper -->
	
	        </div> <!-- end .container -->
	      </div> <!-- end #footer-widgets -->
	
	      <div id="sub-floor">
	        <div class="container">
	          <div class="row">
	            <div class="col-md-4 copyright">
		            <?php if(!empty($cbt_options['footer-copyright-text']))
			            {
				            $cbt_options['footer-copyright-text'] = preg_replace('/{YEAR}/i',  date('Y'), $cbt_options['footer-copyright-text']);
			            }
			        ?>
	
					<?php if(!empty($cbt_options['disable-auto-copyright']) && $cbt_options['disable-auto-copyright'] == 1) { ?>
						<?php if(!empty($cbt_options['footer-copyright-text'])) echo $cbt_options['footer-copyright-text']; ?> 	
					<?php } else { ?>
						&copy; <?php echo date('Y') . ' ' . get_bloginfo('name'); ?>. <?php if(!empty($cbt_options['footer-copyright-text'])) echo $cbt_options['footer-copyright-text']; ?> 
					<?php } ?>
					
	            </div>
	            <div class="col-md-offset-4 attribution">
	              <?php 
				    wp_nav_menu(array(
				    	'theme_location' => 'footer-nav',
				    	'depth' => 1,  
				    	
	    	'container' => false,                           			// remove nav container
	    	'container_class' => 'menu clearfix',           			// class of container (should you choose to use it)
	    	'menu' => __( 'Footer menu', 'cbt' ),  			// nav name
	    	'menu_class' => 'footer-nav navbar-right',  			// adding custom nav class
	    	'before' => '',                                 			// before the menu
	      'after' => '',                                  			// after the menu
	      'link_before' => '',                            			// before each link
	      'link_after' => '',                             			// after each link
				    ));
	              ?>
	            </div>
	          </div> <!-- end .row -->
	        </div>
	      </div>
	
		</div>
    </footer> <!-- end footer -->
    
    <a id="back-to-top" href="#" class="hidden-xs back-to-top" role="button" title="<?php echo __('Return to top', 'cbt')?>" data-toggle="tooltip" data-placement="left"><i class="fa fa-chevron-up"></i></a>
    
    <?php if($cbt_options['boxed_layout'] == '1') echo '</div>'; ?>
    
    


    
    	<div class="mobile-nav-holder navbar"></div>
    </div> <!-- #full-wrapper -->

	<?php if(!empty($cbt_options['google-analytics'])) echo $cbt_options['google-analytics']; ?> 
    <!-- all js scripts are loaded in library/bones.php -->
    <?php wp_footer(); ?>
    <!-- Hello? Doctor? Name? Continue? Yesterday? Tomorrow?  -->
	<?php 
    $tawk_display = FALSE;

    if($cbt_options['tawk_visibility_always_display'] == 1){ 
    	$tawk_display = TRUE; 
    }else{
    	if(($cbt_options['tawk_visibility_front_page'] == 1) && (is_home() || is_front_page()) ){ $tawk_display = TRUE; }
		if(($cbt_options['tawk_visibility_category_pages'] == 1) && is_category() ){ $tawk_display = TRUE; }
		if(($cbt_options['tawk_visibility_tag_pages'] == 1) && is_tag() ){ $tawk_display = TRUE; }
		if(($cbt_options['tawk_visibility_single_post_page'] == 1) && is_single() ){ $tawk_display = TRUE; }
    }
    if(($cbt_options['tawk_visibility_exclude_pages'] == 1) && ($cbt_options['tawk_visibility_always_display'] == 1))
    {
		$excluded_url_list = $cbt_options['tawk_visibility_excluded_pages'];

		$current_url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		$current_url = urldecode($current_url);

		$ssl      = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' );
	    $sp       = strtolower( $_SERVER['SERVER_PROTOCOL'] );
	    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );

	    $current_url = $protocol.'://'.$current_url;
	    $current_url = strtolower($current_url);

		$excluded_url_list = preg_split("/,/", $excluded_url_list);
		foreach($excluded_url_list as $exclude_url)
		{
			$exclude_url = strtolower(urldecode(trim($exclude_url)));
			
			if (strpos($current_url, $exclude_url) !== false) {
					$tawk_display = false;
			}
		}
	}
	if(($cbt_options['tawk_visibility_include_pages'] == 1) && ($cbt_options['tawk_visibility_always_display'] != 1))
	{
		$included_url_list = $cbt_options['tawk_visibility_include_pages'];
		$current_url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		$current_url = urldecode($current_url);

		$ssl      = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' );
	    $sp       = strtolower( $_SERVER['SERVER_PROTOCOL'] );
	    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );

	    $current_url = $protocol.'://'.$current_url;
	    $current_url = strtolower($current_url);

		$included_url_list = preg_split("/,/", $included_url_list);
		foreach($included_url_list as $include_url)
		{
			$include_url = strtolower(urldecode(trim($include_url)));
			if (strpos($current_url, $include_url) !== false) {
					$tawk_display = TRUE;
			}
		}
	}
    if(!empty($cbt_options['tawk_widget_code']) && $tawk_display)
      {
      	echo $cbt_options['tawk_widget_code'];
      }
    ?> 
  </body>

</html> <!-- end page. what a ride! -->
