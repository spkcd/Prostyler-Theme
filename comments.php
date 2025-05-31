<?php
/*
The comments page for Bones
*/

// Do not delete these lines
  if ( ! empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
    die ('Please do not load this page directly. Thanks!');

  if ( post_password_required() ) { ?>
  <?php
    return;
  }
?>

<?php // You can start editing here. ?>

<?php if ( have_comments() ) : ?>

  <div id="comments" class="havecomments">

  <h3><?php comments_number( __( '<span>No</span> Responses', 'cbt' ), __( '<span>One</span> Response', 'cbt' ), _n( '<span>%</span> Response', '<span>%</span> Responses', get_comments_number(), 'cbt' ) );?> to &#8220;<?php the_title(); ?>&#8221;</h3>

  <ol class="commentlist">
    <?php wp_list_comments( 'type=comment&callback=bones_comments' ); ?>
  </ol>

  <?php else : // this is displayed if there are no comments so far ?>

  <?php if ( comments_open() ) : ?>
  <?php // If comments are open, but there are no comments. ?>
  <div id="comments" class="nocomments">
    <h3><?php _e( 'No Comments', 'cbt' ); ?></h3>
    <p><?php _e( 'Be the first to start a conversation', 'cbt' ); ?></p>
  <?php else : // comments are closed ?>
  <?php // If comments are closed. ?>
  <?php endif; ?>

<?php endif; ?>

<?php if ( comments_open() ) : ?>

  <?php if(page_has_comments_nav()) : ?>

    <nav class="comment-nav clearfix">
      <div class="comment-prev">
        <?php previous_comments_link(__( '<i class="fa fa-chevron-left"></i>', 'bones' ) . '  Previous Comments') ?>
      </div>
      <div class="comment-next">
        <?php next_comments_link(__( 'Next Comments  ', 'cbt' ) . '<i class="fa fa-chevron-right"></i>') ?>
      </div>
    </nav>
  <?php endif; ?>

</div> <!-- END #COMMENTS --> 

<div class="contrast-respond-form">

   <?php
  /*
   * Adding bootstrap support to comment form,
   * and some form validation using javascript.
   */
  
  //ob_start();
  comment_form();
  //echo str_replace('class="comment-form"','class="comment-form" name="commentForm" onsubmit="return validateForm();"',ob_get_clean());
  ?>
  
    <script>
      /* basic javascript form validation */
      function validateForm() {
      var form  =  document.forms["commentForm"], 
        x     = form["author"].value,
        y     = form["email"].value,
        z     = form["comment"].value,
        flag  = true,
        d1    = document.getElementById("d1"),
        d2    = document.getElementById("d2"),
        d3    = document.getElementById("d3");

        console.log(x, y, z); 
        
      if (x == null || x == "") {
        d1.innerHTML = "<?php echo __('Name is required', 'cbt'); ?>";
        z = false;
      } else {
        d1.innerHTML = "";
      }
      
      if (y == null || y == "") {
        d2.innerHTML = "<?php echo __('Email is required', 'cbt'); ?>";
        z = false;
      } else {
        d2.innerHTML = "";
      }
      
      if (z == null || z == "") {
        d3.innerHTML = "<?php echo __('Comment is required', 'cbt'); ?>";
        z = false;
      } else {
        d3.innerHTML = "";
      }
      console.log('herr');
      return false;
      if (z == false) {
        return false;
      }
      
    }
  </script>

</div> <!-- END .contrast-respond-form -->


<?php else : ?>

  <?php if ( have_comments() ) : ?>

  </div> <!-- END #COMMENTS (have comments, comments now closed) -->

  <div class="closed">
    <h3><?php _e( 'comments are closed', 'cbt' ); ?></h3>
  </div>

  <?php endif; ?>


<?php endif; // if you delete this the sky will fall on your head ?>

<?php $comments_by_type = &separate_comments($comments); ?>
  <?php if ( ! empty( $comments_by_type['pings'] ) ) { ?>
  <div id="pings">
    <h3>
      <?php _e( 'Trackbacks and Pingbacks:', 'cbt' ); ?>
    </h3>
    <ol class="pinglist">
      <?php wp_list_comments( 'type=pings&callback=list_pings' ); ?>
    </ol>
  </div><!-- /#pings -->
  <?php } // end if ?>