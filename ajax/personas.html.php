<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php

global $a_modulos_permisos_Cliente;
printear('$a_modulos_permisos_Client222');
printear($a_modulos_permisos_Cliente);

printear('PERMISOS_MOD_CONFIGURACIONES_EDITAR');
printear(PERMISOS_MOD_CONFIGURACIONES_EDITAR);
$_SESSION['filtro']['tipo'] = 'Personas';
?>


<?php include_once APP_PATH . '/controllers/personas.php'; ?>

<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; ?>
<?php $Filtro_Form_Action = "ajax/" . basename(__FILE__); ?>

<!-- Bread crumb is created dynamically -->
<div class="row"><!-- row -->

    <div class="col-xs-8 col-sm-7 col-md-7 col-lg-4"><!-- col -->
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-user"></i>
            <?php echo _('Personas') ?>
            <span>>
                <?php echo _('Listado de Personas') ?>
            </span>

        </h1>
    </div><!-- end col -->
    <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 50) { ?>

        <div class="col-xs-4 col-sm-5 col-md-5 col-lg-8" id="sparkscontainer">

            <div id="sparks">
                <!-- PERSONA-->
                <button
                        class="btn btn-sm btn-primary"
                        type            ="button"
                        data-backdrop   ="static"
                        data-toggle     ="modal"
                        data-target     ="#editar"
                        data-type       ="view"
                        data-lnk        ="ajax/persona-editar.html.php"
                        onclick="EditarPersona('view',0,'ajax/persona-editar.html.php')">
                    <?php echo _('Agregar Persona') ?>
                </button>

            </div>

        </div>

    <?php } ?>

</div><!-- end row -->


