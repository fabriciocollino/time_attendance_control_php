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
        var datosObjeto;
        var opcionesEncabezado =  '';
        var encabezado          = [];
        var datos               = [];
        var a_objetosListos     = [];
        var datosObjetos        = {
            'personas': {
                'nombres':      ["Nombre*", "Apellido*", "DNI*", "Legajo*", "Email", "Teléfono Fijo",    "Teléfono Movil", "Notas"],
                'atributos':    ["nombre", "apellido", "dni", "legajo", "email", "telefonoFijo",        "telefonoMovil", "notas"],
                'required':     [true, true,  true, true, false, false, false, false]
            },
            'grupos': {
                'nombres': ["Detalle*", "Mostrar En Vivo"],
                'atributos': ["detalle", "envivo"],
                'required': [true, false]
            }
        };
        var dropbox             = document.getElementById("dropbox");

        $('#submit-preview').prop("disabled", true);
        $('#submit-import').prop("disabled", true);

        // TYPE
        $('#selTipo').change(function () {

            //if ($(this).find('option:selected').attr('value') == '') {}
        });


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

        // PROCESS 0
        handleFiles = function (files, e)    {
            // alert(files);
            // Traverse throught all files and check if uploaded file type is image
            var imageType = /text.csv/;
            var file = files[0];
            // check file type
            if (!file.type.match(imageType)) {
                alert("El archivo \"" + file.name + "\" no es un archivo CSV válido.");
                return false;
            }
            // check file size
            if (parseInt(file.size / 1024) > 2050) {
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
        // PROCESS 1
        function getAsText(fileToRead)  {
            var reader = new FileReader();
            // Read file into memory as UTF-8
            reader.readAsText(fileToRead);
            // Handle errors load
            reader.onload = loadHandler;
            reader.onerror = errorHandler;
        }
        // PROCESS 2
        function loadHandler(event)     {
            var csv = event.target.result;
            processData(csv);
        }
        // PROCESS 3
        function processData(csv)       {

            // DATA LINES
            var allTextLines = csv.split(/\r\n|\n/);
            var lines = [];
            for (var i = 0; i < allTextLines.length; i++) {
                var data = allTextLines[i].split(';');
                var tarr = [];
                for (var j = 0; j < data.length; j++) {
                    tarr.push(data[j]);
                }
                lines.push(tarr);
            }

            // DATA HEADER - DATOS
            encabezado.push("Columna...");
            for (var i = 0; i < lines.length; i++) {
                var linea = lines[i];
                var datosLinea = linea[0].split(',');
                if (i === 0) {//encabezado
                    for (var x = 0; x < datosLinea.length; x++) {
                        encabezado.push(datosLinea[x]);
                    }
                } else {
                    datos.push(datosLinea);
                }
            }

            // SELECT HEADERS
            for (var i = 0; i < encabezado.length; i++) {

                var opt = document.createElement('option');
                var str_encabezado = encabezado[i];
                str_encabezado = str_encabezado.replace('"', '');
                str_encabezado = str_encabezado.replace('"', '');


                opt.innerHTML = str_encabezado;
                opt.value = str_encabezado;
                opcionesEncabezado += opt.outerHTML;

            }

            // HEADERS
            switch (parseInt($('#selTipo').find('option:selected').attr('value'))) {
                case <?php echo IMPORT_PERSONAS; ?>:  //personas
                    datosObjeto = datosObjetos.personas;
                    break;
                case <?php echo IMPORT_GRUPOS; ?>:  //personas
                    datosObjeto = datosObjetos.grupos;
                    break;
            }

            head_SelectOptions();
            $('#submit-preview').prop("disabled", false);
        }

        // HEADERS
        head_SelectOptions = function ()    {

            for (var i = 0; i < datosObjeto.atributos.length; i++) {

                $("#datos-form").append(

                    '<div class="row" id="dato-' + datosObjeto.atributos[i] + '">\n' +

                    '    <section class="col col-1"></section>\n' +

                    '    <section class="col-xs-3 col-sm-3 col-md-3" style="margin-top: 8px;">\n' +
                    datosObjeto.nombres[i] + '\n' +
                    '    </section>\n' +

                    '    <section class="col-xs-1 col-sm-1 col-md-1">\n' +
                    '    </section>\n' +

                    '    <section class="col-xs-3 col-sm-3 col-md-3">\n' +
                    '    <label class="select"> <span class="icon-prepend fa fa-file-code-o"></span>\n' +
                    '    <select name="tipo" id="' + datosObjeto.atributos[i] + '" style="padding-left: 32px;">\n' +
                    opcionesEncabezado +
                    '    </select> <i></i> ' +
                    '    </label>\n' +
                    '    </section>\n' +
                    '</div>');

            }
            $('#submit-filtro').prop("disabled", false);
        };
        // ERROR
        function errorHandler(evt)          {
            if (evt.target.error.name === "NotReadableError") {
                alert("Canno't read file !");
            }
        }
        // OBJECT
        crearobjetosListos = function ()    {
            for (var i = 0; i < datos.length; i++) {

                var objeto = {};
                var select;
                for (var x = 0; x < datosObjeto.atributos.length; x++) {

                    select = document.getElementById("dato-" + datosObjeto.atributos[x]).getElementsByTagName("select")[0];
                    if (select.selectedIndex === 0)
                        objeto[datosObjeto.atributos[x]] = '';
                    else
                        objeto[datosObjeto.atributos[x]] = datos[i][select.selectedIndex - 1];
                }
                a_objetosListos.push(objeto);
            }
        };

        crearobjetosImportar = function () {
            // HEADERS
            switch (parseInt($('#selTipo').find('option:selected').attr('value'))) {
                case <?php echo IMPORT_PERSONAS; ?>:  //personas
                    datosObjeto = datosObjetos.personas;
                    break;
                case <?php echo IMPORT_GRUPOS; ?>:  //personas
                    datosObjeto = datosObjetos.grupos;
                    break;
            }

            $('#dt_basic tr').each(function(row, tr){

                var objeto = {};
                for (var x = 0; x < datosObjeto.atributos.length; x++) {
                    var attribute_key   = datosObjeto.atributos[x];
                    var cell_n = (x+1).toString() ;
                    var cell_key = "td:eq("+cell_n+")";

                    objeto[attribute_key] = $(tr).find(cell_key).text();
                }
                a_objetosListos.push(objeto);
            });
            a_objetosListos.shift();
        };


        // SUBMIT 1 - PREVIEW
        $('#submit-preview').click(function ()  {

            var $form   = $('#filtro-form');
            if (!$form.valid()) {
                return false;
            }

            crearobjetosListos();

            var s_selTipo           =   parseInt($('#selTipo').find('option:selected').attr('value'));
            var s_objetosListos     =   JSON.stringify(a_objetosListos);
            var s_datos             =   JSON.stringify(datosObjeto);
            var s_accion             =   ('preview');


            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data:  {obj:s_objetosListos, tipo:s_selTipo,datos:s_datos,accion:s_accion},
                success: function (data) {
                    function refreshpage() {
                        $('#content').css({opacity: '0.0'}).html(data).delay(100).animate({opacity: '1.0'}, 300);
                        $('#selTipo').val(s_selTipo);
                        $('#submit-import').prop("disabled", false);

                    }
                    setTimeout(refreshpage, 300);
                }
            });

        });


        // SUBMIT 2 - UPLOAD
        $('#submit-import').click(function ()   {


            var $form   = $('#filtro-form');
            if (!$form.valid()) {
                return false;
            }

            crearobjetosImportar();

            var s_selTipo           =   parseInt($('#selTipo').find('option:selected').attr('value'));
            var s_objetosListos     =   JSON.stringify(a_objetosListos);
            var s_datos             =   JSON.stringify(datosObjeto);
            var s_accion             =   ('importar');


            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data:  {obj:s_objetosListos, tipo:s_selTipo,datos:s_datos,accion:s_accion },
                success: function (data) {
                    function refreshpage() {
                        $('#content').css({opacity: '0.0'}).html(data).delay(100).animate({opacity: '1.0'}, 300);
                        $('#selTipo').val(s_selTipo);
                        $('#submit-import').prop("disabled", false);

                    }
                    setTimeout(refreshpage, 300);
                }
            });

        });

        // SUBMIT 3
        $('#filtro-form').bind("keyup keypress", function (e)   {
            var code = e.keyCode || e.which;
            if (code == 13) {
                e.preventDefault();
                return false;
            }
        });



    });



</script>

