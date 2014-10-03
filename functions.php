<?php
/**
 * aricore functions and definitions
 *
 * @package aricore
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'aricore_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function aricore_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on aricore, use a find and replace
	 * to change 'aricore' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'aricore', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'aricore' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'aricore_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // aricore_setup
add_action( 'after_setup_theme', 'aricore_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function aricore_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'aricore' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'aricore_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function aricore_scripts() {
	wp_enqueue_style( 'aricore-style', get_stylesheet_uri() );

	wp_enqueue_script( 'aricore-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'aricore-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'aricore_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/* 
 * ------------------------------------------------------------- *
 * ARI Functions
 * ------------------------------------------------------------- *
 */

/* 
 * ------------------------------------------------------------- *
 * Hover Intent
 * ------------------------------------------------------------- *
 */
// make a little function to enqueue hoverIntent:
function enq_hoverIntent() { wp_enqueue_script('hoverIntent'); }
// now make WP run it:
add_action('wp_enqueue_scripts', 'enq_hoverIntent');

// enclose some jQuery script in a php function:
function init_hoverIntent() { ?>
<script type='text/javascript'>
  jQuery(document).ready(function(){
	jQuery('nav li').hoverIntent({
	  over : navover,
	  out : navout,
	  timeout : 100
	});
	// (how to use both fade and slide effects!):
	function navover(){
	  jQuery(this).children('ul')
		.stop(true,true)
		.fadeIn({duration:600,queue:false})
		.css('display','none')
		.slideDown(600);
	}
	function navout(){
	  jQuery(this).children('ul')
		.stop(true,true)
		.fadeOut({duration:300,queue:false})
		.slideUp(300);
	}
  });
</script>
<?php }
// now make WP load that into the head section of the html page:
add_action('wp_head', 'init_hoverIntent');
/* 
 * ------------------------------------------------------------- *
 * END Hover Intent
 * ------------------------------------------------------------- *
 */

// Load Shotcodes in Text Widgets
add_filter( 'widget_text', 'do_shortcode' );

// Excerpt Length
function custom_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// Excerpt String
function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

// Next & Previous Post Link Classes
add_filter('next_posts_link_attributes', 'posts_link_attributes_1');
add_filter('previous_posts_link_attributes', 'posts_link_attributes_2');

function posts_link_attributes_1() {
	return 'class="next-post-link"';
}
function posts_link_attributes_2() {
	return 'class="next-post-link"';
}

// Post List Featured Image
add_filter('manage_posts_columns', 'featured_image_add_column');
add_filter('manage_pages_columns', 'featured_image_add_column');

function featured_image_add_column($columns) 
{
	$columns['featured_image'] = 'Image';
	return $columns;
}

add_action('manage_posts_custom_column', 'featured_image_render_post_columns', 10, 2);

function featured_image_render_post_columns($column_name, $id) 
{
	if($column_name == 'featured_image')
	{
		$thumb = get_the_post_thumbnail( $id, array(50,50) );
		if($thumb) { echo $thumb; }
	}
}
