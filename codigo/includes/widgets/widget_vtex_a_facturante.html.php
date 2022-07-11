<?php ?>



<!-- TABLE ARTICULE -->
<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <!-- TABLE DIV -->
    <div class = "jarviswidget jarviswidget-color-blueDark" id = "wid-id-50"
         data-widget-editbutton     = "false"   data-widget-colorbutton   = "false"
         data-widget-deletebutton   = "false"   data-widget-sortable      = "false"   data-widget-fullscreenbutton="false">

        <!-- HEADER -->
        <header>
            <span class="widget-icon"> <i class="fa fa-file-code-o"></i> </span>
            <h2><?php echo _('Datos a Importar') ?></h2>
        </header>

        <!-- DIV TABLE FILTER-->
        <div>
            <div class = "widget-body no-padding">
                <form class         = "smart-form"      novalidate  = "novalidate"
                      data-async    = ""                method      = "post"
                      id            = "filtro-form"     action = "<?php echo $Filtro_Form_Action; ?>">

                    <fieldset>

                        <!-- SELECT TIPO -->
                        <div class = "row">

                            <!-- EMPTY COLUMN -->
                            <section class = "col col-1"> </section>

                            <!-- SELECT FORM COLUMN -->
                            <section class = "col col-4">
                                <label class = "select">Tipo</label>
                                <label class = "select">
                                    <span class="icon-prepend fa fa-file-code-o"></span>
                                    <select name="tipo" id="selTipo" style="padding-left: 32px;">
                                        <?php echo HtmlHelper::array2htmloptions($a_Import_Type, $_SESSION['tipo'], false, false, '', 'Seleccione el tipo'); ?>
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                        </div>

                        <!-- SELECT TIPO -->
                        <div class = "row">
                            <section class = "col col-1"> </section>
                        </div>


                        <!-- SELECT TIPO -->
                        <div class = "row">

                            <!-- EMPTY COLUMN -->
                            <section class = "col col-1"> </section>

                            <!-- SELECT FORM COLUMN -->
                            <section class = "col col-4">

                                <label class = "input">Nro. Inicio Factura: </label>
                                <!-- ICON, DEVICE NAME -->
                                <label class="input">

                                    <!-- ICON -->
                                    <i class="icon-prepend fa fa-hdd-o"></i>

                                    <!-- DEVICE NAME -->
                                    <input type         ="text"
                                           name         ="nro_comp"
                                           id           ="nro_comp"
                                           placeholder  ="XXXX"
                                           maxlength    ="4"
                                           minlength    ="4"
                                           value        =<?php
                                           $Persona_nro_factura = Persona_L::obtenerPorId(10);
                                           $nro_factura = $Persona_nro_factura->getRID();

                                           echo $nro_factura; ?> >
                                </label>
                            </section>
                        </div>


                        <!-- EMAIL DESTINATARIOS -->
                        <div class = "row">

                            <!-- EMPTY COLUMN -->
                            <section class = "col col-1"> </section>

                            <!-- SELECT FORM COLUMN -->
                            <section class = "col col-4">

                                <label class = "input">Email Destinatario:</label>
                                <!-- ICON, DEVICE NAME -->
                                <label class="input">

                                    <!-- ICON -->
                                    <i class="icon-prepend fa fa-hdd-o"></i>

                                    <!-- EMAIL -->
                                    <input type         ="text"
                                           name         ="email_destinatario"
                                           id           ="email_destinatario"
                                           value        =<?php echo $T_email_destinatario; ?> >
                                </label>
                            </section>
                        </div>

                        <!-- EMAIL CC -->
                        <div class = "row">

                            <!-- EMPTY COLUMN -->
                            <section class = "col col-1"> </section>

                            <!-- SELECT FORM COLUMN -->
                            <section class = "col col-4">

                                <label class = "input">Email CC:</label>
                                <!-- ICON, DEVICE NAME -->
                                <label class="input">

                                    <!-- ICON -->
                                    <i class="icon-prepend fa fa-hdd-o"></i>

                                    <!-- EMAIL -->
                                    <input type         ="text"
                                           name         ="email_cc"
                                           id           ="email_cc"
                                           value        =<?php echo $T_email_cc; ?> >
                                </label>
                            </section>
                        </div>


                        <!-- DIA HASTA -->
                        <div class = "row">

                            <!-- EMPTY COLUMN -->
                            <section class = "col col-1"> </section>

                            <!-- SELECT FORM COLUMN -->
                            <section class = "col col-4">

                                <label class = "input">Dia hasta:</label>
                                <!-- ICON, DEVICE NAME -->
                                <label class="input">

                                    <!-- ICON -->
                                    <i class="icon-prepend fa fa-hdd-o"></i>

                                    <!-- EMAIL -->
                                    <input type         ="text"
                                           name         ="dia_hasta"
                                           id           ="dia_hasta"
                                           value        =<?php echo _($T_dia_hasta); ?> >
                                </label>
                            </section>
                        </div>

                        <!-- HORA HASTA -->
                        <div class = "row">

                            <!-- EMPTY COLUMN -->
                            <section class = "col col-1"> </section>

                            <!-- SELECT FORM COLUMN -->
                            <section class = "col col-4">

                                <label class = "input">Hora hasta:</label>
                                <!-- ICON, DEVICE NAME -->
                                <label class="input">

                                    <!-- ICON -->
                                    <i class="icon-prepend fa fa-hdd-o"></i>

                                    <!-- EMAIL -->
                                    <input type         ="text"
                                           name         ="hora_hasta"
                                           id           ="hora_hasta"
                                           value        =<?php echo _($T_hora_hasta); ?> >
                                </label>
                            </section>
                        </div>


                        <!-- DROP BOX FILE UPLOAD AREA -->
                        <div class="row">

                            <!-- EMPTY COLUMN -->
                            <section class="col col-1"></section>

                            <!-- TITLE 'Arrastra el archivo', UPLOAD PROGRESS CLASS, FILE ELEMENT HANDLE, UPLOAD PROGRESS CLASS -->
                            <div class="col col 4" id="FileUpload">

                                <!-- TITLE -->
                                <div id = "dropbox">Arrastra el archivo CSV aquí</div>
                                <!-- FILE ELEMENT HANDLE -->
                                <input type     =   "file"      id      =   "fileElem"
                                       multiple =   "false"     accept  =   "text/csv"
                                       onchange =   "handleFiles(this.files)"
                                       style="width: 100%;">

                            </div>
                        </div>


                        <!-- FORM DATA HEADERS-->
                        <div class="row">
                            <!-- EMPTY COLUMN -->
                            <section class="col col-1"></section>

                            <section  class="col-xs-6 col-sm-6 col-md-6">
                                <div id="dataSelect">
                                    <div id="datos-form" name="datos-fo">

                                        <div class="row" style="padding-left: 20px;padding-right: 20px;">
                                            <section class="col-xs-1 col-sm-1 col-md-1"></section>
                                            <section class="col-xs-3 col-sm-3 col-md-3"></section>
                                            <section class="col-xs-1 col-sm-1 col-md-1"></section>
                                            <section class="col-xs-5 col-sm-5 col-md-5"></section>
                                        </div>

                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="row">

                        </div>
                    </fieldset>

                    <!-- BUTTON SUBMIT IMPORT -->
                    <footer>
                        <!-- SUBMIT -->
                        <button type="button" name="btnImport" id="submit-import" class="btn btn-primary">
                            Importar
                        </button>
                        <!-- PREVIEW DATA -->
                        <button type="button" name="btnPreview" id="submit-preview" class="btn btn-primary">
                            Cargar
                        </button>

                    </footer>



                </form>

            </div>
        </div>


    </div>
    <!-- END: TABLE DIV -->

