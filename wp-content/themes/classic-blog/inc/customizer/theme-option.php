<?php

/**
 * Theme Options Panel.
 *
 * @package Classic Blog
 */

$default = classic_blog_get_default_theme_options();

/*slider and its property section*/
require get_template_directory().'/inc/customizer/top-ticker.php';
require get_template_directory().'/inc/customizer/slider.php';
require get_template_directory().'/inc/customizer/featured-page.php';
require get_template_directory().'/inc/customizer/featured-blog.php';

// Add Theme Options Panel.
$wp_customize->add_panel('theme_option_panel',
	array(
		'title'      => esc_html__('Theme Options', 'classic-blog'),
		'priority'   => 200,
		'capability' => 'edit_theme_options',
	)
);

/*layout management section start */
$wp_customize->add_section('theme_option_section_settings',
	array(
		'title'      => esc_html__('Layout Management', 'classic-blog'),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_option_panel',
	)
);

/*Home Page Layout*/
$wp_customize->add_setting('enable_overlay_option',
	array(
		'default'           => $default['enable_overlay_option'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'classic_blog_sanitize_checkbox',
	)
);
$wp_customize->add_control('enable_overlay_option',
	array(
		'label'    => esc_html__('Enable Banner Overlay', 'classic-blog'),
		'section'  => 'theme_option_section_settings',
		'type'     => 'checkbox',
		'priority' => 150,
	)
);

/*Global Layout*/
$wp_customize->add_setting('global_layout',
	array(
		'default'           => $default['global_layout'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'classic_blog_sanitize_select',
	)
);
$wp_customize->add_control('global_layout',
	array(
		'label'          => esc_html__('Sidebar Options', 'classic-blog'),
		'section'        => 'theme_option_section_settings',
		'choices'        => array(
			'left-sidebar'  => esc_html__('Left Sidebar', 'classic-blog'),
			'right-sidebar' => esc_html__('Right Sidebar', 'classic-blog'),
			'no-sidebar'    => esc_html__('No Sidebar', 'classic-blog'),
		),
		'type'     => 'select',
		'priority' => 170,
	)
);

// Setting - read_more_button_text.
$wp_customize->add_setting('read_more_button_text',
	array(
		'default'           => $default['read_more_button_text'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control('read_more_button_text',
	array(
		'label'    => esc_html__('Button Text for Read More', 'classic-blog'),
		'section'  => 'theme_option_section_settings',
		'type'     => 'text',
		'priority' => 170,
	)
);

/*content excerpt in global*/
$wp_customize->add_setting('excerpt_length_global',
	array(
		'default'           => $default['excerpt_length_global'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'classic_blog_sanitize_positive_integer',
	)
);
$wp_customize->add_control('excerpt_length_global',
	array(
		'label'       => esc_html__('Archive Excerpt Length', 'classic-blog'),
		'section'     => 'theme_option_section_settings',
		'type'        => 'number',
		'priority'    => 175,
		'input_attrs' => array('min' => 1, 'max' => 200, 'style' => 'width: 150px;'),

	)
);


// Pagination Section.
$wp_customize->add_section('pagination_section',
	array(
		'title'      => esc_html__('Pagination Options', 'classic-blog'),
		'priority'   => 110,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_option_panel',
	)
);

// Setting pagination_type.
$wp_customize->add_setting('pagination_type',
	array(
		'default'           => $default['pagination_type'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'classic_blog_sanitize_select',
	)
);
$wp_customize->add_control('pagination_type',
	array(
		'label'    => esc_html__('Pagination Type', 'classic-blog'),
		'section'  => 'pagination_section',
		'type'     => 'select',
		'choices'  => array(
			'numeric' => esc_html__('Numeric', 'classic-blog'),
			'default' => esc_html__('Default (Older / Newer Post)', 'classic-blog'),
		),
		'priority' => 100,
	)
);

// Preloader Section.
$wp_customize->add_section('preloader_option',
	array(
		'title'      => esc_html__('Preloader Options', 'classic-blog'),
		'priority'   => 130,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_option_panel',
	)
);

// Setting enable_preloader.
$wp_customize->add_setting('enable_preloader',
	array(
		'default'           => $default['enable_preloader'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'classic_blog_sanitize_checkbox',
	)
);
$wp_customize->add_control('enable_preloader',
	array(
		'label'    => esc_html__('Enable Preloader', 'classic-blog'),
		'section'  => 'preloader_option',
		'type'     => 'checkbox',
		'priority' => 120,
	)
);
// Footer Section.
$wp_customize->add_section('footer_section',
	array(
		'title'      => esc_html__('Footer Options', 'classic-blog'),
		'priority'   => 130,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_option_panel',
	)
);

// Setting copyright_text.
$wp_customize->add_setting('copyright_text',
	array(
		'default'           => $default['copyright_text'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control('copyright_text',
	array(
		'label'    => esc_html__('Footer Copyright Text', 'classic-blog'),
		'section'  => 'footer_section',
		'type'     => 'text',
		'priority' => 120,
	)
);

// Add Theme Options Panel.
$wp_customize->add_panel('theme_color_typo',
    array(
        'title' => esc_html__('General settings', 'classic-blog'),
        'priority' => 40,
        'capability' => 'edit_theme_options',
    )
);

// font Section.
$wp_customize->add_section('font_typo_section',
    array(
        'title' => esc_html__('Fonts & Typography', 'classic-blog'),
        'priority' => 100,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_color_typo',
    )
);

// font Section.
$wp_customize->add_section('colors',
    array(
        'title' => esc_html__('Color Options', 'classic-blog'),
        'priority' => 100,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_color_typo',
    )
);

// Setting - primary_color.
$wp_customize->add_setting('primary_color',
    array(
        'default' => $default['primary_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control('primary_color',
    array(
        'label' => esc_html__('Primary Background Color', 'classic-blog'),
        'section' => 'colors',
        'type' => 'color',
        'priority' => 100,
    )
);


// Setting - secondary_color.
$wp_customize->add_setting('secondary_color',
    array(
        'default' => $default['secondary_color'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control('secondary_color',
    array(
        'label' => esc_html__('Secondary Background Color', 'classic-blog'),
        'section' => 'colors',
        'type' => 'color',
        'priority' => 100,
    )
);

global $classic_blog_google_fonts;

// Setting - primary_font.
$wp_customize->add_setting('primary_font',
    array(
        'default' => $default['primary_font'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'classic_blog_sanitize_select',
    )
);
$wp_customize->add_control('primary_font',
    array(
        'label' => esc_html__('Primary Font', 'classic-blog'),
        'section' => 'font_typo_section',
        'type' => 'select',
        'choices' => $classic_blog_google_fonts,
        'priority' => 100,
    )
);

// Setting - secondary_font.
$wp_customize->add_setting('secondary_font',
    array(
        'default' => $default['secondary_font'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'classic_blog_sanitize_select',
    )
);
$wp_customize->add_control('secondary_font',
    array(
        'label' => esc_html__('Secondary Font', 'classic-blog'),
        'section' => 'font_typo_section',
        'type' => 'select',
        'choices' => $classic_blog_google_fonts,
        'priority' => 110,
    )
);