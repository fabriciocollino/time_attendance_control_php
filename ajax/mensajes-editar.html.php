<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/' . 'mensajes' . '.php'; ?>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title"
        id="modalTitle"><?php if ($o_Mensaje->getId() == null) echo _("Agregar Mensaje"); else echo _("Editar Mensaje"); ?></h4>
</div>
<div class="modal-body" style="padding-top: 0px;">


    <form class="smart-form" novalidate="novalidate" data-async method="post" id="editar-form"
          action="<?php echo 'ajax/logs_mensajes.html.php' ?>?tipo=<?php if ($o_Mensaje->getId() == null) echo "add"; else echo "edit&id=" . $o_Mensaje->getId(); ?>">

		<input type="hidden" name="id" value="<?php echo $T_Id;?>" />
        <fieldset  >
            <legend>Datos Generales</legend>
            <div class="row">
                <section class="col col-6">
                    <label class="select"> <span class="icon-prepend fa fa-asterisk"></span>
                        <select name="tipon" id="" style="padding-left: 32px;">
                            <?php echo $T_Tipon; ?>
                        </select> <i></i> </label>
                </section>
            </div>
        </fieldset>

        <fieldset id="fieldDestino">
            <legend>Destinatarios</legend>
            <div class="row">
                <section class="col col-6">
                    <label class="select"> <span class="icon-prepend fa fa-users"></span>
                        <select id="destinatario_tipo" name="destinatario_tipo" style="padding-left: 32px;">
                            <?php echo $T_Destinatario_Tipo; ?>
                        </select> <i></i> </label>
                </section>

			    <section class="col col-6" id="destinatario_usuario"  >
                    <label class="select"> <span class="icon-prepend fa fa-users"></span>
                        <select id="sel_destinatario_usuario" name="destinatario_usuario" style="padding-left: 32px;">
                            <?php echo $T_Destinatario_Usuarios; ?>
                        </select> <i></i> </label>
                </section>
				<section class="col col-6" id="destinatario_grupo">
                    <label  class="select"> <span class="icon-prepend fa fa-users"></span>
                        <select id="sel_destinatario_grupo" name="destinatario_grupo" style="padding-left: 32px;">
                            <?php echo $T_Destinatario_Grupos; ?>
                        </select> <i></i> </label>
                </section>
				<section class="col col-6" id="destinatario_persona" >
                    <label class="select"> <span class="icon-prepend fa fa-users"></span>
                        <select  id="sel_destinatario_persona" name="destinatario_persona" style="padding-left: 32px;">
                            <?php echo $T_Destinatario_Personas; ?>
                        </select> <i></i> </label>
                </section>
				
            </div>
            
        </fieldset>
		
		
        <fieldset>
            <legend>Disparador</legend>
            <div class="row">
                <section class="col col-6">
                    <label class="select"> <span class="icon-prepend fa fa-asterisk"></span>
                        <select name="disparador_tipo" id="selDisparadorTipo" style="padding-left: 32px;">
                            <?php echo $T_DisparadorTipo; ?>
                        </select> <i></i> </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-6" id="trFecha">
                    <label class="input"> <span class="icon-prepend fa fa-clock-o"></span>
                        <input class="form-control " name="hora" id="hora" type="text"
                               placeholder="Fecha y Hora de Inicio" value="<?php echo $T_Hora ?>">
                    </label>
                </section>
            </div>
            
			
        </fieldset>


        <fieldset>
            <legend>Contenido</legend>
            <div class="row">
                <section class="col col-10" style="width: 100%" id="trTema">
                    <label class="input"> <i class="icon-prepend fa fa-envelope"></i>
                        <input required type="text" name="tema" placeholder="Tema"
                               value="<?php echo htmlentities($T_Tema, ENT_COMPAT, 'utf-8'); ?>">
                    </label>
                </section>
             
                <section class="col col-10" style="width: 100%" id="trMensajec">
                    <label class="textarea textarea-resizable"> <i class="icon-prepend fa fa-envelope"></i>
                        <textarea required name="mensajec" rows="5"
                                  placeholder="Mensaje"><?php echo htmlentities($T_Mensajec, ENT_COMPAT, 'utf-8'); ?></textarea>
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
        <?php if ($o_Mensaje->getId() == null) echo _("Agregar"); else echo _("Guardar"); ?>
    </button>
</div>


<style>
    .clockpicker-popover {
        z-index: 100000;
    }
</style>

<script type="text/javascript">
	$("#hora").datetimepicker({
        minDate: 'moment',
        locale: 'es',
        collapse: false,
        sideBySide: true,
        format: 'YYYY-MM-DD HH:mm:ss'
    });
	
    $("#hora").val('<?php echo $T_Hora ?>');
	
	$('#destinatario_persona').hide();
	$('#destinatario_tipo').change(function () {
        if ($(this).find('option:selected').attr('value') === 'Usuario') { // Users
			$('#destinatario_usuario').show();
			$('#destinatario_grupo').hide();
			// $('#destinatario_persona').hide();
        	$('#sel_destinatario_usuario').attr('required' , true);
			$('#sel_destinatario_grupo').attr('required' , false);
			// $('#sel_destinatario_persona').attr('required' , false);
        } else if ($(this).find('option:selected').attr('value') === 'Grupo') {// Grupos
			$('#destinatario_usuario').hide();
			$('#destinatario_grupo').show();
			// $('#destinatario_persona').hide();
        	$('#sel_destinatario_usuario').attr('required' , false);
			$('#sel_destinatario_grupo').attr('required' , true);
			// $('#sel_destinatario_persona').attr('required' , false);
        } else if (parseInt($(this).find('option:selected').attr('value')) > 0) {// Persona
			$('#destinatario_usuario').hide();
			$('#destinatario_grupo').hide();
			// $('#destinatario_persona').show();
			$('#sel_destinatario_usuario').attr('required' , false);
			$('#sel_destinatario_grupo').attr('required' , false);
			// $('#sel_destinatario_persona').attr('required' , true);
		} else {
			$('#destinatario_usuario').hide();
			$('#destinatario_grupo').hide();
			$('#destinatario_persona').hide();
        	$('#sel_destinatario_usuario').attr('required' , false);
			$('#sel_destinatario_grupo').attr('required' , false);
			$('#sel_destinatario_persona').attr('required' , false);
        }
    });



	$('#selDisparadorTipo').change(function () {
        if ($(this).find('option:selected').attr('value') === '0') {//Now
            $('#trFecha').hide();
        } else if ($(this).find('option:selected').attr('value') === '1') { //Programmed
            $('#trFecha').show();
        } else {
            $('#trFecha').hide();
        }
    });


    $(document).ready(function () {
        //$('#editar').on('shown.bs.modal', function() {

        // $('#trDisparador').hide();
        // $('#selRol').hide();
		
		$('#destinatario_tipo').trigger("change");
		$('#selDisparadorTipo').trigger("change");
       

		
		<?php if($i_DisparadorTipo == 0 ): ?>
			$('#trFecha').hide();
		<?php elseif($i_DisparadorTipo == 1 ): ?>
			$('#trFecha').show();
        <?php endif; ?>




        // $('#selTipoNotificacion').trigger("change");
        
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
