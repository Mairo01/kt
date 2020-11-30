<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package classic_blog
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}
?>
<?php if (classic_blog_get_option('enable_preloader') == 1) { ?>
    <div class="preloader">
        <div class="typing_loader"></div>
    </div>
<?php } ?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'classic-blog'); ?></a>

<div class="navigation-trigger-wrapper">
    <a href="#fullscreen-navigation" class="navigation-trigger">
        <span class="navigation-icon"></span>
        <svg x="0px" y="0px" width="54px" height="54px" viewBox="0 0 54 54">
            <circle fill="transparent" stroke="#57478f" stroke-width="1" cx="27" cy="27" r="25" stroke-dasharray="157 157" stroke-dashoffset="157"></circle>
        </svg>
    </a>
    <span class="navigation-text"><?php esc_html_e('Menu', 'classic-blog'); ?></span>


    <div class="pull-right">
        <?php
        if ( has_nav_menu( 'social' ) ) : ?>
            <nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Social Links Menu', 'classic-blog' ); ?>">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'social',
                    'menu_class'     => 'social-links-menu',
                    'depth'          => 1,
                    'link_before'    => '<span class="screen-reader-text">',
                    'link_after'     => '</span>' . classic_blog_get_svg( array( 'icon' => 'chain' ) ),
                ) );
                ?>
            </nav>
        <?php endif; ?>
    </div>
</div>
<div id="fullscreen-navigation" class="fullscreen-navigation">
    <div class="navigation-wrapper">
        <div class="col col-five">
            <h2 class="font-1"><?php esc_html_e('Navigation', 'classic-blog'); ?></h2>
            <nav id="site-navigation" class="main-navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'mainnav',
                    'menu_id' => 'primary-menu',
                    'container' => 'div',
                    'container_class' => 'menu-container'
                ));
                ?>
            </nav>
        </div>

        <?php if ( is_active_sidebar( 'sidebar-menu' ) ) { ?>
            <div class="col col-five">
                <?php dynamic_sidebar( 'sidebar-menu' ); ?>                
            </div>
        <?php } ?>
    </div>
</div>

<?php $classic_blog_content_block = "content-block" ?>

<div id="page" class="site <?php if (classic_blog_get_option('enable_featured_page_section') == 1) {
    echo esc_attr($classic_blog_content_block);
} ?>">


    <?php $global_banner_image = get_header_image(); ?>
    <?php if (has_header_image()) {
        $classic_blog_header_img = "data-attrbg";
    } else {
        $classic_blog_header_img = "data-attr-blank";
    }

    ?>
    <header id="masthead" class="site-header <?php echo esc_attr($classic_blog_header_img); ?>"
            data-background="<?php echo esc_url($global_banner_image); ?>">
        <div class="site-branding">
            <div class="wrapper">
                <div class="text-center">
                    <div class="logo">
                        <?php
                        the_custom_logo();
                        if (is_front_page() && is_home()) : ?>
                            <h1 class="site-title font-2">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </h1>
                        <?php else : ?>
                            <p class="site-title font-2">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </p>
                        <?php
                        endif;

                        $description = get_bloginfo('description', 'display');
                        if ($description || is_customize_preview()) : ?>
                            <p class="site-description">
                                <span><?php echo esc_html($description); ?></span>
                            </p>
                        <?php
                        endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <?php if (is_front_page() || is_home()) {
        do_action('classic_blog_action_ticker');
    }
    ?>


    <?php
    if (is_front_page() || is_home()) {
        do_action('classic_blog_action_slider_post');
    }
    ?>

    <?php
    if (is_front_page() || is_home()) {
        do_action('classic_blog_action_featured_page');
    }
    ?>
    <div id="content" class="site-content">