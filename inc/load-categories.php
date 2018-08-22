<?php 
function load_subcategories() {


	$query_data = $_GET;

	$parent_category = ($query_data['parent_category']) ? $query_data['parent_category'] : false;
	$parent_category_2 = ($query_data['parent_category_2']) ? $query_data['parent_category_2'] : false;
	$parent_category_3 = ($query_data['parent_category_3']) ? $query_data['parent_category_3'] : false;

	$search_field_value = ($query_data['search_field_value']) ? $query_data['search_field_value'] : '';
	


	// parent category 1 value
	$parent_category_value = $parent_category;
	if ($parent_category == 'none') {
		$parent_category_value = '';
	}

	
	// parent category 2 value
	$parent_category_2_query_args = ''; 
	if ($parent_category_2 != 'none') {
		$parent_category_2_query_args = array(
			'key' => 'other_parents',
			'value' => $parent_category_2,
			'compare' => 'LIKE'
		);
	}

	// parent category 3 value 
	$parent_category_3_query_args = '';
	if ($parent_category_3 != 'none') {
		$parent_category_3_query_args = array(
			'key' => 'other_parents',
			'value' => $parent_category_3,
			'compare' => 'LIKE'
		);
	}
	


	$meta_query_value = array(
		'relation' => 'AND',
		
		$parent_category_2_query_args,
		$parent_category_3_query_args
	);

	// hide parent categories (from customizer field value)
	$hide_categories_ids = get_theme_mod('hide_categories_ids');
	$exclude_categories_array = explode(',', $hide_categories_ids);
	array_push($exclude_categories_array, 39);



	$categories_args = array(
		'parent' => $parent_category_value,
		'search' => $search_field_value,
		'meta_query' => $meta_query_value,
		'hide_empty' => false,
		'exclude'	=> $exclude_categories_array,
		'orderby'       => 'name',
		'order'         => 'DESC',
	);

	$categories = get_categories( $categories_args );

	// 
	$categories_count = count($categories);
	$insufficient_items = 3 - $categories_count % 3;

	if ($categories) {
		$counter = 1;
		foreach ($categories as $cat) {


			// add output theme classes depends on items categories count
			if ($categories_count == 1) {
				$enlarge_class = 'squarebig';
			} else{
				if ($insufficient_items == 1) {
					$enlarge_class = (($counter == 2) ? 'double' : '');
				} elseif ($insufficient_items == 2) {
					$enlarge_class = (($counter == 1) ? 'double' : '');
					$enlarge_class_2 = (($counter == $categories_count) ? 'double' : '');
				} else{
					$enlarge_class = '';
				}
			}

			//-------------------------------------output using theme markup
			$post_featured_image = '';
			$term_id = $cat->term_id;

			$featured_image_id = get_taxonomy_meta($term_id, ktt_var_name('category_featured_image'), true);
			$image_attributes = wp_get_attachment_image_src( $featured_image_id, 'thumbnail' );

			$image_attributes = wp_get_attachment_image_src( $featured_image_id, 'large' );
			$post_featured_image = $image_attributes[0];



			// customize --------------------------------------------------------------------------------
			$general_option_meta_fields = get_option(ktt_var_name('post_minicover_1_meta_info_display'));
			$hover_effect = get_option(ktt_var_name('post_minicover_1_hover_effect'));
			$mask_opacity = get_option(ktt_var_name('post_minicover_1_mask_opacity'));


			$background_color = '';
			$colors = array('#1abc9c', '#2ecc71', '#3498db', '#9b59b6', '#f1c40f', '#e67e22', '#e74c3c', '#95a5a6');
			if(!$background_color) $background_color = $colors[array_rand($colors, 1)];


			global $enlarge;

			?><div class="article-card square <?php echo $enlarge_class.$enlarge_class_2; ?> <?php if($hover_effect) {?>hidehover<?php } ?>" >
						<div class="inner">

							<div class="image-background" style="<?php if ($background_color) {?>background-color:<?php echo $background_color;?>;<?php } ?><?php if (is_single()) {?>background-attachment:fixed;<?php } ?>background-image:url('<?php echo $post_featured_image;?>');">

							</div>

							<div class="image-cover" <?php if (isset($mask_opacity) && $mask_opacity) {?>style="background-color:rgba(0,0,0,<?php echo $mask_opacity;?>);"<?php } ?>>
								<div class="inner">


									<h3><a  href="<?php echo get_category_link($cat->term_id);?>" title="<?php echo $cat->name;?>" class="title ajaxlink"><?php echo $cat->name;?></a></h3>
									<?php if (isset($cat->description) && $cat->description) {?>

											<a <?php if (is_single()) {?> onclick="scrollto('.entry-content', 750, -30)" <?php } else { ?> href="<?php echo get_category_link($cat->term_id);?>" <?php } ?> title="<?php echo $cat->name;?>" class="post-subtitle ajaxlink">
												<?php echo $cat->description;?>
											</a>

									<?php } ?>
									<style>
									h3 + p.description a{
										color: rgba(255,255,255,0.57);
										text-decoration: none;
									}
									</style>
								</div>
							</div>
						</div>
					</div>

<?php
		$counter++;
		} //endforeach
	} else{
		echo '<div class = "no-categories-loaded">No categories found in accordance with your request</div>';
	}
	
	wp_reset_postdata();
	die();
}
add_action( 'wp_ajax_load_subcategories', 'load_subcategories' );
add_action( 'wp_ajax_nopriv_load_subcategories', 'load_subcategories' );







?>