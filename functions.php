<?php
/**
 * Functions for the kc-bt-temp theme
 * 
 * @package kc-bt-temp
 * @author  karo_ej
 * @license GNU General Public License v2 or later
 *
 * Originally based on Starter Block Theme v4 by Jakson (https://jakson.co/)
 */

/* Sets up theme defaults */
function kc_bt_temp_setup() {

		// Enqueue editor styles.
		add_editor_style(
			array(
				'./style.css',
				// Disable this if you don't want to include the CSS Framework in the editor - see my tutorial here: https://jakson.co/css-framework-tutorial
				'/assets/css/css-framework.css',
				// Disable this if you don't want to include the Font Icons CSS in the editor - see my tutorial here: https://jakson.co/button-font-icons-tutorial
				'/assets/css/icon-fonts.css'
			)
		);
 
		// Remove core block patterns.
		remove_theme_support( 'core-block-patterns' );

	}

add_action( 'after_setup_theme', 'kc_bt_temp_setup' );


// Loads styles and scripts
function kc_bt_temp_enqueue_scripts() {
    
	wp_enqueue_style( 'kc-bt-temp-theme-css', get_stylesheet_uri(), [], filemtime(get_stylesheet_directory() . '/style.css') );

	// Disable this if you want don't want to include the CSS Framework
    wp_enqueue_style( 'css-framework', get_stylesheet_directory_uri() . '/assets/css/css-framework.css', [], filemtime(get_stylesheet_directory() . '/assets/css/css-framework.css') );
 	// Disable this if you want don't want to include the Font Icons CSS 
	wp_enqueue_style('icons-css',get_stylesheet_directory_uri() . '/assets/css/icon-fonts.css',[],wp_get_theme()->get( 'Version' ));
    
    wp_enqueue_script('kc-bt-temp-js', get_stylesheet_directory_uri() . '/assets/js/js.js', array('jquery'), '1.0', true);
}
add_action( 'wp_enqueue_scripts', 'kc_bt_temp_enqueue_scripts' );


// Include CSS Class Manager Info to remind folk to install that plugin
require_once get_template_directory() . '/inc/css-class-manager-info.php';


// This registers the classes that appear the dropdown for the https://wordpress.org/plugins/css-class-manager/ - Tutorial: https://jakson.co/css-framework-tutorial
// IMPORTANT: in the CSS Class manager plugin preferences, the "Hide theme.json generated classes" option must be toggled off for these classes to show.
// Disable this if you want don't want to include the CSS Framework
function kc_bt_temp_add_custom_css( $css ) {

	$css_framework = file_get_contents( __DIR__ . '/assets/css/css-framework.css' );
    $css_icons = file_get_contents( __DIR__ . '/assets/css/icon-fonts.css' );

	return $css . $css_framework . $css_icons;
}
add_filter( 'css_class_manager_theme_classes_css', 'kc_bt_temp_add_custom_css' );


// Register Block Styles Examples
function kc_bt_temp_register_block_styles() {

    if ( ! function_exists( 'register_block_style' ) ) {
        return;
    }

    // Blue Button - example of a custom colored button with hover state
    register_block_style(
        'core/button',
        array(
            'name'         => 'blue-btn',
            'label'        => __( 'Blue', 'kc-bt-temp' ),
            'is_default'   => false,
        )
    );

        wp_enqueue_block_style(
            'core/button',
            array(
                'handle' => "kc-bt-temp-blue-btn",
                'src'    => get_theme_file_uri( "assets/css/block-styles/blue-button.css" ),
                'path'   => get_theme_file_path( "assets/css/block-styles/blue-button.css" ),
            )
        );

    // Text Only Button
    register_block_style(
        'core/button',
        array(
            'name'         => 'text-btn',
            'label'        => __( 'Text', 'kc-bt-temp' ),
            'is_default'   => false,
        )
    );

        wp_enqueue_block_style(
            'core/button',
            array(
                'handle' => "kc-bt-temp-text-btn",
                'src'    => get_theme_file_uri( "assets/css/block-styles/text-button.css" ),
                'path'   => get_theme_file_path( "assets/css/block-styles/text-button.css" ),
            )
        );

    // Group Style - removes the default top margin
    register_block_style(
        'core/group',
        array(
            'name'         => 'starter-group',
            'label'        => __( 'margin-top-0', 'kc-bt-temp' ),
            'is_default'   => false,
            'inline_style' => '
            .is-style-starter-group { margin-block-start: 0 !important; }
            ',
        ) 
    );

    // Check Mark List - simple unicode list style using "inline_style" as is very simple CSS
    register_block_style(
        'core/list',
        array(
            'name'         => 'checkmark-list',
            'label'        => __( 'Checkmark', 'kc-bt-temp' ),
            'inline_style' => '
            ul.is-style-checkmark-list {
                list-style-type: "\2713";
            }

            ul.is-style-checkmark-list li {
                padding-inline-start: 10px;
                margin-left: -9px;
            }',
        )
    );


    // Arrow Right List - simple unicode list style
    register_block_style(
        'core/list',
        array(
            'name'         => 'arrow-list',
            'label'        => __( 'Arrow Right', 'kc-bt-temp' ),
            'inline_style' => '
            ul.is-style-arrow-list {
                list-style-type: "\2192";
            }

            ul.is-style-arrow-list li {
                padding-inline-start: 10px;
                margin-left: -9px;
            }',
        )
    );

}
add_action( 'init', 'kc_bt_temp_register_block_styles' );


// Example of Register block pattern categories 
function kc_bt_temp_register_block_pattern_categories() {
    
    register_block_pattern_category(
            'kc-bt-temp/pages',
            array(
                'label'       => __( 'Pages', 'kc-bt-temp' ),
            )
        );
	register_block_pattern_category(
		'kc-bt-temp/hero',
		array(
			'label'       => __( 'Hero', 'kc-bt-temp' ),
		)
	);
    
}
add_action( 'init', 'kc_bt_temp_register_block_pattern_categories' );