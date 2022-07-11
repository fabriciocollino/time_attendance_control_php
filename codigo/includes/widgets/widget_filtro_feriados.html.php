

<!-- NEW WIDGET START -->
<article class = "col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class = "jarviswidget jarviswidget-color-blueDark" id = "wid-id-50" data-widget-editbutton = "false"
         data-widget-colorbutton = "false" data-widget-deletebutton = "false" data-widget-sortable = "false"
         data-widget-fullscreenbutton="false">

        <header>
            <span class = "widget-icon"> <i class = "fa fa-filter"></i> </span>
            <h2><?php echo _('Filtros') ?></h2>
        </header>
        <!-- widget div-->
        <div>
            <!-- widget content -->
            <div class = "widget-body no-padding">
                <form class = "smart-form" novalidate = "novalidate" data-async = "" method = "post" id = "filtro-form"
                      action = "<?php echo $Filtro_Form_Action; ?>">
                    <fieldset>
                        <div class = "row">
                            <section class = "col col-1">
                            </section>
                            <section class = "col col-4">
                                <label class = "select">Persona</label> <label class = "select"> <span
                                            class = "icon-prepend fa fa-user"></span>
                                    <select name = "persona"
                                            id = "selPersona1"
                                            style = "padding-left: 32px;">
                                        <?php echo HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0, 0, 0, 'per_Eliminada = 0'), $_SESSION['filtro']['persona'], true, true, 'PersonayRol', _('Todas las Personas')); ?>
                                    </select> <i></i> </label>
                            </section>
                            <section class = "col col-4" id = "selRol1">
                                <label class = "select">Grupo</label> <label class = "select"> <span
                                            class = "icon-prepend fa fa-user"></span>
                                    <select name = "rolF" style = "padding-left: 32px;">
                                        <?php echo HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos(), $_SESSION['filtro']['rolF'], true, true, '', _('Seleccione un Grupo')); ?>
                                    </select> <i></i> </label>
                            </section>
                        </div>
                        <div class = "row">
                            <section class = "col col-1">
                            </section>
                            <section class = "col col-2" style = "min-width: 180px;">
                                <label class = "select">Fecha</label> <label class = "select"> <span
                                            class = "icon-prepend fa fa-calendar"></span> <select
                                            name = "intervaloFecha" id = "selFecha1" style = "padding-left: 32px;">
                                        <?php echo HtmlHelper::array2htmloptions($IntervalosFechas, $T_Intervalo, false, false); ?>
                                    </select> <i></i> </label>
                            </section>
                            <div id = "fechaPersonalizada1">
                                <section class = "col col-2" style = "min-width: 195px;">
                                    <label class = "select">Intervalo</label>
                                    <div class = "form-group">
                                        <div class = "input-group">
                                            <input class = "form-control "
                                                   style = "padding-left: 5px;font-size: 12px;height: 31px;"
                                                   name = "fechaD"
                                                   id = "fechaD1" type = "text" placeholder = "Desde"
                                                   value = "<?php echo $_SESSION['filtro']['fechaD']; ?>"/> <span
                                                    id = "btnDesde" class = "input-group-addon">
														<i class = "fa fa-calendar"
                                                           style = "cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
													</span>
                                        </div>
                                    </div>
                                </section>
                                <section class = "col col-2" style = "min-width: 195px;">
                                    <label class = "select">&nbsp;</label>
                                    <div class = "form-group">
                                        <div class = "input-group">
                                            <input class = "form-control "
                                                   style = "padding-left: 5px;font-size: 12px;height: 31px;"
                                                   name = "fechaH"
                                                   id = "fechaH1" type = "text" placeholder = "Hasta"
                                                   value = "<?php echo $_SESSION['filtro']['fechaH']; ?>"/> <span
                                                    id = "btnHasta" class = "input-group-addon">
													<i class = "fa fa-calendar"
                                                       style = "cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
												</span>
                                        </div>
                                    </div>
                                </section>
                            </div>

                        </div>

                    </fieldset>

                    <footer>
                        <button type = "button" name = "btnFiltro" id = "submit-filtro" class = "btn btn-primary">
                            Filtrar
                        </button>
                    </footer>

                    <?php echo (isset ($T_Error['error'])) ? '<p class="error">' . htmlentities($T_Error['error'], ENT_COMPAT, 'utf-8') . '</p>' : ''; ?>

                    <script type = "text/javascript">

                        $('#selFecha1').change(function () {
                            if ($(this).find('option:selected').attr('value') === 'F_Personalizado') {
                                $('#fechaPersonalizada1').show();
                                <?php /*	$('#Fechas').hide(); */ ?>
                            }
                            else {
                                $('#fechaPersonalizada1').hide();
                                <?php /*	$('#Fechas').show(); */ ?>
                            }

                        });
                        $('#selPersona1').change(function () {
                            if ($(this).find('option:selected').attr('value') == 'SelectRol') {
                                $('#selRol1').show();
                            }
                            else {
                                $('#selRol1').hide();
                            }

                        });
                        $('#selFecha1').trigger("change");
                        $('#selPersona1').trigger("change");
                        // Date Range Picker
                        $("#fechaD1").datepicker({
                            //defaultDate: "+1w",
                            changeMonth: true,
                            dateFormat: "yy-mm-dd",
                            changeYear: true,
                            numberOfMonths: 1,
                            prevText: '<i class="fa fa-chevron-left"></i>',
                            nextText: '<i class="fa fa-chevron-right"></i>',
                            onClose: function (selectedDate) {
                                $("#fechaH1").datepicker("option", "minDate", selectedDate);
                                var selectedDate = new Date(selectedDate);
                                selectedDate.setMonth(selectedDate.getMonth() + 1);
                                if (selectedDate.getMonth() == 0)
                                    month = 6;
                                else {
                                    month = selectedDate.getMonth() + 6;
                                }
                                dd = selectedDate.getFullYear() + '-' + month + '-' + selectedDate.getDate();

                                $("#fechaH1").datepicker("option", "maxDate", dd);
                            }

                        });
                        $("#fechaH1").datepicker({
                            //defaultDate: "+1w",
                            changeMonth: true,
                            dateFormat: "yy-mm-dd",
                            changeYear: true,
                            numberOfMonths: 1,
                            prevText: '<i class="fa fa-chevron-left"></i>',
                            nextText: '<i class="fa fa-chevron-right"></i>',
                            onClose: function (selectedDate) {
                                $("#fechaD1").datepicker("option", "maxDate", selectedDate);
                                var selectedDate = new Date(selectedDate);
                                selectedDate.setMonth(selectedDate.getMonth() - 6);
                                if (selectedDate.getMonth() == 0)
                                    month = 1;
                                else {
                                    month = selectedDate.getMonth() + 1;
                                }
                                dd = selectedDate.getFullYear() + '-' + month + '-' + selectedDate.getDate();

                                $("#fechaD1").datepicker("option", "minDate", dd);
                                //var str = $(this).val();
                                //var r_date = str.split(" ");
                                //$(this).val(r_date[0]+" 23:59:59");
                            }
                        });


                        $('#btnDesde').click(function () {
                            $(document).ready(function () {
                                var fechaD = ($("#fechaD1").val()).split(" ");
                                var fechaH = ($("#fechaH1").val()).split(" ");

                                $("#fechaD1").val(fechaD[0]);
                                $("#fechaH1").val(fechaH[0]);
                                $("#fechaD1").datepicker().focus();

                            });
                        });
                        $('#btnHasta').click(function () {
                            $(document).ready(function () {
                                $("#fechaH1").datepicker().focus();
                            });
                        });


                        $(document).ready(function () {

                            $('#submit-filtro').click(function () {
                                var fechaD = $("#fechaD1").val();
                                var fechaH = $("#fechaH1").val();
                                $("#fechaD1").val(fechaD + " 00:00:00");
                                $("#fechaH1").val(fechaH + " 23:59:59");
                                var $form = $('#filtro-form');

                                if (!$('#filtro-form').valid()) {
                                    return false;
                                }
                                else {
                                    $('#content').html('<h1 class="ajax-loading-animation"><i class="fa fa-cog fa-spin"></i> Cargando...</h1>');
                                    $.ajax({
                                        type: $form.attr('method'),
                                        url: $form.attr('action'),
                                        data: $form.serialize(),
                                        success: function (data, status) {

                                            $('#content').css({opacity: '0.0'}).html(data).delay(50).animate({opacity: '1.0'}, 300);
                                            $('#editar').off('hidden.bs.modal');

                                        }
                                    });
                                }
                            });
                            $('#filtro-form').bind("keyup keypress", function (e) {
                                var code = e.keyCode || e.which;
                                if (code == 13) {
                                    e.preventDefault();
                                    return false;
                                }
                            });


                        });
                        $(document).ready(function () {
                            var fechaD = ($("#fechaD1").val()).split(" ");
                            var fechaH = ($("#fechaH1").val()).split(" ");

                            $("#fechaD1").val(fechaD[0]);
                            $("#fechaH1").val(fechaH[0]);


                        });
                    </script>
                </form>

            </div>
            <!-- end widget content -->

        </div>
        <!-- end widget div -->

    </div>
    <!-- end widget -->

</article>
<!-- WIDGET END -->
