<?php
/**
 * Classic Blog functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package classic_blog
 */

if (!function_exists('classic_blog_setup')):
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function classic_blog_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Classic Blog, use a find and replace
         * to change 'classic-blog' to the name of your theme in all the template files.
         */
        load_theme_textdomain('classic-blog', get_template_directory().'/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');
        add_image_size('classic-blog-full-800-600', 800, 600, true);
        add_image_size('classic-blog-full-1200-900', 1200, 900, true);

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'mainnav' => esc_html__('Primary Menu', 'classic-blog'),
            'social' => __('Social Menu', 'classic-blog'),
            'footer' => __('Footer Menu', 'classic-blog'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('classic_blog_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support('custom-logo', array(
            'flex-width' => true,
            'flex-height' => true,
        ));

        // Set up the WordPress core custom header feature.
        add_theme_support('custom-header', apply_filters('classic_blog_custom_header_args', array(
            'width' => 1920,
            'height' => 1080,
            'flex-height' => true,
            'header-text' => false,
        )));
    }
endif;
add_action('after_setup_theme', 'classic_blog_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function classic_blog_content_width()
{
    $GLOBALS['content_width'] = apply_filters('classic_blog_content_width', 640);
}

add_action('after_setup_theme', 'classic_blog_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function classic_blog_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'classic-blog'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'classic-blog'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title font-1">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Menu Sidebar', 'classic-blog'),
        'id' => 'sidebar-menu',
        'description' => esc_html__('Add widgets here.', 'classic-blog'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="font-1">',
        'after_title' => '</h2>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Column 1', 'classic-blog'),
        'id' => 'footer-col-1',
        'description' => esc_html__('Displays items on footer section.', 'classic-blog'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="widget-title font-1">',
        'after_title' => '</h5>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Column 2', 'classic-blog'),
        'id' => 'footer-col-2',
        'description' => esc_html__('Displays items on footer section.', 'classic-blog'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="widget-title font-1">',
        'after_title' => '</h5>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Footer Column 3', 'classic-blog'),
        'id' => 'footer-col-3',
        'description' => esc_html__('Displays items on footer section.', 'classic-blog'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="widget-title font-1">',
        'after_title' => '</h5>',
    ));
}

add_action('widgets_init', 'classic_blog_widgets_init');

/**
 * Add Google Font
 */
if (!function_exists('classic_blog_fonts_url')):

    /**
     * Return fonts URL.
     *
     * @since 1.0.0
     * @return string Fonts URL.
     */
    function classic_blog_fonts_url()
    {

        $fonts_url = '';
        $fonts = array();
        $classic_blog_primary_font = classic_blog_get_option('primary_font');
        $classic_blog_secondary_font = classic_blog_get_option('secondary_font');

        $classic_blog_fonts = array();
        $classic_blog_fonts[] = $classic_blog_primary_font;
        $classic_blog_fonts[] = $classic_blog_secondary_font;

        $classic_blog_fonts_stylesheet = '//fonts.googleapis.com/css?family=';

        $i = 0;
        for ($i = 0; $i < count($classic_blog_fonts); $i++) {

            if ('off' !== sprintf(_x('on', '%s font: on or off', 'classic-blog'), $classic_blog_fonts[$i])) {
                $fonts[] = $classic_blog_fonts[$i];
            }

        }

        if ($fonts) {
            $fonts_url = add_query_arg(array(
                'family' => urldecode(implode('|', $fonts)),
            ), 'https://fonts.googleapis.com/css');
        }
        return $fonts_url;
    }
endif;

/**
 * Enqueue scripts and styles.
 */
