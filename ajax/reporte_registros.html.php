<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php global $T_Tipo;
$T_Tipo = 'Registros';
$_SESSION['filtro']['tipo'] = $T_Tipo; ?>
<?php require_once APP_PATH . '/controllers/reportes.php'; ?>

<?php $Filtro_Form_Action = "ajax/reporte_registros.html.php" ;?>

<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; ?>

<div class="row" id="topRowEditableContainer"></div>
<div class="row" id="topRowPopoverContainer"></div>


<div class="row">

    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <i class="fa-fw fa fa-list-alt"></i>
            <?php echo _('Reportes') ?>
            <span>>
                <?php echo _('Registros') ?>
						</span>
        </h1>
    </div>


</div>

<section id="widget-grid" class="">


    <div class="row">
        <?php require_once APP_PATH . '/includes/widgets/widget_filtro_personas.html.php'; ?>
    </div>

    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false"
                 data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-sortable="false">

                <header>
                    <span class="widget-icon">
                        <i class="fa fa-list-alt"></i>
                    </span>

                    <h2>
                        <?php echo _('Registros') ?>
                    </h2>

                    <div id="selTemplate" class="widget-toolbar" role="menu">
                    </div>
                </header>


                <div>
                    <div class="jarviswidget-editbox">
                    </div>

                    <div class="widget-body no-padding">

                        <table id                   ="dt_basic"
                               class                ="table table-striped table-hover dataTable no-footer"
                               aria-describedby     ="dt_basic_info"
                               style="width: 100%;">

                            <thead>
                                <th data-priority="1"><?php echo _('Foto') ?></th>
                                <th data-priority="2"><?php echo _('Legajo') ?></th>
                                <th data-priority="3"><?php echo _('Nombre') ?></th>
                                <th data-priority="4"><?php echo _('Fecha') ?></th>
                                <th data-priority="5"><?php echo _('Horario') ?></th>
                                <th data-priority="6"><?php echo _('Hora') ?></th>
                                <th data-priority="7"><?php echo _('Equipo') ?></th>
                                <th data-priority="8"><?php echo _('Opciones') ?></th>
                            </thead>

                            <tbody class="addNoWrap">

                            <?php if (!is_null($o_Listado)){
                                ?>
                                <?php foreach ($o_Listado as $per_Id => $item){

                                    foreach ($item['logs'] as $key_log => $log){ ?>

                                    <tr class="nowrp">

                                            <!-- IMAGEN -->
                                            <td>
                                                <div class="mediumImageThumb">
                                                    <?php
                                                    if ($item['per_Imagen'] == '') {
                                                        echo '<img style="border-radius: 50%;" src="https://static.enpuntocontrol.com/app/v1/img/avatars/male-big.png" />';
                                                    } else {
                                                        echo '<img style="border-radius: 50%;" src="imagen.php?per_id=' . $per_Id . '&size=60" />';
                                                    }
                                                    ?>
                                                </div>
                                            </td>

                                            <!-- LEGAJO -->
                                            <td>
                                                <?php echo $item['per_Legajo'] ?>
                                            </td>

                                            <!-- NOMBRE -->
                                            <td>
                                                <?php echo mb_convert_case($item['per_Apellido'].", ".$item['per_Nombre'], MB_CASE_TITLE, "UTF-8"); ?>
                                            </td>


                                            <!-- FECHA -->
                                            <td>
                                                <?php echo date("d-m-Y", strtotime($log['Fecha'])); ?>
                                            </td>


                                            <!-- HORARIO -->
                                            <td>
                                                <?php echo $log['Hora_Trabajo_Inicio']." - ".$log['Hora_Trabajo_Fin']; ?>
                                            </td>


                                            <!-- HORA REGISTRO -->
                                            <td data-pk     = "<?php echo $log['Log_Id']; ?>"
                                                <?php if($log['Log_Editado'] == 1){?> class ="log_editado"<?php } ?>>

                                                <?php echo $log['Hora']; ?>
                                            </td>


                                            <!-- EQUIPO REGISTRO -->
                                            <td>
                                                <?php echo $log['Equipo']; ?>
                                            </td>

                                                <!-- EDITAR REGISTRO -->
                                            <td style="white-space: nowrap;">
                                                <button
                                                        class           ="btn btn-default btn-sm fa fa-edit fa-lg"
                                                        type            ="button"
                                                        data-backdrop   ="static"
                                                        data-toggle     ="modal"
                                                        data-target     ="#editar"
                                                        id              = "agregar_registro"

                                                        data-type       = "view"
                                                        data-persona    = "<?php echo $per_Id; ?>"
                                                        data-id         = "<?php echo $log['Log_Id']; ?>"
                                                        data-fecha      = "<?php echo $log['Fecha'] ; ?>"
                                                        data-hora       = "<?php echo $log['Hora']; ?>"
                                                        data-lnk        = "ajax/logs_equipos-editar.html.php">
                                                </button>


                                            <!-- BUTTON DELETE-->
                                                <button
                                                        class           ="btn btn-default btn-sm fa fa-trash-o fa-lg"
                                                        type            ="button"
                                                        data-backdrop   ="static"
                                                        id              ="eliminar_registro"

                                                        data-type       = "delete"
                                                        data-id         = "<?php echo $log['Log_Id']; ?>"
                                                        onclick         = "EliminarRegistro(<?php echo $log['Log_Id']; ?>)">
                                                </button>

                                           </td>


                                    <?php } ?>
                                </tr>
                            <?php }
                            }
                            ?>
                            </tbody>

                        </table>

                    </div>
                </div>



            </div>
        </article>
    </div>
