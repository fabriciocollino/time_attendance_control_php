<?php

/*
 * Uso una funcion de datatatables propia, no la que viene con el template
 * utilizo los archivos datatables.min.js y datatables.min.css
 * el datatables.min.js ya esta pre-concatenado y tiene todos los plugins necesarios
 */

?>

loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/datatables/datatables.min.js", function () {
    $('#dt_basic').DataTable({
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
            <?php if(!isset($Botones_Exportar)): ?>
                {
                    extend: 'pdfHtml5',
                    text: '',
                    pageSize: "A4",
                    className: "buttons-pdf-save buttons-html5"
                },
                {
                    extend: 'excel',
                    text: '',
                    className: "buttons-excel buttons-html5"
                },
                {
                    extend: 'csv',
                    text: '',
                    className: "buttons-csv-save buttons-html5"
                }
            <?php endif; ?>
        ],

        "fnInitComplete": function (oSettings, json) {
            <?php

            if(isset($Botones_Exportar)){
                $_SESSION['filtro']['Guardar_Descargar'] = 'Descargar';
                $T_Tipo     =  isset($_SESSION['filtro']['tipo']) ? $_SESSION['filtro']['tipo'] : '';

            ?>

            $("#dt_basic_length").after("<div class=\"table_tools_group\" id=\"BotonesTabla\" style=\"float:right;\"></div>");


            $("#BotonesTabla").append(
                " <\script> "+
                "  function importar_Archivo() {"+
                "     $('.modal-content').html(\"<div style=\'padding:15px;height:75px;\'><h1 class=\'ajax-loading-animation\'><i class=\'fa fa-cog fa-spin\'></i> Cargando...<h1><div>\"); "+
                "     $.ajax({"+
                "           cache: false,"+
                "           type: 'POST',"+
                "           url: 'ajax/upload-editar.html.php',"+
                "           data: {"+
                "               tipo: '<?php echo $T_Tipo;?>'"+
                "           },"+
                "           success: function (data) {"+
                "               $('.modal-content').show().html(data);"+
                "          }"+
                "      });"+
                "  }"+
                "<\/script>");


            $("#BotonesTabla").append("<a id=\"BotonGuardarPDF\" href=\"<?php echo WEB_ROOT; ?>/descargar_adjunto.php?archivo_tipo=pdf \" title=\"Guardar PDF\" target=\"_blank\" class=\"btn btn-default btn-sm  .DTTT.btn-group fa fa-file-pdf-o\"></a>");
            $("#BotonesTabla").append("<a id=\"BotonGuardarCSV\" href=\"<?php echo WEB_ROOT; ?>/descargar_adjunto.php?archivo_tipo=xls \" title=\"Guardar Excel\" target=\"_blank\" class=\"btn btn-default btn-sm  .DTTT.btn-group fa fa-file-excel-o\"></a>");
            $("#BotonesTabla").append("<a id=\"BotonGuardarCSV\" href=\"<?php echo WEB_ROOT; ?>/descargar_adjunto.php?archivo_tipo=csv \" title=\"Guardar CSV\" target=\"_blank\" class=\"btn btn-default btn-sm  .DTTT.btn-group fa fa-floppy-o\"></a>");

            $('.buttons-pdf-save').addClass("fa fa-file-pdf-o");
            $('.buttons-excel').addClass("fa fa-file-excel-o");
            $('.buttons-csv-save').addClass("fa fa-floppy-o");


        <?php if($T_Tipo ==  'Registros'){  ?>

            $("#BotonesTabla").append(
                "               <a href=\"#\" class=\"btn btn-default btn-sm .DTTT.btn-group fa fa-ellipsis-v dropdown-toggle\" id=\"menu1\" data-toggle=\"dropdown\"> "+
                "               </a>"+
                "               <ul class=\"dropdown-menu pull-right\">"+
                "                   <li>"+
                "                       <a " +
                "                           id             =\"BotonGuardarCSV\""+
                "                           title          =\"Importar Excel\"" +
                "                           class          =\"DTTT.btn-group fa\"" +
                "                           data-backdrop  =\"static\"" +
                "                           data-toggle    =\"modal\"" +
                "                           data-target    =\"#editar\"" +
                "                           data-type      =\"view\"" +
                "                           data-lnk       =\"ajax/upload-editar.html.php\"" +
                "                           onclick        =\"importar_Archivo()\" >" +
                "                               Importar Excel"+
                "                       </a>" +
                "                   </li>"+
                "               </ul>");
            <?php } ?>


            <?php } ?>
        },
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'lB>r>" +
            "t" +
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>"
    });

    <?php if(isset($NoWrap) && $NoWrap): ?>
    var pepe=0;
    $('#dt_basic').on( 'responsive-resize.dt', function () {

        setTimeout(function () {
            if (!$(".addNoWrap").hasClass("nowrp")) {
                $(".addNoWrap").addClass("nowrp");
            }
        },1000);

    });
    <?php endif;  ?>




});
	
	
