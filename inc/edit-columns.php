<?php  

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
		} else{
			echo '-';
		}
	}
}

add_filter('manage_edit-category_columns','manage_categories_other_parents');
add_filter ('manage_category_custom_column', 'manage_category_other_parents_output', 10,4);



?>