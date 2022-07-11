<?php error_reporting(0);?>
<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>


<!-- MODAL CONTROLLER -->
<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '-editar.html.php') . '.php';?>



<!-- MODAL HEADER -->
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title"
        id="modalTitle"><?php if ($o_Licencia->getId() == 0) echo _("Agregar Licencia"); else echo _("Editar Licencia"); ?></h4>
</div>


<!-- MODAL FORM -->
<div class="modal-body" style="padding-top: 0;">

    <form class="smart-form" novalidate="novalidate"
          data-async method="post" id="editar-form"
          action="<?php echo 'ajax/' . $Item_Name . 's.html.php' ?>?tipo=<?php if ($o_Licencia->getId() == 0) echo "add"; else echo "edit&id=" . $o_Licencia->getId(); ?>">


        <!-- DATOS GENERALES: MOTIVO, ENABLE -->
        <fieldset>

            <legend>Detalle</legend>

            <!-- MOTIVO -->
            <div class="row">
                <section class="col col-10" style="width: 100%">
                    <label class="input"> <i class="icon-prepend fa fa-folder-o"></i>
                        <input type="text" name="motivo_Editar" placeholder="Motivo"
                               value="<?php echo htmlentities($o_Licencia->getMotivo(), ENT_COMPAT, 'utf-8'); ?>">
                    </label>
                </section>
            </div>


        </fieldset>

        <!-- PERSONAS: TODAS LAS PERSONAS / PERSONA / GRUPO -->
        <fieldset>

            <legend>
                Personas
            </legend>

            <!-- ROW : PERSONA, grupo -->
            <div class="row">

                <!-- SELECT PERSONA -->
                <section class="col col-6"  >
                    <label class="select">
                        <span class="icon-prepend fa fa-user"></span>

                        <select name="selPersona_Editar" id="selPersona_Editar" style="padding-left: 32px;">
                            <?php echo HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0, 0, 0, 'per_Eliminada=0 and per_Excluir=0'), $o_Licencia->getPerId(), true, true, 'PersonayGrupoLicencia', _('Todas las Personas'));?>
                        </select>

                        <i></i>



                    </label>
                </section>

                <!-- SELECT GRUPO -->
                <section class="col col-6" id="selRol_Editar" >
                    <label class="select"> <span class="icon-prepend fa fa-users"></span>
                        <select name="selGrupo_Editar" id="selGrupo_Editar" style="padding-left: 32px;">
                            <?php echo HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos(), $o_Licencia->getGrupoId(), true, true, '', _('Seleccione un Grupo')); ?>
                        </select> <i></i> </label>
                </section>

            </div>

        </fieldset>

        <!-- PERÍODO: TIPO, REPETITIVA, PERSONALIZADO -->
        <fieldset>

            <legend>Período</legend>

            <!-- SELECT TIPO, INPUT INTERVALO: DÍA COMPLETO -->
            <div class="row">

                <!-- SELECT TIPO -->
                <section class="col col-6" >
                    <label class="select"> <span class="icon-prepend fa fa-calendar"></span>
                        <select name="selTipo_Editar" id="selTipo_Editar" style="padding-left: 32px;">
                            <?php echo HtmlHelper::array2htmloptions($a_Licencias_Tipos, $o_Licencia->getTipo(), false, false); ?>
                        </select> <i></i> </label>
                </section>

                <!-- INPUT INTERVALO: DÍA COMPLETO -->
                <div id="fechaDiaCompleto_Editar">
                    <section class="col col-6">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                       name="diaCompleto_Editar" id="diaCompleto_Editar" type="text" placeholder="Dia"
                                       value="<?php echo $o_Licencia->getFechaInicio('Y-m-d'); ?>">
                                <span id="btnDiaCompleto_Editar" class="input-group-addon">
                                    <i class="fa fa-calendar" style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
                                </span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>


            <!-- SELECT INTERVALO PERSONALIZADO: DESDE, HASTA -->
            <div class="row">
                <div id="fechaPersonalizada_Editar">

                    <!-- DESDE -->
                    <section class="col col-6">
                        <label class="select">Desde</label>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                       name="fechaD_Editar" id="fechaD_Editar" type="text" placeholder="Desde"
                                       value="<?php echo $o_Licencia->getFechaInicio('Y-m-d'); ?>">
                                <span id="btnDesde_Editar" class="input-group-addon">
                                    <i class="fa fa-calendar" style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
                                </span>
                            </div>
                        </div>
                    </section>

                    <!-- HASTA -->
                    <section class="col col-6">
                        <label class="select">Hasta</label>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                       name="fechaH_Editar" id="fechaH_Editar" type="text" placeholder="Hasta"
                                       value="<?php echo $o_Licencia->getFechaFin('Y-m-d'); ?>">
                                <span id="btnHasta_Editar" class="input-group-addon">
                                    <i class="fa fa-calendar" style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
                                </span>
                            </div>
                        </div>
                    </section>

                </div>
            </div>


        </fieldset>


    </form>

