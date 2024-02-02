$(document).ready(function () {
    $('.domain-table').DataTable({
        'paging': true,
        'pagingType': 'numbers',
        'ordering': true,
        'searching': true,
        'info': true,
        'lengthChange': true,
        'pageLength': 10,
        'order': [
            [0, 'asc']
        ],
        'columnDefs': [
            {
                'orderable': false,
                'targets': [5]
            }
        ],
        'language': {
            'searchPlaceholder': 'Domain, Created On, Status, etc.',
            'emptyTable': 'No data available in table',
            'infoEmpty': 'Showing 0 to 0 of 0 entries'
        },
    });

    $('.dataTables_filter input[type="search"]').css(
        {
            'min-width': '200px',
            'display': 'inline-block'
        }
    );
});
