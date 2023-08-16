<aside class="col-lg-3 aside border-end">
    <div class="card my-3">
        <div class="card-header card__header">
            프로젝트단계
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_tax_list('project_state', 'fs-7 badge badge__blue m-1'); ?>
            </div>
        </div>
    </div>

    <div class="card my-3">
        <div class="card-header card__header">
            공종
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_tax_list('system_type', 'fs-7 badge badge__green m-1'); ?>
            </div>
        </div>
    </div>

    <div class="card my-3">
        <div class="card-header card__header">
            키워드
        </div>
        <div class="card__group__tags p-2 pb-3 text-center">
                <?php
                // wp_tag_cloud(array(
                //     'smallest' => 10,
                //     'largest' => 16,
                //     'hide_empty' => false
                // ));
                ?>
                <?php dynamic_sidebar('sidebar1'); ?>
        </div>
    </div>

    <div class="card my-3">
        <div class="card-header card__header">
            이슈상태
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_issue_state_list(); ?>
            </div>
        </div>
    </div>

    <div class="my-3">
    </div>

</aside>