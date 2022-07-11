<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php global $T_Tipo;
$T_Tipo = 'Marcaciones';
$_SESSION['filtro']['tipo'] = $T_Tipo;
?>
<?php require_once APP_PATH . '/controllers/reportes.php';
?>
<?php $Filtro_Form_Action = "ajax/" . basename(__FILE__); ?>

<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; ?>

<div class="row" id="topRowEditableContainer"></div>
<div class="row" id="topRowPopoverContainer"></div>


<div class="row">

    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <i class="fa-fw fa fa-list-alt"></i>
            <?php echo _('Reportes') ?>
            <span>>
                <?php echo _('Marcaciones') ?>
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
                        <?php echo _('Marcaciones') ?>
                    </h2>

                    <div id="selTemplate" class="widget-toolbar" role="menu">
                    </div>
                </header>


                <div>
                    <div class="jarviswidget-editbox">
                    </div>

                    <div class="widget-body no-padding">

                        <table id="dt_basic"
                               class="table table-striped table-hover dataTable no-footer"
                               aria-describedby="dt_basic_info"
                               style="width: 100%;">

                            <thead>
                            <th data-priority="1"><?php echo _('Foto') ?></th>
                            <th data-priority="2"><?php echo _('Legajo') ?></th>
                            <th data-priority="3"><?php echo _('Nombre') ?></th>

                            <th data-priority="4"><?php echo _('Fecha') ?></th>
                            <th data-priority="5"><?php echo _('Horario') ?></th>

                            <th data-priority="7"><?php echo _('Entrada') ?></th>
                            <th data-priority="8"><?php echo _('Salida') ?></th>

                            <th data-priority="9"><?php echo _('Horas') ?></th>

                            </thead>

                            <tbody class="addNoWrap">
                            <?php if (!is_null($o_Listado)){ ?>
                                <?php foreach ($o_Listado as $per_Id => $item){?>

                                    <tr>

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
                                        <table class="tablainterna no-footer" aria-describedby="dt_basic_info">

                                            <tbody>

                                            <?php
                                            foreach ($item['logs'] as $key_log => $log){ ?>

                                                <tr class="nowrp">

                                                    <td>
                                                        <?php echo date("d-m-Y", strtotime($log['Fecha_Inicio'])); ?>
                                                    </td>

                                                </tr>

                                            <?php } ?>

                                            </tbody>
                                        </table>
                                    </td>


                                    <!-- HORARIO -->
                                    <td>
                                        <table class="tablainterna no-footer" aria-describedby="dt_basic_info">

                                            <tbody>
                                            <?php
                                            foreach ($item['logs'] as $key_log => $log){ ?>

                                                <tr class="nowrp">
                                                    <td>
                                                        <?php echo $log['Hora_Trabajo_Inicio']." - ".$log['Hora_Trabajo_Fin']; ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </td>


                                    <!-- ENTRADA -->
                                    <td>
                                        <table class="tablainterna no-footer" aria-describedby="dt_basic_info">

                                            <!-- DATA -->
                                            <tbody>

                                            <?php

                                            foreach ($item['logs'] as $key_log => $log){ ?>

                                                <tr class="nowrp">

                                                    <!-- TIME IN -->
                                                    <td data-pk     = "<?php echo $log['Log_Id_Inicio']; ?>"
                                                        <?php if($log['Log_Editado_Inicio'] == 1){?> class ="log_editado"<?php } ?>>

                                                        <?php echo $log['Hora_Inicio']; ?>
                                                    </td>


                                                    <!-- DEVICE IN -->
                                                    <td>
                                                        <?php echo $log['Equipo_Inicio']; ?>
                                                    </td>

                                                    <!-- EDIT IN -->
                                                    <td>
                                                        <a href             = "#"
                                                           id               = "agregar_registro"
                                                           type             = "button"
                                                           data-backdrop    = "static"
                                                           data-toggle      = "modal"
                                                           data-target      = "#editar"

                                                           data-type        = "view"
                                                           data-persona     = "<?php echo $per_Id; ?>"
                                                           data-id          = "<?php echo $log['Log_Id_Inicio']; ?>"
                                                           data-fecha       = "<?php echo $log['Fecha_Inicio'] ; ?>"
                                                           data-hora        = "<?php echo $log['Hora_Inicio']; ?>"
                                                           data-lnk         = "ajax/logs_equipos-editar.html.php">

                                                            <i class="fa fa-pencil" title="Editar"></i>
                                                        </a>
                                                    </td>

                                                </tr>

                                            <?php } ?>

                                            </tbody>
                                        </table>


                                    </td>

                                    <!-- SALIDA -->
                                    <td>
                                        <table class="tablainterna no-footer" aria-describedby="dt_basic_info">

                                            <!-- DATA -->
                                            <tbody>

                                            <?php

                                            foreach ($item['logs'] as $key_log => $log){ ?>

                                                <tr class="nowrp">

                                                    <!-- TIME OUT -->
                                                    <td data-pk     = "<?php echo $log['Log_Id_Fin']; ?>"
                                                        <?php if($log['Log_Editado_Fin'] == 1){?> class ="log_editado"<?php } ?>>

                                                        <?php echo $log['Hora_Fin'] == '' ? '-': $log['Hora_Fin'] ?>
                                                    </td>

                                                    <!-- DEVICE IN -->
                                                    <td>
                                                        <?php echo $log['Equipo_Fin'] == '' ? '': $log['Equipo_Fin'] ?>
                                                    </td>

                                                    <!-- EDIT OUT -->
                                                    <td>
                                                        <a href="#"
                                                           id               = "agregar_registro"
                                                           type             = "button"
                                                           data-backdrop    = "static"
                                                           data-toggle      = "modal"
                                                           data-target      = "#editar"
                                                           data-type        = "view"
                                                           data-persona     = "<?php echo $per_Id; ?>"
                                                           data-id          = "<?php echo $log['Log_Id_Fin']; ?>"
                                                           data-fecha       = "<?php
                                                           if($log['Log_Id_Fin'] == ''){
                                                               echo $log['Fecha_Trabajo_Fin'];
                                                           }
                                                           else{
                                                               echo $log['Fecha_Fin'];
                                                           }
                                                           ?>"
                                                           data-hora        = "<?php
                                                           if($log['Log_Id_Fin'] == ''){
                                                               echo $log['Hora_Trabajo_Fin'];
                                                           }
                                                           else{
                                                               echo $log['Hora_Fin'];
                                                           }
                                                           ?>"
                                                           data-lnk         = "ajax/logs_equipos-editar.html.php">

                                                            <i class="fa fa-pencil" title="Editar"></i>
                                                        </a>
                                                    </td>

                                                </tr>

                                            <?php } ?>

                                            </tbody>
                                        </table>


                                    </td>





                                    <!-- HORAS TRABAJADAS -->
                                    <td>
                                        <table class="tablainterna no-footer" aria-describedby="dt_basic_info">

                                            <!-- DATA -->
                                            <tbody>
                                            <?php
                                            foreach ($item['logs'] as $key_log => $log){ ?>

                                                <tr class="nowrp">

                                                    <td>
                                                        <?php echo $log['Total_Intervalo']; ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </td>




                                <?php } ?>
                                </tr>
                            <?php } ?>
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
    $NoWrap = 1;
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
