<?php
// Restore original error reporting now that critical issues are fixed
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
//@ini_set('xdebug.max_nesting_level', 250);



/************* INCLUDE NEEDED FILES ***************/
require_once( dirname( __FILE__ ) . '/library/defines.php' );

// Theme page builder
require_once( ST_LIBRARY_PATH . '/builder/stencilor-builder.php' );

require_once( ST_LIBRARY_PATH . '/navwalker.php' ); // needed for bootstrap navigation


require_once( ST_LIBRARY_PATH . '/cbt_framework/init.php' ); // needed for bootstrap navigation


// REDUX Framework
require_once( ST_LIBRARY_PATH . '/admin/ReduxCore/framework.php' );

// Theme options panel configuration
require_once( ST_LIBRARY_PATH . '/option-config.php' );


// Custom metaboxes and fields
// https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
require_once( ST_LIBRARY_PATH . '/metabox/init.php' );

/* library/brew.php (functions specific to BREW)
  - navwalker
  - Redux framework
  - Read more > Bootstrap button
  - Bootstrap style pagination
  - Bootstrap style breadcrumbs
*/
require_once( ST_LIBRARY_PATH . '/brew.php' ); // if you remove this, BREW will break
/*
1. library/bones.php
	- head cleanup (remove rsd, uri links, junk css, ect)
	- enqueueing scripts & styles
	- theme support functions
	- custom menu output & fallbacks
	- related post function
	- page-navi function
	- removing <p> from around images
	- customizing the post excerpt
	- custom google+ integration
	- adding custom fields to user profiles
*/
require_once( ST_LIBRARY_PATH . '/bones.php' ); // if you remove this, bones will break

/************* INCLUDE TESTS LIB IN DEV MODE ***************/
if(file_exists( dirname( __FILE__ ) . '/TEMP_TESTS/MAIN.php' ))
	include_once( dirname( __FILE__ ) . '/TEMP_TESTS/MAIN.php' );

function cbt_jquery_init() 
{
	wp_enqueue_script( 'jquery' );
}
add_action( 'init', 'cbt_jquery_init', 9999 );


/*
4. library/translation/translation.php
	- adding support for other languages
*/
// require_once( 'library/translation/translation.php' ); // this comes turned off by default

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
//add_image_size( 'bones-thumb-600', 600, 150, true );
//add_image_size( 'bones-thumb-300', 300, 100, true );
add_image_size( 'post-featured', 750, 300, true );
/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Default Sidebar', 'cbt' ),
		'description' => __( 'The first (primary) sidebar.', 'cbt' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	
	
	register_sidebar(array(
		'id' => 'extra-widgetarea-1',
		'name' => __( 'Extra Widget Area 1', 'cbt' ),
		'description' => __( 'To be used in pages with "Widgetised Sidebar" element.', 'cbt' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	
	register_sidebar(array(
		'id' => 'extra-widgetarea-2',
		'name' => __( 'Extra Widget Area 2', 'cbt' ),
		'description' => __( 'To be used in pages with "Widgetised Sidebar" element.', 'cbt' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));


// add footer widgets

  register_sidebar(array(
    'id' => 'footer-1',
    'name' => __( 'Footer Widget 1', 'cbt' ),
    'description' => __( '1st column of footer.', 'cbt' ),
    'before_widget' => '<div id="%1$s" class="widget widgetFooter %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widgettitle">',
    'after_title' => '</h4>',
  ));

  register_sidebar(array(
    'id' => 'footer-2',
    'name' => __( 'Footer Widget 2', 'cbt' ),
    'description' => __( '2nd column of footer.', 'cbt' ),
    'before_widget' => '<div id="%1$s" class="widget widgetFooter %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widgettitle">',
    'after_title' => '</h4>',
  ));


	global $cbt_options; 
	$footerColumns = (!empty($cbt_options['footer_columns'])) ? $cbt_options['footer_columns'] : '2';
	
	if($footerColumns == '3' || $footerColumns == '4')
	{
		register_sidebar(array(
		'id' => 'footer-3',
		'name' => __( 'Footer Widget 3', 'cbt' ),
		'description' => __( '3rd column of footer.', 'cbt' ),
		'before_widget' => '<div id="%1$s" class="widget widgetFooter %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
		));
	}
	if($footerColumns == '4'){
		register_sidebar(array(
		'id' => 'footer-4',
		'name' => __( 'Footer Widget 4', 'cbt' ),
		'description' => __( '4th column of footer.', 'cbt' ),
		'before_widget' => '<div id="%1$s" class="widget widgetFooter %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
		));
	}
	
	global $cbt_options;
	$sidebars = isset($cbt_options['sidebars']) ? $cbt_options['sidebars'] : array();
	if(! $sidebars) return;
	
	foreach($sidebars as $sidebar)
	{
		// skips blanks
		if(! $sidebar OR ! trim($sidebar)) continue;
		
		register_sidebar(array(
		'id' => sanitize_title_with_dashes($sidebar),
		'name' => $sidebar,
		'description' => 'Custom sidebar',
		'before_widget' => '<div id="%1$s" class="widget widget-sidebar %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
		));
	}

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'cbt' ),
		'description' => __( 'The second (secondary) sidebar.', 'cbt' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!





/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix comment-container">
			<div class="comment-author vcard">
				<?php
				/*
					this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
					echo get_avatar($comment,$size='32',$default='<path_to_url>' );
				*/
				?>
				<?php // custom gravatar call ?>
				<?php
					// create variable
					$bgauthemail = get_comment_author_email();
				?>
				<img data-gravatar="https://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=64" class="load-gravatar avatar avatar-48 photo" height="64" width="64" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
				<?php // end custom gravatar call ?>
			</div>
      <div class="comment-content">
        <?php printf(__( '<cite class="fn">%s</cite>', 'cbt' ), get_comment_author_link()) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'cbt' )); ?> </a></time>
        <?php edit_comment_link(__( '(Edit)', 'cbt' ),'  ','') ?>
  			<?php if ($comment->comment_approved == '0') : ?>
  				<div class="alert alert-info">
  					<p><?php _e( 'Your comment is awaiting moderation.', 'cbt' ) ?></p>
  				</div>
  			<?php endif; ?>
  			<section class="comment_content clearfix">
  				<?php comment_text() ?>
  			</section>
  			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div> <!-- END comment-content -->
		</article>
	<?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!

/*************** PINGS LAYOUT **************/

function list_pings( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	<li id="comment-<?php comment_ID(); ?>">
		<span class="pingcontent">
			<?php printf(__('<cite class="fn">%s</cite> <span class="says"></span>'), get_comment_author_link()) ?>
			<?php comment_text(); ?>
		</span>
	</li>
<?php } // end list_pings



/**
 * Tell WordPress to run cbt_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'cbt_setup' );

if ( ! function_exists( 'cbt_setup' ) )
{
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function cbt_setup() 
	{
	
		/* Make available for translation.
		 * Translations can be added to the /languages/ directory.
		 */
		load_theme_textdomain( 'cbt', get_template_directory() . '/languages' );
	}
}


// ------------------------------------------------------
//  theme update api
// ------------------------------------------------------
require 'wp-updates-theme.php';
$key = trim(get_option('cbt_license_key'));
$example_update_checker = new ThemeUpdateChecker_pst(
	basename( get_template_directory() ),                                            //Theme folder name, AKA "slug". 
	'http://members.prostylertheme.com/update_api/theme-rev-updater.php?key='.$key //URL of the metadata file.
);