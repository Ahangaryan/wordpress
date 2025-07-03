<?php
/**
 * Plugin Name: JetEngine Video Coverflow Slider
 * Description: Elementor widget to show JetEngine repeater videos in a coverflow slider.
 * Version: 1.0
 * Author: Hamid Ahangaryan
 */

if (!defined('ABSPATH')) exit;

// Register Widget
add_action('elementor/widgets/register', function($widgets_manager) {
	require_once __DIR__ . '/widget-coverflow-video.php';
	$widgets_manager->register(new \JetEngine_Coverflow_Video());
});

// Enqueue Swiper assets
add_action('wp_enqueue_scripts', function() {
	wp_register_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
	wp_register_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], null, true);
});
