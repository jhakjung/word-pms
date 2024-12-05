jQuery(document).ready(function ($) {
    // "테이블 추가" 버튼 클릭 이벤트
    $("#insert_table_button").on("click", function () {
        // 파일 선택 팝업 띄우기
        const input = $("<input>")
            .attr("type", "file")
            .attr("accept", ".xls,.xlsx");

        input.trigger("click");

        input.on("change", function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (event) {
                const data = new Uint8Array(event.target.result);
                const workbook = XLSX.read(data, { type: "array" });

                // 첫 번째 시트 가져오기
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];

                // 데이터를 JSON 형식으로 변환
                const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

                // HTML 테이블 생성
                let htmlTable = `
                    <style>
                        table {
                            border-collapse: collapse;
                            width: 100%;
                            margin: 5px 0;
                            font-size: 16px;
                            text-align: center;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            white-space: nowrap;  /* 모든 열 줄바꿈 방지 */
                        }
                        th {
                            background-color: #f4f4f4;
                            font-weight: bold;
                        }
                        tr:nth-child(even) {
                            background-color: #f9f9f9;
                        }
                        tr:hover {
                            background-color: #f1f1f1;
                        }
                        .filter-input {
                            width: 90%;
                            padding: 5px;
                            margin-bottom: 5px;
                        }
                    </style>
                    <script>
                        // 필터링 함수
                        function filterTable(input, columnIndex) {
                            const filter = input.value.toLowerCase();
                            const table = document.querySelector("table");
                            const rows = table.querySelectorAll("tbody tr");
                            rows.forEach(row => {
                                const cell = row.cells[columnIndex];
                                if (cell && cell.textContent.toLowerCase().includes(filter)) {
                                    row.style.display = "";
                                } else {
                                    row.style.display = "none";
                                }
                            });
                        }
                    </script>
                    <table border="1">
                        <thead>
                            <tr>`;

                // 첫 번째 행(헤더) 처리
                const headers = jsonData[0];
                headers.forEach((header, index) => {
                    htmlTable += `
                        <th>
                            ${header || "Column " + (index + 1)}<br>
                            <input type="text" class="filter-input" onkeyup="filterTable(this, ${index})" placeholder="Filter ${header}">
                        </th>`;
                });

                htmlTable += `</tr></thead><tbody>`;

                // 나머지 행(데이터) 처리
                jsonData.slice(1).forEach(row => {
                    htmlTable += "<tr>";
                    headers.forEach((_, index) => {
                        htmlTable += `<td>${row[index] || ""}</td>`;
                    });
                    htmlTable += "</tr>";
                });

                htmlTable += "</tbody></table>";

                // HTML 테이블을 에디터에 삽입
                if (typeof tinymce !== "undefined") {
                    tinymce.activeEditor.execCommand("mceInsertContent", false, htmlTable);
                } else {
                    alert("TinyMCE 편집기를 사용할 수 없습니다!");
                }
            };

            reader.readAsArrayBuffer(file);
        });
    });
});
