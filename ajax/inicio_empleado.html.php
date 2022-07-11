<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<style>
    #div_unread_count_alertas, #div_unread_count_ausencias, #div_unread_count_en_vivo, #div_unread_count_llegadas_tarde, #div_unread_count_salidas_temprano, #div_unread_count_mensajes {
	display:none;
}

</style>
<section id="widget-grid" class="">

    <!-- TITULO, AGREGAR REGISTRO -->
    <div class="row">
        <!-- TITULO -->
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <h1 class="page-title txt-color-blueDark">

                <!-- PAGE HEADER -->
                <i class="fa-fw fa fa-home"></i>
                Inicio
            </h1>
        </div>

        <!-- AGREGAR REGISTRO -->
        <?php /*

		<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
				<button style="float:right;margin-bottom: 10px;" id="agregar_registro" class="btn btn-sm btn-primary" type="button" data-backdrop="static" data-toggle="modal" data-target="#editar" data-type="view" data-lnk="ajax/logs_equipos-editar.html.php">
					<?php echo _('Agregar Registro') ?>
					</button>
					<?php
						//INCLUYO los view/edit etc de los cosos
						require_once APP_PATH . '/templates/edit-view_modal.html.php';
						?>
			<script>
				loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/x-editable/moment.min.js", loadMockJax);

				function loadMockJax() {
					loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/x-editable/jquery.mockjax.min.js", loadXeditable);
				}

				function loadXeditable() {
					loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/x-editable/x-editable.min.js", loadTypeHead);
				}

				function loadTypeHead() {
					loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/typeahead/typeahead.min.js", loadTypeaheadjs);
				}

				function loadTypeaheadjs() {
					loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/typeahead/typeaheadjs.min.js", loadClockPicker);
				}

				function loadClockPicker() {
					loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/clockpicker/clockpicker.min.js", runXEdit);
				}
				function runXEdit() {
					$('a[data-toggle=modal], button[data-toggle=modal]').click(function () {
						let data_id = '';
						let lnk = '';
						let view_type = '';
						if (typeof $(this).data('id') !== 'undefined') {
							data_id = $(this).data('id');
						}
						if (typeof $(this).data('lnk') !== 'undefined') {
							lnk = $(this).data('lnk');
						}
						if (typeof $(this).data('type') !== 'undefined') {
							view_type = $(this).data('type');
						}
						// alert(lnk);

						$('.modal-content').html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Cargando...</h1></div>");
						$.ajax({
							cache: false,
							type: 'POST',
							url: lnk,
							data: {tipo: view_type, id: data_id,openEditInicio: 'Open Agregar Registro'},
							success: function (data) {
								$('.modal-content').show().html(data);
							}
						});

					});
				}
			</script>
		</div>
		*/ ?>

    </div>

    <!-- *ENVIVO, *AUSENCIAS, FEED EN VIVO  -->
    <div class="row">

        <!-- EN VIVO -->
        <article class                          =   "col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class                          =   "jarviswidget jarviswidget-color-blueDark"
                 data-widget-load               =   "ajax/widgets_empleado/widget_en-vivo.html.php"
                 id                             =   "wid-id-108"
                 data-widget-refresh            =   "500"
                 data-widget-editbutton         =   "false"
                 data-widget-colorbutton        =   "false"
                 data-widget-deletebutton       =   "false"
                 data-widget-fullscreenbutton   =   "false">

                <!-- ICON, TITLE, BUTTON, TOTAL-->
                <header>
                    <!-- WIDGET ICON -->
                    <span class="widget-icon">
                        <i class="fa fa-play"></i>
                    </span>

                    <!-- WIDGET TITLE -->
                    <h2 style="width: 22%;">
                        <?php echo _('En Vivo') ?>
                    </h2>


                    <!-- WIDGET TOTAL -->
                    <div class="widget-toolbar" role="menu">
                        <div class="badge bg-color-greenLight" id="div_unread_count_en_vivo" style="">
                            0
                        </div>
                    </div>

                </header>

                <!-- BODY DIV -->
                <div>
                    <div class="widget-body">

                    </div>
                </div>

            </div><!-- end widget -->
        </article>


            <!-- AUSENCIAS -->
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets_empleado/widget_ausencias.html.php?selIntervalo=hoy"
                 id="wid-id-102"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">
                <!-- ICON, TITLE, BUTTON, TOTAL-->
                <header>
                    <!-- WIDGET ICON -->
                    <span class="widget-icon"> <i class="fa fa-clock-o"></i> </span>
                    <!-- WIDGET TITLE -->
                    <h2 style="width: 16%;"><?php echo _('Ausencias') ?></h2>


                    <!-- WIDGET BUTTON -->
                    <div id="selIntervaloAUSENCIAS" class="widget-toolbar" role="menu">
                        <div class="btn-group">

                            <!-- ICON, TITULO-->
                            <button id="btn_IntervaloAusencias"
                                    class="btn dropdown-toggle btn-xs bg-color-blueDark txt-color-white btn-labeled"
                                    data-toggle="dropdown" aria-expanded="true">
                                <span class="btn-label btn-label-widget-dropdown"><i class="fa fa-calendar"></i></span>
                                <span class="btn_text">Hoy </span> <i class="fa fa-caret-down"></i>
                            </button>

                            <!-- ON CLICK DIA, SEMANA -->
                            <ul class="dropdown-menu pull-right">

                                <!-- ON CLICK DIA -->
                                <li class="active">
                                    <a href="javascript:void(0);"
                                       data-intervalo="hoy"
                                       onclick="selIntervaloAUSENCIAS(this);return;"
                                       class="a-item-widget-dropdown">
                                        <i class="fa fa-check i-item-widget-dropdown"></i>Hoy
                                    </a>
                                </li>

                                <!-- ON CLICK SEMANA -->
                                <li>
                                    <a href="javascript:void(0);" data-intervalo="esta_semana"
                                       onclick="selIntervaloAUSENCIAS(this);return;" class="a-item-widget-dropdown">
                                        <i class="fa i-item-widget-dropdown"></i>Semana </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <!-- WIDGET TOTAL -->
                    <div class="widget-toolbar" role="menu">
                        <div class="badge bg-color-greenLight" id="div_unread_count_ausencias"
                             style="vertical-align:middle;">
                            0
                        </div>
                    </div>
                </header>
                <!-- BODY DIV -->
                <div>
                    <div class="widget-body"></div>
                </div>
            </div>
        </article>


    </div>

    <!-- *SALIDAS TEMPRANO, ALERTAS -->

    <!-- SALIDAS TEMPRANO -->
    <div class="row">

        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets_empleado/widget_salidas_temprano.html.php?selIntervalo=hoy" id="wid-id-104"
                 data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">

                <header>
                    <!--<div class="jarviswidget-ctrls" role="menu"><a href="#"
                                                                   onclick="AgrandarWidget('#wid-id-104');return false;"
                                                                   class="button-icon" rel="tooltip" title=""
                                                                   data-placement="bottom"
                                                                   data-original-title="Agrandar"><i
                                    class="fa fa-angle-double-right"></i></a></div>
                        <div class="jarviswidget-ctrls" role="menu"><a href="#" onclick="AchicarWidget('#wid-id-104');return false;" class="button-icon" rel="tooltip" title="" data-placement="bottom" data-original-title="Achicar"><i class="fa fa-angle-double-left"></i></a></div>-->
                    <span class="widget-icon"> <i class="fa fa-clock-o"></i> </span>
                    <h2 style="width: 25%;"><?php echo _('Salidas Temprano') ?></h2>
                    <div id="selIntervaloSALIDASTEMPRANO" class="widget-toolbar" role="menu">
                        <div class="btn-group">
                            <button id="btn_IntervaloSalidasTemprano"
                                    class="btn dropdown-toggle btn-xs bg-color-blueDark txt-color-white btn-labeled"
                                    data-toggle="dropdown" aria-expanded="true">
                                <span class="btn-label btn-label-widget-dropdown"><i class="fa fa-calendar"></i></span>
                                <span class="btn_text">Hoy </span> <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li class="active">
                                    <a href="javascript:void(0);" data-intervalo="hoy"
                                       onclick="selIntervaloSALIDASTEMPRANO(this);return;"
                                       class="a-item-widget-dropdown"><i class="fa fa-check i-item-widget-dropdown"></i>Hoy </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" data-intervalo="esta_semana"
                                       onclick="selIntervaloSALIDASTEMPRANO(this);return;"
                                       class="a-item-widget-dropdown"><i class="fa i-item-widget-dropdown"></i>Semana </a>
                                </li>
                            </ul>
                        </div>
                    </div>
					 <div class="widget-toolbar" role="menu">
                        <div class="badge bg-color-greenLight" id="div_unread_count_salidas_temprano" style="">
                            0
                        </div>
                    </div>
                </header>


                <div><!-- widget div-->
                    <!-- widget content -->
                    <div class="widget-body">

                    </div><!-- end widget content -->
                </div><!-- end widget div -->
            </div><!-- end widget -->
        </article>

    <!-- ALERTAS -->
        <?php /*
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets_empleado/widget_alertas.html.php" data-widget-refresh="1200" id="wid-id-111"
                 data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">

                <header>
                    <span class="widget-icon"> <i class="glyphicon glyphicon-bullhorn"></i> </span>
                    <h2 style="width: 50%;"><?php echo _('Alertas') ?></h2>
                    <div class="widget-toolbar" role="menu">
                        <div class="badge bg-color-greenLight" id="div_unread_count_alertas" style="">
                            0
                        </div>
                    </div>
                </header>


                <div>
                    <div class="widget-body">

                    </div>
                </div>
            </div>
        </article>
        */?>
    </div>



    <!-- ESTADISTICAS, *LLEGADAS TARDE, *MENSAJES -->
    <div class="row">

        <!-- ESTADISTICAS -->
        <?php /*
        <!-- ESTADISTICAS -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets_empleado/widget_estadisticas.html.php" id="wid-id-105"
                 data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                    <h2>
                        <?php echo _('Estadísticas') ?>
                    </h2>
                </header>

                <div><div class="widget-body"></div></div>
            </div>
        </article>
    */?>


        <!-- *LLEGADAS TARDE -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets_empleado/widget_llegadas_tarde.html.php?selIntervalo=hoy" id="wid-id-103"
                 data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">
                <!-- ICON, TITLE, BUTTON, TOTAL-->
                <header>
                    <!-- WIDGET ICON -->
                    <span class="widget-icon"><i class="fa fa-clock-o"></i></span>
                    <!-- WIDGET TITLE -->
                    <h2 style="width: 20%;"><?php echo _('Llegadas Tarde') ?></h2>
                    <!-- WIDGET BUTTON -->
                    <div id="selIntervaloLLEGADASTARDE" class="widget-toolbar" role="menu">
                        <div class="btn-group">
                            <!-- BUTTON ICON, BUTTON TEXT -->
                            <button id="btn_IntervaloLlegadasTarde"
                                    class="btn dropdown-toggle btn-xs bg-color-blueDark txt-color-white btn-labeled"
                                    data-toggle="dropdown" aria-expanded="true">
                                <!-- BUTTON ICON -->
                                <span class="btn-label btn-label-widget-dropdown"><i class="fa fa-calendar"></i></span>
                                <!-- BUTTON TEXT-->
                                <span class="btn_text">Hoy </span><i class="fa fa-caret-down"></i>
                            </button>
                            <!-- ON CLICK HOY, ON CLICK ESTA SEMANA -->
                            <ul class="dropdown-menu pull-right">
                                <!-- ON CLICK HOY -->
                                <li class="active">
                                    <a href="javascript:void(0);" data-intervalo="hoy"
                                       onclick="selIntervaloLLEGADASTARDE(this);return;" class="a-item-widget-dropdown">
                                        <i class="fa fa-check i-item-widget-dropdown"></i>Hoy
                                    </a>
                                </li>
                                <!-- ON CLICK ESTA SEMANA -->
                                <li>
                                    <a href="javascript:void(0);" data-intervalo="esta_semana"
                                       onclick="selIntervaloLLEGADASTARDE(this);return;" class="a-item-widget-dropdown">
                                        <i class="fa i-item-widget-dropdown"></i>Semana
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- WIDGET TOTAL -->
                    <div class="widget-toolbar" role="menu">
                        <div class="badge bg-color-greenLight" id="div_unread_count_llegadas_tarde"
                             style="vertical-align: middle">
                            0
                        </div>
                    </div>
                </header>
                <!-- BODY DIV -->
                <div>
                    <div class="widget-body"></div>
                </div>
            </div>

        </article>


        <!-- *MENSAJES -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets_empleado/widget_mensajes.html.php" data-widget-refresh="1200" id="wid-id-110"
                 data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-envelope-o"></i> </span>
                    <h2><?php echo _('Mensajes') ?></h2>
                    <div class="widget-toolbar" role="menu">
                        <div class="badge bg-color-greenLight" id="div_unread_count_mensajes" style="">
                            0
                        </div>
                    </div>
                </header>


                <div>
                    <div class="widget-body">

                    </div>
                </div>
            </div>
        </article>

	</div>

    <!-- FACTURACIÓN -->
    <div class="row">
        <!-- FACTURACION -->
        <?php  /* ?>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets_empleado/widget_facturacion.html.php" data-widget-refresh="500" id="wid-id-107"
                 data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-key"></i> </span>
                    <h2><?php echo _('Suscripción') ?></h2>
                </header>

                <div>
                    <div class="widget-body">

                    </div>
                </div>

            </div>
        </article>
        <?php */ ?>
    </div>

    <!-- NOVEDADES-->   <!-- EQUIPOS -->
    <div class="row">
        <!-- NOVEDADES-->
        <?php /* ?>
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                    <div class="jarviswidget jarviswidget-color-blueDark"
                         data-widget-load="ajax/widgets_empleado/widget_novedades.html.php" data-widget-refresh="1800" id="wid-id-106"
                         data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">

                        <header>
                            <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                            <h2><?php echo _('Novedades enPunto') ?></h2>
                        </header>

                        <div>
                            <div class="widget-body">
                            </div>
                        </div>

                    </div>
                </article><!-- WIDGET END -->
        <?php */ ?>

        <!-- EQUIPOS -->
        <?php /* ?>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets_empleado/widget_equipos.html.php" data-widget-refresh="120" id="wid-id-100"
                 data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-hdd-o"></i> </span>
                    <h2><?php echo _('Equipos') ?></h2>
                </header>

                <div>
                    <div class="widget-body">
                    </div>
                </div>

            </div>
        </article><!-- WIDGET END -->
        <?php */ ?>

    </div>

    <!-- ACCESOS RAPIDOS -->
    <div class="row">
        <!-- NEW WIDGET START -->
        <?php /* ?>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets_empleado/widget_accesos-rapidos.html.php" id="wid-id-108"
                 data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-hdd-o"></i> </span>
                    <h2><?php echo _('Accesos Rápidos') ?></h2>
                </header>


                <div><!-- widget div-->
                    <!-- widget content -->
                    <div class="widget-body">

                    </div><!-- end widget content -->
                </div><!-- end widget div -->
            </div><!-- end widget -->
        </article><!-- WIDGET END -->
        <?php */ ?>

    </div>


