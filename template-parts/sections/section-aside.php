<aside class="col-lg-3 border-end">
    <div class="card my-3">
        <div class="card-header">
            프로젝트단계
        </div>
        <div class="p-3">
            <div class="card-group">
                <?php custom_get_tax_list('project_state', 'fs-7 badge bg-primary m-1'); ?>
            </div>
        </div>
    </div>

    <div class="card my-3">
        <div class="card-header">
            공종
        </div>
        <div class="p-3">
            <div class="card-group">
                <?php custom_get_tax_list('system_type', 'fs-7 badge bg-secondary m-1'); ?>
            </div>
        </div>
    </div>

    <div class="card my-3">
        <div class="card-header">
            태그
        </div>
        <div class="p-3">
            <div class="tag-list">
                <?php wp_tag_cloud(array(
                    'smallest' => 11,
                    'largest' => 16,
                    'hide_empty' => false
                )); ?>
            </div>
        </div>
    </div>

    <div class="card my-3">
        <div class="fs-6 text-center card-header">
            이슈상태
        </div>
        <div class="p-3">
            <div class="tag-list d-flex flex-wrap justify-content-center">
                <?php
                // $issue_status = ['해결', '미결', '종결'];
                $solved = "badge bg-vivid-cyan2 fs-7 m-1";
                $unsolved = "badge bg-vivid-red fs-7 m-1";
                $closed = "badge bg-secondary fs-7 m-1";
                ?>
                <span class="<?php echo $solved; ?>"><a href="#">해결</a></span>
                <span class="<?php echo $unsolved; ?>"><a href="#">미결</a></span>
                <span class="<?php echo $closed; ?>"><a href="#">종결</a></span>
            </div>
        </div>
    </div>

    <div class="my-3">
        <?php dynamic_sidebar('sidebar2'); ?>
    </div>

</aside>