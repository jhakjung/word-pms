<aside class="col-lg-3 aside border-end">
    <div class="card my-3">
        <div class="card-header card__header">
        <i class="text-dark text-opacity-50 fas fa-folder"></i>&nbsp;&nbsp;프로젝트단계
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_tax_list('project_state', 'fs-7 badge badge__blue m-1'); ?>
            </div>
        </div>
    </div>

    <div class="card my-3">
        <div class="card-header card__header">
            <i class="text-dark text-opacity-50 fas fa-cube"></i>&nbsp;&nbsp;공종
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_tax_list('system_type', 'fs-7 badge badge__green m-1'); ?>
            </div>
        </div>
    </div>

    <div class="card my-3">
        <div class="card-header card__header">
            <i class="text-dark text-opacity-50 fas fa-tag"></i>&nbsp;&nbsp;키워드
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_tax_list('post_tag', 'fs-7 badge badge__yellow m-1'); ?>
            </div>
        </div>
    </div>

    <div class="card my-3">
        <div class="card-header card__header">
            <i class="text-dark text-opacity-50 fas fa-info"></i>&nbsp;&nbsp;이슈상태
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