</section>

<script type="text/javascript">

    pageSetUp();

    //TODO: puedo mejorar esto, pasando el btn y el widget id en el onclick y hacer una sola funcion para todos
    function selIntervaloLLEGADASTARDE(item) {
        var widget = "#wid-id-103";
        var btn = "#btn_IntervaloLlegadasTarde";
        if ($(item).data("intervalo") === 'hoy') {

            $(widget).find('a[data-intervalo="esta_semana"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="esta_semana"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="hoy"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="hoy"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=hoy');
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Hoy ");

        } else if ($(item).data("intervalo") === 'esta_semana') {

            $(widget).find('a[data-intervalo="hoy"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="hoy"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="esta_semana"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="esta_semana"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=esta_semana')
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Semana ");
        }
    }

    function selIntervaloSALIDASTEMPRANO(item) {

        var widget = "#wid-id-104";
        var btn = "#btn_IntervaloSalidasTemprano";

        if ($(item).data("intervalo") === 'hoy') {

            $(widget).find('a[data-intervalo="esta_semana"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="esta_semana"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="hoy"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="hoy"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=hoy');
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Hoy ");

        } else if ($(item).data("intervalo") === 'esta_semana') {

            $(widget).find('a[data-intervalo="hoy"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="hoy"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="esta_semana"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="esta_semana"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=esta_semana')
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Semana ");
        }

    }

    function selIntervaloAUSENCIAS(item) {

        var widget = "#wid-id-102";
        var btn = "#btn_IntervaloAusencias";

        if ($(item).data("intervalo") === 'hoy') {

            $(widget).find('a[data-intervalo="esta_semana"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="esta_semana"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="hoy"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="hoy"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=hoy');
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Hoy ");

        } else if ($(item).data("intervalo") === 'esta_semana') {

            $(widget).find('a[data-intervalo="hoy"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="hoy"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="esta_semana"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="esta_semana"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=esta_semana')
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Semana ");
        }

    }

    function selFiltroEnVivo(item) {
        var widget = "#wid-id-108";
        var btn = "#btn_FiltroEnVivo";

        if ($(item).data("filtro") === 'grupos') {

            $(widget).find('a[data-filtro="equipos"]').parent().removeClass("active");
            $(widget).find('a[data-filtro="equipos"]').children().removeClass("fa-check");

            $(widget).find('a[data-filtro="grupos"]').parent().addClass("active");
            $(widget).find('a[data-filtro="grupos"]').children().addClass("fa-check");

            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selFiltro=grupos');
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Grupos");
        }

        else if ($(item).data("filtro") === 'equipos') {

            $(widget).find('a[data-filtro="grupos"]').parent().removeClass("active");
            $(widget).find('a[data-filtro="grupos"]').children().removeClass("fa-check");

            $(widget).find('a[data-filtro="equipos"]').parent().addClass("active");
            $(widget).find('a[data-filtro="equipos"]').children().addClass("fa-check");

            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selFiltro=equipos')
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Equipos");
        }
    }

    function setMensajeVisto(mensaje_id) {

        var widget = "#wid-id-110";

        $.ajax({
            cache: false,
            type: 'POST',
            url: 'codigo/controllers/mensajes.php',
            data: {tipo: 'accion', id: mensaje_id, cmd: 'mark_as_read'},
            success: function (data, status) {
                $(widget + ' .jarviswidget-refresh-btn').click();
            }
        });


    }

    $('body').tooltip({
        selector: '[data-toggle="tooltip"], [title]:not([data-toggle="popover"])',
        trigger: 'hover',
        container: 'body'
    }).on('click mousedown mouseup', '[data-toggle="tooltip"], [title]:not([data-toggle="popover"])', function () {
        $('[data-toggle="tooltip"], [title]:not([data-toggle="popover"])').tooltip('destroy');
    });

