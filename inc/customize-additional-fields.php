<?php  
add_action('customize_register', 'theme_customize_register');

function theme_customize_register($wp_customize){

	//----------------------------------------------------------------------------------------------------Categories output section
	$wp_customize->add_section('additional_options',
		array(
			'title' 	=> "Categories output",
			'description' => 'This categories will be hidden from all categories list displayed on homepage',
		)
	);


	//--------------------------------settings
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