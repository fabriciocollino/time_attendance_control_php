<?php

/*
 *
 * Para que este widget funcione, hay que asignar la variable $Filtro_Form_Action previamente
 */
?>


<!-- TABLE ARTICULE -->
<article class = "col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <!-- TABLE DIV -->
    <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-51"
         data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false"
         data-widget-sortable="false"    data-widget-fullscreenbutton="false">

        <!-- HEADER -->
        <header>
            <span class="widget-icon"> <i class="fa fa-filter"></i> </span>
            <h2><?php echo _('Filtros') ?></h2>
        </header>

        <!-- widget div-->
        <div>
            <!-- DIV TABLE FILTER -->
            <div class="widget-body no-padding">

                <form class="smart-form" novalidate="novalidate" data-async="" method="post" id="filtro-form"
                      action="<?php echo $Filtro_Form_Action; ?>">

                    <!-- TOGGLE FLAGDATA, TOGGLE ACTIVO -->

                    <fieldset>

                        <div class = "row">

                            <section class = "col col-1"> </section>


                            <!-- PERSONA -->
                            <section class = "col col-4">

                                <!-- HEADER -->
                                <label class = "select">
                                    Persona
                                </label>

                                <!-- OPCIONES PERSONA -->
                                <label class = "select">
                                    <span class = "icon-prepend fa fa-user"></span>
                                    <select name = "persona" id = "selPersona" style = "padding-left: 32px;">
                                        <?php
                                        $condition = $_SESSION['filtro']['activos'] == ('on' || 1) ? 'per_Eliminada = 0' : 'per_Eliminada = 0  and per_Excluir=0';
                                        echo HtmlHelper::array2htmloptions(Persona_L::obtenerTodos(0, 0, 0, $condition), $_SESSION['filtro']['persona'], true, true, 'PersonayRol', _('Todas las Personas'));
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
                                        echo HtmlHelper::array2htmloptions(Grupo_L::obtenerTodos(), $_SESSION['filtro']['rolF'], true, true, '', _('Seleccione un Grupo'));
                                        ?>
                                    </select> <i></i>
                                </label>

                            </section>

                            <input type="hidden" id="activos" name="activos" value="<?php  echo $_SESSION['filtro']['activos'] ;?>";>

                        </div>

                    </fieldset>

                    <!-- BOTON FILTRAR FOOTER -->
                    <footer>

                        <!-- TOGGLE FLAGDATA -->
                        <section class="col col-1">
                            <input type="hidden" value="0" name="flagdata">
                        </section>

                        <!-- TOGGLE ACTIVO -->
                        <section class="col col-2" style="min-width: 215px;">
                            <label class="toggle" style="font-size:13px;">
                                <input type="checkbox" id="activosButton" name="activosButton"
                                    <?php echo ($_SESSION['filtro']['activos']) ? 'checked="checked"' : '' ?>>
                                <i data-swchon-text="Si" data-swchoff-text="No"></i>Mostrar Inactivas
                            </label>
                        </section>

                        <button type="button" name="btnFiltro" id="submit-filtro" class="btn btn-primary">
                            Filtrar
                        </button>
                    </footer>

                    <!-- ERRORES -->
                    <?php echo (isset($T_Error['error'])) ? '<p class="error">' . htmlentities($T_Error['error'], ENT_COMPAT, 'utf-8') . '</p>' : ''; ?>










                    <!-- SCRIPT -->
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


                        $('#selPersona').trigger("change");



                        $('#activosButton').change(function () {

                            <?php if($T_Filtro_Activos){    ?>

                                $('#activos').val(0);

                            <?php }
                            else{?>
                                $('#activos').val(1);

                            <?php }?>

                            $('#submit-filtro').trigger("click");
                        });


                        // AJAX CALL ON CLICK , BUTTON FLTER PRESSED//
                        $(document).ready(function () {

                            // AJAX CALL ON CLICK //
                            $('#submit-filtro').click(function () {

                                var $form = $('#filtro-form');

                                // INVALID FORM //
                                if (!$form.valid()) {
                                    return false;
                                }

                                // AJAX CALL //
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

                            // BUTTON PRESSED //
                            $('#filtro-form').bind("keyup keypress", function (e) {
                                var code = e.keyCode || e.which;
                                if (code === 13) {
                                    e.preventDefault();
                                    return false;
                                }
                            });

                        });

                    </script>
                </form>

            </div>
            <!-- end widget content -->
        </div>
        <!-- end widget -->
    </div>
    <!-- end widget -->

</article>
<!-- WIDGET END -->