</script>

<!-- INTRODUCCION AYUDA -->
<?php
$IniciarAyuda = 0;
if (Config_L::p('primera_vez') == 1) {
    $o_primera_vez = Config_L::obtenerPorParametro('primera_vez');
    $o_primera_vez->setValor(0);
    $o_primera_vez->save('Off');
    $IniciarAyuda = 1;
}
//intro.js
if ($IniciarAyuda) { ?>
    <!-- Intro.js -->
    <script src="<?php echo WEB_ROOT; ?>/https://static.enpuntocontrol.com/app/v1/js/plugin/intro/intro.min.js"></script>


    <script type="text/javascript">
        function startIntro() {
            var intro = introJs();
            intro.setOptions({
                skipLabel: 'Salir',
                nextLabel: 'Siguiente',
                prevLabel: 'Atrás',
                doneLabel: 'Terminado',
                doneLabel: 'Siguiente Página',
                steps: [
                    {
                        intro: "<div style=\"width:300px;\"></div><h3>Bienvenido a</br> enPunto!</h3></br> Este breve tutorial le explicará las funciones del sistema.</br></br>Puede acceder nuevamente a este tutorial, presionando el boton de ayuda, en la esquina derecha superior de la página.</br></br> Para continuar, haga click en <b>siguiente</b></b></br>"
                    },
                    {
                        intro: "<div style=\"width:300px;\"></div>Éste es el <b>Dashboard</b>.</br></br>Desde aquí, podrá ver el estado del sistema y estadísticas en tiempo real del mismo.</br></br><b>Veamos que se puede hacer!</b></br></br>"
                    },
                    {
                        element: document.querySelector('#wid-id-100'),
                        position: 'right',
                        intro: "<div style=\"width:300px;\"></div>Éste es el <b>Widget de Equipos</b>.</br></br>Muestra el status de cada Equipo, online/offline y un gráfico del tiempo de respuesta.</br></br>"
                    },
                    {
                        element: document.querySelector('#wid-id-100 header'),
                        position: 'right',
                        intro: "<div style=\"width:300px;\"></div>Puede mover y ordenar los widgets a gusto, arrastrándolos desde la barra superior.</br></br>"
                    },
                    {
                        element: document.querySelector('#wid-id-100 header .jarviswidget-ctrls'),
                        position: 'right',
                        intro: "<div style=\"width:300px;\"></div>También puede maximizarlos a pantalla completa, o minimizarlos.</br></br>"
                    },
                    {
                        element: document.querySelector('#wid-id-104'),
                        position: 'left',
                        intro: "<div style=\"width:300px;\"></div>Éste es el <b>Widget de Accesos Rápidos</b>.</br></br>Los botones le permitirán acceder a las páginas más importantes con un solo click y además puede cargar personas desde la misma página.</br></br>"
                    },
                    {
                        element: document.querySelector('#wid-id-102'),
                        position: 'right',
                        intro: "<div style=\"width:300px;\"></div>Éste es el <b>Widget de Mensajes</b>.</br></br>Todas las alertas del sistema aparecerán en este widget.</br></br>"
                    },
                    {
                        element: document.querySelector('#wid-id-110'),
                        position: 'left',
                        // intro: "<div style=\"width:300px;\"></div>Éste es el <b>Widget de Estadísticas</b>.</br></br>Aquí podrá ver las estadísticas del sistema, como la cantidad de personas por equipo.</br></br>"
                        intro: "<div style=\"width:300px;\"></div>Éste es el <b>Widget de Mensajes</b>.</br></br>Todas las alertas del sistema aparecerán en este widget.</br></br>"
                    },
                    {
                        element: document.querySelector('#wid-id-103'),
                        position: 'right',
                        intro: "<div style=\"width:300px;\"></div>Éste es el <b>Widget de Novedades</b>.</br></br>Manténgase al tanto de todas las novedades de TekBox.</br></br>"
                    },
                    {
                        element: document.querySelector('#wid-id-105'),
                        position: 'left',
                        intro: "<div style=\"width:300px;\"></div>Éste es el <b>Widget de Licencia</b>.</br></br>Aquí podrá observar el estado de la licencia del sistema y los próximos vencimientos.</br></br>"
                    },
                    {
                        element: document.querySelector('#wid-id-111'),
                        position: 'left',
                        intro: "<div style=\"width:300px;\"></div>Éste es el <b>Widget de Licencia</b>.</br></br>Aquí podrá observar el estado de la licencia del sistema y los próximos vencimientos.</br></br>"
                    },
                    {
                        intro: "<div style=\"width:300px;\"></div><h3>Listo para seguir?</h3></br>Exploremos la siguiente página del sistema!</br></br>"
                    }

                ]
            });

            intro.start();
            /*.oncomplete(function() {
             $('nav a[href="equipos"]').trigger('click');
             }); */
        }


        $(document).ready(function () {
            startIntro();
        });


    </script>


<?php } ?>
<?php // require_once APP_PATH . '/includes/chat_widget.php'; ?>