</article>



<!-- SCRIPT -->
<script type="text/javascript">

    var crearobjetosListos;
    var head_SelectOptions;
    var table_DataPreview;
    var crearobjetosImportar;
    var handleFiles;

    <!-- SCRIPT DATA -->
    $(document).ready(function () {

        // VAR
        var datos               = [];
        var dropbox             = document.getElementById("dropbox");

        $('#submit-preview').prop("disabled", true);
        $('#submit-import').prop("disabled", true);

        // DISABLE BUTTONS
        $('#dt_basic').change(function () {

            if ($('#dt_basic tr').length == 1) {
                $('#submit-preview').prop("disabled", true);
                $('#submit-import').prop("disabled", true);
            };
        });

        // D&D 1
        dropbox.addEventListener("dragenter", dragenter , false);
        dropbox.addEventListener("dragleave", dragleave , false);
        dropbox.addEventListener("dragover" , dragover  , false);
        dropbox.addEventListener("drop"     , drop      , false);

        // D&D 2
        function defaults(e)     {
            e.stopPropagation();
            e.preventDefault();
        }
        function dragenter(e)    {
            $(this).addClass("active");
            defaults(e);
        }
        function dragover(e)     {
            defaults(e);
        }
        function dragleave(e)    {
            $(this).removeClass("active");
            defaults(e);
        }
        function drop(e)         {
            $(this).removeClass("active");
            defaults(e);
            var dt = e.dataTransfer;
            var files = dt.files;

            handleFiles(files, e);
        }

        // PROCESS
        handleFiles = function (files, e)   {
            // alert(files);
            // Traverse throught all files and check if uploaded file type is image
            var imageType = /text.csv/;
            var file = files[0];
            // check file type
            if (0){ //==!file.type.match(imageType)) {
                alert("El archivo \"" + file.name + "\" no es un archivo CSV válido.");
                return false;
            }
            // check file size
            if (parseInt(file.size / 1024) > 5000) {
                alert("El archivo \"" + file.name + "\" es muy grande");
                return false;
            }

            $('#inputImageExtension').val(file.type);

            // $('#FileUploadRow').prop("disabled", true);
            $("#FileUpload").hide();
            $("#dataSelect").show();
            $('#submit-import').prop("disabled", true);
            getAsText(file);
        };
        function getAsText(fileToRead)      {
            var reader = new FileReader();
            reader.readAsText(fileToRead);
            reader.onload = loadHandler;
            reader.onerror = errorHandler;
        }
        function loadHandler(event)         {
            var csv = event.target.result;
            processData(csv);
        }
        function processData(csv)           {

            // DATA LINES
            var allTextLines = csv.split(/\r\n|\n/);

            for (var i = 0; i < allTextLines.length; i++) {

                var data = allTextLines[i].split(';');
                var tarr = [];

                for (var j = 0; j < data.length; j++) {
                    tarr.push(data[j]);
                }

                datos.push(tarr);
            }
            $('#submit-preview').prop("disabled", false);
            console.log(datos);

            $('#submit-preview').click();
        }

        // ERROR
        function errorHandler(evt)          {
            if (evt.target.error.name === "NotReadableError") {
                alert("Canno't read file !");
            }
        }

        // SUBMIT PREVIEWº
        $('#submit-preview').click(function ()  {

            var $form   = $('#filtro-form');
            if (!$form.valid()) {
                return false;
            }
           // console.log(datos);


            var s_selTipo           =   parseInt($('#selTipo').find('option:selected').attr('value'));
            var s_datos             =   JSON.stringify(datos);
            var s_accion             =  ('preview');
            var s_nro_comp             =  $('#nro_comp').val();
            var s_email_destinatario    =  $('#email_destinatario').val();
            var s_email_cc             =  $('#email_cc').val();
            var s_dia_hasta             =  $('#dia_hasta').val();
            var s_hora_hasta             =  $('#hora_hasta').val();

           // console.log(s_datos);

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data:  {
                    tipo:s_selTipo,
                    data:s_datos,
                    accion:s_accion,
                    nro_comp:s_nro_comp,
                    email_destinatario:s_email_destinatario,
                    email_cc:s_email_cc,
                    dia_hasta:s_dia_hasta,
                    hora_hasta:s_hora_hasta

                },
                success: function (data) {
                    function refreshpage() {
                        $('#content').css({opacity: '0.0'}).html(data).delay(100).animate({opacity: '1.0'}, 300);
                        $('#selTipo').val(s_selTipo);
                        $('#nro_comp').val(s_nro_comp);
                        $('#submit-import').prop("disabled", false);

                    }
                    setTimeout(refreshpage, 300);
                }
            });

        });

        // BIND
        $('#filtro-form').bind("keyup keypress", function (e)   {
            var code = e.keyCode || e.which;
            if (code == 13) {
                e.preventDefault();
                return false;
            }
        });



    });

</script>

