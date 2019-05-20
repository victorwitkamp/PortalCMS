$(document).ready(function() {
    var table = $('#example')
    .DataTable({
        // columnDefs: [
        //     {
        //         targets: 0,
        //         className: 'dt-head-justify'
        //     }
        //   ],
        // "scrollX": true,
        // "autoWidth": true,
        "language": {
            "url": '//cdn.datatables.net/plug-ins/1.10.19/i18n/Dutch.json'
        },
        // responsive: true,
        // compact: true,
        "ordering": false,
        // processing: true
    })
} );
