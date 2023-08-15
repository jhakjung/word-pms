<?php get_header();?>

<?php

  while(have_posts()) {
    the_post(); ?>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php the_content(); ?>
    <hr>
    <?php
    // echo get_field('issue_state');

    // ACF 필드 "issue_state"의 ID를 가져옴
$issue_state_id = get_field("issue_state");

// "issue_state" 택소노미에서 ID를 사용하여 Term 객체를 가져옴
$issue_state_term = get_term_by("id", $issue_state_id, "issue_state");

// Term 객체에서 이름을 가져와 출력
if ($issue_state_term) {
    echo $issue_state_term->name;
} else {
    echo "Term not found.";
}

    ?>
<?php }

?>


<?php get_footer(); ?>