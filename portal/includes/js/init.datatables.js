$(document).ready(function () {
    $("#example").DataTable({
        scrollX: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Dutch.json"
        },
        paging: !1,
        ordering: !0,
        compact: true,
        select: true
    })
});
