<?php

class Sort_Categories_Widget extends WP_Widget {

	/*-------------------------------------------------------------------------------Register widget with WordPress.*/
	function __construct() {
		parent::__construct(
			'sort_categories_wiget', // Base ID
			esc_html__( 'Sort categories', 'sort_categories' ), // Name
			array( 'description' => esc_html__( 'Outputs sorting categories widget', 'sort_categories' ), ) // Args
		);
	}



	

	/*-------------------------------------------------------------------------------Front-end */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			} 

			echo '<form action = "#" class = "sort-categories-form" id = "sortCategories">';


				//--------------------------------------seach field
				$show_search_field = isset( $instance['show_search_field'] ) ? $instance['show_search_field'] : false;
				if ($show_search_field) {
					echo '<input type = "text" class = "cat-search-field" name = "search_cat" placeholder = "Search for...">';
				}

				
				//--------------------------------------output categories select 1
				if ( $instance['sort_select_1'] ) {

					$sort_select_1_categories_IDs = explode(',', $instance['sort_select_1']);
					$sort_select_1_categories_IDs = sort_included_categories($sort_select_1_categories_IDs);

					output_categories_select($sort_select_1_categories_IDs, '1', 'Regions');
					
				}

				//--------------------------------------output categories select 2
				if ( $instance['sort_select_2'] ) {

					$sort_select_2_categories_IDs = explode(',', $instance['sort_select_2']);
					$sort_select_2_categories_IDs = sort_included_categories($sort_select_2_categories_IDs);
					
					output_categories_select($sort_select_2_categories_IDs, '2', 'Expertise');

				}


				//--------------------------------------output categories select 3
				if ( $instance['sort_select_3'] ) {

					$sort_select_3_categories_IDs = explode(',', $instance['sort_select_2']);
					$sort_select_3_categories_IDs = sort_included_categories($sort_select_3_categories_IDs);
					
					output_categories_select($sort_select_3_categories_IDs, '3', 'Other criteria');

				}


				echo '<div class = "submit-wrapper">
					<button class = "reset-catfilter" type="reset" value="Reset">Reset</button>
					<input type = "submit" value = "Submit">
					<div class = "clear"></div>
					</div>
					</form>';

		echo $args['after_widget'];
	}

	/*-------------------------------------------------------------------------------Back-end widget form*/
	public function form( $instance ) { ?>

		<div class = "sort-categories-widget-wrapper">
			<?php 
				$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'sort_categories' );
				$sort_select_1 = ! empty( $instance['sort_select_1'] ) ? $instance['sort_select_1'] : '';
				$sort_select_2 = ! empty( $instance['sort_select_2'] ) ? $instance['sort_select_2'] : '';
				$sort_select_3 = ! empty( $instance['sort_select_3'] ) ? $instance['sort_select_3'] : '';

				$show_search_field = isset( $instance['show_search_field'] ) ? (bool) $instance['show_search_field'] : false;
			?>
			
			<p>
				<!-- Title -->
				<label class = "field-item" for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><b><?php esc_attr_e( 'Title:', 'sort_categories' ); ?></b></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
				<br><br>

				
				<!-- Show search field -->
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_search_field'); ?>" name="<?php echo $this->get_field_name('show_search_field'); ?>"<?php checked( $show_search_field ); ?> />
				<label for="<?php echo $this->get_field_id( 'show_search_field' ); ?>">Display search field?</label>
				<br><br>

				
				<!-- Select field 1 -->
				<label class = "field-item" for="<?php echo $this->get_field_id( 'sort_select_1' ); ?>"><b><?php _e('Categories select field 1', 'example'); ?></b>
					<br><span>Place categories ids separated by comma.</span>
				</label><br>
				<input id="<?php echo $this->get_field_id( 'sort_select_1' ); ?>" type = "text" name="<?php echo $this->get_field_name( 'sort_select_1' ); ?>" value="<?php echo $sort_select_1; ?>" />
				<br><br>


				<!-- Select field 2 -->
				<label class = "field-item" for="<?php echo $this->get_field_id( 'sort_select_2' ); ?>"><b><?php _e('Categories select field 2', 'example'); ?></b>
					<br><span>Place categories ids separated by comma. Leave empty field to hide this select</span>
				</label><br>
				<input id="<?php echo $this->get_field_id( 'sort_select_2' ); ?>" type = "text" name="<?php echo $this->get_field_name( 'sort_select_2' ); ?>" value="<?php echo $sort_select_2; ?>"/>
				<br><br>

				<!-- Select field 3 -->
				<label class = "field-item" for="<?php echo $this->get_field_id( 'sort_select_3' ); ?>"><b><?php _e('Categories select field 3', 'example'); ?></b>
					<br><span>Place categories ids separated by comma. Leave empty field to hide this select</span>
				</label><br>
				<input id="<?php echo $this->get_field_id( 'sort_select_3' ); ?>" type = "text" name="<?php echo $this->get_field_name( 'sort_select_3' ); ?>" value="<?php echo $sort_select_3; ?>"/>
			</p>
		</div>


		<style type="text/css">
			.field-item span{
				color: grey;
				font-size: 11px;
			}
			.field-item{
				font-size: 15px;
			}
			.field-item input[type = "text"]{
				border: 1px solid grey;
			}
			.sort-categories-widget-wrapper input[type = "text"]{
				width: 100%;
			}
		</style>


<?php 
	}

	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		$instance['show_search_field'] = $new_instance['show_search_field'];

		$instance['sort_select_1'] = strip_tags( $new_instance['sort_select_1'] );

		$instance['sort_select_2'] = strip_tags( $new_instance['sort_select_2'] );
		
		$instance['sort_select_3'] = strip_tags( $new_instance['sort_select_3'] );

		return $instance;
	}

} // class Sort_Categories_Widget




// register widget
function register_sort_categories_widget() {
    register_widget( 'Sort_Categories_Widget' );
}
add_action( 'widgets_init', 'register_sort_categories_widget' );






function sort_included_categories($categories_to_order){
	/*
		- Sort get_categories output by widget input field value string

		$categories_to_order - array of categories ids (array)
		
	*/

	$category_order = $categories_to_order;
	$category_array = array();

	$categories = get_categories(array('include'=>$categories_to_order,  'hide_empty' => false));

	if ($categories) {
		foreach($categories as $cat) {
			$category_array[array_search($cat->cat_ID,$category_order)] = '<option class = "category-'.$cat->term_id.'" value="'.$cat->term_id.'">'.$cat->name.'</option>';
		}

		ksort($category_array);

		return $category_array;
	}
}




function output_categories_select($categories_to_output, $select_number, $first_select_text){
	/*
		- Output select field items html

		$select_number - sequence number of <select> tag (for name and class attributes) (string)
		$first_select_text - first select text value (string)

	*/

	echo '<select class = "sort-select-'.$select_number.'" name="sort_select_'.$select_number.'">';
		echo '<option class = "empty" value="none">'.$first_select_text.'</option>';
			foreach($categories_to_output as $category){
				echo $category;  	
			}
	echo '</select>';
}





?>