function classic_blog_scripts()
{
    $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

    $fonts_url = classic_blog_fonts_url();
    if (!empty($fonts_url)) {
        wp_enqueue_style('classic-blog-google-fonts', $fonts_url, array(), null);
    }

    wp_enqueue_style('jquery-slick', get_template_directory_uri() . '/assets/slick/css/slick' . $min . '.css');
    wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/magnific-popup/magnific-popup.css');

    wp_enqueue_style('classic-blog-style', get_stylesheet_uri());

    wp_enqueue_script('classic-blog-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true);

    wp_enqueue_script('classic-blog-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true);

    wp_enqueue_script('jquery-slick', get_template_directory_uri() . '/assets/slick/js/slick' . $min . '.js', array('jquery'), '', true);
    wp_enqueue_script('jquery-magnific-popup', get_template_directory_uri() . '/assets/magnific-popup/jquery.magnific-popup' . $min . '.js', array('jquery'), '', true);
    wp_enqueue_script('match-height', get_template_directory_uri() . '/assets/jquery-match-height/js/jquery.matchHeight' . $min . '.js', array('jquery'), '', true);
    wp_enqueue_script('classic-blog-script', get_template_directory_uri() . '/js/script.js', array('jquery'), '', 1);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    wp_add_inline_style('classic-blog-style', classic_blog_custom_css());

}

add_action('wp_enqueue_scripts', 'classic_blog_scripts');

/**
 * Enqueue admin scripts and styles.
 */
function classic_blog_admin_scripts()
{
    wp_enqueue_style('admin-style', get_template_directory_uri() . '/assets/css/admin-style.css');
}

add_action('admin_enqueue_scripts', 'classic_blog_admin_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions for this theme.
 */
require get_template_directory() . '/inc/custom-functions.php';

/**
 * Custom hooks for this theme.
 */
require get_template_directory() . '/inc/custom-hooks.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * SVG icons functions and filters.
 */
require get_parent_theme_file_path('/inc/icon-functions.php');

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

/**
 * CSS related hooks.
 */

if (!function_exists('classic_blog_custom_css')) :

    /**
     * Do action theme custom CSS.
     *
     * @since 1.0.0
     */
    function classic_blog_custom_css()
    {
        global $classic_blog_google_fonts;

        $classic_blog_primary_color = classic_blog_get_option('primary_color');
        $classic_blog_secondary_color = classic_blog_get_option('secondary_color');
        $classic_blog_primary_font = $classic_blog_google_fonts[classic_blog_get_option('primary_font')];
        $classic_blog_secondary_font = $classic_blog_google_fonts[classic_blog_get_option('secondary_font')];

        $classic_blog_banner_overlay = classic_blog_get_option('enable_overlay_option');
        ?>
        <style type="text/css">
            <?php if ( $classic_blog_banner_overlay == 1 ){ ?>
                .overlay.overlay-enable:before {
                    content: "";
                }
            <?php } ?>

            <?php if (!empty ($classic_blog_primary_color)) { ?>
                .site a:hover, .site a:focus, .site .nav-previous a, .site .nav-next a {
                    color: <?php echo esc_html($classic_blog_primary_color) ?>;
                }
                .site .preloader,
                .site .site-info,
                .site button:hover,
                .site button:active, button:focus,
                .site input[type="button"]:hover,
                .site input[type="button"]:focus,
                .site input[type="button"]:active,
                .site input[type="reset"]:hover,
                .site input[type="reset"]:active,
                .site input[type="reset"]:focus,
                .site input[type="submit"]:hover,
                .site input[type="submit"]:active,
                .site input[type="submit"]:focus,
                .site .comment-form .submit:hover,
                .site .comment-form .submit:active,
                .site .comment-form .submit:focus {
                    background-color: <?php echo esc_html($classic_blog_primary_color) ?>;
                }
                .site .post-navigation a:hover,
                .site .post-navigation a:focus {
                    border-color: <?php echo esc_html($classic_blog_primary_color) ?>;
                }
            <?php } ?>

            <?php if (!empty ($classic_blog_secondary_color)) { ?>
                body .newsletter-block,
                body .newsletter-block a{
                    color: <?php echo esc_html($classic_blog_secondary_color) ?>;
                }
            <?php } ?>

            <?php if(!empty($classic_blog_primary_font)){ ?>
                body, button, input, select, optgroup, textarea, .font-1 {
                    font-family: <?php echo esc_html($classic_blog_primary_font); ?> !important;
                }
            <?php } ?>

            <?php if(!empty($classic_blog_secondary_font)){ ?>
                body h1, body h2, body h3, body h4, body h5, body h6, body .site .site-title {
                    font-family: <?php echo esc_html($classic_blog_secondary_font); ?> !important;
                }
            <?php } ?>
        </style>

    <?php }

endif;