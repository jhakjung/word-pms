<?php
// Theme resource FIles
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
function enqueue_custom_scripts() {
	wp_enqueue_style('main-css', get_theme_file_uri('/assets/styles/bootstrap.css'));
	wp_enqueue_script('fa-js', '//kit.fontawesome.com/61b7275f5f.js', 'NULL', '5.9.0', false);
	wp_enqueue_script('main-js', get_theme_file_uri('bundled.js'), 'NULL', '1.0', true);
	wp_enqueue_style('my-style', get_stylesheet_uri());
}

// 성과물 포스트 타입 등록
function document_post_types() {
	register_post_type('document', array(
		'show_in_rest' => true,
		// 'capability_type' => 'document',
		// 'map_meta_cap'  => true,
		'supports' => array('title', 'editor', 'comments', 'author', 'tag'),
		'rewrite' => array('slug' => 'documents'),
		'taxonomies'  => array('category'),
		'has_archive' => true,
		'public' => true,
		'labels' => array(
			'name' => '성과물',
			'add_new_item' => '성과물 추가',
			'edit_item' => '성과물 수정',
			'all_items' => '성과물 목록',
			'singular_name' => '성과물' ),
		'menu_icon' => 'dashicons-media-document'
	));
}
add_action('init', 'document_post_types');

// 카테고리명을 출력하는 함수
function get_all_category_list() {
	$args = array(
		'hide_empty' => 0, // 미분류 카테고리를 포함하여 모든 카테고리를 가져오도록 설정
	);
	$categories = get_categories($args);
	return $categories;
}

// 특정 카테고리의 자식 카테고리를 출력하는 함수
function custom_get_category_children($parent_cat_name) {
    $parent_category = get_category_by_slug($parent_cat_name);
    if ($parent_category) {
        $args = array(
            'hide_empty' => 0, // 미분류 카테고리를 포함하여 모든 자식 카테고리를 가져오도록 설정
            'parent' => $parent_category->term_id, // 부모 카테고리의 ID를 지정하여 자식 카테고리만 가져옴
            'orderby' => 'slug',
            'order' => 'ASC'
        );
        $children_categories = get_categories($args);
    } else {
        $children_categories = array(); // 자식 카테고리가 없는 경우 빈 배열 반환
    }
    return $children_categories;
}

// Register Widgets
add_action('widgets_init', 'pms_widget');
function pms_widget() {
	register_sidebar(array(
		'name'		=> esc_html__('Sidebar-1', 'pms'),
		'id'			=> 'sidebar1',
		'description'	=> esc_html__('Add widgets here', 'pms'),
		'before_widget'	=> '<div id="%1$s" class="widget-1">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="text-dark"> ',
		'after_title'		=> '</h4>'
	));
	register_sidebar(array(
		'name'		=> esc_html__('Sidebar-2', 'pms'),
		'id'			=> 'sidebar2',
		'description'	=> esc_html__('Add widgets here', 'pms'),
		'before_widget'	=> '<div id="%1$s" class="widget-2">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="text-dark"> ',
		'after_title'		=> '</h4>'
	));
	register_sidebar(array(
		'name'		=> esc_html__('Sidebar-3', 'pms'),
		'id'			=> 'sidebar3',
		'description'	=> esc_html__('Add widgets here', 'pms'),
		'before_widget'	=> '<div id="%1$s" class="widget-3">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="text-dark"> ',
		'after_title'		=> '</h4>'
	));
}

// 택소노미별 리스트를 btn으로 출력
function custom_get_tax_list($taxonomy) { ?>
	<section class="d-flex flex-wrap justify-content-center border-bottom">
		<?php
		// 'project_state' Taxonomy에 속하는 Term들을 가져옴
		$terms = get_terms(array(
			'taxonomy' => $taxonomy,
			'hide_empty' => false, // 빈 Term도 출력
			'orderby' => 'slug',
			'order' => 'ASC'
		));

		// $button_class = 'btn-light';
		// echo '<a href="#" class="btn m-2 border ' . $button_class . '">전체단계</a>';

		foreach ($terms as $term) {
			if ($term) {
				$term_name = $term->name;
				$term_link = get_term_link($term); ?>

				<span class="badge bg-primary m-1"><a href="<?php echo $term_link; ?>" class="badge_a_tag"><?php echo $term_name . '(' . $term->count . ')'; ?></a></span>

				<!-- // echo '<a href="' . $term_link . '" class="btn m-2 border '">' . $term_name . '</a>'; -->
			<?php }
		}
		?>
	</section>
<?php } ?>