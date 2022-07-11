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



                <form class         = "smart-form"
                      novalidate    = "novalidate"
                      action        = "ajax/upload.html.php"
                      method        = "post"
                      enctype       = "multipart/form-data"
                      id            = "upload-form">

                    <input type         ="file"
                           name         ="uploaded_file"
                           id           ="uploaded_file"
                           onchange     ="handleFiles(this.file)">



                    <input type         ="submit"
                           value        ="Upload Image"
                           name         ="submit">




                </form>

            </div>
        </div>


    </div>
    <!-- END: TABLE DIV -->

</article>



<!-- SCRIPT -->
<script type="text/javascript">

    $(document).ready(function () {

        //
        handleFiles = function (files, e) {

            // FILE
            var file = $("#uploaded_file").files()[0];

            // FILE READER NOT SUPPORTED
            if (typeof FileReader == "undefined") return;

            // FILE READER
            reader = new FileReader();
            reader.readAsDataURL(file);

            // XML REQUEST
            xhr = new XMLHttpRequest();
            xhr.open("post", "upload.php", true);

            // UPLOAD READY
            xhr.onreadystatechange = function (oEvent) {
                if (!(xhr.readyState === 4 && xhr.status === 200)) {
                    alert("Error" + xhr.statusText);
                    return false;
                }
            };

            // SET HEADERS
            xhr.setRequestHeader("Content-Type", "multipart/form-data");
            xhr.setRequestHeader("X-File-Name", file.fileName);
            xhr.setRequestHeader("X-File-Size", file.size);
            xhr.setRequestHeader("X-File-Type", file.type);

            // SEND FILE
            xhr.send(file);
        }

        $("#upload-form").submit(function(e) {
            e.preventDefault();
        });


    });




</script>


