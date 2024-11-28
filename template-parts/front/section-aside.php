<aside class="col-lg-3 aside mr-3">
    <div id="shadow-box" class="card my-3">
        <div class="card-header card__header">
            <i class="text-dark text-opacity-50 fas fa-star"></i>&nbsp;&nbsp;즐겨찾기
        </div>
        <div class="card-body container">
            <ul class="favorite">
                <?php custom_get_favorites(); ?>

            </ul>
        </div>
    </div>

    <div id="shadow-box" class="card my-3">
        <div class="card-header card__header">
            <i class="text-dark text-opacity-50 fas fa-tag"></i>&nbsp;&nbsp;태그
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_all_tags("m-1"); ?>
            </div>
        </div>
    </div>

    <div id="shadow-box" class="card my-3">
        <div class="card-header card__header">
        <i class="text-dark text-opacity-50 fas fa-folder"></i>&nbsp;&nbsp;성과물
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_document_category(); ?>
            </div>
        </div>
    </div>

    <!-- <div id="shadow-box" class="card my-3">
        <div class="card-header card__header">
            <i class="text-dark text-opacity-50 fas fa-cube"></i>&nbsp;&nbsp;공종
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_tax_list('system_type', 'fs-7 badge badge__green m-1'); ?>
            </div>
        </div>
    </div> -->



</aside>