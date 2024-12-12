// jQuery(document).ready(function ($) {
//     // "테이블 추가" 버튼 클릭 이벤트
//     $("#insert_table_button").on("click", function () {
//         // 파일 선택 팝업 띄우기
//         const input = $("<input>")
//             .attr("type", "file")
//             .attr("accept", ".xls,.xlsx");

//         input.trigger("click");

//         input.on("change", function (e) {
//             const file = e.target.files[0];
//             if (!file) return;

//             const reader = new FileReader();

//             // 캐시 무효화 루틴 (Cache-Busting)
//             const cacheBustValue = new Date().getTime(); // 현재 타임스탬프 생성
//             console.log("캐시 무효화 값: " + cacheBustValue); // 디버깅용 (옵션)


//             reader.onload = function (event) {
//                 try {
//                     const data = new Uint8Array(event.target.result);
//                     const workbook = XLSX.read(data, { type: "array" });

//                     // 첫 번째 시트 가져오기 및 JSON 변환
//                     const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
//                     const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

//                     // HTML 테이블 생성 (특정 클래스 'custom-excel-table' 추가)
//                     let htmlTable = `
//                         <style>
//                             table {
//                                 border-collapse: collapse;
//                                 width: 100%;
//                                 margin: 5px 0;
//                                 font-size: 16px;
//                                 text-align: center;
//                             }
//                             th, td {
//                                 border: 1px solid #ddd;
//                                 padding: 8px;
//                                 white-space: nowrap; /* 모든 열 줄바꿈 방지 */
//                             }
//                             th {
//                                 background-color: #f4f4f4;
//                                 font-weight: bold;
//                             }
//                             tr:nth-child(even) {
//                                 background-color: #f9f9f9;
//                             }
//                             tr:hover {
//                                 background-color: #f1f1f1;
//                             }
//                             .filter-input {
//                                 width: 90%;
//                                 padding: 5px;
//                                 margin-bottom: 5px;
//                             }
//                         </style>
//                         <script>
//                             // 필터링 함수
//                             function filterTable(input, columnIndex) {
//                                 const filter = input.value.toLowerCase();
//                                 const table = input.closest("table");
//                                 const rows = table.querySelectorAll("tbody tr");
//                                 rows.forEach(row => {
//                                     const cell = row.cells[columnIndex];
//                                     if (cell && cell.textContent.toLowerCase().includes(filter)) {
//                                         row.style.display = "";
//                                     } else {
//                                         row.style.display = "none";
//                                     }
//                                 });
//                             }
//                         </script>
//                         <table class="custom-excel-table">
//                             <thead>
//                                 <tr>`;

//                     // 첫 번째 행(헤더) 처리
//                     const headers = jsonData[0];
//                     headers.forEach((header, index) => {
//                         htmlTable += `
//                             <th>
//                                 ${header || "Column " + (index + 1)}<br>
//                                 <input type="text" class="filter-input" onkeyup="filterTable(this, ${index})" placeholder="Filter ${header}">
//                             </th>`;
//                     });

//                     htmlTable += `</tr>
//                             </thead>
//                             <tbody>`;

//                     // 나머지 행(데이터) 처리
//                     jsonData.slice(1).forEach((row) => {
//                         htmlTable += "<tr>";
//                         headers.forEach((_, index) => {
//                             htmlTable += `<td>${row[index] || ""}</td>`;
//                         });
//                         htmlTable += "</tr>";
//                     });

//                     htmlTable += `</tbody>
//                         </table>`;

//                     // 표 삽입 로직 (재시도 포함)
//                     const insertTable = (content) => {
//                         if (typeof tinymce !== "undefined" && tinymce.activeEditor) {
//                             tinymce.activeEditor.execCommand("mceInsertContent", false, content);
//                         } else {
//                             // TinyMCE 로드 지연 시 재시도
//                             setTimeout(() => insertTable(content), 100);
//                         }
//                     };

//                     // 최종 HTML 테이블 삽입
//                     insertTable(htmlTable);
//                 } catch (error) {
//                     // 에러 발생 시 사용자에게 알림
//                     alert("오류가 발생했습니다. 파일을 다시 확인해주세요.\n" + error.message);
//                 }
//             };

//             // 파일 읽기 시작
//             reader.readAsArrayBuffer(file);
//         });
//     });
// });

jQuery(document).ready(function ($) {
    // "테이블 추가" 버튼 클릭 이벤트
    $("#insert_table_button").on("click", function () {
        // 파일 선택 팝업 생성 및 초기화
        const input = $("<input>", {
            type: "file",
            accept: ".xls,.xlsx",
        }).trigger("click");

        input.on("change", function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                try {
                    const data = new Uint8Array(event.target.result);
                    const workbook = XLSX.read(data, { type: "array" });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

                    // HTML 테이블 생성
                    const headers = jsonData[0];
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
                                white-space: nowrap;
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
                            function filterTable(input, columnIndex) {
                                const filter = input.value.toLowerCase();
                                const table = input.closest("table");
                                const rows = table.querySelectorAll("tbody tr");
                                rows.forEach(row => {
                                    const cell = row.cells[columnIndex];
                                    row.style.display = cell && cell.textContent.toLowerCase().includes(filter) ? "" : "none";
                                });
                            }
                        </script>
                        <table class="custom-excel-table">
                            <thead>
                                <tr>`;

                    // 헤더 생성
                    headers.forEach((header, index) => {
                        htmlTable += `
                            <th>
                                ${header || `Column ${index + 1}`}<br>
                                <input type="text" class="filter-input" onkeyup="filterTable(this, ${index})" placeholder="Filter ${header}">
                            </th>`;
                    });

                    htmlTable += `</tr></thead><tbody>`;

                    // 데이터 행 생성
                    jsonData.slice(1).forEach((row) => {
                        htmlTable += "<tr>";
                        headers.forEach((_, index) => {
                            htmlTable += `<td>${row[index] || ""}</td>`;
                        });
                        htmlTable += "</tr>";
                    });

                    htmlTable += `</tbody></table>`;

                    // 테이블 삽입 함수
                    const insertTable = (content) => {
                        if (typeof tinymce !== "undefined" && tinymce.activeEditor) {
                            tinymce.activeEditor.execCommand("mceInsertContent", false, content);
                        } else {
                            setTimeout(() => insertTable(content), 100);
                        }
                    };

                    // 최종 테이블 삽입
                    insertTable(htmlTable);
                } catch (error) {
                    alert(`오류가 발생했습니다: ${error.message}`);
                }
            };

            // 파일 읽기 시작
            reader.readAsArrayBuffer(file);
        });
    });
});
