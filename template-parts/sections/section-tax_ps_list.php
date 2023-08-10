<section class="container-fluid border-bottom">
	<!-- <div class="d-flex flex-wrap justify-content-center"> -->
<br>
    <?php
    // 'project_state' Taxonomy에 속하는 Term들을 가져옴
    // $terms = get_terms(array(
    //     'taxonomy' => 'project_state',
    //     'hide_empty' => false, // 빈 Term도 출력
    // ));

    // if (!empty($terms) && !is_wp_error($terms)) {
    //     echo '<ul class="nav nav-tabs">';
    //     foreach ($terms as $term) {
    //         echo '<li class="nav-item"><a class="nav-link" href="' . get_term_link($term) . '">' . $term->name . '</a></li>';
    //     }
    //     echo '</ul>';
    // } else {
    //     echo 'No terms found.';
    // }
    ?>

    <?php
    // 'project_state' Taxonomy에 속하는 Term들을 가져옴
    $terms = get_terms(array(
        'taxonomy' => 'project_state',
        'hide_empty' => false, // 빈 Term도 출력
    )); ?>

    <ul class="nav nav-tabs">
        <li class="nav-item">
                    <a class="nav-link active" href="<?php get_term_link($term); ?>"><?php echo "전체"; ?></a>
        </li>
        <?php
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php get_term_link($term); ?>"><?php echo  $term->name; ?></a>
                </li>
            <?php }

        }?>
    </ul>
<br>
<!-- <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="#">Active</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Link</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Link</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled">Disabled</a>
  </li>
</ul> -->

	<!-- </div> -->
</section>