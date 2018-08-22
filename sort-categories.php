<?php
/*
Plugin Name: Sort categories
Description: Add sorting categories widget
Version: 1.0
Text Domain: sort_categories
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
//second_parent_cat
function acf_update_term_meta($value, $post_id, $field) {
	$term_id = intval(filter_var($post_id, FILTER_SANITIZE_NUMBER_INT));
	if($term_id > 0)
		update_term_meta($term_id, $field['name'], $value);
	return $value;
}
add_filter('acf/update_value/name=second_parent_cat', 'acf_update_term_meta', 10, 3);



function acf_load_term_meta($value, $post_id, $field) {
	$term_id = intval(filter_var($post_id, FILTER_SANITIZE_NUMBER_INT));
	if($term_id > 0)
		$value = get_term_meta($term_id, $field['name'], true);
	return $value;
}
add_filter('acf/load_value/name=second_parent_cat', 'acf_load_term_meta', 10, 3);


//third_parent_cat
function acf_update_term_meta_third_category_parent($value, $post_id, $field) {
	$term_id = intval(filter_var($post_id, FILTER_SANITIZE_NUMBER_INT));
	if($term_id > 0)
		update_term_meta($term_id, $field['name'], $value);
	return $value;
}
add_filter('acf/update_value/name=third_parent_cat', 'acf_update_term_meta_third_category_parent', 10, 3);



function acf_load_term_meta_third_category_parent($value, $post_id, $field) {
	$term_id = intval(filter_var($post_id, FILTER_SANITIZE_NUMBER_INT));
	if($term_id > 0)
		$value = get_term_meta($term_id, $field['name'], true);
	return $value;
}
add_filter('acf/load_value/name=third_parent_cat', 'acf_load_term_meta_third_category_parent', 10, 3);





//other parents
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






//----------------------------------------------------------------------------include other plugin parts
// include_once( 'inc/custom-get-template-part-plugin.php' );
include_once( 'inc/widget.php' );
include_once( 'inc/load-categories.php' );







//----------------------------------------------------------------------------additional categories columns in wp-admin

// ----------------------------------------------------------Output ID
function manage_categoriesID($columns){
	$columns['categoryID'] = 'ID';
	return $columns;
}

function manage_category_ID_output($deprecated,$column_name,$term_id){
	if ($column_name == 'categoryID') {
   	echo $term_id;
	}
}

add_filter('manage_edit-category_columns','manage_categoriesID');
add_filter ('manage_category_custom_column', 'manage_category_ID_output', 10,3);


// // ----------------------------------------------------------second parent column
// function manage_categories_second_parent($columns){
// 	$columns['second_parent'] = '2nd parent';
// 	return $columns;
// }


// function manage_category_second_parent_output($deprecated,$column_name,$term_id){
// 	if ($column_name == 'second_parent') {

// 		$second_parent = get_field('second_parent_cat', 'category'.$term_id);

// 		if ($second_parent) {
// 			echo get_category($second_parent)->name;
// 		} else{
// 			echo '-';
// 		}
// 	}
// }

// add_filter('manage_edit-category_columns','manage_categories_second_parent');
// add_filter ('manage_category_custom_column', 'manage_category_second_parent_output', 10,3);




// // ----------------------------------------------------------third parent column
// function manage_categories_third_parent($columns){
// 	$columns['third_parent'] = '3rd parent';
// 	return $columns;
// }


// function manage_category_third_parent_output($deprecated,$column_name,$term_id){
// 	if ($column_name == 'third_parent') {

// 		$third_parent = get_field('third_parent_cat', 'category'.$term_id);

// 		if ($third_parent) {
// 			echo get_category($third_parent)->name;
// 		} else{
// 			echo '-';
// 		}
// 	}
// }

// add_filter('manage_edit-category_columns','manage_categories_third_parent');
// add_filter ('manage_category_custom_column', 'manage_category_third_parent_output', 10,4);


// ----------------------------------------------------------other parents column
function manage_categories_other_parents($columns){
	$columns['other_parents'] = 'Other parents';
	return $columns;
}


function manage_category_other_parents_output($deprecated,$column_name,$term_id){
	if ($column_name == 'other_parents') {

		$other_parents = get_field('other_parents', 'category'.$term_id);

		if ($other_parents) {
			$counter = 1;
			foreach ($other_parents as $p) {
				$cat = get_category($p);
				echo $counter.') '.$cat->name.'<br>';

				$counter++;
			}
			// echo '<pre>'; 
			// print_r($third_parent); 
			// echo '</pre>';
			// echo get_category($third_parent)->name;
		} else{
			echo '-';
		}
	}
}

add_filter('manage_edit-category_columns','manage_categories_other_parents');
add_filter ('manage_category_custom_column', 'manage_category_other_parents_output', 10,4);






//----------------------------------------------------------------------------output categories hide option
add_action('customize_register', 'theme_customize_register');

function theme_customize_register($wp_customize){
	



	//----------------------------------------------------------------------------------------------------footer settings
	$wp_customize->add_section('additional_options',
		array(
			'title' 	=> "Categories output",
			'description' => '',
		)
	);


	//--------------------------------footer settings
	$all_fields = array(
		array('hide_categories_ids', 'Place categories ID (separated by comma)', 'text')
	);

	foreach ($all_fields as $s) {
		$wp_customize->add_setting($s[0],
			array(
				'default'	=>	'',
				'transport' =>	'refresh'
				)
		);

		$wp_customize->add_control(
			new WP_Customize_Control($wp_customize, $s[0],
				array(
					'label'		=> $s[1],
					'section'	=> 'additional_options',
					'settings'	=> $s[0],
					'type'		=> $s[2],	
				)
			)
		);
	} //end foreach
	
}









?>
