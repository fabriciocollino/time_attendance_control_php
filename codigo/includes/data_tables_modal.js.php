

loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/datatables/datatables.min.js", function () {


    $('#dt_basic_modal').DataTable({
        "aaSorting": [],
        "oLanguage": {"sUrl": "https://static.enpuntocontrol.com/app/v1/js/plugin/datatables/Spanish.json"},
        "autoWidth": true,
        "responsive": {
            details: {
                type: 'column',
                target: 'tr',
                renderer: function (api, rowIdx, columns) {
                    var data = $.map(columns, function (col, i) {
                        return col.hidden ?
                            '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                            '<td class="dt_responsive_col_title">' + col.title + ':' + '</td> ' +
                            '<td class="dt_responsive_col_data">' + col.data + '</td>' +
                            '</tr>' :
                            '';
                    }).join('');

                    return data ?
                        $('<table/>').append(data) :
                        false;
                }
            },
            type: "column"
        },
        buttons: [
        ],
        "fnInitComplete": function (oSettings, json) {
        },
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'lB>r>" +
            "t" +
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>"
    });

    <?php
    //$NoWrap=false;
    if(isset($NoWrap) && $NoWrap): ?>
    var pepe=0;
    $('#dt_basic_modal').on( 'responsive-resize.dt', function () {

        setTimeout(function () {
            if (!$(".addNoWrap").hasClass("nowrp")) {
                $(".addNoWrap").addClass("nowrp");
            }
        },10);

    });
    <?php endif;  ?>





});
	
	
