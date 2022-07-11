<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/alertas.php'; ?>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title"
        id="modalTitle"><?php if ($o_Notificacion->getId() == null) echo _("Agregar Alerta"); else echo _("Editar Alerta"); ?></h4>
</div>
<div class="modal-body" style="padding-top: 0px;">


    <form class="smart-form" novalidate="novalidate" data-async method="post" id="editar-form"
          action="<?php echo 'ajax/alertas.html.php' ?>?tipo=<?php if ($o_Notificacion->getId() == null) echo "add"; else echo "edit&id=" . $o_Notificacion->getId(); ?>">

        <fieldset>
            <legend>Datos Generales</legend>
            <div class="row">
                <section class="col col-10" style="width: 100%">
                    <label class="input"> <i class="icon-prepend fa fa-bullhorn"></i>
                        <input required type="text" name="detalle" placeholder="Descripción"
                               value="<?php echo htmlentities($T_Detalle, ENT_COMPAT, 'utf-8'); ?>">
                    </label>
                </section>
            </div>

        </fieldset>

        <fieldset id="fieldDestino">


            <legend>Destinatario</legend>

            <!-- DESTINATARIO USUARIO -->
            <div class="row">
			    <section class="col col-6" id="section_destinatario_usuario"  >
                    <label class="select"> <span class="icon-prepend fa fa-users"></span>
                        <select id="destinatario_usuario" name="destinatario_usuario" style="padding-left: 32px;">
                            <?php echo $T_Destinatario_Usuario; ?>
                        </select> <i></i> </label>
                </section>
            </div>

            <!-- DESTINATARIO INPUT -->
            <div class="row">
                <section class="col col-6"  id="section_destinatario_email">
                    <label class="input"><span class="icon-prepend fa fa-asterisk"></span>
                        <input type="email" id="destinatario_email" placeholder="Correo electrónico" name="destinatario_email" style="padding-left: 32px;"  value="<?php echo $T_Email; ?>" />
                        <i></i> </label>
                </section>
            </div>

        </fieldset>


        <fieldset>
            <legend>Disparador</legend>


            <!-- DISPARADOR PERSONA -->
            <div class="row">
                <section class="col col-6" id="section_disparador_persona">
                    <label class="select"> <span class="icon-prepend fa fa-user"></span>
                        <select required name="disparador_persona" id="disparador_persona" style="padding-left: 32px;">
                            <?php echo $T_Disparador_Persona; ?>
                        </select> <i></i> </label>
                </section>
                <section class="col col-6" id="section_disparador_grupo">
                    <label class="select"> <span class="icon-prepend fa fa-user"></span>
                        <select name="disparador_grupo" id="disparador_grupo" style="padding-left: 32px;">
                            <?php echo $T_Disparador_Grupo; ?>
                        </select> <i></i> </label>
                </section>
            </div>

            <!-- DISPARADOR EVENTO -->
            <div class="row">
                <section class="col col-6" id="section_disparador_evento">
                    <label class="select"> <span class="icon-prepend fa fa-bolt"></span>
                        <select  required name="disparador_evento" id="disparador_evento" style="padding-left: 32px;">
                            <?php echo $T_Disparador_Evento; ?>
                        </select> <i></i> </label>
                </section>
            </div>


        </fieldset>


        <fieldset id="trMensajec">
            <legend>Contenido</legend>

            <!-- ASUNTO -->
            <div class="row" style="display:none;">
                <section class="col col-12" style="width: 100%" id="section_mensaje_asunto">
                    <label class="input"> <i class="icon-prepend fa fa-envelope"></i>
                        <input required type="text" id="mensaje_detalle" name="mensaje_detalle" placeholder="Asunto"
                               value="<?php echo htmlentities($T_Mensaje_Detalle, ENT_COMPAT, 'utf-8'); ?>" />
                    </label>
                </section>
            </div>

            <!-- MENSAJE -->
            <div class="row">
                <section class="col col-10" style="width: 100%" id="section_mensaje_cuerpo">
                    <label class="textarea textarea-resizable"> <i class="icon-prepend fa fa-envelope"></i>
                        <textarea name="mensaje_cuerpo" id="" rows="5" placeholder="Mensaje"><?php echo htmlentities($T_Mensaje_Cuerpo, ENT_COMPAT, 'utf-8'); ?></textarea>
                    </label>
                </section>
            </div>

        </fieldset>


    </form>


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>
    <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar">
        <?php if ($o_Notificacion->getId() == null) echo _("Agregar"); else echo _("Guardar"); ?>
    </button>
</div>


<style>
    .clockpicker-popover {
        z-index: 100000;
    }
</style>

<script type="text/javascript">



    $( "#myDiv" ).css( "border", "3px solid red" );

    $("#hora").datetimepicker({
        minDate: 'moment',
        locale: 'es',
        collapse: false,
        sideBySide: true,
        format: 'YYYY-MM-DD HH:mm:ss'
    });

    $('#disparador_persona').change(function () {

        if($('#disparador_persona').val()  === 'SelectRol') {
            $('#section_disparador_grupo').show();
        }
        else{
            $('#section_disparador_grupo').hide();
            $('#disparador_grupo').val('');
        }
    });




    $(document).ready(function () {

        $('#disparador_persona').trigger("change");

        $(function () {
            // Validation


            $("#editar-form").validate({
                // Rules for form validation
                rules: {
                    destinatario_usuario: {
                        required: true
                    },
                    disparador_persona: {
                        required: true
                    },
                    disparador_grupo: {
                        required: "#section_disparador_grupo:visible"
                    },
                    disparador_evento: {
                        required: true
                    }
                },
                // Messages for form validation
                messages: {
                    destinatario_usuario: {
                        required: "Seleccione un destinatario"
                    },
                    disparador_persona: {
                        required: "Seleccione un disparador"
                    },
                    disparador_grupo: {
                        required: "Seleccione un grupo"
                    },
                    disparador_evento: {
                        required: "Seleccione un evento"
                    }

                },

            });
        });


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
