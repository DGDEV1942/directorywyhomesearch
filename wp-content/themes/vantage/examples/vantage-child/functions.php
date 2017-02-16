<?php

// BEFORE USING: Move the vantage-child theme into the /themes/ folder.
//
// You can add you own actions, filters and code below.

function va_child_theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
}
add_action( 'wp_enqueue_scripts', 'va_child_theme_enqueue_styles' );