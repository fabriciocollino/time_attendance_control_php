<?php

?>

<!-- TABLE ARTICULE -->
<article class = "col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <!-- TABLE DIV -->
    <div class = "jarviswidget jarviswidget-color-blueDark" id = "wid-id-50"
         data-widget-editbutton = "false"
		 data-widget-colorbutton = "false"
         data-widget-deletebutton = "false"
         data-widget-sortable = "false"
         data-widget-fullscreenbutton="false">

        <!-- HEADER -->
        <header>
			<span class = "widget-icon"> <i class = "fa fa-filter"></i> </span>
			<h2><?php echo _('Filtros') ?></h2>
		</header>

        <!-- DIV TABLE FILTER -->
		<div>
			<div class = "widget-body no-padding">


				<form class = "smart-form" novalidate = "novalidate" data-async = "" method = "post" id = "filtro-form"
					  action = "<?php echo $Filtro_Form_Action; ?>">

                    <!-- CAMPOS DEL FILTRO -->
                    <fieldset>



                        <!-- FECHAS -->
						<div class = "row">
							<section class="col col-1"> </section>
                            <!-- DROP DOWN FECHAS PREDEFINIDAS -->
							<section class="col col-2" style="min-width: 180px;">
								<label class="select">Fecha</label>
								<label class="select">
                                    <span class="icon-prepend fa fa-calendar"></span>
									<select name="intervaloFecha" id="selFecha" style="padding-left: 32px;">
                                        <?php echo HtmlHelper::array2htmloptions($IntervalosFechas, $T_Intervalo, false, false); ?>
									</select> <i></i>
								</label>
							</section>

                            <!-- FECHA PERSONALIZADA-->
							<div id="fechaPersonalizada">

                                <!-- FECHA D-->
								<section class="col col-2" style="min-width: 195px;">
									<label class="select">Intervalo</label>
									<div class="form-group">
										<div class="input-group">
											<input class="form-control "
												   name="fechaD"
												   id="fechaD"
												   type="text" style="padding-left: 5px;font-size: 12px;height: 31px;"
												   placeholder="Desde"
												   value="<?php echo $_SESSION['filtro']['fechaD']; ?>" />
											<span id="btnDesde" class="input-group-addon">
														<i class="fa fa-calendar" style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
													</span>
										</div>
									</div>
								</section>

                                <!-- FECHA H-->
								<section class="col col-2" style="min-width: 195px;">
									<label class="select">&nbsp;</label>
									<div class="form-group">
										<div class="input-group">
											<input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;" name="fechaH"
												   id="fechaH" type="text" placeholder="Hasta"
												   value="<?php echo $_SESSION['filtro']['fechaH']; ?>" />
											<span id="btnHasta" class="input-group-addon">
													<i class="fa fa-calendar" style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
											</span>
										</div>
									</div>
								</section>
							</div>
						</div>

					</fieldset>

                    <!-- BUTTON SUBMIT, CHECKBOX TOTALES_POR_DIA -->
					<footer>

                        <!-- BUTTON SUBMIT -->
						<button type="button" name="btnFiltro" id="submit-filtro" class="btn btn-primary">
							Filtrar
						</button>


					</footer>

                    <!-- ERROR CHECK -->
                    <?php echo (isset ($T_Error['error'])) ? '<p class="error">' . htmlentities($T_Error['error'], ENT_COMPAT, 'utf-8') . '</p>' : ''; ?>

                    <!-- SCRIPT -->
                    <script type="text/javascript">

                        $('#selFecha').change(function () {
							if ($(this).find('option:selected').attr('value') === 'F_Personalizado') {
								$('#fechaPersonalizada').show();
							}
							else {
								$('#fechaPersonalizada').hide();
							}
						});
						$('#selFecha').trigger("change");


						// DATES
						$("#fechaD").datepicker({
							changeMonth: true,
                            dateFormat: "yy-mm-dd",
							changeYear: true,
							numberOfMonths: 1,
							prevText: '<i class="fa fa-chevron-left"></i>',
							nextText: '<i class="fa fa-chevron-right"></i>',
							onClose: function (selectedDate) {
								$("#fechaH").datepicker("option", "minDate", selectedDate);
								var selectedDate = new Date(selectedDate);
								selectedDate.setMonth(selectedDate.getMonth() + 1);
								if(selectedDate.getMonth() == 0)
									month = 6;
								else {
									month = selectedDate.getMonth() + 6;
								}
								dd = selectedDate.getFullYear()+'-'+month+'-'+selectedDate.getDate();

								$("#fechaH").datepicker("option", "maxDate", dd);
							}

						});
						$("#fechaH").datepicker({
							changeMonth: true,
                            dateFormat: "yy-mm-dd",
							changeYear: true,
							numberOfMonths: 1,
							prevText: '<i class="fa fa-chevron-left"></i>',
							nextText: '<i class="fa fa-chevron-right"></i>',
							onClose: function (selectedDate) {
								$("#fechaD").datepicker("option", "maxDate", selectedDate);
								var selectedDate = new Date(selectedDate);
								selectedDate.setMonth(selectedDate.getMonth() - 6);
								if(selectedDate.getMonth() == 0)
									month = 1;
								else {
									month = selectedDate.getMonth() + 1;
								}
								dd = selectedDate.getFullYear()+'-'+month+'-'+selectedDate.getDate();

								$("#fechaD").datepicker("option", "minDate", dd);
							}
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

						// Fecha sin hora, hide/show filters according Module
						$(document).ready(function () {
							var fechaD = ($("#fechaD").val()).split(" ");
							var fechaH = ($("#fechaH").val()).split(" ");

							$("#fechaD").val(fechaD[0]);
							$("#fechaH").val(fechaH[0]);

							var _tipo_reporte = "<?php echo $T_Tipo; ?>";

							if( _tipo_reporte === 'Feriados' )
								$('#filtro_feriados').show();
							else
								$('#filtro_feriados').hide();

						});

						// SUBMIT
                        $(document).ready(function () {
                            $('#submit-filtro').click(function () {

                                var fechaD = $("#fechaD").val();
                                var fechaH = $("#fechaH").val();
                                $("#fechaD").val(fechaD+" 00:00:00");
                                $("#fechaH").val(fechaH+" 23:59:59");

                                var $form = $('#filtro-form');

                                if (!$('#filtro-form').valid()) {
                                    return false;
                                }
                                else {
                                    $('#content').html('<h1 class="ajax-loading-animation"><i class="fa fa-cog fa-spin"></i> Cargando...</h1>');

                                    $.ajax({
                                        type:   $form.attr('method'),
                                        url:    $form.attr('action'),
                                        data:   $form.serialize(),
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

					</script>


				</form>
			</div>
            <!-- END: DIV TABLE FILTER -->

        </div>
        <!-- END: TABLE DIV -->
    </div>
</article>
<!-- END: TABLE ARTICULE -->
