<?php
// Theme resource FIles
function enqueue_custom_scripts() {
	wp_enqueue_style('main-css', get_theme_file_uri('/assets/styles/bootstrap.css'));
	wp_enqueue_script('fa-js', '//kit.fontawesome.com/61b7275f5f.js', 'NULL', '5.9.0', false);
	wp_enqueue_script('main-js', get_theme_file_uri('bundled.js'), 'NULL', '1.0', true);
	wp_enqueue_style('my-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

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

// 택소노미별 리스트를 출력
function custom_get_tax_list($taxonomy) {

	// 'project_state' Taxonomy에 속하는 Term들을 가져옴
	$terms = get_terms(array(
		'taxonomy' => $taxonomy,
		'hide_empty' => false, // 빈 Term도 출력
		'orderby' => 'slug',
		'order' => 'ASC'
	));

	// foreach ($terms as $term) {
	// 	if ($term) {
	// 		$term_name = $term->name;
	// 		$term_link = get_term_link($term);

	return $terms;

	}

// 이슈상태 리스트를 출력
function custom_get_issue_state_list() { ?>
	<?php
	$terms = get_terms(array(
		'taxonomy' => 'test',
		'hide_empty' => false, // 빈 Term도 출력
		'orderby' => 'slug',
		'order' => 'ASC'
	));
	foreach ($terms as $term) {
		if ($term) {
			$term_name = $term->name;
			if ($term_name == "미결") {
				$class = "badge bg-vivid-red fs-7 m-1";
			} elseif ($term_name == "해결") {
				$class = "badge bg-vivid-cyan2 fs-7 m-1";
			} elseif ($term_name == "종결") {
				$class = "badge bg-vivid-cyan-blue fs-7 m-1";
			} else {
				$class = "badge bg-secondary fs-7 m-1";
			}
			$term_link = get_term_link($term); ?>
			<span class="<?php echo $class; ?>"><a href="<?php echo $term_link; ?>"><?php echo $term_name . '(' . $term->count . ')'; ?></a></span>
		<?php }
	} ?>
<?php }

// 카테고리 archive 페이지로 연결해주는 링크 생성 함수
function custom_cat_archive_link($category_slug) {
    $category = get_category_by_slug($category_slug);
    if ($category) {
        $category_archive_url = get_term_link($category);
        if (!is_wp_error($category_archive_url)) {
            return $category_archive_url;
        }
    }
    return '';
}

// Archive title을 출력할 때 괄호를 없애주는 함수
function custom_get_the_archive_title() {
	$archive_title = get_the_archive_title();
	// 현재 페이지가 카테고리 아카이브 페이지인지 확인
	$archive_title = str_replace(array('[', ']'), '', $archive_title);
	return $archive_title;
}

// 새로운 포스트의 슬러그를 'YYMM-중복 체크된 연번'로 설정
function custom_force_default_post_slug( $data ) {
    if ( empty( $data['post_name'] ) ) {
        global $wpdb;
        $post_count = $wpdb->get_var( "SELECT MAX(SUBSTRING_INDEX(post_name, '-', -1)) FROM $wpdb->posts WHERE post_status IN ('publish', 'draft', 'pending', 'private', 'trash')" );
        $post_count = sprintf( "%03d", intval( $post_count ) + 1 );

        $new_post_slug = date('ym') . '-' . $post_count;
        $slug_exists = $wpdb->get_var( $wpdb->prepare( "SELECT post_name FROM $wpdb->posts WHERE post_name = %s", $new_post_slug ) );

        while ( $slug_exists ) {
            $post_count++;
            $post_count = sprintf( "%03d", $post_count );
            $new_post_slug = date('ym') . '-' . $post_count;
            $slug_exists = $wpdb->get_var( $wpdb->prepare( "SELECT post_name FROM $wpdb->posts WHERE post_name = %s", $new_post_slug ) );
        }

        $data['post_name'] = $new_post_slug;
    }
    return $data;
}
add_filter( 'wp_insert_post_data', 'custom_force_default_post_slug' );

