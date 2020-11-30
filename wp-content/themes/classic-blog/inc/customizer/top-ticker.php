<?php
/**
 * ticker section
 *
 * @package classic_blog
 */

$default = classic_blog_get_default_theme_options();

// Ticker Main Section.
$wp_customize->add_section('ticker_section_settings',
	array(
		'title'      => esc_html__('Ticker Options', 'classic-blog'),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_option_panel',
	)
);

// Setting - show_ticker_section.
$wp_customize->add_setting('show_ticker_section',
	array(
		'default'           => $default['show_ticker_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'classic_blog_sanitize_checkbox',
	)
);
$wp_customize->add_control('show_ticker_section',
	array(
		'label'    => esc_html__('Enable Ticker', 'classic-blog'),
		'section'  => 'ticker_section_settings',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

/*content excerpt in home ticker*/
$wp_customize->add_setting('home_top_ticker',
	array(
		'default'           => $default['home_top_ticker'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'classic_blog_sanitize_positive_integer',
	)
);
$wp_customize->add_control('home_top_ticker',
	array(
		'label'       => esc_html__('Select no Ticker', 'classic-blog'),
		'section'     => 'ticker_section_settings',
		'type'        => 'number',
		'priority'    => 110,
		'input_attrs' => array('min' => 0, 'max' => 10, 'style' => 'width: 150px;'),

	)
);


// Setting - drop down category for ticker.
$wp_customize->add_setting('select_category_for_ticker',
	array(
		'default'           => $default['select_category_for_ticker'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control(new Classic_Blog_Dropdown_Taxonomies_Control($wp_customize, 'select_category_for_ticker',
		array(
			'label'           => esc_html__('Category for Ticker', 'classic-blog'),
			'section'         => 'ticker_section_settings',
			'type'            => 'dropdown-taxonomies',
			'taxonomy'        => 'category',
			'priority'        => 130,

		)));

