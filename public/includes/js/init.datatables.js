/* global $ */
$(document).ready(function () {
    $('#example').DataTable({
        //scrollX: !0,
        "scrollX": true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Dutch.json'
        },
        paging: false,
        ordering: !0
    })
});
