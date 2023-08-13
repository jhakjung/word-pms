<?php
$template = get_page_template();
echo "현재 페이지의 템플릿 파일: $template";
?>


<?php if(is_page_template('index.php')) {
        echo "index.php";
    } elseif(is_page_template('taxonomy.php')) {
        echo "taxonomy.php";
        $taxonomy = get_queried_object();
        $taxonomy_info = get_taxonomy($taxonomy->taxonomy);
        echo $taxonomy_info->labels->name; // taxonomy label
        echo $taxonomy->name; // taxonomy term
    } ?>