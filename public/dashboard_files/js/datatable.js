var initDatatable;
var initDatatable2;
var removeOldButtonsStyles;

$(function (){


    var dt_manage_columns_btn   = 'Column visibility';
    var dt_copy_btn             = 'Copy';
    var dt_export_btn           = 'Excel';
    var dt_csv_btn              = 'CSV';
    var dt_pdf_btn              = 'PDF';

    if ($('.dt_copy_btn').length) {
        dt_copy_btn = $('.dt_copy_btn').val();
    }

    if ($('.dt_export_btn').length) {
        dt_export_btn = $('.dt_export_btn').val();
    }

    if ($('.dt_manage_columns_btn').length) {
        dt_manage_columns_btn = $('.dt_manage_columns_btn').val();
    }

    if ($('.dt_csv_btn').length) {
        dt_csv_btn = $('.dt_csv_btn').val();
    }

    if ($('.dt_pdf_btn').length) {
        dt_pdf_btn = $('.dt_pdf_btn').val();
    }




    var buttonsList = [
            /*{
                text: dt_manage_columns_btn,
                extend: 'colvis',
                className: 'btn btn-success'
            },*/
            {
                text: dt_export_btn,
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                className: 'btn bg-purple glyphicon glyphicon-file print-btn',
                charset: 'utf-8',
                bom: true
            },
            /*{
                text : dt_copy_btn,
                extend: 'copyHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                className: 'btn btn-success'
            },*/
            /*{
                text : dt_csv_btn,
                extend: 'csvHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                className: 'btn btn-success'
            },*/
            /*{
                text : dt_pdf_btn,
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: 'visible',
                    orthogonal: "myExport"
                },
                className: 'btn bg-purple glyphicon glyphicon-file print-btn',
                charset: 'UTF-8',
                bom: true
            },*/
        ];



    removeOldButtonsStyles = function(){

        if($('.dt-buttons').length)
        {
            $('.dt-buttons').find('.dt-button').removeClass('dt-button');
        }

    };

    initDatatable = function (){

        if($('.table_with_buttons').length == 0 ){
            return false;
        }

        var t = $('.table_with_buttons').DataTable({
            dom: 'Bfrtip',
            buttons: buttonsList
        });

        t.on('order.dt search.dt', function () {
            t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        $('.toggle_columns a.toggle-vis').on('click', function (e) {
            e.preventDefault();

            // Get the column API object
            var column = t.column($(this).attr('data-column'));

            // Toggle the visibility
            column.visible(!column.visible());
        });

        removeOldButtonsStyles();

    };

    initDatatable();


    initDatatable2 = function (){

        if($('.table_with_buttons_without_paging').length == 0 ){
            return false;
        }

        var t2 = $('.table_with_buttons_without_paging').DataTable({
            dom: 'Bfrtip',
            searching: false,
            "paging": false,
            "info": false,
            buttons: buttonsList
        });

        t2.on('order.dt search.dt', function () {
            t2.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        $('.toggle_columns a.toggle-vis').on('click', function (e) {
            e.preventDefault();

            // Get the column API object
            var column = t2.column($(this).attr('data-column'));

            // Toggle the visibility
            column.visible(!column.visible());
        });

        removeOldButtonsStyles();

    };
    initDatatable2();




});
