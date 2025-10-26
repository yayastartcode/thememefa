<?php
/**
 * beritanih functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package beritanih
 */

if ( ! defined( 'BERITANIH_VERSION' ) ) {
	/*
	 * Set the theme’s version number.
	 *
	 * This is used primarily for cache busting. If you use `npm run bundle`
	 * to create your production build, the value below will be replaced in the
	 * generated zip file with a timestamp, converted to base 36.
	 */
	define( 'BERITANIH_VERSION', '0.1.0' );
}

if ( ! defined( 'BERITANIH_TYPOGRAPHY_CLASSES' ) ) {
	/*
	 * Set Tailwind Typography classes for the front end, block editor and
	 * classic editor using the constant below.
	 *
	 * For the front end, these classes are added by the `beritanih_content_class`
	 * function. You will see that function used everywhere an `entry-content`
	 * or `page-content` class has been added to a wrapper element.
	 *
	 * For the block editor, these classes are converted to a JavaScript array
	 * and then used by the `./javascript/block-editor.js` file, which adds
	 * them to the appropriate elements in the block editor (and adds them
	 * again when they’re removed.)
	 *
	 * For the classic editor (and anything using TinyMCE, like Advanced Custom
	 * Fields), these classes are added to TinyMCE’s body class when it
	 * initializes.
	 */
	define(
		'BERITANIH_TYPOGRAPHY_CLASSES',
		'prose prose-neutral max-w-none prose-a:text-primary'
	);
}

if ( ! function_exists( 'beritanih_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function beritanih_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on beritanih, use a find and replace
		 * to change 'beritanih' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'beritanih', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'menu-1' => __( 'Primary', 'beritanih' ),
				'menu-2' => __( 'Footer Menu', 'beritanih' ),
				'menu-pages' => __( 'Pages Menu', 'beritanih' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );
		add_editor_style( 'style-editor-extra.css' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Remove support for block templates.
		remove_theme_support( 'block-templates' );
	}
endif;
add_action( 'after_setup_theme', 'beritanih_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function beritanih_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'beritanih' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'beritanih' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s bg-white rounded-xl shadow-lg p-6 mb-6">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="flex items-center gap-2 mb-6 pb-3 border-b-2 border-blue-600"><svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><h3 class="text-xl font-bold text-gray-900 section-title-border">',
			'after_title'   => '</h3></div>',
		)
	);
}
add_action( 'widgets_init', 'beritanih_widgets_init' );

/**
 * Register custom widgets.
 */
function beritanih_register_widgets() {
	// Include widget files
	require_once get_template_directory() . '/inc/widgets/class-popular-posts-widget.php';
	require_once get_template_directory() . '/inc/widgets/class-categories-widget.php';
	require_once get_template_directory() . '/inc/widgets/class-advertisement-widget.php';

	// Register widgets
	register_widget( 'Beritanih_Popular_Posts_Widget' );
	register_widget( 'Beritanih_Categories_Widget' );
	register_widget( 'Beritanih_Advertisement_Widget' );
}
add_action( 'widgets_init', 'beritanih_register_widgets' );

/**
 * Enqueue scripts and styles.
 */
