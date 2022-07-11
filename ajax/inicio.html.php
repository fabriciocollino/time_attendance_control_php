<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<style>
    #div_unread_count_ausencias,
    #div_unread_count_en_vivo,
    #div_unread_count_llegadas_tarde,
    #div_unread_count_salidas_temprano,
    #div_unread_count_licencias,
    #div_unread_count_suspensiones{
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
        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
            <button style="float:right;margin-bottom: 10px;" id="agregar_registro" class="btn btn-sm btn-primary"
                    type="button" data-backdrop="static" data-toggle="modal"
                    data-target="#editar" data-type="view" data-lnk="ajax/logs_equipos-editar.html.php"
                    data-fecha="<?php echo date("Y-m-d");?>">
                <?php echo _('Agregar Registro') ?>
            </button>
            <?php
       
            require_once APP_PATH . '/templates/edit-view_modal.html.php';
            ?>
            <script>
                $("#headerWidget_suspensiones").text("Suspensiones");
                $("#headerWidget_licencias").text("Licencias");



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
                        let log_fecha = '';
                        if (typeof $(this).data('id') !== 'undefined') {
                            data_id = $(this).data('id');
                        }
                        if (typeof $(this).data('lnk') !== 'undefined') {
                            lnk = $(this).data('lnk');
                        }
                        if (typeof $(this).data('type') !== 'undefined') {
                            view_type = $(this).data('type');
                        }
                        if (typeof $(this).data('fecha') !== 'undefined') {
                            log_fecha = $(this).data('fecha');
                        }
                        // alert(lnk);

                        $('.modal-content').html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Cargando...</h1></div>");
                        $.ajax({
                            cache: false,
                            type: 'POST',
                            url: lnk,
                            data: {tipo: view_type, id: data_id,openEditInicio: 'Open Agregar Registro',fecha: log_fecha},
                            success: function (data) {
                                $('.modal-content').show().html(data);
                            }
                        });

                    });
                }
            </script>
        </div>
    </div>

    <!-- ENVIVO, AUSENCIAS, FEED EN VIVO-->
    <div class="row">

        <!-- EN VIVO -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets/widget_en-vivo.html.php?selFiltro=grupos"
                 id="wid-id-108"
                 data-widget-refresh="500"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">
                <!-- ICON, TITLE, BUTTON, TOTAL-->
                <header>
                    <!-- WIDGET ICON -->
                    <span class="widget-icon"> <i class="fa fa-play"></i> </span>
                    <!-- WIDGET TITLE -->
                    <h2 style="width: 22%;"><?php echo _('En Vivo') ?></h2>


                    <!-- WIDGET BUTTON -->
                    <div id="selFiltroEnVivo" class="widget-toolbar" role="menu">
                        <div class="btn-group">

                            <!-- ICON, TITULO -->
                            <button id="btn_FiltroEnVivo"
                                    class="btn dropdown-toggle btn-xs bg-color-blueDark txt-color-white btn-labeled"
                                    data-toggle="dropdown" aria-expanded="true">
                                <!-- ICON -->
                                <span class="btn-label btn-label-widget-dropdown"><i class="fa fa-users"></i></span>
                                <!-- TEXT-->
                                <span class="btn_text">Grupos </span>
                                <i class="fa fa-caret-down"></i>
                            </button>

                            <!-- ON CLICK GRUPOS, EQUIPOS -->
                            <ul class="dropdown-menu pull-right">

                                <!-- ON CLICK GRUPOS -->
                                <li class="active">
                                    <a href="javascript:void(0);"
                                       data-filtro="grupos"
                                       onclick="selFiltroEnVivo(this);return;"
                                       class="a-item-widget-dropdown">
                                        <i class="fa fa-check i-item-widget-dropdown"></i>Grupos </a>
                                </li>

                                <!-- ON CLICK EQUIPOS -->
                                <li>
                                    <a href="javascript:void(0);" data-filtro="equipos"
                                       onclick="selFiltroEnVivo(this);return;" class="a-item-widget-dropdown">
                                        <i class="fa i-item-widget-dropdown"></i>Equipos
                                    </a>
                                </li>

                            </ul>

                        </div>
                    </div>
                    <!-- WIDGET TOTAL -->
                    <div class="widget-toolbar" role="menu">
                        <div class="badge bg-color-greenLight" id="div_unread_count_en_vivo" style="">
                            0
                        </div>
                    </div>

                </header>
                <!-- BODY DIV -->
                <div>
                    <div class="widget-body"></div>
                </div>

            </div><!-- end widget -->
        </article>

        <!-- AUSENCIAS -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="jarviswidget jarviswidget-color-blueDark" data-widget-load="ajax/widgets/widget_ausencias.html.php?selIntervalo=F_Hoy" id="wid-id-102" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false">

                <!-- ICON, TITLE, BUTTON, TOTAL-->
                <header>


                    <!-- ICONO -->
                    <span class="widget-icon">
                        <i class="fa fa-clock-o"></i>
                    </span>

                    <!-- TITULO -->
                    <h2 style="width: 16%;">
                        <?php echo _('Ausencias') ?>
                    </h2>


                    <!-- WIDGET BUTTON -->
                    <div id="selIntervaloAUSENCIAS" class="widget-toolbar" role="menu">
                        <div class="btn-group">

                            <!-- ICON, TITULO-->
                            <button id="btn_IntervaloAusencias" class="btn dropdown-toggle btn-xs bg-color-blueDark txt-color-white btn-labeled" data-toggle="dropdown" aria-expanded="true">
                                <span class="btn-label btn-label-widget-dropdown">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <span class="btn_text">Hoy </span> <i class="fa fa-caret-down"></i>
                            </button>

                            <!-- ON CLICK DIA, SEMANA -->
                            <ul class="dropdown-menu pull-right">

                                <!-- ON CLICK DIA -->
                                <li class="active">
                                    <a href="javascript:void(0);" data-intervalo="F_Hoy" onclick="selIntervaloAUSENCIAS(this);return;" class="a-item-widget-dropdown">
                                        <i class="fa fa-check i-item-widget-dropdown"></i>Hoy </a>
                                </li>

                                <!-- ON CLICK SEMANA -->
                                <li>
                                    <a href="javascript:void(0);" data-intervalo="F_Semana" onclick="selIntervaloAUSENCIAS(this);return;" class="a-item-widget-dropdown">
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

    <!-- LLEGADAS TARDE, SALIDAS TEMPRANO -->
    <div class="row">
        <!-- LLEGADAS TARDE -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets/widget_llegadas_tarde.html.php?selIntervalo=F_Hoy" id="wid-id-103"
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
                                    <a href="javascript:void(0);" data-intervalo="F_Hoy"
                                       onclick="selIntervaloLLEGADASTARDE(this);return;" class="a-item-widget-dropdown">
                                        <i class="fa fa-check i-item-widget-dropdown"></i>Hoy
                                    </a>
                                </li>
                                <!-- ON CLICK ESTA SEMANA -->
                                <li>
                                    <a href="javascript:void(0);" data-intervalo="F_Semana"
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
        
        
        
        
        
        
        
        
        
        
        <!-- SALIDAS TEMPRANO -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets/widget_salidas_temprano.html.php?selIntervalo=F_Hoy" id="wid-id-104"
                 data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">

                <header>
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
                                    <a href="javascript:void(0);" data-intervalo="F_Hoy"
                                       onclick="selIntervaloSALIDASTEMPRANO(this);return;"
                                       class="a-item-widget-dropdown"><i class="fa fa-check i-item-widget-dropdown"></i>Hoy </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" data-intervalo="F_Semana"
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

    </div>

    <!-- LICENCIAS, SUSPENSIONES -->
    <div class="row">
        
        <!-- LICENCIAS -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets/widget_licencias.html.php?selIntervalo=F_Hoy" id="wid-id-153"
                 data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">
                <!-- ICON, TITLE, BUTTON, TOTAL-->
                <header>
                    <!-- WIDGET ICON -->
                    <span class="widget-icon"><i class="fa fa-calendar"></i></span>
                    <!-- WIDGET TITLE -->
                    <h2 id="headerWidget_licencias" style="width: 20%;">Licencias</h2>
                    <!-- WIDGET BUTTON -->
                    <div id="selIntervaloLICENCIAS" class="widget-toolbar" role="menu">
                        <div class="btn-group">
                            <!-- BUTTON ICON, BUTTON TEXT -->
                            <button id="btn_IntervaloLicencias"
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
                                    <a href="javascript:void(0);" data-intervalo="F_Hoy"
                                       onclick="selIntervaloLICENCIAS(this);return;" class="a-item-widget-dropdown">
                                        <i class="fa fa-check i-item-widget-dropdown"></i>Hoy
                                    </a>
                                </li>
                                <!-- ON CLICK ESTA SEMANA -->
                                <li>
                                    <a href="javascript:void(0);" data-intervalo="F_Semana"
                                       onclick="selIntervaloLICENCIAS(this);return;" class="a-item-widget-dropdown">
                                        <i class="fa i-item-widget-dropdown"></i>Semana
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- WIDGET TOTAL -->
                    <div class="widget-toolbar" role="menu">
                        <div class="badge bg-color-greenLight" id="div_unread_count_licencias"
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


        <!-- SUSPENSIONES -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

            <div class="jarviswidget jarviswidget-color-blueDark"
                 data-widget-load="ajax/widgets/widget_suspensiones.html.php?selIntervalo=F_Hoy" id="wid-id-134"
                 data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
                 data-widget-fullscreenbutton="false">
                <!-- ICON, TITLE, BUTTON, TOTAL-->
                <header>
                    <!-- WIDGET ICON -->
                    <span class="widget-icon"><i class="fa fa-calendar"></i></span>
                    <!-- WIDGET TITLE -->
                    <h2 id="headerWidget_suspensiones" style="width: 20%;">Suspensiones</h2>
                    <!-- WIDGET BUTTON -->
                    <div id="selIntervaloSUSPENSIONES" class="widget-toolbar" role="menu">
                        <div class="btn-group">
                            <!-- BUTTON ICON, BUTTON TEXT -->
                            <button id="btn_IntervaloSuspensiones"
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
                                    <a href="javascript:void(0);" data-intervalo="F_Hoy"
                                       onclick="selIntervaloSUSPENSIONES(this);return;" class="a-item-widget-dropdown">
                                        <i class="fa fa-check i-item-widget-dropdown"></i>Hoy
                                    </a>
                                </li>
                                <!-- ON CLICK ESTA SEMANA -->
                                <li>
                                    <a href="javascript:void(0);" data-intervalo="F_Semana"
                                       onclick="selIntervaloSUSPENSIONES(this);return;" class="a-item-widget-dropdown">
                                        <i class="fa i-item-widget-dropdown"></i>Semana
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- WIDGET TOTAL -->
                    <div class="widget-toolbar" role="menu">
                        <div class="badge bg-color-greenLight" id="div_unread_count_suspensiones"
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


    </div>


