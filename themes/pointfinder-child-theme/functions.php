<?php
//Your Code Here

add_action( 'wp_enqueue_scripts', 'pfch_theme_enqueue_styles' );
function pfch_theme_enqueue_styles() {
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array()
    );
}

add_filter('rwmb_google_maps_url', function($url) {
	if(function_exists(PFSAIssetControl)) {
		$maplanguage = PFSAIssetControl('setup5_mapsettings_maplanguage', '', 'en');
		$setup5_map_key = PFSAIssetControl('setup5_map_key', '', '');
		if (!empty($setup5_map_key)) {
			$setup5_map_key = '&key=' . $setup5_map_key;
		} else {
			$setup5_map_key = '';
		}

		$url = $url . '?libraries=places&language=' . $maplanguage . $setup5_map_key;

	}
	return $url;
});

add_action('after_setup_theme', 'remove_widget_init');
function remove_widget_init() {
	global $wp_filter;
	remove_action( 'widgets_init','pointfinder_extrafunction_03' );

	return;
}

require_once( get_stylesheet_directory().'/includes/pf-grid-shortcodes-static.php');
require_once( get_stylesheet_directory().'/includes/pfcustomwidgets.php');
require_once( get_stylesheet_directory().'/includes/ajax/ajax-poidata.php');
require_once( get_stylesheet_directory().'/includes/ajax/ajax-listdata.php');

add_action( 'widgets_init','pointfinder_extrafunction_03_new' );

function shortcode_cleaner() {
	remove_shortcode( 'pf_itemgrid2' ); // Not exactly required
	add_shortcode( 'pf_itemgrid2', 'pf_itemgrid2_func_new' );
}
add_action( 'init', 'shortcode_cleaner' );

function add_and_remove() {
	remove_action( 'PF_AJAX_HANDLER_pfget_markers', 'pf_ajax_markers' );
	remove_action( 'PF_AJAX_HANDLER_nopriv_pfget_markers', 'pf_ajax_markers' );

	remove_action( 'PF_AJAX_HANDLER_pfget_listitems', 'pf_ajax_list_items' );
	remove_action( 'PF_AJAX_HANDLER_nopriv_pfget_listitems', 'pf_ajax_list_items' );

	add_action( 'PF_AJAX_HANDLER_pfget_markers', 'pf_ajax_markers_new' );
	add_action( 'PF_AJAX_HANDLER_nopriv_pfget_markers', 'pf_ajax_markers_new' );

	add_action( 'PF_AJAX_HANDLER_pfget_listitems', 'pf_ajax_list_items_new' );
	add_action( 'PF_AJAX_HANDLER_nopriv_pfget_listitems', 'pf_ajax_list_items_new' );
}
add_action( 'init' , 'add_and_remove' );

function set_lat_lng($post_id) {
	if(!isset($_POST['webbupointfinder_items_location'])) {
		return;
	}
	//echo $_POST['webbupointfinder_items_location'];
	$loc=explode(',',$_POST['webbupointfinder_items_location']) ;

	//echo $loc[0].'<br>';
	//echo $loc[1].'<br>';
	update_post_meta($post_id, 'latitude', $loc[0]);
	update_post_meta($post_id, 'longitude', $loc[1]);
	//exit();
}
add_action( 'save_post', 'set_lat_lng' );

?>