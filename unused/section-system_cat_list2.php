<!-- 부트스트랩 리스트 스타일 -->
<div class="">
    <?php
    $current_category_name = "공종별";
    ?>
    <ul class="list-group w-auto">
        <li class="list-group-item text-bg-secondary border-bottom pl-4">
            <?php echo $current_category_name; ?>
        </li>
        <?php
        $categories = custom_get_category_children('system');
        foreach ($categories as $category) {
            if ($category) { ?>

            <li class="list-group-item border px-4 lh-1">
                <?php echo $category->name; ?>
            </li>

        <?php }
        } ?>
    </ul>
</div>