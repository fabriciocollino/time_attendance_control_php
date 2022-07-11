<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '-editar.html.php') . '.php'; ?>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="modalTitle"><?php
        if ($o_Horario_Multiple->getId() == null)
            echo _("Agregar Horario Múltiple");
        else
            echo _("Editar Horario Múltiple");
        ?></h4>
</div>
<div class="modal-body" style="padding-top: 0px;">
    <div style="display:none;" id="hor_ID"><?php echo $o_Horario_Multiple->getId(); ?></div>


    <div class="row">

        <div class="col-sm-12 col-md-12 col-lg-4">
            <form class="smart-form" novalidate="novalidate" data-async method="post" id="editar-form"
                  action="<?php echo 'ajax/' . $Item_Name . 's.html.php' ?>?tipo=<?php
                  if ($o_Horario_Multiple->getId() == null)
                      echo "add";
                  else
                      echo "edit&id=" . $o_Horario_Multiple->getId();
                  ?>">
                <fieldset>
                    <legend>Datos Generales</legend>
                    <div class="row">
                        <label class="col-md-3 col-lg-3 label-for-icon">Descripción</label>
                        <section class="col col-md-9 col-lg-9" style="padding-right:0px;">
                            <label class="input"> <i class="icon-prepend fa fa-pencil-square-o"></i>
                                <input id="detalle" type="text" name="detalle" placeholder="Descripción"
                                       value="<?php echo htmlentities($o_Horario_Multiple->getDetalle(), ENT_COMPAT, 'utf-8'); ?>">
                            </label>
                        </section>
                    </div>


                </fieldset>

            </form>


        </div>
        <div class="col-sm-12 col-md-12 col-lg-8">

            <div class="table-responsive" style="padding-top: 20px;">

                <table id="tabla-horarios" class="table table-no-border table-striped">
                    <thead>
                    <tr>

                        <th>Horario</th>
                        <th>Detalle</th>
                        <th>Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $horarios = $o_Horario_Multiple->getHorarios();
                    if (!is_null($horarios) && $horarios != '') {
                        //echo "<pre>";print_r($horarios);echo "</pre>";
                        $horarios = json_decode($horarios, true);
                        //echo "<pre>";print_r($horarios);echo "</pre>";
                    } else {
                        $horarios = array(
                            array(
                                'horario_id' => 0
                            )
                        );
                    }

                    foreach ($horarios as $secuencia) {

                        ?>
                        <tr>
                            <td>
                                <form class="smart-form form-horarioID">
                                    <label class="select"> <span class="icon-prepend fa fa-clock-o"></span>
                                        <select name="horarioId" class="horario_id" style="padding-left: 32px;">
                                            <?php echo HtmlHelper::array2htmloptions(Hora_Trabajo_L::obtenerTodos(), $secuencia['horario_id'], true, true, '', 'Seleccione un Horario'); ?>
                                        </select> <i></i> </label>
                                </form>
                            </td>
                            <td>
                                <div class="horDetalle"></div>
                            </td>
                            <td>
                                <button data-type="deleteHorario" title="Eliminar"
                                        class="btn btn-default btn-sm fa fa-trash-o fa-lg"
                                        style="line-height: .75em;margin-top: 3px;"></button>
                            </td>
                        </tr>
                        <?php
                    }

                    ?>
                    </tbody>
                </table>

            </div>

            <div class="row">
                <button style="margin-left: 15px;" type="button" class="btn btn-default" onclick="agregarHorario();">
                    Agregar Horario
                </button>

            </div>
        </div>

    </div>

    <div id="horarioIDMaster" style="display:none;">
        <select name="horarioId" class="horario_id" style="padding-left: 32px;">
            <?php echo HtmlHelper::array2htmloptions(Hora_Trabajo_L::obtenerTodos(), $secuencia['horario_id'], true, true, '', 'Seleccione un Horario'); ?>
        </select>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>
    <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar">
        <?php
        if ($o_Horario_Multiple->getId() == null)
            echo _("Agregar");
        else
            echo _("Guardar");
        ?>
    </button>
</div>

<style>
    .clockpicker-popover {
        z-index: 100000;
    }

    @media (min-width: 768px) {
        .modal-dialog {
            width: 80% !important;
        }
    }