</section>

<?php
require_once APP_PATH . '/templates/edit-view_modal.html.php';
?>

<script type="text/javascript">


    pageSetUp();

    if ($('.DTTT_dropdown.dropdown-menu').length) {
        $('.DTTT_dropdown.dropdown-menu').remove();
    }


    loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/clockpicker/clockpicker.min.js");


    <?php
    //INCLUYO el js de las datatables
    $NoWrap = 0;
    require_once APP_PATH . '/includes/data_tables_reportes.js.php';
    ?>


    $('.log_editado').attr('tabindex', '0');


    $('.log_editado').click(function() {

        var e=$(this);
        var originalHeight;
        var originalWidth;
        $.ajax({
            url: 'ajax/logs_web-mini.html.php?tipo=detallesdelogeditado&id=' + e.data('pk'),
            type: 'GET',
            beforeSend: function() {
                e.prop('title', 'Detalles del registro');
                e.popover({container: '#topRowPopoverContainer', placement: 'top', html: true, trigger: 'focus' ,content: '<div id="listaDeLogs"><img src="https://static.enpuntocontrol.com/app/v1/img/loading.gif" width="30" /></div>'}).popover('show');
                var pop = $('.popover');
                originalHeight = pop.height();
                originalWidth = pop.width();
                $('.popover-title').append('<button id="popoverclose" onclick="$(this).closest(\'div.popover\').popover(\'hide\');" type="button" class="close">&times;</button>');

            },
            complete: function() {

            },
            success: function(result) {
                $("#listaDeLogs").html(result);
                //e.prop('title', 'Este registro ha sido editado'); //pongo el titulo como estaba antes
                var pop = $('.popover');
                var newHeight = pop.height();
                var newWidth = pop.width();
                var top = parseFloat(pop.css('top'));
                var left = parseFloat(pop.css('left'));
                var changeInHeight = newHeight - originalHeight;
                var changeInWidth = newWidth - originalWidth;

                pop.css({ top: top - changeInHeight, left: left - (changeInWidth / 2) });
            }
        });

    });


    // PERSONAS
    function EliminarRegistro(registroID) {

        $.SmartMessageBox({
            title: "Eliminar Registro",
            content: "Está por eliminar el registro. Esta operación no se puede deshacer. ¿Desea continuar?" ,
            buttons: '[No][Si]'
        }, function (ButtonPressed) {
            if (ButtonPressed === "Si") {
                //esto refresca la pagina
                //loadURLwData('feriados', $('#content'), {tipo: view_type, id: data_id});


                $.ajax({
                    type: 'POST',
                    url: 'ajax/logs_equipos-editar.html.php',
                    data: {
                        tipo:   'delete',
                        id:     registroID
                    },
                    success: function (data, status) {
                        location.reload();
                    },
                    error : function(xhr, ajaxOptions, thrownError) {
                        //container.html('<h4 style="margin-top:10px; display:block; text-align:left"><i class="fa fa-warning txt-color-orangeDark"></i> Error 404! Page not found.</h4>');
                    }

                });

            }
            else if (ButtonPressed === "No") {
                SmartUnLoading();
            }

        });


    };



    <!-- MODAL AJAX -->
    $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {


        var data_id         = '';
        var lnk             = '';
        var view_type       = '';
        var persona_id      = '';
        var log_fecha       = '';
        var log_hora        = '';

        if (typeof $(this).data('id') !== 'undefined') {
            data_id = $(this).data('id');
        }
        if (typeof $(this).data('lnk') !== 'undefined') {
            lnk = $(this).data('lnk');
        }
        if (typeof $(this).data('type') !== 'undefined') {
            view_type = $(this).data('type');
        }
        if (typeof $(this).data('persona') !== 'undefined') {
            persona_id = $(this).data('persona');
        }
        if (typeof $(this).data('fecha') !== 'undefined') {
            log_fecha = $(this).data('fecha');
        }
        if (typeof $(this).data('hora') !== 'undefined') {
            log_hora = $(this).data('hora');
        }


        $('.modal-content').html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Cargando...</h1></div>");
        $.ajax({
            cache: false,
            type: 'POST',
            url: lnk,
            data: {tipo: view_type, id: data_id, persona: persona_id, fecha: log_fecha, hora: log_hora},
            success: function (data) {
                $('.modal-content').show().html(data);
            }
        });

    });

</script>