</section>

<script type="text/javascript">

    pageSetUp();

    function selIntervaloLLEGADASTARDE(item) {
        var widget = "#wid-id-103";
        var btn = "#btn_IntervaloLlegadasTarde";
        if ($(item).data("intervalo") === 'F_Hoy') {

            $(widget).find('a[data-intervalo="F_Semana"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="F_Semana"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="F_Hoy"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="F_Hoy"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=F_Hoy');
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Hoy ");

        } else if ($(item).data("intervalo") === 'F_Semana') {

            $(widget).find('a[data-intervalo="F_Hoy"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="F_Hoy"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="F_Semana"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="F_Semana"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=F_Semana')
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Semana ");
        }
    }

    function selIntervaloLICENCIAS(item) {
        var widget = "#wid-id-153";
        var btn = "#btn_IntervaloLicencias";
        if ($(item).data("intervalo") === 'F_Hoy') {

            $(widget).find('a[data-intervalo="F_Semana"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="F_Semana"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="F_Hoy"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="F_Hoy"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=F_Hoy');
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Hoy ");

        } else if ($(item).data("intervalo") === 'F_Semana') {

            $(widget).find('a[data-intervalo="F_Hoy"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="F_Hoy"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="F_Semana"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="F_Semana"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=F_Semana')
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Semana ");
        }
    }

    function selIntervaloSUSPENSIONES(item) {
        var widget = "#wid-id-134";
        var btn = "#btn_IntervaloSuspensiones";
        if ($(item).data("intervalo") === 'F_Hoy') {

            $(widget).find('a[data-intervalo="F_Semana"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="F_Semana"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="F_Hoy"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="F_Hoy"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=F_Hoy');
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Hoy ");

        } else if ($(item).data("intervalo") === 'F_Semana') {

            $(widget).find('a[data-intervalo="F_Hoy"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="F_Hoy"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="F_Semana"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="F_Semana"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=F_Semana')
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Semana ");
        }
    }

    function selIntervaloSALIDASTEMPRANO(item) {

        var widget = "#wid-id-104";
        var btn = "#btn_IntervaloSalidasTemprano";

        if ($(item).data("intervalo") === 'F_Hoy') {

            $(widget).find('a[data-intervalo="F_Semana"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="F_Semana"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="F_Hoy"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="F_Hoy"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=F_Hoy');
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Hoy ");

        } else if ($(item).data("intervalo") === 'F_Semana') {

            $(widget).find('a[data-intervalo="F_Hoy"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="F_Hoy"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="F_Semana"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="F_Semana"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=F_Semana')
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Semana ");
        }

    }

    function selIntervaloAUSENCIAS(item) {

        var widget = "#wid-id-102";
        var btn = "#btn_IntervaloAusencias";

        if ($(item).data("intervalo") === 'F_Hoy') {

            $(widget).find('a[data-intervalo="F_Semana"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="F_Semana"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="F_Hoy"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="F_Hoy"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=F_Hoy');
            $(widget + ' .jarviswidget-refresh-btn').click();
            $(btn + ' .btn_text').text("Hoy ");

        } else if ($(item).data("intervalo") === 'F_Semana') {

            $(widget).find('a[data-intervalo="F_Hoy"]').parent().removeClass("active");
            $(widget).find('a[data-intervalo="F_Hoy"]').children().removeClass("fa-check");
            $(widget).find('a[data-intervalo="F_Semana"]').parent().addClass("active");
            $(widget).find('a[data-intervalo="F_Semana"]').children().addClass("fa-check");
            $(widget).data("widget-load", $(widget).data("widget-load").split('?')[0] + '?selIntervalo=F_Semana')
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


    $('body').tooltip({
        selector: '[data-toggle="tooltip"], [title]:not([data-toggle="popover"])',
        trigger: 'hover',
        container: 'body'}).on('click mousedown mouseup', '[data-toggle="tooltip"], [title]:not([data-toggle="popover"])', function () {
        $('[data-toggle="tooltip"], [title]:not([data-toggle="popover"])').tooltip('destroy');});

</script>




