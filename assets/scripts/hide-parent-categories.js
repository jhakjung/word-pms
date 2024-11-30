document.addEventListener("DOMContentLoaded", function () {
    // 부모 카테고리를 숨김
    document.querySelectorAll("ul.acf-checkbox-list > li").forEach(function (item) {
        // 자식 요소 `ul.children`이 있는 경우 부모로 간주
        if (item.querySelector("ul.children")) {
            item.style.display = "none"; // 부모 카테고리 숨기기
        }
    });
});
