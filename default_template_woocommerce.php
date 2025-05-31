<?php
global $cbt_options;
get_header(); ?>

	<?php $heading_type = 'empty'; include(locate_template('template_parts/heading_area.php')); ?>



      <div class="container">

        <div id="content" class="clearfix row">
        
          <div id="main" class="col-md-12 clearfix" role="main">


              <section class="page-content entry-content clearfix" itemprop="articleBody">
               
                <?php do_action('woocommerce_before_main_content'); ?>
               
              	<?php woocommerce_content();  ?>
              	
              	<?php do_action('woocommerce_after_main_content'); ?>
            
              </section> <!-- end article section -->

        
          </div> <!-- end #main -->
            
        </div> <!-- end #content -->

      </div> <!-- end .container -->

<?php get_footer();