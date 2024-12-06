jQuery(document).ready(function ($) {
    // 필터링 함수
    $(document).on("keyup", ".filter-input", function () {
        const input = $(this);
        const filter = input.val().toLowerCase();
        const columnIndex = input.closest("th").index();
        const table = input.closest("table");
        const rows = table.find("tbody tr");

        rows.each(function () {
            const cell = $(this).find("td").eq(columnIndex);
            if (cell.text().toLowerCase().includes(filter)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
