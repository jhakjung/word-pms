<div class="col-lg-9">
    <div class="archive-header fs-4 text-center text-secondary my-4">
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
        echo "자료목록 ‣ ". "<span class='text-bg-secondary'>&nbsp;".$title."&nbsp;</span><hr id='my_hr' class='my_4 hr-1'>";
        ?>
    </div>