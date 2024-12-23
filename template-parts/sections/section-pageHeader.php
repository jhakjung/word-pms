<div class="col-lg-9">
    <div class="page-header">
        <?php
        if (is_home()) {
            $title = "전체 글";
        } elseif (is_archive()) {
            if (is_category()) {
                $title = custom_get_the_archive_title();
                // $title = str_replace('카테고리', '분류', $title);
            }  else {
                $title = custom_get_the_archive_title();
            }
        }
        else {
            echo "not taxonomy page";
        }
        echo "【 ". $title . " 】";
        ?>
    </div>