<!-- widget grid -->
<section id="widget-grid" class="">

    <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() == 10): ?>

    <div class="row" id="filterWidget" style="display: none;">
        <?php else: ?>
        <div class="row" id="filterWidget">
            <?php endif; ?>
            <?php require_once APP_PATH . '/includes/widgets/widget_filtro_personas.html.php'; ?>
        </div>
        <!-- row -->
        <div class="row">

            <!-- NEW WIDGET START -->
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false"
                     data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false"
                     data-widget-sortable="false">

                    <!-- HEADER -->
                    <header>
                        <span class="widget-icon"> <i class="fa fa-user"></i> </span>
                        <h2>
                            <?php echo _('Listado de Personas') ?>
                        </h2>
                    </header>


                    <div><!-- widget div-->

                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox"><!-- This area used as dropdown edit box --></div>

                        <!-- widget content -->
                        <div class="widget-body no-padding">

                            <table id="dt_basic" class="table table-striped table-hover dataTable no-footer"
                                   aria-describedby="dt_basic_info" style="width: 100%;">

                                <thead>
                                    <tr>
                                        <th data-priority="1"><?php echo _('Foto') ?></th>
                                        <th data-priority="2"><?php echo _('Legajo') ?></th>
                                        <th data-priority="3"><?php echo _('Apellido, Nombre') ?></th>
                                        <th data-priority="4"><?php echo _('Horario de Trabajo') ?></th>

                                        <th data-priority="5"><?php echo _('DNI') ?></th>
                                        <th data-priority="6"><?php echo _('E-mail') ?></th>
                                        <th data-priority="7"><?php echo _('Grupos') ?></th>

                                        <th data-priority="3" style="width:150px;"><?php echo _('Opciones') ?></th>

                                    </tr>
                                </thead>


                                <tbody>
                                <?php if (!is_null($o_Listado)): ?>

                                    <?php foreach ($o_Listado as $key => $item): ?>

                                        <tr style="<?php if ($item->getEnabled()){}
                                        else{echo "color:#aaa;";}?>">

                                            <!-- IMAGEN -->
                                            <td>
                                                <div class="mediumImageThumb" title="<?php echo 'ID: '.$item->getId(); ?>">
                                                    <?php
                                                    if ($item->getImagen() == '') {
                                                        echo '<img style="border-radius: 50%;" src="https://static.enpuntocontrol.com/app/v1/img/avatars/male-big.png" />';
                                                    }
                                                    else {
                                                        echo '<img style="border-radius: 50%;" src="imagen.php?per_id=' . $item->getId() . '&size=60" />';
                                                    } ?></div>
                                            </td>
                                            <!-- LEGAJO -->
                                            <td>
                                                <?php echo $item->getLegajo(); ?>
                                            </td>
                                            <!-- NOMBRE -->
                                            <td>
                                                <?php
                                                $_nombre = $item->getApellido() . ', ' . $item->getNombre();
                                                echo mb_convert_case($_nombre, MB_CASE_TITLE, "UTF-8"); ?>

                                            </td>
                                            <!-- HORARIO -->
                                            <?php switch ($item->getHorTipo()) {
                                                case HORARIO_NORMAL: ?>
                                                    <td>
                                                        <!--Normal:-->
                                                        <?php
                                                        $_horario = Hora_Trabajo_L::obtenerPorId($item->getHorId())->getDetalle();
                                                        echo mb_convert_case($_horario, MB_CASE_TITLE, "UTF-8");
                                                        ?>
                                                    </td>
                                                    <?php break;
                                                case HORARIO_FLEXIBLE: ?>
                                                    <td>
                                                        <!--Flexible:-->
                                                        <?php
                                                        $_horario = Horario_Flexible_L::obtenerPorId($item->getHorId())->getDetalle();
                                                        echo mb_convert_case($_horario, MB_CASE_TITLE, "UTF-8");
                                                        ?>
                                                    </td>
                                                    <?php break;
                                                case HORARIO_ROTATIVO: ?>
                                                    <td>
                                                        <!--Rotativo:-->
                                                        <?php
                                                        $_horario =  Horario_Rotativo_L::obtenerPorId($item->getHorId())->getDetalle();
                                                        echo mb_convert_case($_horario, MB_CASE_TITLE, "UTF-8");
                                                        ?>
                                                    </td>
                                                    <?php break;
                                                case HORARIO_MULTIPLE: ?>
                                                    <td>
                                                        <!--Múltiple:-->
                                                        <?php
                                                        $_horario = Horario_Multiple_L::obtenerPorId($item->getHorId())->getDetalle();
                                                        echo mb_convert_case($_horario, MB_CASE_TITLE, "UTF-8");
                                                        ?>
                                                    </td>
                                                    <?php break;

                                                default: //cuando no tiene horario ?>
                                                    <td>
                                                        Sin Horario
                                                    </td>
                                                    <?php break;
                                            } ?>


                                            <!-- DNI -->
                                            <td>
                                                <?php
                                                echo number_format($item->getDni(), 0, ',', '.');



                                                //echo $item->getDni(); ?>
                                            </td>
                                            <!-- EMAIL  -->
                                            <td>
                                                <?php
                                                if($item->getEmail() != '')
                                                    echo $item->getEmail();
                                                else
                                                    echo 'Sin Correo'
                                                ?>

                                            </td>
                                            <!-- GRUPOS -->
                                            <td><?php
                                                $a_Grupos_Personas = Grupos_Personas_L::obtenerARRAYPorPersona($item->getId());
                                                if(is_null($a_Grupos_Personas)){
                                                    echo '-';
                                                }else {
                                                    $a_o_Grupos = Grupo_L::obtenerTodos();
                                                    if ($a_o_Grupos != null) {
                                                        $salida = '';
                                                        foreach ($a_o_Grupos as $o_Grupo) {
                                                            if (in_array($o_Grupo->getId(), $a_Grupos_Personas))
                                                                $salida .= $o_Grupo->getDetalle() . " - ";
                                                        }
                                                        $salida = rtrim($salida, " - ");
                                                        echo $salida;
                                                    }
                                                }
                                                ?>
                                            </td>


                                            <!-- OPCIONES -->
                                            <td style="white-space: nowrap;">

                                                <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 10) { ?>


                                                    <!-- HUELLAS -->
                                                    <button
                                                            id              ="<?php echo $item->getId(); ?>"
                                                            title           ="Administrar Huellas"
                                                            class=          "btn btn-default btn-sm fa fa-hand-pointer-o fa-lg"                                                            type            ="button"
                                                            data-backdrop   ="static"
                                                            data-toggle     ="modal"
                                                            data-target     ="#editar"
                                                            data-type       ="view"
                                                            data-id         ="<?php echo $item->getId(); ?>"
                                                            data-lnk        ="ajax/persona-editar-fp.html.php"
                                                            onclick         ="EditarPersona('view',<?php echo $item->getId(); ?> ,'ajax/persona-editar-fp.html.php')">
                                                    </button>

                                                    <!-- TAG -->
                                                    <button
                                                            title           ="Administrar Tarjeta RFID"
                                                            class           ="btn btn-default btn-sm fa fa-tag fa-lg"
                                                            type            ="button"
                                                            data-backdrop   ="static"
                                                            data-toggle     ="modal"
                                                            data-target     ="#editar"
                                                            data-type       ="view"
                                                            data-id         ="<?php echo $item->getId(); ?>"
                                                            data-lnk        ="ajax/persona-editar-tag.html.php"
                                                            onclick         ="EditarPersona('view',<?php echo $item->getId(); ?>,'ajax/persona-editar-tag.html.php')">
                                                    </button>

                                                    <!-- PERSONA-->
                                                    <button
                                                            class           ="btn btn-default btn-sm fa fa-edit fa-lg"
                                                            type            ="button"
                                                            data-backdrop   ="static"
                                                            data-toggle     ="modal"
                                                            data-target     ="#editar"
                                                            data-type       ="view"
                                                            data-id         ="<?php echo $item->getId(); ?>"
                                                            data-lnk        ="ajax/persona-editar.html.php"
                                                            onclick         ="EditarPersona('view',<?php echo $item->getId(); ?>,'ajax/persona-editar.html.php')">
                                                    </button>


                                                <?php } ?>


                                                <!--  SOLO ADMINS -->
                                                <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 999) { ?>


                                                    <!-- BUTTON DELETE-->
                                                    <button title       ="<?php echo _('Eliminar') ?>"
                                                            data-type   ="delete"
                                                            data-id     ="<?php echo $item->getId(); ?>"
                                                            class       ="btn btn-default btn-sm fa fa-trash-o fa-lg"
                                                            style       ="line-height: .75em;"
                                                            onclick     ="EliminarPersona(<?php   echo $item->getId(); ?>)">
                                                    </button>




                                                <?php } ?>

                                            </td>

                                        </tr>
                                    <?php endforeach; ?>


                                <?php else: ?>
                                <?php endif; ?>

                                </tbody>
                            </table>

                        </div>
                        <!-- end widget content -->
                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->

            </article>
            <!-- WIDGET END -->
        </div>
        <!-- end row -->
