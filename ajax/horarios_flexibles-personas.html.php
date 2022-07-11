<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '-personas.html.php') . '.php'; ?>

<?php
if ($T_Tipo == "insert") {
    //esto es para el ajax del insertar/eliminar, para que solo devuelva el output del controller
}
else {


    //$a_Tipos_De_Horario, $o_Persona->getHorTipo()
//Hora_Trabajo_L::obtenerTodos(), $o_Persona->getHorId()
    ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            &times;
        </button>
        <h4 class="modal-title"
            id="modalTitle"><?php if ($o_Horario_Flexible->getId() == null) echo _("Agregar Horario");
            else echo _("Agregar personas al Horario"); ?></h4>
    </div>
    <div class="modal-body" style="padding-top: 0px;">


        <form class="smart-form" novalidate="novalidate" data-async method="post" id="editar-form" action="#">


            <fieldset>
                <div class="row">
                    <div id="loader" style="height: 5px;"></div>
                    <select multiple id="selHorarios">
                        <?php
                        //$_condicion='per_Hor_Tipo='.$T_Filtro_Horario_Tipo.' and per_Hor_Id='.$T_Filtro_Horario_Id;
                        //$a_Horario_Personas = Registry::getInstance()->DbConn->Select_Lista_assocID('personas',$condicion,'per_Id');

                        $a_o_Personas       = Persona_L::obtenerDesdeFiltro(Filtro_L::get_filtro_persona());

                        if ($a_o_Personas != null) {
                            foreach ($a_o_Personas as $o_Persona) {
                                $checked = '';
                                if ($o_Persona->getHorTipo() == $T_Filtro_Horario_Tipo && $o_Persona->getHorId() == $T_Filtro_Horario_Id) {
                                    $checked = 'selected="selected"';
                                }

                                echo '<option value="' . $o_Persona->getId() . '" ' . $checked . ' >';
                                echo $o_Persona->getLegajo()." - ".mb_convert_case($o_Persona->getNombreCompleto(), MB_CASE_TITLE, "UTF-8");
                                echo "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

            </fieldset>


        </form>


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            Salir
        </button>
    </div>

    <script type="text/javascript">


        loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/multi-select/jquery.multi-select.js", function () {
            loadScript("https://static.enpuntocontrol.com/app/v1/js/plugin/multi-select/jquery.quicksearch.js", pagefunction);
        });

        function pagefunction() {

            $('#selHorarios').multiSelect({
                selectableHeader: "<legend>Personas</legend></br><section><i class='icon-prepend icon-prepend-multi-select-search fa fa-search'></i><input type='text' class='search-input' autocomplete='off' placeholder='Buscar'></section>",
                selectionHeader: "<legend><?php echo $o_Horario_Flexible->getDetalle(); ?></legend></br><section><i class='icon-prepend icon-prepend-multi-select-search fa fa-search'></i><input type='text' class='search-input' autocomplete='off' placeholder='Buscar'></section>",
                afterSelect: function (value) {
                    AgregarPersona(value);
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function (value) {
                    EliminarPersona(value);
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterInit: function (ms) {

                    var that = this,
                        $selectableSearch = that.$selectableUl.prev().find('input'),
                        $selectionSearch = that.$selectionUl.prev().find('input'),
                        selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                        selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                        .on('keydown', function (e) {
                            if (e.which === 40) {
                                that.$selectableUl.focus();
                                return false;
                            }
                        });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                        .on('keydown', function (e) {
                            if (e.which == 40) {
                                that.$selectionUl.focus();
                                return false;
                            }
                        });
                }
            });
        }


        $(document).ready(function () {
        });

        function AgregarPersona(persona) {

            $('#loader').html('<img src="https://static.enpuntocontrol.com/app/v1/img/loading.gif" width="30" />');

            $.ajax({
                url: '<?php echo 'ajax/horarios_flexibles.html.php' ?>?tipo=insert&f_horario_tipo=<?php echo $T_Filtro_Horario_Tipo; ?>&id=<?php echo $o_Horario_Flexible->getId(); ?>&personaID=' + persona,
                dataType: 'html',
                async: true,
                timeout: 5000, //timeout de 5 segundos
                success: function (text) {

                    if (text) {
                        if (text == "error_persona_ya_existe") {
                            alert("<?php echo _('La persona que intenta agregar, ya existe en el grupo.') ?>");
                            $('#loader').html('');
                            alert("Error, no se puedo agregar la persona. Compruebe su conexion a Internet.");
                            return false;
                        }

                        $('#loader').html('');
                    }
                    $('#loader').html('');

                },
                error: function (text) {
                    alert("Error, no se puedo agregar la persona. Compruebe su conexion a Internet.");
                    $('#loader').html('');
                }

            });
        };

        function EliminarPersona(persona) {

            $('#loader').html('<img src="https://static.enpuntocontrol.com/app/v1/img/loading.gif" width="30" />');

            $.ajax({
                url: '<?php echo 'ajax/horarios_flexibles.html.php' ?>?tipo=remove&f_horario_tipo=<?php echo $T_Filtro_Horario_Tipo; ?>&id=<?php echo $o_Horario_Flexible->getId(); ?>&personaID=' + persona,
                dataType: 'html',
                async: true,
                timeout: 5000, //timeout de 5 segundos
                success: function (text) {
                    if (text) {

                    }
                    $('#loader').html('');
                },
                error: function (text) {
                    alert("Error, no se puedo agregar la persona. Compruebe su conexion a Internet.");
                    $('#loader').html('');
                }

            });

        };


        $('#editar').on('hidden.bs.modal', function () {
            //esto refresca la pagina
            loadURL('<?php echo $Item_Name?>s', $('#content'));


        });


    </script>


<?php } ?>