function beritanih_scripts() {
	// Google Fonts: Inter
	wp_enqueue_style( 'beritanih-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null );

	wp_enqueue_style( 'beritanih-style', get_stylesheet_uri(), array(), BERITANIH_VERSION );
	// Custom utilities loaded after Tailwind output to prevent being overwritten by watch builds
	wp_enqueue_style( 'beritanih-custom-utilities', get_template_directory_uri() . '/css/custom-utilities.css', array( 'beritanih-style' ), BERITANIH_VERSION );
	
	// Enqueue category slider styles only on front page
	if ( is_front_page() ) {
		wp_enqueue_style( 'beritanih-category-slider', get_template_directory_uri() . '/css/category-slider.css', array( 'beritanih-style' ), BERITANIH_VERSION );
	}
	wp_enqueue_script( 'beritanih-script', get_template_directory_uri() . '/js/script.min.js', array(), BERITANIH_VERSION, true );
	
	// Enqueue sliders script only on front page
	if ( is_front_page() ) {
		wp_enqueue_script( 'beritanih-sliders', get_template_directory_uri() . '/js/sliders.min.js', array(), BERITANIH_VERSION, true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'beritanih_scripts' );

/**
 * Enqueue the block editor script.
 */
function beritanih_enqueue_block_editor_script() {
	$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if (
		$current_screen &&
		$current_screen->is_block_editor() &&
		'widgets' !== $current_screen->id
	) {
		wp_enqueue_script(
			'beritanih-editor',
			get_template_directory_uri() . '/js/block-editor.min.js',
			array(
				'wp-blocks',
				'wp-edit-post',
			),
			BERITANIH_VERSION,
			true
		);
		wp_add_inline_script( 'beritanih-editor', "tailwindTypographyClasses = '" . esc_attr( BERITANIH_TYPOGRAPHY_CLASSES ) . "'.split(' ');", 'before' );
	}
}
add_action( 'enqueue_block_assets', 'beritanih_enqueue_block_editor_script' );

/**
 * Add the Tailwind Typography classes to TinyMCE.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function beritanih_tinymce_add_class( $settings ) {
	$settings['body_class'] = BERITANIH_TYPOGRAPHY_CLASSES;
	return $settings;
}
add_filter( 'tiny_mce_before_init', 'beritanih_tinymce_add_class' );

/**
 * Limit the block editor to heading levels supported by Tailwind Typography.
 *
 * @param array  $args Array of arguments for registering a block type.
 * @param string $block_type Block type name including namespace.
 * @return array
 */
function beritanih_modify_heading_levels( $args, $block_type ) {
	if ( 'core/heading' !== $block_type ) {
		return $args;
	}

	// Remove <h1>, <h5> and <h6>.
	$args['attributes']['levelOptions']['default'] = array( 2, 3, 4 );

	return $args;
}
add_filter( 'register_block_type_args', 'beritanih_modify_heading_levels', 10, 2 );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Get random post tags for header display
 *
 * @param int $limit Number of tags to retrieve (default: 15)
 * @return array Array of tag objects
 */
function beritanih_get_random_tags( $limit = 15 ) {
	$tags = get_tags( array(
		'orderby' => 'count',
		'order'   => 'DESC',
		'number'  => 50, // Get more tags to randomize from
	) );

	if ( empty( $tags ) ) {
		return array();
	}

	// Shuffle the tags to randomize them
	shuffle( $tags );

	// Return only the requested number of tags
	return array_slice( $tags, 0, $limit );
}

/**
 * Customizer additions.
 */
function beritanih_customize_register( $wp_customize ) {
	// Add Category Slider Section
	$wp_customize->add_section( 'beritanih_category_slider', array(
		'title'    => __( 'Category Slider', 'beritanih' ),
		'priority' => 30,
	) );

	// Category Slider Enable/Disable
	$wp_customize->add_setting( 'beritanih_category_slider_enable', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );

	$wp_customize->add_control( 'beritanih_category_slider_enable', array(
		'label'   => __( 'Enable Category Slider', 'beritanih' ),
		'section' => 'beritanih_category_slider',
		'type'    => 'checkbox',
	) );

	// Category Selection
	$wp_customize->add_setting( 'beritanih_category_slider_category', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$categories = get_categories( array( 'hide_empty' => false ) );
	$category_choices = array( '' => __( 'Select Category', 'beritanih' ) );
	
	foreach ( $categories as $category ) {
		$category_choices[ $category->term_id ] = $category->name;
	}

	$wp_customize->add_control( 'beritanih_category_slider_category', array(
		'label'   => __( 'Select Category', 'beritanih' ),
		'section' => 'beritanih_category_slider',
		'type'    => 'select',
		'choices' => $category_choices,
	) );

	// Section Title
	$wp_customize->add_setting( 'beritanih_category_slider_title', array(
		'default'           => 'SPORT',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'beritanih_category_slider_title', array(
		'label'   => __( 'Section Title', 'beritanih' ),
		'section' => 'beritanih_category_slider',
		'type'    => 'text',
	) );

	// Second Category Slider Enable/Disable
	$wp_customize->add_setting( 'beritanih_category_slider_2_enable', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );

	$wp_customize->add_control( 'beritanih_category_slider_2_enable', array(
		'label'   => __( 'Enable Second Category Slider', 'beritanih' ),
		'section' => 'beritanih_category_slider',
		'type'    => 'checkbox',
	) );

	// Second Category Selection
	$wp_customize->add_setting( 'beritanih_category_slider_2_category', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'beritanih_category_slider_2_category', array(
		'label'   => __( 'Select Second Category', 'beritanih' ),
		'section' => 'beritanih_category_slider',
		'type'    => 'select',
		'choices' => $category_choices,
	) );

	// Second Section Title
	$wp_customize->add_setting( 'beritanih_category_slider_2_title', array(
		'default'           => 'TECHNOLOGY',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'beritanih_category_slider_2_title', array(
		'label'   => __( 'Second Section Title', 'beritanih' ),
		'section' => 'beritanih_category_slider',
		'type'    => 'text',
	) );

	// Third Category Slider Enable/Disable
	$wp_customize->add_setting( 'beritanih_category_slider_3_enable', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );

	$wp_customize->add_control( 'beritanih_category_slider_3_enable', array(
		'label'   => __( 'Enable Third Category Slider', 'beritanih' ),
		'section' => 'beritanih_category_slider',
		'type'    => 'checkbox',
	) );

	// Third Category Selection
	$wp_customize->add_setting( 'beritanih_category_slider_3_category', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'beritanih_category_slider_3_category', array(
		'label'   => __( 'Select Third Category', 'beritanih' ),
		'section' => 'beritanih_category_slider',
		'type'    => 'select',
		'choices' => $category_choices,
	) );

	// Third Section Title
	$wp_customize->add_setting( 'beritanih_category_slider_3_title', array(
		'default'           => 'LIFESTYLE',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'beritanih_category_slider_3_title', array(
		'label'   => __( 'Third Section Title', 'beritanih' ),
		'section' => 'beritanih_category_slider',
		'type'    => 'text',
	) );
}
add_action( 'customize_register', 'beritanih_customize_register' );

/**
 * Fix pagination for front page
 */
function beritanih_fix_front_page_pagination() {
	global $wp_rewrite;
	
	// Add pagination rewrite rule for front page
	add_rewrite_rule(
		'^page/([0-9]+)/?$',
		'index.php?paged=$matches[1]',
		'top'
	);
}
add_action( 'init', 'beritanih_fix_front_page_pagination' );

/**
 * Redirect old pagination URLs to pretty URLs and handle page/1 redirect
 */
function beritanih_redirect_pagination() {
	if ( is_home() && ! is_front_page() ) {
		return; // Don't redirect on blog page
	}
	
	// Redirect /page/1/ to home URL
	if ( is_front_page() ) {
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
		
		if ( $paged == 1 ) {
			// Check if URL contains /page/1/
			$request_uri = $_SERVER['REQUEST_URI'];
			if ( strpos( $request_uri, '/page/1/' ) !== false || strpos( $request_uri, '/page/1' ) !== false ) {
				wp_redirect( home_url( '/' ), 301 );
				exit;
			}
		}
	}
	
	// Redirect old ?paged=X format to /page/X/
	if ( is_front_page() && isset( $_GET['paged'] ) ) {
		$paged = intval( $_GET['paged'] );
		
		if ( $paged == 1 ) {
			// Redirect ?paged=1 to home
			wp_redirect( home_url( '/' ), 301 );
			exit;
		} elseif ( $paged > 1 ) {
			// Redirect ?paged=X to /page/X/
			$redirect_url = home_url( "/page/{$paged}/" );
			wp_redirect( $redirect_url, 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'beritanih_redirect_pagination' );

/**
 * Custom pagination function that handles page/1 properly
 */
function beritanih_paginate_links( $args = array() ) {
	$defaults = array(
		'base' => '%_%',
		'format' => '/page/%#%/',
		'total' => 1,
		'current' => 0,
		'show_all' => false,
		'prev_next' => true,
		'prev_text' => '« Sebelumnya',
		'next_text' => 'Selanjutnya »',
		'end_size' => 1,
		'mid_size' => 2,
		'type' => 'list',
		'add_args' => false,
		'add_fragment' => '',
		'before_page_number' => '',
		'after_page_number' => ''
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Handle the base URL for page 1
	if ( is_front_page() ) {
		$big = 999999999;
		$base_url = str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );
		
		// Remove /page/1/ from base URL and replace with home URL for page 1
		$args['base'] = $base_url;
		
		// Custom format function to handle page 1
		add_filter( 'paginate_links', 'beritanih_fix_page_one_link' );
	}
	
	return paginate_links( $args );
}

/**
 * Fix page 1 link to point to home URL instead of /page/1/
 */
function beritanih_fix_page_one_link( $link ) {
	// Replace /page/1/ with home URL
	$home_url = trailingslashit( home_url() );
	$link = str_replace( $home_url . 'page/1/', $home_url, $link );
	
	return $link;
}