</section>
<!-- end widget grid -->


<?php
//INCLUYO los view/edit etc de los cosos
require_once APP_PATH . '/templates/edit-view_modal.html.php';
?>


<script type="text/javascript">

    // VARIABLES
    var contadorTimeout = 0;
    var contadorPulls = 0;
    var equiposOK = [];

    // PAGE
    pageSetUp();

    if ($('.DTTT_dropdown.dropdown-menu').length) {
        $('.DTTT_dropdown.dropdown-menu').remove();
    }

    <?php require_once APP_PATH . '/includes/data_tables_otros.js.php'; ?>

    // ASYNC CALLS, MODAL
    $(document).ready(function () {

        // CONTROLLER FUNCTIONS EXECUTION
        <?php echo $T_sync_checker; ?>
        <?php echo $T_sync_js_start; ?>

        //MODAL
        /*
        $('a[data-toggle=modal], button[data-toggle=modal]').click(function () {

            var data_id = '';
            var lnk = '';
            var view_type = '';
            if (typeof $(this).data('id') !== 'undefined') {
                data_id = $(this).data('id');
            }
            if (typeof $(this).data('lnk') !== 'undefined') {
                lnk = $(this).data('lnk');
            }
            if (typeof $(this).data('type') !== 'undefined') {
                view_type = $(this).data('type');
            }

            console.log('data_id');
            console.log(data_id);
            console.log('lnk');
            console.log(lnk);
            console.log('view_type');
            console.log(view_type);
            //$('.modal-content').html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Cargando...</h1></div>");



            $.ajax({
                cache: false,
                type: 'POST',
                url: lnk,
                data: {tipo: view_type, id: data_id},
                success: function (data) {
                    console.log('data');
                    console.log(data);
                    $('.modal-content').show().html(data);
                }
            });

        });
*/
    });



    // PERSONAS
    function EditarPersona(view_type,data_id,url) {


        $('.modal-content').html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Cargando...</h1></div>");
        $.ajax({
            cache: false,
            type: 'POST',
            url: url,
            data: {
                tipo: view_type,
                id: data_id
            },
            success: function (data) {
                $('.modal-content').show().html(data);
            }
        });
    }

    // PERSONAS
    function BloquearPersona(Persona) {
        loadURLwData(
                    '<?php echo $Item_Name . 's' ?>',
                    $('#content'),
                    {
                        tipo: 'disable',
                        id: Persona
                    }
        );
    }
    function DesBloquearPersona(Persona) {
        loadURLwData('<?php echo $Item_Name . 's' ?>', $('#content'), {tipo: 'enable', id: Persona});
    }

    // FILAS
    function getRow(table_id, arg1) {
        var oTable = $('#' + table_id).dataTable(), data = oTable.fnGetData(), row, i, l = data.length;
        for (i = 0; i < l; i++) {
            row = data[i];

            // columns to search are hard-coded, but you could easily pass this in as well
            if ($.inArray(arg1, row) >= 0) {
                return $('#' + table_id + ' tr').eq(i + 1);
            }
        }
        return false;
    }
    function disableRow(perNombre) {

        //   $row = getRow('dt_basic', perNombre);

        //   $row.addClass('text-muted');
        //   $row.children("td").children("button").addClass('disabled');
       // $row.children("td:last").append("<h4 class=\"ajax-loading-animation\" style=\"display: inline;\"><i class=\"fa fa-cog fa-spin\"></i></h4>");
    }
    function enableRow(perNombre) {
        $row = getRow('dt_basic', perNombre);

        //     $row.removeClass('text-muted');
        //     $row.children("td").children("button").removeClass('disabled');
        //    $row.children("td").children("h4").fadeOut(300);
    }

    // ELIMINAR
    $('a[data-type=delete], button[data-type=delete]').click(function () {
        var data_id = '';
        var lnk = '';
        var view_type = '';
        if (typeof $(this).data('id') !== 'undefined') {
            data_id = $(this).data('id');
        }
        if (typeof $(this).data('lnk') !== 'undefined') {
            lnk = $(this).data('lnk');
        }
        if (typeof $(this).data('type') !== 'undefined') {
            view_type = $(this).data('type');
        }
        ExistMsg = 0;//por un error en el plugin smartmessagebox  http://myorange.ca/supportforum/question/smartmessagebox-not-working-after-page-reload-smartmsgboxcount-not-reset


        $.SmartMessageBox({
            title: "Eliminar <?php echo $T_Titulo_Singular; ?>",
            content: "Está por eliminar <?php echo $T_Titulo_Pre; ?> <?php echo $T_Titulo_Singular; ?>.</br>Esta operación no se puede deshacer. ¿Desea continuar?",
            buttons: '[No][Si]'
        }, function (ButtonPressed) {
            if (ButtonPressed === "Si") {
                //esto refresca la pagina
                SmartUnLoading();
                loadURLwData('<?php echo $Item_Name . 's' ?>', $('#content'), {tipo: view_type, id: data_id});
            }
            else if (ButtonPressed === "No") {
                SmartUnLoading();
            }

        });


    });






    // SYNC CHECK
    function syncChecker(perID, perNombre, cantEquipos) {

        $.ajax({
            cache: false,
            type: 'POST',
            url: 'pspull.php',
            data: {id: perID, cmd: 'CMD_ACK', type:'TYPE_PERSON'},

            success: function (data) {

                console.log(data);

                // SYNC ERROR (NO DATA)
                if(     data == ''      ||      data.indexOf('Fatal error:') >= 1     ){


                    // PULL COUNTER
                    contadorPulls = 0;

                    // CONSOLE LOG
                    console.log('timeout PS Fatal Error');

                    // DISMISS ALERT CLICK
                    $('button[data-dismiss="alert"]').click();

                    // BOTCLOSE CLICK
                    $("[id^=botClose]").click();

                    // MESSAGE BOX: SYNC ERROR
                    $.bigBox({
                        title: "No se sincronizaron los datos!",
                        content: "Algunos equipos no responden, los datos se sincronizarán automáticamente cuando los equipos vuelvan a conectarse.</br>",
                        color: "#C46A69",
                        timeout: 6000,
                        icon: "fa fa-warning shake animated",
                        sound: false
                    });

                    // ENABLE ROW
                    enableRow(perNombre);
                    equiposOK=[];

                    return;
                }


                // DATA TO JSON: MENSAJE
                var mensaje = JSON.parse(data);

                // CONSOLE LOG
                console.log('mensaje:',mensaje);


                // DEVICE (DUPLICATED) CONFIRMATION
                if($.inArray(mensaje.attributes.uuid, equiposOK) > -1){
                    console.log('vino un mensaje del mismo equipo, descartado...');
                }

                // DEVICE CONFIRMATION: SYNCK SUCCESSFULL (SYNC RECALL IF EQUIPOS>1)
                if(     mensaje.data[0].id == perID       &&      $.inArray(mensaje.attributes.uuid, equiposOK) == -1     ){

                    // EQUIPOS OK: ADD UUID
                    equiposOK.push(mensaje.attributes.uuid);


                    // CONSOLE LOGS
                    console.log('persona',mensaje.data[0].id);
                    console.log('status',mensaje.data[0].result);
                    console.log('equipo',mensaje.attributes.uuid);


                    // SYNC CHECKER (FUNCTION RECALL)
                    if(cantEquipos>1){
                        console.log('mas de un equipo. iniciando chequeo nuevamente');
                        cantEquipos--;
                        syncChecker(perID, perNombre, cantEquipos);
                    }

                    // END OF SYNC CHECKER
                    else{

                        //ENABLE ROW
                        enableRow(perNombre);

                        // CONTADOR PULLS
                        contadorPulls = 0;

                        // DISMISS ALERT CLICK
                        $('button[data-dismiss="alert"]').click();

                        // BOTCLOSE CLICK
                        $("[id^=botClose]").click();

                        // MESSAGE BOX: SYNC SUCCESS
                        $.bigBox({
                            title: "Datos sincronizados!",
                            content: "Se han sincronizado los cambios en todos los equipos.</br>",
                            color: "#739E73",
                            timeout: 6000,
                            icon: "fa fa-check shake animated",
                            //number : "2"
                            sound: false
                        });
                        equiposOK=[];
                    }

                }


                else{

                    // CONSOLE LOG
                    console.log('detectado mensaje que no es ack y persona');

                    // TRY AGAIN (SYNC CHECKER)
                    if (contadorPulls < 3) {
                        syncChecker(perID, perNombre, cantEquipos);
                        contadorPulls++;
                    }

                    // DO NOT TRY AGAIN (TIMEOUT)
                    else {

                        // CONSOLE LOG
                        console.log('timeout de intentos');

                        // DISMISS ALERT CLICK
                        $('button[data-dismiss="alert"]').click();

                        // BOTCLOSE CLICK
                        $("[id^=botClose]").click();

                        // MESSAGE BOX: SYNC ERROR
                        $.bigBox({
                            title: "No se sincronizaron los datos!",
                            content: "Algunos equipos no responden, los datos se sincronizarán automáticamente cuando los equipos vuelvan a conectarse.</br>",
                            color: "#C46A69",
                            timeout: 6000,
                            icon: "fa fa-warning shake animated",
                            sound: false
                        });

                        // ENABLE ROW
                        enableRow(perNombre);

                        // CLEAR EQUIPOS_OK ARRAY
                        equiposOK = [];
                    }
                }

            }
        });
    }

</script>




