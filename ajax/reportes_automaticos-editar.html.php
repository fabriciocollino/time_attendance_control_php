<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/reportes_automaticos.php'; ?>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title"
        id="modalTitle"><?php if ($o_Notificacion->getId() == null) echo _("Agregar Reporte Automático"); else echo _("Editar Reporte Automático"); ?></h4>
</div>
<div class="modal-body" style="padding-top: 0px;">


    <form class="smart-form" novalidate="novalidate" data-async method="post" id="editar-form"
          action="<?php echo 'ajax/reportes_automaticos.html.php' ?>?tipo=<?php if ($o_Notificacion->getId() == null) echo "add"; else echo "edit&id=" . $o_Notificacion->getId(); ?>">


        <!-- DATOS GENERALES -->
        <fieldset>

            <!-- LEYENDA -->
            <legend>Detalle</legend>

            <!-- DETALLE -->
            <div class="row">
                <section class="col col-10" style="width: 100%">
                    <label class="input"> <i class="icon-prepend fa fa-bullhorn"></i>
                        <input required type="text" name="detalle" placeholder="Descripción"
                               value="<?php echo htmlentities($T_Detalle, ENT_COMPAT, 'utf-8'); ?>" />
                    </label>
                    </label>
                </section>
            </div>

        </fieldset>

        <!-- DESTINATARIOS -->
        <fieldset id="fieldDestino">

            <!-- LEYENDA -->
            <legend>Destinatarios</legend>

            <!-- DESTINATARIO: USUARIO, GRUPO, PERSONA -->
            <div class="row">

                <!-- TIPO DE DESTINATARIO -->
                <section class="col col-6">
                    <label class="select"> <span class="icon-prepend fa fa-users"></span>
                        <select id="destinatario_tipo" name="destinatario_tipo" style="padding-left: 32px;">
                            <?php echo $T_Destinatario_Tipo; ?>
                        </select> <i></i> </label>
                </section>

                <!-- USUARIO -->
                <section class="col col-6" id="destinatario_usuario"  >
                    <label class="select"> <span class="icon-prepend fa fa-users"></span>
                        <select id="sel_destinatario_usuario" name="destinatario_usuario" style="padding-left: 32px;">
                            <?php echo $T_Destinatario_Usuarios; ?>
                        </select> <i></i> </label>
                </section>

                <!-- GRUPO -->
                <section class="col col-6" id="destinatario_grupo">
                    <label  class="select"> <span class="icon-prepend fa fa-users"></span>
                        <select id="sel_destinatario_grupo" name="destinatario_grupo" style="padding-left: 32px;">
                            <?php echo $T_Destinatario_Grupos; ?>
                        </select> <i></i> </label>
                </section>


            </div>

            <!-- DESTINATARIO: OTRO EMAIL -->
            <div class="row">

                <!-- EMAIL-->
                <section class="col col-6" >
                    <label class="input"><span class="icon-prepend fa fa-asterisk"></span>
                        <input type="email" id="email_me" placeholder="Otra dirección de email" name="email_me" style="padding-left: 32px;"  value="<?php echo $T_Email_Me; ?>" />
                        <i></i>
                    </label>
                </section>

            </div>
            <!-- FRECUENCIA, FECHA Y HORA -->
            <div class="row">

                <!-- FRECUENCIA -->
                <section class="col col-6" id="trRepetir">
                    <label class="select"> <span class="icon-prepend fa fa-bolt"></span>
                        <select required name="repetir" id="selRepetir" style="padding-left: 32px;">
                            <?php echo $T_Repetir; ?>
                        </select> <i></i> </label>
                </section>

                <!-- FECHA HORA -->
                <section class="col col-6" id="trFecha">
                    <label class="input"> <span class="icon-prepend fa fa-clock-o"></span>
                        <input required class="form-control " name="hora" id="hora" type="text"
                               placeholder="Fecha y Hora de Inicio" value="<?php echo $T_Hora ?>" />
                    </label>
                </section>
            </div>

        </fieldset>


        <!-- REPORTE -->
        <fieldset>

            <!-- LEYENDA -->
            <legend>Reporte</legend>

            <!-- TIPO DE REPORTE -->
            <div class="row">
                <section class="col col-6" id="trReportec">
                    <label class="select"> <span class="icon-prepend fa fa-play"></span>
                        <select required name="reportec" style="padding-left: 32px;">
                            <?php echo $T_Reportec; ?>
                        </select> <i></i> </label>
                </section>
            </div>

            <!-- FILTRO PERSONA/GRUPO -->
            <div class="row">

                <!-- PERSONA -->
                <section class="col col-6" id="trPersonac">
                    <label class="select"> <span class="icon-prepend fa fa-play"></span>
                        <select name="personac" id="selPersonac" style="padding-left: 32px;">
                            <?php echo $T_Personac; ?>
                        </select> <i></i> </label>

                </section>

                <!-- GRUPO -->
                <section class="col col-6" id="trRolc">
                    <label class="select"> <span class="icon-prepend fa fa-play"></span>
                        <select name="rolc" id="selRolc" style="padding-left: 32px;">
                            <?php echo $T_Grupoc; ?>
                        </select> <i></i> </label>
                </section>

            </div>

            <!-- INTERVALO -->
            <div class="row">
                <section class="col col-6" id="trIntervaloc">
                    <label class="select"> <span class="icon-prepend fa fa-hdd-o"></span>
                        <select  required name="intervaloc" id="selIntervaloc" style="padding-left: 32px;">
                            <?php echo $T_Intervaloc; ?>
                        </select> <i></i> </label>
                </section>
            </div>



        </fieldset>

        <!-- CONTENIDO -->
        <fieldset>

            <!-- LEYENDA -->
            <legend>Contenido</legend>

            <!-- ASUNTO -->
            <div class="row">
                <section class="col col-12" style="width: 100%" id="trDetallec">
                    <label class="input"> <i class="icon-prepend fa fa-envelope"></i>
                        <input required type="text" name="detallec" placeholder="Asunto"
                               value="<?php echo htmlentities($T_Detallec, ENT_COMPAT, 'utf-8'); ?>" />
                    </label>
                </section>
            </div>

            <!-- MENSAJE -->
            <div class="row">
                <section class="col col-10" style="width: 100%" id="trMensajec">
                    <label class="textarea textarea-resizable"> <i class="icon-prepend fa fa-envelope"></i>
                        <textarea name="mensajec" rows="5" placeholder="Mensaje"><?php echo htmlentities($T_Mensajec, ENT_COMPAT, 'utf-8');?></textarea>
                    </label>
                </section>
            </div>

            <!-- PDF, EXCEL, CSV -->
            <div class="row">
                <section class="col col-6" id="">
                    <label class="select"> <span class="icon-prepend fa fa-hdd-o"></span>
                        <select  required name="DescargarTipo" id="selDescargarTipo" style="padding-left: 32px;">
                            <?php echo $T_DescargarTipo; ?>
                        </select> <i></i> </label>
                </section>
            </div>


        </fieldset>
    </form>
