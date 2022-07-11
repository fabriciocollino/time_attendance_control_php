


<article class = "col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class                      = "jarviswidget jarviswidget-color-blueDark"
         id                         = "wid-id-50"
         data-widget-editbutton     = "false"
         data-widget-colorbutton    = "false"
         data-widget-deletebutton   = "false"
         data-widget-sortable       = "false"
         data-widget-fullscreenbutton="false">

        <!-- HEADER -->
        <header>
            <span class = "widget-icon"> <i class = "fa fa-filter"></i> </span>
            <h2><?php echo _('Filtros') ?></h2>
        </header>

        <!-- DIV TABLE FILTER -->
        <div>
            <div class = "widget-body no-padding">


                <form class             = "smart-form"
                      novalidate        = "novalidate"
                      data-async        = ""
                      method            = "post"
                      id                = "filtro-form"
                      action            = "<?php echo $Filtro_Form_Action; ?>">

                    <fieldset>

                        <!-- PERSONA, GRUPO -->
                        <div class  = "row">

                            <section class = "col col-1"> </section>


                            <!-- PERSONA -->
                            <section class = "col col-4">

                                <!-- HEADER -->
                                <label class = "select">
                                    Persona
                                </label>

                                <!-- OPCIONES PERSONA -->

                                <?php
                                // LISTADO PERSONAS
                                $o_Lista            = Persona_L::obtenerDesdeFiltro($T_Filtro_Array,'per_Apellido ASC' );
                                $o_Lista_index      = $T_Filtro_Array["Persona"];
                                ?>

                                <label class = "select">
                                    <span class = "icon-prepend fa fa-user"></span>
                                    <select name = "persona" id = "selPersona" style = "padding-left: 32px;">
                                        <?php
                                        echo HtmlHelper::array2htmloptions($o_Lista,$o_Lista_index, true, true, 'PersonayRol', _('Todas las Personas'));
                                        ?>
                                    </select> <i></i>
                                </label>
                            </section>


                            <!-- GRUPOS -->
                            <section class = "col col-4" id = "selRol">
                                <label class = "select">
                                    Grupo
                                </label>

                                <!-- OPCIONES GRUPO -->
                                <label class = "select">
                                    <span class = "icon-prepend fa fa-user"></span>
                                    <select name = "rolF" id = "rolF" style = "padding-left: 32px;">
                                        <?php
                                        $o_Lista          = Grupo_L::obtenerTodos();
                                        $o_Lista_index    = $T_Filtro_Array["Grupo"];

                                        echo HtmlHelper::array2htmloptions($o_Lista,$o_Lista_index , true, true, '', _('Seleccione un Grupo'));
                                        ?>
                                    </select> <i></i>
                                </label>

                            </section>
                        </div>

                        <!-- ESTADO -->
                        <div class  ="row">
                            <section class="col col-1"> </section>

                            <!-- ESTADO -->
                            <section class="col col-4">
                                Estado
                                <label class="select">
                                    <span class="icon-prepend fa fa-check-circle"></span>
                                    <select name="estado" id="estadoSel" style="padding-left: 32px;">
                                        <option value="Todos">Todos</option>
                                        <option value="Activo">Activos</option>
                                        <option value="Inactivo">Inactivos</option>
                                    </select> <i></i> </label>
                            </section>

                        </div>

                        <!-- INTERVALO -->
                        <div class  = "row" id = "div_intervalo">
                            <section class="col col-1"> </section>

                            <section class="col col-2" style="min-width: 180px;">
                                <label class="select">
                                    Fecha
                                </label>

                                <!-- OPCIONES FECHA -->
                                <label class="select">
                                    <span class="icon-prepend fa fa-calendar"></span>
                                    <select name="intervaloFecha" id="selFecha" style="padding-left: 32px;">
                                        <?php
                                        $o_Lista          = $IntervalosFechas;
                                        $o_Lista_index    = isset($_SESSION['filtro']['intervalo']) ? $_SESSION['filtro']['intervalo'] : 'F_Hoy' ;
                                        echo HtmlHelper::array2htmloptions($o_Lista, $o_Lista_index , false, false);
                                        ?>
                                    </select> <i></i>
                                </label>


                            </section>

                            <!-- FECHA PERSONALIZADA-->
                            <div id="fechaPersonalizada">


                                <!-- FECHA DESDE -->
                                <section class="col col-2" style="min-width: 195px;">
                                    <label class="select">
                                        Intervalo
                                    </label>



                                    <div class="form-group">
                                        <div class="input-group">
                                            <input class        = "form-control "
                                                   name         = "fechaD"
                                                   id           = "fechaD"
                                                   type         = "text"
                                                   style        = "padding-left: 5px;font-size: 12px;height: 31px;"
                                                   placeholder  = "Desde"
                                                   value        = "<?php echo $_SESSION['filtro']['fechaD']; ?>" />

                                            <span id="btnDesde" class="input-group-addon">
                                                <i class="fa fa-calendar" style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
                                            </span>
                                        </div>
                                    </div>



                                </section>

                                <!-- FECHA HASTA-->
                                <section class="col col-2" style="min-width: 195px;">
                                    <label class="select">&nbsp;</label>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <input class="form-control "
                                                   style        = "padding-left: 5px;font-size: 12px;height: 31px;"
                                                   name         = "fechaH"
                                                   id           = "fechaH"
                                                   type         = "text"
                                                   placeholder  = "Hasta"
                                                   value        = "<?php echo $_SESSION['filtro']['fechaH']; ?>" />

                                            <span id="btnHasta" class="input-group-addon">
                                                <i class="fa fa-calendar" style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
											</span>
                                        </div>
                                    </div>
                                </section>


                            </div>
                        </div>


                        <!-- MOSTRAR TODOS LOS FILTROS -->
                        <div class  = "row" >

                            <section class="col col-1"> </section>

                            <section class="col col-4">

                                <a id = "button_toggle" class="toggle-btn" href="javascript:void(0);" style="color: darkgrey;background-color: transparent;">Mostrar filtros</a>

                            </section>
                        </div>

                        <!-- EQUIPO, GENERO, RANGO DE EDAD, ESTADO CIVIL, TALLA DE CAMISA, LEGAJO, DNI, NRO CONTRIBUYENTE, CORREO ELECTRONICO TRABAJO -->
                        <div class  = "row" id = "filtro_toggle" style="display:none;margin-left: 0px;">


                            <!-- HORARIO -->
                            <div class  = "row">
                                <section class="col col-1"> </section>



                                <section class="col col-4">
                                    <label class = "select">Tipo de Horario</label>
                                    <label class="select">
                                        <select name="horario_tipo_id" id="horario_tipo_id" style="padding-left: 32px;">
                                            <?php echo HtmlHelper::array2htmloptions($a_Tipos_De_Horario,   $T_Filtro_Array["Tipo de Horario"], true, false, '', _('Todos')); ?>
                                        </select> <i></i>
                                    </label>
                                </section>
                                <section class="col col-4" id="sel-horarioNormal1">
                                    <label class = "select">Horario</label>
                                    <label class="select">
                                        <span class="icon-prepend fa fa-clock-o"></span>
                                        <select name="horario_normal_id" id="horario_normal_id" style="padding-left: 32px;">
                                            <?php echo HtmlHelper::array2htmloptions(Hora_Trabajo_L::obtenerTodos(),   $T_Filtro_Array["Tipo de Horario"] == HORARIO_NORMAL ? $T_Filtro_Array["Horario Normal"] : 0, true, true, 'Filtro_Horario', 'Todos'); ?>
                                        </select> <i></i>
                                    </label>
                                </section>
                                <section class="col col-4" id="sel-horarioFlexible1">
                                    <label class = "select">Horario</label>
                                    <label class="select">
                                        <span class="icon-prepend fa fa-clock-o"></span>
                                        <select name="horario_flexible_id" id="horario_flexible_id" style="padding-left: 32px;">
                                            <?php echo HtmlHelper::array2htmloptions(Horario_Flexible_L::obtenerTodos(),  $T_Filtro_Array["Tipo de Horario"] == HORARIO_FLEXIBLE ? $T_Filtro_Array["Horario Flexible"] : 0, true, true, 'Filtro_Horario', 'Todos'); ?>
                                        </select> <i></i>
                                    </label>
                                </section>
                                <section class="col col-4" id="sel-horarioRotativo1">
                                    <label class = "select">Horario</label>
                                    <label class="select">
                                        <span class="icon-prepend fa fa-clock-o"></span>
                                        <select name="horario_rotativo_id" id="horario_rotativo_id" style="padding-left: 32px;">
                                            <?php echo HtmlHelper::array2htmloptions(Horario_Rotativo_L::obtenerTodos(),   $T_Filtro_Array["Tipo de Horario"] == HORARIO_ROTATIVO ?   $T_Filtro_Array["Horario Rotativo"] : 0, true, true, 'Filtro_Horario', 'Todos'); ?>
                                        </select> <i></i>
                                    </label>
                                </section>
                                <section class="col col-4" id="sel-horarioMultiple1">
                                    <label class = "select">Horario</label>

                                    <label class="select">
                                        <span class="icon-prepend fa fa-clock-o"></span>
                                        <select name="horario_multiple_id" id="horario_multiple_id" style="padding-left: 32px;">
                                            <?php echo HtmlHelper::array2htmloptions(Horario_Multiple_L::obtenerTodos(),$T_Filtro_Horario_Tipo == HORARIO_MULTIPLE ?        $T_Filtro_Array["Horario Multiple"] : 0, true, true, 'Filtro_Horario', 'Todos'); ?>
                                        </select> <i></i>
                                    </label>
                                </section>

                            </div>

                            <!-- EQUIPO -->
                            <div class  ="row">
                                <section class="col col-1"> </section>
                                <section class="col col-4">
                                    <label class = "select">Equipo</label>
                                    <label class="select">
                                        <select name="equipo_id" id="equipoSel" style="padding-left: 32px;">
                                            <?php


                                            $array_filtro_equipos = Equipo_L::obtenerTodosenArray();
                                            $object = new Equipo_O();
                                            $object->setDetalle("Web");
                                            $object->setId(-99);
                                            array_unshift($array_filtro_equipos,$object);

                                            echo HtmlHelper::array2htmloptions($array_filtro_equipos, $T_Filtro_Array["Equipo"], true, true, '', _('Todos'));

                                            ?>
                                        </select> <i></i>
                                    </label>
                                </section>
                            </div>

                            <!-- GENERO -->
                            <div class  = "row">
                                <section class="col col-1"> </section>
                                <section class="col col-4">
                                    Género
                                    <label class="select">
                                        <select name="genero" id="generoSel" style="padding-left: 32px;">
                                            <option value="">Todos</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                        </select> <i></i> </label>
                                </section>
                            </div>

                            <!-- ESTADO CIVIL, TALLA DE CAMISA -->
                            <div class  = "row">
                                <section class="col col-1"> </section>

                                <!-- ESTADO CIVIL -->
                                <section class="col col-2">
                                    Estado civil
                                    <label class="select">
                                        <select name="estadoCivil" id="estadoCivilSel" style="padding-left: 32px;">
                                            <option value="">Todos</option>
                                            <option value="Soltero/a">Soltero/a</option>
                                            <option value="Casado/a">Casado/a</option>
                                            <option value="Viudo/a">Viudo/a</option>
                                        </select> <i></i> </label>
                                </section>

                                <!-- TALLA DE CAMISA -->
                                <section class="col col-2">
                                    Talla de camisa
                                    <label class="select">
                                        <select name="talleCamisa" id="talleCamisaSel" style="padding-left: 32px;">
                                            <option value="">Todos</option>
                                            <option value="XS">XS</option>
                                            <option value="S">S</option>
                                            <option value="M">M</option>
                                            <option value="L">L</option>
                                            <option value="XL">XL</option>
                                            <option value="XXL">XXL</option>
                                        </select> <i></i> </label>
                                </section>

                            </div>

                            <!-- RANGO DE EDAD -->
                            <div class  = "row">
                                <section class="col col-1"> </section>

                                <!-- EDAD DESDE  -->
                                <section class="col col-2">
                                    Rango de edad
                                    <label class="input">
                                        <input type="number" name="edad_desde" id="edad_desde" placeholder=""  min="0">
                                    </label>
                                </section>
                                <!-- EDAD HASTA -->
                                <section class="col col-2"><br>
                                    <label class="input">
                                        <input type="number" name="edad_hasta" id="edad_hasta" placeholder="" min="0" >
                                    </label>
                                </section>


                            </div>
                            
                            <!-- LEGAJO, DNI -->
                            <div class  = "row">
                                <section class="col col-1"> </section>

                                <!-- LEGAJO -->
                                <section class="col col-4">
                                Legajo
                                <label class="input">
                                    <input type="text" name="legajo" id="legajo" placeholder="">
                                </label>

                                <!-- DNI -->
                                </section>
                                <section class="col col-4">
                                    DNI
                                    <label class="input">
                                        <input type="text" name="dni" id="dni" placeholder="">
                                    </label>
                                </section>
                            </div>

                            <!-- NRO CONTRIBUYENTE, CORREO ELECTRONICO TRABAJO -->
                            <div class  = "row">
                                <section class="col col-1"> </section>

                                <!-- NRO CONTRIBUYENTE -->
                                <section class="col col-4">
                                    Nro. de contribuyente
                                    <label class="input">
                                        <input type="text" name="nroContribuyente" id="nroContribuyente" placeholder="">
                                    </label>
                                </section>

                                <!-- CORREO ELECTRONICO TRABAJO -->
                                <section class="col col-4">
                                    Correo Electrónico
                                    <label class="input">
                                        <input type="email" name="email" id="email">
                                    </label>
                                </section>

                            </div>


                            <!-- BORRAR FILTROS -->
                            <div class  = "row" >
                                <section class="col col-1"> </section>

                                <section class="col col-4">
                                    <a id = "borrar_filtros" href="javascript:void(0);" style="color: darkgrey;background-color: transparent;">
                                        Borrar filtros
                                    </a>

                                </section>
                            </div>



                        </div>

                    </fieldset>

                    <!-- FILTRAR -->
                    <footer>
                        <!-- FILTRAR -->
                        <button type="button" name="btnFiltro" id="submit-filtro" class="btn btn-primary">
                            Filtrar
                        </button>


                    </footer>



                    <script type="text/javascript">


                        // SELECT PERSONA CHANGE
                        $('#selPersona').change(function () {

                            if ($(this).find('option:selected').attr('value') === 'SelectRol') {
                                $('#selRol').show();
                            }
                            else {
                                $('#rolF').val('');
                                $('#selRol').hide();
                            }

                        });

                        // SELECT FECHA ON CHANGE
                        $('#selFecha').change(function () {

                            if ($(this).find('option:selected').attr('value') === 'F_Personalizado') {
                                $('#fechaPersonalizada').show();
                            }
                            else {
                                $('#fechaPersonalizada').hide();
                            }
                        });

                        // SELECT HORARIO CHANGE
                        $('#horario_tipo_id').change(function () {

                            if ($(this).find('option:selected').attr('value') ==<?php echo HORARIO_NORMAL; ?>) {
                                $('#sel-horarioNormal1').show();
                                $('#sel-horarioFlexible1').hide();
                                $('#sel-horarioRotativo1').hide();
                                $('#sel-horarioMultiple1').hide();

                                $('#horario_flexible_id').val('');
                                $('#horario_rotativo_id').val('');
                                $('#horario_multiple_id').val('');
                            }
                            else if ($(this).find('option:selected').attr('value') ==<?php echo HORARIO_FLEXIBLE; ?>) {
                                $('#sel-horarioNormal1').hide();
                                $('#sel-horarioFlexible1').show();
                                $('#sel-horarioRotativo1').hide();
                                $('#sel-horarioMultiple1').hide();

                                $('#horario_normal_id').val('');
                                $('#horario_rotativo_id').val('');
                                $('#horario_multiple_id').val('');
                            }
                            else if ($(this).find('option:selected').attr('value') ==<?php echo HORARIO_ROTATIVO; ?>) {
                                $('#sel-horarioNormal1').hide();
                                $('#sel-horarioFlexible1').hide();
                                $('#sel-horarioRotativo1').show();
                                $('#sel-horarioMultiple1').hide();

                                $('#horario_normal_id').val('');
                                $('#horario_flexible_id').val('');
                                $('#horario_multiple_id').val('');
                            }
                            else if ($(this).find('option:selected').attr('value') ==<?php echo HORARIO_MULTIPLE; ?>) {
                                $('#sel-horarioNormal1').hide();
                                $('#sel-horarioFlexible1').hide();
                                $('#sel-horarioRotativo1').hide();
                                $('#sel-horarioMultiple1').show();

                                $('#horario_normal_id').val('');
                                $('#horario_flexible_id').val('');
                                $('#horario_rotativo_id').val('');
                            }
                            else {
                                $('#sel-horarioNormal1').hide();
                                $('#sel-horarioFlexible1').hide();
                                $('#sel-horarioRotativo1').hide();
                                $('#sel-horarioMultiple1').hide();

                                $('#horario_normal_id').val('');
                                $('#horario_flexible_id').val('');
                                $('#horario_rotativo_id').val('');
                                $('#horario_multiple_id').val('');
                            }
                        });



                        // FECHA DESDE
                        $("#fechaD").datetimepicker({
                            locale: 'es',
                            collapse: true,
                            //sideBySide: true,
                            format: 'YYYY-MM-DD HH:mm:ss'
                        });

                        // FECHA HASTA
                        $("#fechaH").datetimepicker({
                            locale: 'es',
                            collapse: true,
                            //sideBySide: true,
                            format: 'YYYY-MM-DD HH:mm:ss'
                        });


                        $("#fechaD").change(function () {

                            $("#fechaH").datetimepicker({
                                minDate: $("#fechaD").val()
                            });

                        });

                        $("#fechaH").change(function () {

                            $("#fechaD").datetimepicker({
                                maxDate: $("#fechaH").val()
                            });

                        });


                        // FECHA DESDE
                        $('#btnDesde').click(function () {
                            $(document).ready(function () {
                                $("#fechaD").datetimepicker().focus();
                            });
                        });

                        // FECHA HASTA
                        $('#btnHasta').click(function () {
                            $(document).ready(function () {
                                $("#fechaH").datetimepicker().focus();
                            });
                        });


                        // HTML READY
                        $(document).ready(function () {

                            <?php if(!in_array($Item_Name,$Filtro_Mostrar ['Intervalo'])){  ?>
                                $('#div_intervalo').hide();
                            <?php } ?>



                            // VARIABLES
                            document.getElementById("estadoSel").value              = "<?php echo $T_Filtro_Array["Estado"];?>";
                            document.getElementById("generoSel").value              = "<?php echo $T_Filtro_Array["Genero"];?>";
                            document.getElementById("dni").value                    = "<?php echo $T_Filtro_Array["DNI"];?>";
                            document.getElementById("legajo").value                 = "<?php echo $T_Filtro_Array["Legajo"];?>";
                            document.getElementById("estadoCivilSel").value         = "<?php echo $T_Filtro_Array["Estado Civil"];?>";

                            document.getElementById("horario_tipo_id").value        = "<?php echo $T_Filtro_Array["Tipo de Horario"];?>";
                            document.getElementById("horario_normal_id").value      = "<?php echo $T_Filtro_Array["Horario Normal"];?>";
                            document.getElementById("horario_flexible_id").value    = "<?php echo $T_Filtro_Array["Horario Flexible"];?>";
                            document.getElementById("horario_multiple_id").value    = "<?php echo $T_Filtro_Array["Horario Multiple"];?>";
                            document.getElementById("horario_rotativo_id").value    = "<?php echo $T_Filtro_Array["Horario Rotativo"];?>";

                            document.getElementById("equipoSel").value              = "<?php echo $T_Filtro_Array["Equipo"];?>";
                            document.getElementById("nroContribuyente").value       = "<?php echo $T_Filtro_Array["Nro. Contribuyente"];?>";
                            document.getElementById("talleCamisaSel").value         = "<?php echo $T_Filtro_Array["Talle Camisa"];?>";
                            document.getElementById("email").value                  = "<?php echo $T_Filtro_Array["Email"];?>";
                            document.getElementById("edad_desde").value             = "<?php echo $T_Filtro_Array["Edad Desde"];?>";
                            document.getElementById("edad_hasta").value             = "<?php echo $T_Filtro_Array["Edad Hasta"];?>";

                            // TRIGGER CHANGE
                            $('#selPersona').trigger("change");
                            $('#selFecha').trigger("change");
                            $('#horario_tipo_id').trigger("change");


                            // SUBMIT
                            $('#submit-filtro').click(function () {

                                var $form = $('#filtro-form');

                                if (!$('#filtro-form').valid()) {
                                    return false;
                                }
                                else {
                                    $('#content').html('<h1 class="ajax-loading-animation"><i class="fa fa-cog fa-spin"></i> Cargando...</h1>');
                                    $.ajax({
                                        type    : $form.attr('method'),
                                        url     : $form.attr('action'),
                                        data    : $form.serialize(),

                                        success: function (data, status) {
                                            $('#content').css({opacity: '0.0'}).html(data).delay(50).animate({opacity: '1.0'}, 300);
                                            $('#editar').off('hidden.bs.modal');
                                        }
                                    });
                                }
                            });

                            // SUBMIT
                            $('#borrar_filtros').click(function () {

                                // VARIABLES
                                // VARIABLES
                                document.getElementById("selPersona").value             = 'TodasLasPersonas'
                                document.getElementById("rolF").value                   = '';

                                document.getElementById("estadoSel").value              = "Activo";
                                document.getElementById("generoSel").value              = "";
                                document.getElementById("dni").value                    = "";
                                document.getElementById("legajo").value                 = "";
                                document.getElementById("estadoCivilSel").value         = "";

                                document.getElementById("horario_tipo_id").value        = "";
                                document.getElementById("horario_normal_id").value      = "";
                                document.getElementById("horario_flexible_id").value    = "";
                                document.getElementById("horario_multiple_id").value    = "";
                                document.getElementById("horario_rotativo_id").value    = "";

                                document.getElementById("equipoSel").value              = "";
                                document.getElementById("nroContribuyente").value       = "";
                                document.getElementById("talleCamisaSel").value         = "";
                                document.getElementById("email").value                  = "";
                                document.getElementById("edad_desde").value             = "";
                                document.getElementById("edad_hasta").value             = "";

                                document.getElementById("selFecha").value               = "F_Hoy";

                                // TRIGGER CHANGE
                                $('#selPersona').trigger("change");
                                $('#selFecha').trigger("change");
                                $('#horario_tipo_id').trigger("change");

                            });


                            // KEYUP
                            $('#filtro-form').bind("keyup keypress", function (e) {
                                var code = e.keyCode || e.which;
                                if (code == 13) {
                                    e.preventDefault();
                                    return false;
                                }
                            });

                            // EFECTO TOGGLE: MOSTRAR FILTROS
                            $(".toggle-btn").click(function(){
                                $("#filtro_toggle").slideToggle();
                            });

                        });
                    </script>

                    <style>

                        a:visited {
                            background: transparent;
                        }

                    </style>


                </form>
            </div>
            <!-- END: DIV TABLE FILTER -->

        </div>
        <!-- END: TABLE DIV -->
    </div>
</article>
<!-- END: TABLE ARTICULE -->