</style>


<script type="text/javascript">


    $(function () {
        // Validation

        $("#editar-form").validate({
            // Rules for form validation
            rules: {
                detalle: {
                    required: true
                }
            },
            // Messages for form validation
            messages: {
                detalle: {
                    required: '<?php echo _('Por favor ingrese una descripción') ?>'
                }
            },
            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            }
        });


    });

    $(document).ready(function () {

        $('#tabla-horarios').on('change', '.horario_id', function () {
            mostrarDetalle();
        });


        $('#submit-editar').click(function () {
            var $form = $('#editar-form');

            if (!$('#editar-form').valid()) {
                return false;
            } else {
                var falseFlag = false;

                $(".form-horarioID").each(function () {
                    $(this).validate({
                        rules: {horarioId: {required: true}},
                        messages: {horarioId: {required: '<?php echo _('Por favor seleccione un horario') ?>'}},
                        errorPlacement: function (error, element) {
                            error.insertAfter(element);
                        }
                    });
                    if (!$(this).valid()) {
                        falseFlag = true;
                        return false;
                    }
                });

                if (falseFlag) {
                    return false;
                } else {


                    $.ajax({
                        type: $form.attr('method'),
                        url: $form.attr('action'),
                        //data: $form.serialize() + '&horarios=' + JSON.stringify(calendarEvents,null,4),
                        data: $form.serialize() + '&horarios=' + JSON.stringify(HorariosTableToArray()),
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
            }
        });

        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });


        $('#editar-form').bind("keyup keypress", function (e) {
            var code = e.keyCode || e.which;
            if (code === 13) {
                e.preventDefault();
                return false;
            }
        });


    });


    function HorariosTableToArray() {

        var myRows = [];

        $("#tabla-horarios").find("tbody tr").each(function (index) {


            var horario_id = $(this).find(".horario_id").val();


            myRows.push(
                {
                    'horario_id': horario_id
                }
            );

        });


        return myRows;
    }

    function agregarHorario() {


        $("#tabla-horarios tr:last").after(
            '<tr>' +
            '<td>' +
            '<form class=\"smart-form form-horarioID\">' +
            '<label class=\"select\"> <span class=\"icon-prepend fa fa-clock-o\"></span>' +
            '<i></i> </label>' +
            '</form>' +
            '</td>' +
            '<td>' +
            '<div class="horDetalle"></div>' +
            '</td>' +
            '<td>' +
            '<button data-type=\"deleteHorario\" title=\"Eliminar\" class=\"btn btn-default btn-sm fa fa-trash-o fa-lg\" style=\"line-height: .75em;margin-top: 3px;\"></button>' +
            '</td>' +
            '</tr>');

        $('#tabla-horarios tr:last .select').append($('#horarioIDMaster').html());


    }

    $('#tabla-horarios').on('click', 'button[data-type=deleteHorario]', function () {
        eliminarHorario(this);
    });


    function eliminarHorario(horario) {

        horario.closest('tr').remove();

    }



    <?php
    $horarios_de_trabajo = Hora_Trabajo_L::obtenerTodos();
    if (!is_null($horarios_de_trabajo)) {
        echo 'var detalles = new Object();' . PHP_EOL;
        foreach ($horarios_de_trabajo as $horario) {
            $dias_array = explode('<br />', $horario->getTextoDias($a_dias));
            $domingo = array_splice($dias_array, 0, 1);
            array_splice($dias_array, 6, 0, $domingo);
            $dias_array = array_map('trim', $dias_array);
            echo PHP_EOL . 'detalles["' . $horario->getId() . '"]=' . json_encode($dias_array);
        }
    }

    ?>


    function mostrarDetalle() {
        $("#tabla-horarios").find("tbody tr").each(function (index) {

            var horario_id = $(this).find(".horario_id").val();
            if(horario_id!='') {
                var detalle = '';
                var i;
                for (i = 0; i < 7; i++) {
                    detalle += detalles[horario_id][i] + '<br>';
                }
                $(this).find(".horDetalle").html(detalle);
            }
        });

    }
    mostrarDetalle();





</script>
