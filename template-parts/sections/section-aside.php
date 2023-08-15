<aside class="col-lg-3 aside border-end">
    <div class="card my-3">
        <div class="card-header card__header">
            프로젝트단계
        </div>
        <div class="p-2">
            <div class="card__group">
                <?php custom_get_tax_list('project_state', 'fs-7 badge bg-primary m-1'); ?>
            </div>
        </div>
    </div>

    <div class="card my-3">
        <div class="card-header card__header">
            공종
        </div>
        <div class="p-2">
            <div class="card__group">
                <?php custom_get_tax_list('system_type', 'fs-7 badge bg-secondary m-1'); ?>
            </div>
        </div>
    </div>

    <div class="card my-3">
        <div class="card-header card__header">
            태그
        </div>
        <div class="card__group__tags p-2 text-center">
                <?php wp_tag_cloud(array(
                    'smallest' => 10,
                    'largest' => 16,
                    'hide_empty' => false
                )); ?>
        </div>
    </div>

    <div class="card my-3">
        <div class="card-header card__header">
            이슈상태
        </div>
        <div class="p-2">
            <div class="card__group">
                <?php custom_get_issue_state_list(); ?>
            </div>
        </div>
    </div>

    <div class="my-3">
        <?php dynamic_sidebar('sidebar2'); ?>
    </div>

</aside>