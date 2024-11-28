<div class="col-lg-9">
    <div class="archive-header fs-3 fw-bold text-secondary text-center mt-3 mb-5">
        <?php
        if (is_home()) {
            $title = "전체 글";
        } elseif (is_archive()) {
                $title = custom_get_the_archive_title();
            }
        else {
            echo "No taxonomy page";
        }
        // echo "---------- ". $title . " <<<<<<<<<";
        // echo "【 Archive ". $title . " 】";
        echo "[ 자료목록 ] ". $title;
        ?>
    </div>