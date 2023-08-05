<?php
// Theme resource FIles
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
function enqueue_custom_scripts() {
  wp_enqueue_style('main-css', get_theme_file_uri('/assets/styles/bootstrap.css'));
  // wp_enqueue_style('main-css', get_theme_file_uri('/assets/styles/style.css'));
  // wp_enqueue_style('bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
  wp_enqueue_style('my-style', get_stylesheet_uri());

  wp_enqueue_script('fa-js', '//kit.fontawesome.com/61b7275f5f.js', 'NULL', '5.9.0', false);
  wp_enqueue_script('main-js', get_theme_file_uri('bundled.js'), 'NULL', '1.0', true);
  // wp_enqueue_script('custom-js', get_theme_file_uri('/assets/js/custom.js'), array('jquery'), '1.0', true);
}