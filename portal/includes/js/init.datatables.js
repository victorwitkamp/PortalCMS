/*
 * Copyright Victor Witkamp (c) 2020.
 */

document.addEventListener("DOMContentLoaded", function () {
    new DataTable("#example", {
        scrollX: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/2.3.8/i18n/nl-NL.json"
        },
        paging: false,
        ordering: true,
        order: [[1, "asc"]],
        compact: true,
        select: true
    });
});
