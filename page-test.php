<?php get_header();?>

<?php
// $fields = get_field_object('issue_staut', 54);

// echo '<pre>';
// 	print_r($fields);
// echo '</pre>';
// echo get_sub_field('issue_status');

// $field_groups = acf_get_field_groups();
// $count = 0;

// foreach ($field_groups as $field_group) {
//     $group_name = $field_group['title'];
//     $group_key = $field_group['key'];

//     echo "Group Name: $group_name<br>";

//     $acf_fields = acf_get_fields($group_key);

//     foreach ($acf_fields as $acf_field) {

// 		//get the field name
// 	$field_name = $acf_fields[$count]["name"];

// 	//create a dymanic variable and grab the value of the field
// 	${"$field_name"} = get_field($field_name);

// 	//create a nice list of variable names during development
// 	$array_of_field_names[] = $field_name;

// 	//BONUS: create an array in case you want to print everything out
// 	$array_of_field_values[] = array(
// 	'field_name' => $field_name,
// 	'value' => ${"$field_name"},
// 	);
//     }

// 	print_r($array_of_field_names);
// 	print_r($array_of_field_values);


//     echo "<br>";
// }


// $field = get_field('issue_status');
// if ($field == NULL) {
// 	echo 'Null';
// }
// // echo $field->name;
// print_r($field);

// 'project_state' Taxonomy에 속하는 Term들을 가져옴
// $terms = get_terms(array(
// 	'taxonomy' => 'issue_status',
// 	'hide_empty' => false, // 빈 Term도 출력
// ));
// print_r($terms);
// $issue_status_values = get_field('issue_status'); // 'issue_status' 필드의 값들을 가져옴

// if ($issue_status_values) {
//     foreach ($issue_status_values as $value) {
//         echo $value . '<br>'; // 각 값 출력
//     }
// } else {
//     echo 'No values found.';
// }



?>



<?php get_footer(); ?>