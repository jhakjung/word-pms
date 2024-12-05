jQuery(document).ready(function ($) {
    $("#insert_table_button").on("click", function () {
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

                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

                let htmlTable = `
                    <style>
                        table.custom-table {
                            border-collapse: collapse;
                            width: 100%;
                            margin: 5px 0;
                            font-size: 16px;
                            text-align: center;
                        }
                        table.custom-table th, table.custom-table td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            white-space: nowrap;
                        }
                        table.custom-table th {
                            background-color: #f4f4f4;
                            font-weight: bold;
                            position: relative;
                        }
                        table.custom-table tr:nth-child(even) {
                            background-color: #f9f9f9;
                        }
                        table.custom-table tr:hover {
                            background-color: #f1f1f1;
                        }
                    </style>
                    <table class="custom-table" border="1">
                        <thead>
                            <tr>`;

                const headers = jsonData[0];
                headers.forEach((header, index) => {
                    htmlTable += `
                        <th>
                            ${header || "Column " + (index + 1)}
                        </th>`;
                });

                htmlTable += `</tr></thead><tbody>`;

                jsonData.slice(1).forEach(row => {
                    htmlTable += "<tr>";
                    headers.forEach((_, index) => {
                        htmlTable += `<td>${row[index] || ""}</td>`;
                    });
                    htmlTable += "</tr>";
                });

                htmlTable += "</tbody></table>";

                if (typeof tinymce !== "undefined" && tinymce.activeEditor) {
                    tinymce.activeEditor.execCommand("mceInsertContent", false, htmlTable);
                } else {
                    const textEditor = $("#content");
                    const currentContent = textEditor.val();
                    textEditor.val(currentContent + "\n" + htmlTable);
                }
            };

            reader.readAsArrayBuffer(file);
        });
    });
});
