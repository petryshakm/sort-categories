<?php
/*
Plugin Name: Sort categories
Description: Add sorting categories widget
Version: 1.0
Text Domain: sort_categories




This plugin works with Literatum theme and requires ACF plugin installed and taxonomy field "other_parents" created (multiple checkbox field type).

*/




define( 'PLUGIN_DIR', dirname(__FILE__).'/' );

//----------------------------------------------------------------------------register scripts and styles
function Sort_categories_scripts() {   
    wp_enqueue_script( 'cat_sorting', plugin_dir_url( __FILE__ ) . 'assets/js/cat-sorting.js', array('jquery'), '1.0.0' );
    wp_localize_script( 'cat_sorting', 'GlimpsesAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts', 'Sort_categories_scripts');


function add_style() {
	wp_enqueue_style( 'sort_cat_styles', plugin_dir_url( __FILE__ ) . 'assets/css/styles.css' );
}
add_action( 'wp_enqueue_scripts', 'add_style' );



//----------------------------------------------------------------------------change acf saving metadata for taxonomies
function acf_update_term_meta_other_parents($value, $post_id, $field) {
	$term_id = intval(filter_var($post_id, FILTER_SANITIZE_NUMBER_INT));
	if($term_id > 0)
		update_term_meta($term_id, $field['name'], $value);
	return $value;
}
add_filter('acf/update_value/name=other_parents', 'acf_update_term_meta_other_parents', 10, 3);



function acf_load_term_meta_other_parents($value, $post_id, $field) {
	$term_id = intval(filter_var($post_id, FILTER_SANITIZE_NUMBER_INT));
	if($term_id > 0)
		$value = get_term_meta($term_id, $field['name'], true);
	return $value;
}
add_filter('acf/load_value/name=other_parents', 'acf_load_term_meta_other_parents', 10, 3);



//----------------------------------------------------------------------------include plugin parts
include_once( 'inc/widget.php' );
include_once( 'inc/load-categories.php' );
include_once( 'inc/edit-columns.php' );
include_once( 'inc/customize-additional-fields.php' );









?>
