<?php
/*
Plugin Name: RSS Feed Podcast by Categories
Plugin URI: https://moduloinfo.ca/wordpress/
Description: This plugin allow you to display a rss file and sort it by category using accordions
Author: Carl Sansfacon
Version: 1.1
Author URI: https://moduloinfo.ca/
*/
$csrssreadershortcodename = "rssreader";



function carlsansrssreader($atts = [], $content = null, $tag = '') {
	if(!is_admin()){
  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);
  // override default attributes with user attributes
  $wporg_atts = shortcode_atts([
                                       'rssurl' => 'default',
                                   ], $atts, $tag);

  ob_start();
	include  plugin_dir_path( __FILE__ ) . 'rssreadershortcode.php';
	$string = ob_get_clean();
	return $string;
	}
return "";
}

function csrssreadercustom_shortcode_scripts() {
    global $post;
    global $csrssreadershortcodename;
    if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $csrssreadershortcodename) && !is_admin()) {
			$jspath =  plugin_dir_url( __DIR__ ) . 'rss-feed-podcast-by-categories/js/accordions.js';
			wp_register_script( 'csaccordions', $jspath , '', '', true );
			wp_enqueue_script( 'csaccordions' );

      $jspath =  plugin_dir_url( __DIR__ ) . 'rss-feed-podcast-by-categories/js/amplitude.js';
			wp_register_script( 'csamplitude', $jspath , '', '', false );
			wp_enqueue_script( 'csamplitude' );

      $jspath =  plugin_dir_url( __DIR__ ) . 'rss-feed-podcast-by-categories/js/player.js';
			wp_register_script( 'csplayer', $jspath , '', '', true );
			wp_enqueue_script( 'csplayer' );

    }
}

function csrssreadercustom_shortcode_styles(){
	global $post;
  global $csrssreadershortcodename;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $csrssreadershortcodename) && !is_admin() ) {
		$cssautocomplete = plugin_dir_url( __DIR__ ) . 'rss-feed-podcast-by-categories/css/accordions.css';
		wp_enqueue_style(
						'csaccordions',
						$cssautocomplete
		);

    $cssplayer = plugin_dir_url( __DIR__ ) . 'rss-feed-podcast-by-categories/css/player.css';
		wp_enqueue_style(
						'cssplayer',
						$cssplayer
		);
	}
}

add_action( 'get_footer', 'csrssreadercustom_shortcode_styles' );
add_action( 'wp_enqueue_scripts', 'csrssreadercustom_shortcode_scripts');

add_shortcode($csrssreadershortcodename, 'carlsansrssreader');
?>
