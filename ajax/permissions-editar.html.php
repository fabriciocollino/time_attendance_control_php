<?php error_reporting(0);	?>
<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '-editar.html.php') . '.php';?>
<?php
// SET IF: EDIT VIEW OR CREATE VIEW
if( $T_Tipo== 'view' && $o_Permiso->getId() != 0){
    $persona_Editable=false;
}
else{
    $persona_Editable=true;
}
?>



<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title"
        id="modalTitle"><?php if ($o_Permiso->getId() == null) echo _("Agregar Permiso"); else echo _("Editar Permiso"); ?></h4>
</div>
<div class="modal-body" style="padding-top: 0;">

    <form class="smart-form" novalidate="novalidate" data-async method="post" id="editar-form"
          action="<?php echo 'ajax/' . $Item_Name . '.html.php' ?>?tipo=<?php if ($o_Permiso->getId() == null) echo "add"; else echo "edit&id=" . $o_Permiso->getId(); ?>">

        <!-- DATOS GENERALES: MOTIVO, ENABLE -->
        <fieldset>

            <legend>Datos Generales</legend>

            <!-- MOTIVO -->
            <div class="row">
                <section class="col col-10" style="width: 100%">
                    <label class="input"> <i class="icon-prepend fa fa-folder-o"></i>
                        <input type="text" name="motivo" placeholder="Motivo"
                               value="<?php echo htmlentities($o_Permiso->getMotivo(), ENT_COMPAT, 'utf-8'); ?>">
                    </label>
                </section>
            </div>

            <!-- ENABLE -->
            <div class="row" style="display:none;">
                <section class="col col-4">
                    <label class="toggle">
                        <input type="checkbox" id="enabled"
                               name="enabled" <?php if ($o_Permiso->getEnabled() == 1) echo "checked=\"yes\""; ?> >
                        <i data-swchon-text="SI" data-swchoff-text="NO"></i>Activa
                    </label>
                </section>
            </div>

        </fieldset>

        <!-- PERSONAS: TODAS LAS PERSONAS / PERSONA / GRUPO -->
        <fieldset>
            <!-- LEGEND -->
            <legend>
                Personas
            </legend>

            <!-- SELECT FILTER -->
            <div class="row">

                <!-- SELECT PERSONA -->
                <section class="col col-6"  >
                    <label class="select">
                        <span class="icon-prepend fa fa-user"></span>

                        <select name="persona" id="selPersona" style="padding-left: 32px;"
                            <?php if (!$persona_Editable) { echo "style='background-color:#d3d3d3;' disabled";} ?> >
                            <?php echo HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0, 0, 0, 'per_Eliminada=0 and per_Excluir=0'), $o_Permiso->getPerId(), true, true, 'PersonayGrupoLicencia', _('Todas las Personas'));?>
                        </select>
                        <i></i>

                        <!-- CASE 'EDIT' -->
                        <?php if (!$persona_Editable) {?>
                            <span>
                                * No editable
                                <input type="hidden" name="persona" value=" <?php echo $o_Permiso->getPerId();?> " >
                             </span>
                        <?php } ?>

                    </label>
                </section>

                <!-- SELECT GRUPO -->

                <section class="col col-6" id="selRol">
                    <label class="select"> <span class="icon-prepend fa fa-users"></span>
                        <select name="grupo" id="selGrupo" style="padding-left: 32px;">
                            <?php echo HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos(), $o_Permiso->getGrupoId(), true, true, '', _('Seleccione un Grupo')); ?>
                        </select> <i></i> </label>
                </section>
            </div>

        </fieldset>

        <!-- PERÍODO: TIPO, REPETITIVA, PERSONALIZADO -->
        <fieldset>
            <legend>Período</legend>

            <div class="row">

                <!-- SELECT TIPO -->
                <section class="col col-6" id="selTipo">
                    <label class="select"> <span class="icon-prepend fa fa-calendar"></span>
                        <select name="selTipo" id="selTipo" style="padding-left: 32px;">
                            <?php echo HtmlHelper::array2htmloptions($a_Permisos_Tipos, $o_Permiso->getTipo(), false, false); ?>
                        </select> <i></i> </label>
                </section>


            </div>

            <!-- REPETITIVA -->
            <div class="row" id="repetitiva" style="display:none !important;">
                <section class="col col-6" style="display:none !important;">
                    <label class="select"> <span class="icon-prepend fa fa-calendar"></span>
                        <select name="repetitiva" id="selSalidaTemprano" style="padding-left: 32px;">
                            <?php echo HtmlHelper::array2htmloptions($TiposdeLicenciasRepetitivas, $o_Permiso->getRepetitiva(), false, false); ?>
                        </select> <i></i> </label>
                </section>
                <section class="col col-6">
                    <span style="display:none;line-height: 32px;" id="descripcionRepetitiva">La licencia no se repite</span>
                </section>
            </div>


            <!-- SELECT INTERVALO: PERSONALIZADO -->
            <div class="row">

                <!-- INPUT INERVALO: LLEGADA TARDE, SALIDA TEMPRANO, DÍA COMPLETO -->
                <div id="intervaloLlegadaTarde">
                    <section class="col col-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="icon-prepend fa fa-calendar"></span>
                                <input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                       name="fechaLlegadaTarde" id="fechaLlegadaTarde" type="text" placeholder="Dia"
                                       value="<?php echo $o_Permiso->getFechaInicio(Config_L::p('f_fecha_corta')); ?>">
                                <span id="btnfechaLlegadaTarde" class="input-group-addon"><i class="fa fa-calendar"
                                                                                             style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i></span>
                            </div>
                            <div id="DIVfechaLlegadaTarde"></div>
                        </div>
                    </section>
                    <section class="col col-6">
                        <label class="select"> <span class="icon-prepend fa fa-calendar"></span>
                            <select name="intervaloLlegadaTarde" id="selLlegadaTarde" style="padding-left: 32px;">
                                <?php echo HtmlHelper::array2htmloptions($IntervalosLlegadaTardeLicencias, $o_Permiso->getDuracionFilterString(), false, false); ?>
                            </select> <i></i> </label>
                    </section>
                    <div id="duracionLlegadaTarde">
                        <section class="col col-2">
                            <label class="input">
                                <input type="text" name="duracionLlegadaTarde" id="duracionLlegadaTarde" placeholder=""
                                       value="<?php echo $o_Permiso->getDuracionNumber(); ?>">
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="select">
                                <select name="intervaloDuracionLlegadaTarde">
                                    <?php echo HtmlHelper::array2htmloptions($SelectorMinutosHoras, $o_Permiso->getDuracionMHFilterString(), false, false); ?>
                                </select> <i></i> </label>
                        </section>
                    </div>
                </div>

                <div id="intervaloSalidaTemprano">
                    <section class="col col-6">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                       name="fechaSalidaTemprano" id="fechaSalidaTemprano" type="text" placeholder="Dia"
                                       value="<?php echo $o_Permiso->getFechaInicio(Config_L::p('f_fecha_corta')); ?>">
                                <span id="btnfechaSalidaTemprano" class="input-group-addon"><i class="fa fa-calendar"
                                                                                               style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i></span>
                            </div>
                            <div id="DIVfechaSalidaTemprano"></div>
                        </div>
                    </section>
                    <section class="col col-6">
                        <label class="select"> <span class="icon-prepend fa fa-calendar"></span>
                            <select name="intervaloSalidaTemprano" id="selSalidaTemprano" style="padding-left: 32px;">
                                <?php echo HtmlHelper::array2htmloptions($IntervalosSalidaTempranoLicencias, $o_Permiso->getDuracionFilterString(), false, false); ?>
                            </select> <i></i> </label>
                    </section>
                    <div id="duracionSalidaTemprano">
                        <section class="col col-2">
                            <label class="input">
                                <input type="text" name="duracionSalidaTemprano" placeholder=""
                                       value="<?php echo $o_Permiso->getDuracionNumber(); ?>">
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="select">
                                <select name="intervaloDuracionSalidaTemprano">
                                    <?php echo HtmlHelper::array2htmloptions($SelectorMinutosHoras, $o_Permiso->getDuracionMHFilterString(), false, false); ?>
                                </select> <i></i> </label>
                        </section>
                    </div>
                </div>

                <div id="diaCompleto">
                    <section class="col col-6">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                       name="diaCompleto" id="fechaDiaCompleto" type="text" placeholder="Dia"
                                       value="<?php echo $o_Permiso->getFechaInicio(Config_L::p('f_fecha_corta')); ?>">
                                <span id="btndiaCompleto" class="input-group-addon"><i class="fa fa-calendar"
                                                                                       style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i></span>
                            </div>
                            <div id="DIVfechaDiaCompleto"></div>
                        </div>
                    </section>
                </div>


                <div id="fechaPersonalizada">
                    <section class="col col-6">
                        <label class="select">Desde</label>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                       name="LfechaD" id="fechaD" type="text" placeholder="Desde"
                                       value="<?php echo $o_Permiso->getFechaInicio(Config_L::p('f_fecha_corta')); ?>">
                                <span id="btnDesde" class="input-group-addon"><i class="fa fa-calendar"
                                                                                 style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i></span>
                            </div>
                            <div id="DIVfechaD"></div>
                        </div>
                    </section>
                    <section class="col col-6">
                        <label class="select">Hasta</label>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                       name="LfechaH" id="fechaH" type="text" placeholder="Hasta"
                                       value="<?php echo $o_Permiso->getFechaFin(Config_L::p('f_fecha_corta')); ?>">
                                <span id="btnHasta" class="input-group-addon"><i class="fa fa-calendar"
                                                                                 style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i></span>
                            </div>
                            <div id="DIVfechaH"></div>
                        </div>
                    </section>
                </div>

            </div>


        </fieldset>


    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>
    <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar">
        <?php if ($o_Permiso->getId() == null) echo _("Agregar"); else echo _("Guardar"); ?>
    </button>
