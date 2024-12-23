<aside class="col-lg-3 aside mr-3">
    <div id="shadow-box" class="card mt-4 mb-3">
        <div class="card-header text-secondary card__header">
            <i class="fas fa-star"></i>&nbsp;&nbsp;즐겨찾기
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_favorites("badge bg-green bg-gradient m-1"); ?>
            </div>
        </div>
    </div>

    <div id="shadow-box" class="card my-3">
        <div class="card-header text-secondary card__header">
            <i class="fas fa-tag"></i>&nbsp;&nbsp;태그
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_all_tags("badge badge__yellow bg-gradient text-dark m-1"); ?>
            </div>
        </div>
    </div>

    <div id="shadow-box" class="card my-3">
        <div class="card-header text-secondary card__header">
        <i class="fas fa-folder"></i>&nbsp;&nbsp;성과물
        </div>
        <div class="p-3">
            <div class="card__group">
                <?php custom_get_document_category(); ?>
            </div>
        </div>
    </div>

</aside>