</div>


<!-- FOOTER -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>
    <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar">
        <?php if ($o_Notificacion->getId() == null) echo _("Agregar"); else echo _("Guardar"); ?>
    </button>
</div>


<script type="text/javascript">

    $("#hora").datetimepicker({
        minDate: 'moment',
        locale: 'es',
        collapse: false,
        sideBySide: true,
        format: 'YYYY-MM-DD HH:mm:ss'
    });


    $('#destinatario_tipo').change(function () {
        <!-- USUARIO -->
        if ($(this).find('option:selected').attr('value') === 'Usuario') {
            $('#destinatario_usuario').show();
            $('#destinatario_grupo').hide();
            $('#sel_destinatario_usuario').attr('required' , true);
            $('#sel_destinatario_grupo').attr('required' , false);

            $("#destinatario_grupo").val('');
        }
        <!-- USUARIO -->
        else if ($(this).find('option:selected').attr('value') === 'Grupo') {
            $('#destinatario_usuario').hide();
            $('#destinatario_grupo').show();
            $('#sel_destinatario_usuario').attr('required' , false);
            $('#sel_destinatario_grupo').attr('required' , true);

            $("#destinatario_usuario").val('');
        }
        <!-- PERSONA -->
        else if (parseInt($(this).find('option:selected').attr('value')) > 0) {
            $('#destinatario_usuario').hide();
            $('#destinatario_grupo').hide();
            $('#sel_destinatario_usuario').attr('required' , false);
            $('#sel_destinatario_grupo').attr('required' , false);

            $("#destinatario_usuario").val('');
            $("#destinatario_grupo").val('');
        }

    });


    $('#selPersonac').change(function () {
        if ($(this).find('option:selected').attr('value') === 'SelectRol') {
            $('#trRolc').show();
        } else { //diferido
            $('#trRolc').hide();
            $("#selRolc").val('');
        }
    });


    $(document).ready(function () {

        $('#trRolc').hide();

        <?php if($i_Destinatario_Usuarios != 0 || $nuevo_reporte){ ?>
        $("#destinatario_tipo").val('Usuario');
        <?php }
        elseif ($i_Destinatario_Grupos != 0 ){ ?>
        $("#destinatario_tipo").val('Grupo');
        <?php }?>


        <?php if ($i_Grupoc != 0  ){ ?>
        $("#selPersonac").val('SelectRol');
        <?php }
        elseif ($i_Personac == 0){ ?>
        $("#selPersonac").val('TodasLasPersonas');
        <? } ?>


        $('#destinatario_tipo').trigger("change");
        $('#selPersonac').trigger("change");

        $('#submit-editar').click(function () {
            var $form = $('#editar-form');

            if (!$('#editar-form').valid()) {
                return false;
            } else {

                $.ajax({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),

                    success: function (data, status) {
                        $('#editar').modal('hide');
                        function refreshpage() {
                            $('#content').css({opacity: '0.0'}).html(data).delay(50).animate({opacity: '1.0'}, 300);
                            $('body').removeData('bs.modal');
                        }

                        setTimeout(refreshpage, 200);
                    }
                });
            }

        });

        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

    });


</script>
