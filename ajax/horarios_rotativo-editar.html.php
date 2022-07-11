<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '-editar.html.php') . 's' . '.php'; ?>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="modalTitle"><?php
        if ($o_Horario_Rotativo->getId() == null)
            echo _("Agregar Horario Rotativo");
        else
            echo _("Editar Horario Rotativo");
        ?></h4>
</div>
<div class="modal-body" style="padding-top: 0px;">
    <div style="display:none;" id="hor_ID"><?php echo $o_Horario_Rotativo->getId(); ?></div>


    <div class="row">

        <div class="col-sm-12 col-md-12 col-lg-4">
            <form class="smart-form" novalidate="novalidate" data-async method="post" id="editar-form"
                  action="<?php echo 'ajax/' . $Item_Name . 's.html.php' ?>?tipo=<?php
                  if ($o_Horario_Rotativo->getId() == null)
                      echo "add";
                  else
                      echo "edit&id=" . $o_Horario_Rotativo->getId();
                  ?>">
                <fieldset>
                    <legend>Datos Generales</legend>
                    <div class="row">
                        <label class="col-md-3 col-lg-3 label-for-icon">Descripción</label>
                        <section class="col col-md-9 col-lg-9" style="padding-right:0;">
                            <label class="input"> <i class="icon-prepend fa fa-pencil-square-o"></i>
                                <input id="detalle" type="text" name="detalle" placeholder="Descripción"
                                       value="<?php echo htmlentities($o_Horario_Rotativo->getDetalle(), ENT_COMPAT, 'utf-8'); ?>">
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <label class="col-md-3 col-lg-3 label-for-icon">Inicio</label>
                        <section class="col col-md-9 col-lg-9" style="padding-right:0px;">
                            <label class="input"> <i class="icon-prepend fa fa-calendar"></i>
                                <input type="text" id="fecha_inicio" name="fecha_inicio" placeholder="Fecha de Inicio"
                                       value="<?php echo $o_Horario_Rotativo->getFechaInicio(Config_L::p('f_fecha_corta')); ?>">
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
                        <th style="width: 10%;">Secuencia</th>
                        <th>Horario</th>
                        <th>Duración</th>
                        <th>Detalle</th>
                        <th>Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $horarios = $o_Horario_Rotativo->getHorarios();
                    if (!is_null($horarios) && $horarios != '') {
                        //echo "<pre>";print_r($horarios);echo "</pre>";
                        $horarios = json_decode($horarios, true);
                        //echo "<pre>";print_r($horarios);echo "</pre>";
                    } else {
                        $horarios = array(
                            array(
                                'orden' => 1,
                                'horario_id' => 0,
                                'duracion' => ''
                            )
                        );
                    }

                    foreach ($horarios as $secuencia) {

                        ?>
                        <tr>
                            <td class="handle">
                                <label class="fa fa-reorder fa-lg reorder-handle"></label><span
                                        style="padding-left: 10px;" class="orden"><?php echo $secuencia['orden']; ?></span>
                            </td>
                            <td>
                                <form class="smart-form form-horarioID">
                                    <label class="select"> <span class="icon-prepend fa fa-clock-o"></span>
                                        <select name="horarioId" class="horario_id" style="padding-left: 32px;">
                                            <?php echo HtmlHelper::array2htmloptions(Hora_Trabajo_L::obtenerTodos(), $secuencia['horario_id'], true, true, '', 'Seleccione un Horario'); ?>
                                        </select> <i></i> </label>
                                </form>
                            </td>
                            <td>
                                <form class="smart-form form-horarioDuracion">
                                    <label class="input" style="display:inline;">
                                        <input type="text" class="duracion" name="horarioDuracion"
                                               value="<?php echo htmlentities($secuencia['duracion']); ?>"
                                               style="width:40px;display:inline;">
                                    </label>
                                    <span style="display:inline;">días</span>
                                </form>
                            </td>
                            <td>
                                <div class="horDetalle"></div>
                            </td>
                            <td>
                                <button data-type="deleteHorario" data-orden="<?php echo $secuencia['orden']; ?>"
                                        title="Eliminar"
                                        class="btn btn-default btn-sm fa fa-trash-o fa-lg"
                                        style="line-height: .75em;margin-top: 3px;"></button>
                            </td>
                        </tr>
                        <?php
                    }

                    ?>
                    </tbody>
                </table>
                <div id="errorSemana"
                     style="display:none; color:red;    margin-bottom: 10px;padding-left: 5px;text-align: center;"></div>

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
        if ($o_Horario_Rotativo->getId() == null)
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


        $("#fecha_inicio").datepicker({
            //defaultDate: "+1w",
            changeMonth: true,
            dateFormat: "yy-mm-dd 00:00:00",
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',

        });


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

        $('#tabla-horarios').on('change', '.duracion', function () {
            mostrarDetalle();
        });

        $('#tabla-horarios').on('click', 'button[data-type=deleteHorario]', function () {
            eliminarHorario($(this).data('orden'));
        });


        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
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
                $(".form-horarioDuracion").each(function () {
                    $(this).validate({
                        rules: {horarioDuracion: {required: true}},
                        messages: {horarioDuracion: {required: '<?php echo _('Por favor inserte un numero de') ?>'}},
                        errorPlacement: function (error, element) {
                            error.insertAfter(element);
                        }
                    });
                    if (!$(this).valid()) {
                        falseFlag = true;
                        return false;
                    }
                });

                if (!calcularSemana()) {
                    falseFlag = true;

                    $('#errorSemana').text('La suma no corresponde a un múltiplo de 7 para completar semanas completas');
                    $('#errorSemana').show();
                }

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


        $("#tabla-horarios").sortable({
            items: "tr",
            cursor: 'move',
            handle: '.handle',
            opacity: 0.6,
            stop: function () {
                reCalcularOrden();
                mostrarDetalle();
            }

        });


    });


    function HorariosTableToArray() {

        var myRows = [];

        $("#tabla-horarios").find("tbody tr").each(function (index) {

            var orden = $(this).find(".orden").text();
            var horario_id = $(this).find(".horario_id").val();
            var duracion = $(this).find(".duracion").val();

            myRows.push(
                {
                    'orden': orden,
                    'duracion': duracion,
                    'horario_id': horario_id
                }
            );

        });


        return myRows;
    }

    function agregarHorario() {

        var orden = calcularOrdenMax();


        $("#tabla-horarios tr:last").after(
            '<tr>' +
            '<td class=\"handle\">' +
            '<label class=\"fa fa-reorder fa-lg reorder-handle\"></label><span style=\"padding-left: 10px;\" class=\"orden\">' + orden + '</span>' +
            '</td>' +
            '<td>' +
            '<form class=\"smart-form form-horarioID\">' +
            '<label class=\"select\"> <span class=\"icon-prepend fa fa-clock-o\"></span>' +
            '<i></i> </label>' +
            '</form>' +
            '</td>' +
            '<td>' +
            '<form class=\"smart-form form-horarioDuracion\" >' +
            '<label class=\"input\" style=\"display:inline;\">' +
            '<input type=\"text\" class=\"duracion\" name=\"horarioDuracion\" style=\"width:40px;display:inline;\">' +
            '</label>' +
            '<span style=\"display:inline;\">&nbsp;días</span>' +
            '</form>' +
            '</td>' +
            '<td>' +
            '<div class="horDetalle"></div>' +
            '</td>' +
            '<td>' +
            '<button data-type=\"deleteHorario\" data-orden=\"' + orden + '\" title=\"Eliminar\" class=\"btn btn-default btn-sm fa fa-trash-o fa-lg\" style=\"line-height: .75em;margin-top: 3px;\"></button>' +
            '</td>' +
            '</tr>');

        $('#tabla-horarios tr:last .select').append($('#horarioIDMaster').html());


    }


    function eliminarHorario(horario) {

        $("#tabla-horarios").find("tbody tr").each(function (index) {

            var orden = $(this).find(".orden").text();
            if (eval(orden) === eval(horario)) {
                $(this).remove();
            }
        });
        reCalcularOrden();

    }


    function calcularOrdenMax() {
        //esta funcion devuelve el numero de orden. (el ultimo)

        var ordenes = [];

        $("#tabla-horarios").find("tbody tr").each(function (index) {
            var orden = $(this).find(".orden").text();
            ordenes.push(orden);
        });

        return Math.max.apply(Math, ordenes) + 1;

    }


    function reCalcularOrden() {

        var contador = 1;
        $("#tabla-horarios").find("tbody tr").each(function (index) {
            var orden = $(this).find(".orden").text(contador);
            contador++;

        });

        mostrarDetalle();

    }


    function calcularSemana() {
        var suma = 0;
        $("#tabla-horarios").find("tbody tr").each(function (index) {
            var duracion = $(this).find(".duracion").val();
            suma = eval(suma) + eval(duracion);

        });

        if (suma % 7 == 0)
            return true;
        else
            return false;

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
        var contador = 0;
        var x = 0;
        $("#tabla-horarios").find("tbody tr").each(function (index) {
            var duracion = $(this).find(".duracion").val();
            var horario_id = $(this).find(".horario_id").val();
            var detalle = '';
            var i;
            if(horario_id!='') {
                for (i = contador; i < eval(contador) + eval(duracion); i++) {
                    if (x % 7 == 0 & x > 0)x = 0; //eval(i) - eval(contador)
                    detalle += detalles[horario_id][x] + '<br>';
                    x++;
                }
                $(this).find(".horDetalle").html(detalle);
                contador = eval(contador) + eval(duracion) + 1;
            }

        });

    }
    mostrarDetalle();

</script>
