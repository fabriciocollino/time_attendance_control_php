<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '-editar.html.php') . '.php'; ?>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="modalTitle">
        <?php
            if ($T_Id == 0)
                echo _("Agregar Registro");
            else
                echo _("Editar Registro");
        ?>
    </h4>
</div>
<div class="modal-body" style="padding-top: 0;">

    <form class="smart-form"
          novalidate="novalidate"
          data-async
          method="post"
          id="editar-form"
          action="<?php echo 'ajax/logs_equipos-editar.html.php' ?>?tipo=<?php if ($o_Log->getId() == 0) echo "add"; else echo "edit"; ?>">


        <!-- SENDER -->
        <input type         ="hidden"
               name         ="id"
               value        ="<?php if ($o_Log->getId() != 0) echo $o_Log->getId(); else echo 0; ?>">

        <!-- SENDER -->
        <input type         ="hidden"
               name         ="persona"
               value        ="<?php if ($T_Persona != 0) echo $T_Persona; else echo 0; ?>">

        <!-- SENDER -->
        <input type         ="hidden"
               name         ="persona"
               value        ="<?php if ($T_Persona != 0) echo $T_Persona; else echo 0; ?>">

        <fieldset>
            <legend>Datos</legend>

            <!-- PERSONA / GRUPO -->
            <div class="row">


                <!-- PERSONA -->
                <section class="col col-6">

                    <label class="select">
                        Persona
                    </label>

                    <label class="select">

                        <span class="icon-prepend fa fa-user">
                        </span>

                        <select name="persona" id="persona" style="padding-left: 32px;"
                            <?php  if($T_Persona != 0){ ?> disabled="disabled"<?php } ?>  >

                            <?php
                                if($T_Persona == 0){
                                    echo HtmlHelper::array2htmloptions(Persona_L::obtenerDesdeFiltro(Filtro_L::get_filtro_persona()), '', true, true, 'PersonayRegistro','Seleccione una Persona');
                                }
                                else{
                                    echo HtmlHelper::array2htmloptions(Persona_L::obtenerDesdeFiltro(Filtro_L::get_filtro_persona()), $T_Persona, true, true, 'PersonayRegistro','Seleccione una Persona');
                                }
                            ?>
                        </select>
                        <i></i>

                    </label>

                </section>

                <!-- GRUPO -->
                <section class="col col-6" id="trGrupo">
                    <br>
                    <label class="select">
                        <span class="icon-prepend fa fa-user"></span>
                        <select name="grupo" id="grupo" style="padding-left: 32px;">
                            <?php echo HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos(), '', true, true, 'GrupoPersonas', _('Seleccione un Grupo')); ?>
                        </select> <i></i> </label>
                </section>
            </div>

            <!-- DIA Y HORA  -->
            <div class="row">
                <section class="col col-6">

                    <!-- DÍA -->
                    <label class="select">
                        Día
                    </label>

                    <div class="form-group">
                        <div class="input-group">
                            <input class        ="form-control "
                                   style        ="padding-left: 5px; font-size: 12px; height: 31px;"
                                   name         ="fecha"
                                   id           ="fecha"
                                   type         ="text"
                                   placeholder  ="Fecha"
                                   autocomplete ="off"
                                   value        ="<?php
                                               if($o_Log->getId() == 0){
                                                   echo $T_Fecha;
                                               }
                                               else{
                                                   echo $o_Log->getFechaHora('Y-m-d');
                                               }
                                            ?>">

                            <span id="btnDesde" class="input-group-addon">
                                <i class="fa fa-calendar" style="cursor:pointer;line-height: 19px!important;padding-left: 5px;">
                                </i>
                            </span>
                        </div>

                    </div>
                </section>

                <!-- HORA -->
                <section class="col col-6">
                    <label class="select">
                        Hora
                    </label>

                    <div class="form-group">
                        <div class="input-group">
                            <input class        ="form-control "
                                   style        ="padding-left: 5px;font-size: 12px;height: 31px;"
                                   name         ="hora"
                                   id           ="hora"
                                   type         ="text"
                                   placeholder  ="Hora"
                                   autocomplete ="off"
                                   value        ="<?php
                                                       if($o_Log->getId() == 0){
                                                           echo '';
                                                       }
                                                       else{
                                                           echo $o_Log->getFechaHora('H:i:s');
                                                       }
                                                       ?>"
                                   style        ="text-align: center;"/>

                            <span style="cursor:pointer;" class="input-group-addon">
                                <i class="fa fa-clock-o" style="line-height:19px!important;padding-left: 5px;">
                                </i>
                            </span>
                        </div>
                        <div id="mensaje" style="color:red;display:none;">
							No es posible seleccionar un tiempo en el futuro.
						</div>
                    </div>

                </section>
            </div>
        </fieldset>


    </form>


