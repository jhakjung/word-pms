<?php

function pms_files() {
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('main_styles', get_theme_file_uri('/css/style.css'));
  wp_enqueue_style('extra_styles', get_theme_file_uri('style.css'));
}

add_action('wp_enqueue_scripts', 'pms_files');