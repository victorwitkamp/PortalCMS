/*
 * Copyright Victor Witkamp (c) 2020.
 */

$(document).ready(function () {
    $("#example").DataTable({
        scrollX: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Dutch.json"
        },
        paging: true,
        ordering: !0,
        "order": [[ 1, 'asc' ]],
        compact: true,
        select: true,
    });
});
