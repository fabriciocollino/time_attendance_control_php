<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '-editar.html.php') . '.php'; ?>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="modalTitle"><?php if ($o_Hora_Trabajo->getId() == null)
            echo _("Agregar Horario");
        else
            echo _("Editar Horario");
        ?></h4>
</div>
<div class="modal-body" style="padding-top: 0;">


    <form class="smart-form" novalidate="novalidate" data-async method="post" id="editar-form"
          action="<?php echo 'ajax/horarios.html.php' ?>?tipo=<?php if ($o_Hora_Trabajo->getId() == null)
              echo "add";
          else
              echo "edit&id=" . $o_Hora_Trabajo->getId();
          ?>">


        <fieldset>

            <div class="row">
                <label class="col-md-2 label-for-icon">Descripción</label>
                <section class="col col-8">
                    <label class="input"> <i class="icon-prepend fa fa-pencil-square-o"></i>
                        <input type="text" name="nombre" placeholder="Descripción"
                               value="<?php echo htmlentities($o_Hora_Trabajo->getDetalle(), ENT_COMPAT, 'utf-8'); ?>">
                    </label>
                </section>
            </div>

        </fieldset>

        <fieldset>
            <div class="row">
                <label class="label" style="padding-left: 22px;">Días</label>
                <section>
                    <div class="row" style="margin-bottom: 2px;">
                        <div class="col col-2">

                        </div>
                        <div class="col col-2">

                        </div>
                        <div class="col col-3">
                            Inicio
                        </div>

                        <div class="col col-3">
                            Fin
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col col-2">

                        </div>
                        <div class="col col-2">
                            <label class="label-for-icon"><?php echo $a_dias[2] ?></label>
                        </div>
                        <div class="col col-3">
                            <div class="input-group" id="btn_inicio_lun">
                                <input type="text" name="hs_inicio_lun" id="hs_inicio_lun" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsInicioLun('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>

                        <div class="col col-3">
                            <div class="input-group" id="btn_fin_lun">
                                <input type="text" name="hs_fin_lun" id="hs_fin_lun" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsFinLun('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>
                        <div class="col col-2">
                            <a id="btn_copiar" style="font-size:10px;cursor:pointer;">Copiar &darr;</a>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col col-2">

                        </div>
                        <div class="col col-2">
                            <label class="label-for-icon"><?php echo $a_dias[3] ?></label>
                        </div>
                        <div class="col col-3">
                            <div class="input-group" id="btn_inicio_mar">
                                <input type="text" name="hs_inicio_mar" id="hs_inicio_mar" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsInicioMar('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>

                        <div class="col col-3">
                            <div class="input-group" id="btn_fin_mar">
                                <input type="text" name="hs_fin_mar" id="hs_fin_mar" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsFinMar('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col col-2">

                        </div>
                        <div class="col col-2">
                            <label class="label-for-icon"><?php echo $a_dias[4] ?></label>
                        </div>
                        <div class="col col-3">
                            <div class="input-group" id="btn_inicio_mie">
                                <input type="text" name="hs_inicio_mie" id="hs_inicio_mie" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsInicioMie('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>

                        <div class="col col-3">
                            <div class="input-group" id="btn_fin_mie">
                                <input type="text" name="hs_fin_mie" id="hs_fin_mie" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsFinMie('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col col-2">

                        </div>
                        <div class="col col-2">
                            <label class="label-for-icon"><?php echo $a_dias[5] ?></label>
                        </div>
                        <div class="col col-3">
                            <div class="input-group" id="btn_inicio_jue">
                                <input type="text" name="hs_inicio_jue" id="hs_inicio_jue" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsInicioJue('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>

                        <div class="col col-3">
                            <div class="input-group" id="btn_fin_jue">
                                <input type="text" name="hs_fin_jue" id="hs_fin_jue" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsFinJue('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col col-2">

                        </div>
                        <div class="col col-2">
                            <label class="label-for-icon"><?php echo $a_dias[6] ?></label>
                        </div>
                        <div class="col col-3">
                            <div class="input-group" id="btn_inicio_vie">
                                <input type="text" name="hs_inicio_vie" id="hs_inicio_vie" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsInicioVie('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>

                        <div class="col col-3">
                            <div class="input-group" id="btn_fin_vie">
                                <input type="text" name="hs_fin_vie" id="hs_fin_vie" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsFinVie('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col col-2">

                        </div>
                        <div class="col col-2">
                            <label class="label-for-icon"><?php echo $a_dias[7] ?></label>
                        </div>
                        <div class="col col-3">
                            <div class="input-group" id="btn_inicio_sab">
                                <input type="text" name="hs_inicio_sab" id="hs_inicio_sab" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsInicioSab('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>

                        <div class="col col-3">
                            <div class="input-group" id="btn_fin_sab">
                                <input type="text" name="hs_fin_sab" id="hs_fin_sab" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsFinSab('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col col-2">

                        </div>
                        <div class="col col-2">
                            <label class="label-for-icon"><?php echo $a_dias[1] ?></label>
                        </div>
                        <div class="col col-3">
                            <div class="input-group" id="btn_inicio_dom">
                                <input type="text" name="hs_inicio_dom" id="hs_inicio_dom" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsInicioDom('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>

                        <div class="col col-3">
                            <div class="input-group" id="btn_fin_dom">
                                <input type="text" name="hs_fin_dom" id="hs_fin_dom" size="4" class="form-control"
                                       value="<?php echo htmlentities($o_Hora_Trabajo->getHsFinDom('H:i'), ENT_COMPAT, 'utf-8'); ?>"
                                       style="text-align: center;"/>
                                <span style="cursor:pointer;" class="input-group-addon"><i class="fa fa-clock-o"
                                                                                           style="line-height:20px!important;"></i></span>
                            </div>
                        </div>
                    </div>

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
        <?php if ($o_Hora_Trabajo->getId() == null)
            echo _("Agregar");
        else
            echo _("Guardar");
        ?>
    </button>
</div>

<script type="text/javascript">


    $(function () {
        // Validation





        $("#editar-form").validate({
            // Rules for form validation
            rules: {
                nombre: {
                        required: true,
                },
                hs_inicio_lun: {
                        required: true,
                        minlength: 5,
                        maxlength: 5
                },
                hs_inicio_mar: {
                       required: true,
                       minlength: 5,
                       maxlength: 5
                },
                hs_inicio_mie: {
                       required: true,
                       minlength: 5,
                       maxlength: 5
                },
                hs_inicio_jue: {
                       required: true,
                       minlength: 5,
                       maxlength: 5
                },
                hs_inicio_vie: {
                       required: true,
                       minlength: 5,
                        maxlength: 5
                },
                hs_inicio_sab: {
                       required: true,
                       minlength: 5,
                       maxlength: 5
                },
                hs_inicio_dom: {
                       required: true,
                       minlength: 5,
                       maxlength: 5
                },

                hs_fin_lun: {
                       required: true,
                       minlength: 5,
                       maxlength: 5
                },
                hs_fin_mar: {
                       required: true,
                       minlength: 5,
                       maxlength: 5
                },
                hs_fin_mie: {
                       required: true,
                       minlength: 5,
                       maxlength: 5
                },
                hs_fin_jue: {
                       required: true,
                       minlength: 5,
                       maxlength: 5
                },
                hs_fin_vie: {
                       required: true,
                       minlength: 5,
                       maxlength: 5
                },
                hs_fin_sab: {
                       required: true,
                       minlength: 5,
                       maxlength: 5
                },
                hs_fin_dom: {
                       required: true,
                       minlength: 5,
                       maxlength: 5
                }

            },
            // Messages for form validation
            messages: {
                nombre: {
                    required: '<?php echo _('Por favor ingrese el nombre') ?>'
                },
                hs_inicio_lun: {
                    required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },
                hs_inicio_mar: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },
                hs_inicio_mie: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },
                hs_inicio_jue: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },
                hs_inicio_vie: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },
                hs_inicio_sab: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },
                hs_inicio_dom: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },

                hs_fin_lun: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },
                hs_fin_mar: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },
                hs_fin_mie: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },
                hs_fin_jue: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },
                hs_fin_vie: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },
                hs_fin_sab: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                },
                hs_fin_dom: {
                                 required: '<?php echo _('Por favor ingrese un horario') ?>',
                    minlength: '<?php echo _('Formato 00:00') ?>',
                    maxlength: '<?php echo _('Formato 00:00') ?>'
                }
            },
            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            }
        });
    });

    $(document).ready(function () {
        $('#submit-editar').click(function () {
            var $form = $('#editar-form');

            if (!$('#editar-form').valid()) {
                return false;
            } else {
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

        $('#btn_copiar').click(function () {
            let horario_inicio = $("#hs_inicio_lun").val();
            let horario_fin = $("#hs_fin_lun").val();

            $("#hs_inicio_mar").val(horario_inicio);
            $("#hs_inicio_mie").val(horario_inicio);
            $("#hs_inicio_jue").val(horario_inicio);
            $("#hs_inicio_vie").val(horario_inicio);

            $("#hs_fin_mar").val(horario_fin);
            $("#hs_fin_mie").val(horario_fin);
            $("#hs_fin_jue").val(horario_fin);
            $("#hs_fin_vie").val(horario_fin);


        });

        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

        $('#editar-form').bind("keyup keypress", function (e) {
            var code = e.keyCode || e.which;
            if (code == 13) {
                e.preventDefault();
                return false;
            }
        });

        /*
         * CLOCKPICKER
         */

        $('#btn_inicio_lun').clockpicker({
            placement: 'bottom',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_inicio_lun').focus();
            }
        });
        $('#btn_fin_lun').clockpicker({
            placement: 'bottom',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_fin_lun').focus();
            }
        });
        $('#btn_inicio_mar').clockpicker({
            placement: 'bottom',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_inicio_mar').focus();
            }
        });
        $('#btn_fin_mar').clockpicker({
            placement: 'bottom',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_fin_mar').focus();
            }
        });
        $('#btn_inicio_mie').clockpicker({
            placement: 'bottom',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_inicio_mie').focus();
            }
        });
        $('#btn_fin_mie').clockpicker({
            placement: 'bottom',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_fin_mie').focus();
            }
        });
        $('#btn_inicio_jue').clockpicker({
            placement: 'top',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_inicio_jue').focus();
            }
        });
        $('#btn_fin_jue').clockpicker({
            placement: 'top',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_fin_jue').focus();
            }
        });
        $('#btn_inicio_vie').clockpicker({
            placement: 'top',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_inicio_vie').focus();
            }
        });
        $('#btn_fin_vie').clockpicker({
            placement: 'top',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_fin_vie').focus();
            }
        });
        $('#btn_inicio_sab').clockpicker({
            placement: 'top',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_inicio_sab').focus();
            }
        });
        $('#btn_fin_sab').clockpicker({
            placement: 'top',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_fin_sab').focus();
            }
        });
        $('#btn_inicio_dom').clockpicker({
            placement: 'top',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_inicio_dom').focus();
            }
        });
        $('#btn_fin_dom').clockpicker({
            placement: 'top',
            donetext: 'Aceptar',
            autoclose: 'true',
            afterShow: function () {
                $('#hs_fin_dom').focus();
            }
        });


    });

</script>
