<div class="col-lg-9">
    <div class="single-main container">
        <?php
        // 현재 첨부 파일에 연결된 원 포스트 ID 가져오기
        $parent_post_id = wp_get_post_parent_id(get_the_ID());

        // 원 포스트 정보 가져오기
        $parent_post = get_post($parent_post_id); ?>

        <div class="post-title fs-3 mt-3">
            <?php
            if ($parent_post) {
                echo "첨부파일 위치: ";
                ?>
                <a style="color:#0d6efd !important" href="<?php echo get_permalink($parent_post->ID); ?>"><?php echo $parent_post->post_title; ?></a>
                <?php } ?>
        </div>

        <hr>

        <div class="post-content mt-2 px-1">
            <?php
            // 첨부 파일 정보 가져오기
            $attachment_title = get_the_title();
            $attachment_id = get_the_ID();
            $attachment_url = wp_get_attachment_url($attachment_id);

            // 첨부 파일 표시
            echo '<p class="text-primary fs-6 p-2"><a style="color:#0d6efd" href="' . $attachment_url . '">' . $attachment_title . '</a></p>';?>
            <br>
        </div>
    </div>
</div>