</div>


<!-- MODAL FOOTER -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>
    <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar">
        <?php if ($o_Licencia->getId() == 0) echo _("Agregar"); else echo _("Guardar"); ?>
    </button>
</div>


<!-- MODAL SCRIPT -->
<script type="text/javascript">
    

    // FORM READY: FUNCTIONS
    $(document).ready(function () {


        // SELECT PERSONA: DEAFAULT VALUE
        <?php
        if ($o_Licencia->getId() == 0) {   ?>
        $("#selPersona_Editar").val("TodasLasPersonas");
        <?php }
        else {?>
        $('#selPersona_Editar').prop("disabled", true);
        // $('select').prop("disabled", true);
        //$('input').attr('readonly', true);
        //$('input').prop("disabled", true);
        //$('#submit-editar').hide();

        <?php   }  ?>

        
        
        <!-- SELECT PERSONA: ON CHANGE -->
        $('#selPersona_Editar').change(function () {

            if ($(this).find('option:selected').attr('value') == 'SelectRol') {
                $('#selRol_Editar').show();
            } else {
                $('#selRol_Editar').hide();
                $("#selGrupo_Editar").val("");

            }
        });

        <!-- SELECT TIPO: ON CHANGE -->
        $('#selTipo_Editar').change(function () {
            if ($(this).find('option:selected').attr('value') == <?php echo LICENCIA_DIA_COMPLETO; ?>) {
                $('#fechaDiaCompleto_Editar').show();
                $('#fechaPersonalizada_Editar').hide();
           //     $('#repetitiva_Editar').show();
            } else if ($(this).find('option:selected').attr('value') == <?php echo LICENCIA_PERSONALIZADA; ?>) {
                $('#fechaDiaCompleto_Editar').hide();
                $('#fechaPersonalizada_Editar').show();
               // $('#repetitiva_Editar').hide();
            } else {//seleccione un tipo
                $('#fechaDiaCompleto_Editar').hide();
                $('#fechaPersonalizada_Editar').hide();
               // $('#repetitiva_Editar').hide();
            }
        });

        <!-- TRIGGER UPDATE UPDATE CHANGES  -->
        $('#selPersona_Editar').trigger("change");
        $('#selTipo_Editar')   .trigger("change");

        <!-- DATE PICKER: FECHA DESDE -->
        $("#fechaD_Editar").datepicker({
            changeMonth: true,
            dateFormat: "yy-mm-dd",
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function (selectedDate) {
                $("#fechaH_Editar").datepicker("option", "minDate", selectedDate);
            }

        });

        <!-- DATE PICKER: FECHA HASTA -->
        $("#fechaH_Editar").datepicker({
            changeMonth: true,
            dateFormat: "yy-mm-dd",
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function (selectedDate) {
                $("#fechaD_Editar").datepicker("option", "maxDate", selectedDate);
            }
        });

        <!-- DATE PICKER: FECHA DÍA COMPLETO -->
        $("#diaCompleto_Editar").datepicker({
            changeMonth: true,
            dateFormat: "yy-mm-dd",
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>'
        });

        <!-- BUTTON DESDE: CLICK -->
        $('#btnDesde_Editar').click(function () {
            $(document).ready(function () {
                $("#fechaD_Editar").datepicker().focus();
            });
        });

        <!-- BUTTON HASTA: CLICK -->
        $('#btnHasta_Editar').click(function () {
            $(document).ready(function () {
                $("#fechaH_Editar").datepicker().focus();
            });
        });


        <!-- BUTTON DÍA COMPLETO: CLICK -->
        $('#btnDiaCompleto_Editar').click(function () {
            $(document).ready(function () {
                $("#diaCompleto_Editar").datepicker().focus();
            });
        });


        <!-- FORM DATA: VALIDATION -->
        $(function () {

            $("#editar-form").validate({
                rules: {
                    motivo_Editar: {
                        required: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    selPersona_Editar: {
                        required: true
                    },
                    selGrupo_Editar: {
                        required: "#selGrupo:visible"
                    },
                    selTipo_Editar: {
                        required: true
                    },
                    diaCompleto_Editar: {
                        required: "#fechaDiaCompleto:visible"
                    },
                    fechaD_Editar: {
                        required: "#fechaD:visible"
                    },
                    fechaH_Editar: {
                        required: "#fechaH:visible"
                    }
                },
                // Messages for form validation
                messages: {
                    motivo_Editar: {
                        required: '<?php echo _('Por favor ingrese el motivo del permiso') ?>',
                        minlength: '<?php echo _('El motivo es muy corto') ?>',
                        maxlength: '<?php echo _('El motivo es muy largo') ?>'
                    },
                    selPersona_Editar: {
                        required: "Por favor seleccione un item"
                    },
                    selGrupo_Editar: {
                        required: "Seleccione un grupo"
                    },
                    selTipo_Editar: {
                        required: "Seleccione un tipo"
                    },
                    diaCompleto_Editar: {
                        required: "Seleccione un día"
                    },
                    fechaD_Editar: {
                        required: "Seleccione una fecha"
                    },
                    fechaH_Editar: {
                        required: "Seleccione una fecha"
                    }
                },
                // Do not change code below
                errorPlacement: function (error, element) {
                    if (element.attr("name") === "diaCompleto_Editar") {
                        error.insertAfter("#DIVfechaDiaCompleto_Editar");
                    }
                    else if (element.attr("name") === "fechaD_Editar") {
                        error.insertAfter("#DIVfechaD_Editar");
                    }
                    else if (element.attr("name") === "fechaH_Editar") {
                        error.insertAfter("#DIVfechaH_Editar");
                    }
                    else {
                        error.insertAfter(element);
                    }

                }
            });
        });

        var fechaDiaCompleto    = ($("#diaCompleto_Editar").val()).split(" ");
        var fechaD              = ($("#fechaD_Editar").val()).split(" ");
        var fechaH              = ($("#fechaH_Editar").val()).split(" ");

        $("#diaCompleto_Editar").val(fechaDiaCompleto[0]);
        $("#fechaD_Editar").val(fechaD[0]);
        $("#fechaH_Editar").val(fechaH[0]);


        <!-- BUTTON SUBMIT -->
        $('#submit-editar').click(function () {

            var fechaDiaCompleto = $("#diaCompleto_Editar").val();
            var fechaD = $("#fechaD_Editar").val();
            var fechaH = $("#fechaH_Editar").val();

            $("#diaCompleto_Editar").val(fechaDiaCompleto + " 00:00:00");
            $("#fechaD_Editar").val(fechaD + " 00:00:00");
            $("#fechaH_Editar").val(fechaH + " 23:59:59");


            var $form = $('#editar-form');

            if (!$('#editar-form').valid()) {
                return false;
            } else {
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


        <!-- MODAL: ON CLOSE -->
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

        <!-- WINDOW: KEY DOWN -->
        $(window).keydown(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                return false;
            }
        });


    });


</script>
