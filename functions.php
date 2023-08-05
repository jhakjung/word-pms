<?php
// Theme resource FIles
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
function enqueue_custom_scripts() {
  wp_enqueue_style('main-css', get_theme_file_uri('/assets/styles/bootstrap.css'));
  wp_enqueue_style('my-style', get_stylesheet_uri());
  wp_enqueue_script('fa-js', '//kit.fontawesome.com/61b7275f5f.js', 'NULL', '5.9.0', false);
  wp_enqueue_script('main-js', get_theme_file_uri('bundled.js'), 'NULL', '1.0', true);
}