</div>


<script type="text/javascript">

    <?php if ($persona_Editable) {   ?>
    $("#selPersona").val("TodasLasPersonas");
    // $("#selPersona").prop("selectedIndex", 0);
    <?php  } ?>

    $(document).ready(function () {

        /*

      $a_Licencias_Tipos = array(
          LICENCIA_LLEGADA_TARDE => 'Llegada Tarde',
          LICENCIA_SALIDA_TEMPRANO => 'Salida Temprano',
          LICENCIA_DIA_COMPLETO => 'Día Completo',
          LICENCIA_PERSONALIZADA => 'Personalizada'
      );


		$("#selTipo").find("option").eq(2).remove();
		$("#selTipo").find("option").eq(2).html("Ausencia");
 */

        $('#selFecha').change(function () {
            if ($(this).find('option:selected').attr('value') === 'F_Personalizado') {
                $('#fechaPersonalizada').show();
                <?php /*	$('#Fechas').hide(); */ ?>
            } else {
                $('#fechaPersonalizada').hide();
                <?php /*	$('#Fechas').show(); */ ?>
            }
        });
        $('#selPersona').change(function () {
            if ($(this).find('option:selected').attr('value') == 'SelectRol') {
                $('#selRol').show();
            } else {
                $('#selRol').hide();
            }
        });
        $('#selTipo').change(function () {
            if ($(this).find('option:selected').attr('value') == <?php echo PERMISO_LLEGADA_TARDE; ?>) {
                $('#intervaloLlegadaTarde').show();
                $('#intervaloSalidaTemprano').hide();
                $('#diaCompleto').hide();
                $('#fechaPersonalizada').hide();
                $('#repetitiva').hide();
            } else if ($(this).find('option:selected').attr('value') == <?php echo PERMISO_SALIDA_TEMPRANO; ?>) {
                $('#intervaloLlegadaTarde').hide();
                $('#intervaloSalidaTemprano').show();
                $('#diaCompleto').hide();
                $('#fechaPersonalizada').hide();
                $('#repetitiva').hide();
            } else if ($(this).find('option:selected').attr('value') == <?php echo PERMISO_DIA_COMPLETO; ?>) {
                $('#intervaloLlegadaTarde').hide();
                $('#intervaloSalidaTemprano').hide();
                $('#diaCompleto').show();
                $('#fechaPersonalizada').hide();
                $('#repetitiva').show();
            } else if ($(this).find('option:selected').attr('value') == <?php echo PERMISO_PERSONALIZADA; ?>) {
                $('#intervaloLlegadaTarde').hide();
                $('#intervaloSalidaTemprano').hide();
                $('#diaCompleto').hide();
                $('#fechaPersonalizada').show();
                $('#repetitiva').hide();
            } else {//seleccione un tipo
                $('#intervaloLlegadaTarde').hide();
                $('#intervaloSalidaTemprano').hide();
                $('#diaCompleto').hide();
                $('#fechaPersonalizada').hide();
                $('#repetitiva').hide();
            }
        });
        $('#selLlegadaTarde').change(function () {
            if ($(this).find('option:selected').attr('value') === 'F_Personalizado') {
                $('#duracionLlegadaTarde').show();
            } else {
                $('#duracionLlegadaTarde').hide();
            }
        });
        $('#selSalidaTemprano').change(function () {
            if ($(this).find('option:selected').attr('value') === 'F_Personalizado') {
                $('#duracionSalidaTemprano').show();
            } else {
                $('#duracionSalidaTemprano').hide();
            }
        });
        $('#repetitiva').change(function () {
            if ($(this).find('option:selected').attr('value') === '0') {//no repetir
                $('#descripcionRepetitiva').hide();
            } else if ($(this).find('option:selected').attr('value') === '1') { //todas las semanas
                var d = new Date($('#fechaDiaCompleto').val());
                var n = d.getDay();
                var weekday = new Array(7);
                weekday[0] = "Domingos";
                weekday[1] = "Lunes";
                weekday[2] = "Martes";
                weekday[3] = "Miércoles";
                weekday[4] = "Jueves";
                weekday[5] = "Viernes";
                weekday[6] = "Sábados";

                $('#descripcionRepetitiva').html('La licencia se repetirá todos los ' + weekday[n]);
                $('#descripcionRepetitiva').show();

            }
        });


        $('#selFecha').trigger("change");
        $('#selPersona').trigger("change");
        $('#selTipo').trigger("change");
        $('#selLlegadaTarde').trigger("change");
        $('#selSalidaTemprano').trigger("change");
        $('#repetitiva').trigger("change");
        // Date Range Picker

        $("#fechaD").datepicker({
            //defaultDate: "+1w",
            changeMonth: true,
            dateFormat: "yy-mm-dd",
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function (selectedDate) {
                $("#fechaH").datepicker("option", "minDate", selectedDate);
            }

        });
        $("#fechaH").datepicker({
            //defaultDate: "+1w",
            changeMonth: true,
            dateFormat: "yy-mm-dd",
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function (selectedDate) {
                $("#fechaD").datepicker("option", "maxDate", selectedDate);
            }
        });

        $("#fechaDiaCompleto").datepicker({
            //defaultDate: "+1w",
            changeMonth: true,
            dateFormat: "yy-mm-dd 00:00:00",
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>'
        });
        $("#fechaLlegadaTarde").datepicker({
            //defaultDate: "+1w",
            changeMonth: true,
            dateFormat: "yy-mm-dd 00:00:00",
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>'
        });
        $("#fechaSalidaTemprano").datepicker({
            //defaultDate: "+1w",
            changeMonth: true,
            dateFormat: "yy-mm-dd 00:00:00",
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>'
        });


        $('#btnDesde').click(function () {
            $(document).ready(function () {
                $("#fechaD").datepicker().focus();
            });
        });
        $('#btnHasta').click(function () {
            $(document).ready(function () {
                $("#fechaH").datepicker().focus();
            });
        });

        $('#btnfechaLlegadaTarde').click(function () {
            $(document).ready(function () {
                $("#fechaLlegadaTarde").datepicker().focus();
            });
        });
        $('#btnfechaSalidaTemprano').click(function () {
            $(document).ready(function () {
                $("#fechaSalidaTemprano").datepicker().focus();
            });
        });
        $('#btndiaCompleto').click(function () {
            $(document).ready(function () {
                $("#fechaDiaCompleto").datepicker().focus();
            });
        });

        $(function () {
            // Validation


            $("#editar-form").validate({
                // Rules for form validation
                rules: {
                    motivo: {
                        required: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    persona: {
                        required: true
                    },
                    grupo: {
                        required: "#selGrupo:visible"
                    },
                    selTipo: {
                        required: true
                    },
                    fechaLlegadaTarde: {
                        required: "#fechaLlegadaTarde:visible"
                    },
                    fechaSalidaTemprano: {
                        required: "#fechaSalidaTemprano:visible"
                    },
                    duracionLlegadaTarde: {
                        required: "#duracionLlegadaTarde:visible"
                    },
                    duracionSalidaTemprano: {
                        required: "#duracionSalidaTemprano:visible"
                    },
                    diaCompleto: {
                        required: "#fechaDiaCompleto:visible"
                    },
                    LfechaD: {
                        required: "#fechaD:visible"
                    },
                    LfechaH: {
                        required: "#fechaH:visible"
                    }
                },
                // Messages for form validation
                messages: {
                    motivo: {
                        required: '<?php echo _('Por favor ingrese el motivo del permiso') ?>',
                        minlength: '<?php echo _('El motivo es muy corto') ?>',
                        maxlength: '<?php echo _('El motivo es muy largo') ?>'
                    },
                    persona: {
                        required: "Por favor seleccione un item"
                    },
                    grupo: {
                        required: "Seleccione un grupo"
                    },
                    selTipo: {
                        required: "Seleccione un tipo"
                    },
                    fechaLlegadaTarde: {
                        required: "Seleccione una fecha"
                    },
                    fechaSalidaTemprano: {
                        required: "Seleccione una fecha"
                    },
                    duracionLlegadaTarde: {
                        required: "Seleccione una duración"
                    },
                    duracionSalidaTemprano: {
                        required: "Seleccione una duración"
                    },
                    diaCompleto: {
                        required: "Seleccione un día"
                    },
                    LfechaD: {
                        required: "Seleccione una fecha"
                    },
                    LfechaH: {
                        required: "Seleccione una fecha"
                    }
                },
                // Do not change code below
                errorPlacement: function (error, element) {
                    if (element.attr("name") === "fechaLlegadaTarde") {
                        error.insertAfter("#DIVfechaLlegadaTarde");
                    }
                    else if (element.attr("name") === "fechaSalidaTemprano") {
                        error.insertAfter("#DIVfechaSalidaTemprano");
                    }
                    else if (element.attr("name") === "diaCompleto") {
                        error.insertAfter("#DIVfechaDiaCompleto");
                    }
                    else if (element.attr("name") === "LfechaD") {
                        error.insertAfter("#DIVfechaD");
                    }
                    else if (element.attr("name") === "LfechaH") {
                        error.insertAfter("#DIVfechaH");
                    }
                    else {
                        error.insertAfter(element);
                    }

                }
            });
        });


        $('#submit-editar').click(function () {
            var $form = $('#editar-form');

            if (!$('#editar-form').valid()) {
                return false;
            }
            else {
                $('#editar').modal('hide');

                function showProcesando() {
                    $('#content').css({opacity: '0.0'}).html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Procesando...</h1></div>").delay(50).animate({opacity: '1.0'}, 300);
                }
                setTimeout(showProcesando, 300);
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


        $(window).keydown(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                return false;
            }
        });


    })
    ;


</script>