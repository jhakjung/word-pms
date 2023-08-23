<?php
// 택소노미 value 값: 예) ps01
$taxonomy = 'project_state';
$term_value = isset($_GET[$taxonomy]) ? sanitize_text_field($_GET[$taxonomy]) : '';

// print_r($term_value);
$term = get_term_by('slug', $term_value, $taxonomy);
$term_name = preg_replace('/\d{2}_/', '', $term->name);

echo $term_name;

// WP_Query arguments for fetching posts based on 'tax' value
$args = array(
    'post_type' => 'document', // Replace 'aaa' with your actual post type
    'tax_query' => array(
        array(
            'taxonomy' => 'project_state', // Replace 'tax' with your actual taxonomy
            'field'    => 'slug',
            'terms'    => $term_value,
        ),
    ),
);

// Execute the query
// $custom_query = new WP_Query($args);

// // Start the loop
// if ($custom_query->have_posts()) :
//     while ($custom_query->have_posts()) : $custom_query->the_post();
//         // Your loop content goes here
//         the_title(); // Example: Display the post title
//     endwhile;
//     wp_reset_postdata(); // Reset the query
// else :
//     echo '<p>No posts found.</p>';
// endif;
?>

<?php
	// $current_category = get_the_terms(get_the_ID(), $taxonomy);
	$current_category_name = '계약';
	$current_category_slug = 'ps01';
	// $current_doc_title = get_the_title(get_the_ID());
	?>
	<ul class="list-group doc-list">
		<li class="list-group-item border-bottom text-dark bg-light px-4">
			<?php echo $current_category_name; ?>
		</li>
		<?php
		$args = array(
			'post_type' => 'document',
			'tax_query' => array(
					array(
							'taxonomy' => $taxonomy,
							'field' => 'slug',
							'terms' => $current_category_slug,
					),
			),
			'posts_per_page' => -1
		);
		$query = new WP_Query($args);

		if ($query->have_posts()) {
			while ($query->have_posts()) {
					$query->the_post();	?>

					<li class="list-group-item border px-4 fs-5">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</li>
			<?php }
			wp_reset_postdata();
		} else {
			echo '<li class="list-group-item">해당 카테고리에 속하는 포스트가 없습니다.</li>';
		}
		?>
	</ul>
</div>