</div>
<div class="modal-footer">
    <?php if ($o_Log->getId() != null){ ?>

        <button type="submit" class="btn btn-default" data-dismiss="modal" id="submit-delete">
            Eliminar
        </button>

    <?php } ?>

    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>
    <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar">
        <?php if ($o_Log->getId() == null) echo _("Guardar"); else echo _("Editar"); ?>
    </button>

</div>


<script type="text/javascript">


    $(document).ready(function () {


		$('#persona').trigger("change");
		$('#trGrupo').hide();

		$('#persona').change(function(){
			if ($(this).find('option:selected').attr('value') === 'SelectRol') {
				 $('#trGrupo').show();
				 $('#grupo').attr('required' , true);
			}else{
				 $('#trGrupo').hide();
				 $('#grupo').attr('required' , false);
			}
		});
		
        $(function () {
            // Validation


            $("#editar-form").validate({
                // Rules for form validation
                rules: {
                    persona: {
                        required: true
                    },
                    fecha: {
                        required: true
                    },
                    hora: {
                        required: true
                    }
                },
                // Messages for form validation
                messages: {
                    persona: {
                        required: '<?php echo _('Por favor seleccione una persona') ?>'
                    },
                    fecha: {
                        required: '<?php echo _('Por favor ingrese la fecha') ?>'
                    },
                    hora: {
                        required: '<?php echo _('Por favor ingrese la hora') ?>'
                    }
                },
                // Do not change code below
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                }
            });
        });


        $("#fecha").datepicker({
            //defaultDate: "+1w",
            changeMonth: true,
            dateFormat: "yy-mm-dd",
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            maxDate: new Date
        });

        $('#hora').clockpicker({
            placement: 'bottom',
            donetext: 'Aceptar',
            autoclose: 'true'
        });

        $('#submit-editar').click(function () {
			// abduls
			var $form = $('#editar-form');
			var d = new Date();
			
			if(Date.parse(formatDate(d.toDateString())+' '+d.getHours() + ":" + d.getMinutes()) < Date.parse($('#fecha').val() + ' ' + $('#hora').val())){

				$("#mensaje").show();
				setTimeout(function () {$("#mensaje").hide("slow")}, 1000);
				return false;
			}

			if (!$('#editar-form').valid()) {
                return false;
            }

			else {
                let fecha_log = $('#fecha').val() + ' ' + $('#hora').val() + ":00";

                if (isNaN(Date.parse(fecha_log)) !== false) {
                    console.log("Fecha mal formada");
                    return;
                }
				$.ajax({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    success: function (data, status) {

                        $('#editar').modal('hide');
						function refreshpage() {
                            location.reload();
                            $('body').removeData('bs.modal');
                        }

                        setTimeout(refreshpage, 200);
                    }
                });


            }

        });


        $('#submit-delete').click(function () {

            var $form = $('#editar-form');


            if (!$('#editar-form').valid()) {
                return false;
            }
            else {

                $.ajax({
                    type: $form.attr('method'),
                    url: 'ajax/logs_equipos-editar.html.php?tipo=delete',
                    data: $form.serialize(),
                    success: function (data, status) {

                        $('#editar').modal('hide');
                        function refreshpage() {
                            location.reload();
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


        $(window).keydown(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                return false;
            }
        });


    });
	
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

</script>
