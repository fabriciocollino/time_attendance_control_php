<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php //require_once APP_PATH . '/controllers/upload.php';?>
<?php
$T_Tipo                             =   !isset($_REQUEST['tipo']) ? isset($_SESSION['filtro']['tipo']) ? $_SESSION['filtro']['tipo'] : '' : $_REQUEST['tipo'];
$_SESSION['filtro']['tipo_data']    =   'Modelo_Importar_'.$T_Tipo;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="modalTitle">
        <?php

        echo _("Importar ").$T_Tipo;


        ?>
    </h4>
</div>
<div class="modal-body" style="padding-top: 0;">

    <form
            id          ="editar-form"
            class       ="smart-form"
            novalidate  ="novalidate"
            action      ="/upload.php"
            enctype     ="multipart/form-data"
            method      ="post">


        <!-- SENDER -->
        <input type         ="hidden"
               name         ="tipo"
               value        ="<?php echo $T_Tipo; ?>">



        <fieldset>
            <!-- INPUT FILE -->
            <div class="row">

                <!-- EMPTY COLUMN -->
                <section class="col col-1"></section>

                <!-- TITLE 'Arrastra el archivo', UPLOAD PROGRESS CLASS, FILE ELEMENT HANDLE, UPLOAD PROGRESS CLASS -->
                <div class="col col 4">
                    <input
                            type    ="file"
                            name    ="uploaded_files"
                            size    ="100">
                </div>
            </div>

        </fieldset>


        <fieldset>
            <!-- INPUT FILE -->
            <div class="row">

                <!-- EMPTY COLUMN -->
                <section class="col col-1"></section>



                <!--  -->
                <div class="col col 4">

                    <a href="<?php echo WEB_ROOT; ?>/descargar_adjunto.php?archivo_tipo=xls " target="_blank" >
                        <?php echo _("Descargar modelo Excel para importar "). $T_Tipo ?>
                    </a>


                </div>
            </div>

        </fieldset>




        <input
                style   ="display: none"
                id      ="submit-upload"
                type    ="submit"
                value   ="Send">
    </form>



</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>
    <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar" value="Send">
        <?php echo _("Importar"); ?>
    </button>

</div>


<script type="text/javascript">


    $(document).ready(function () {


        // SUBMIT 2 - UPLOAD
        $('#submit-upload').click(function ()   {

            var $form   = $('#editar-form');
            if (!$form.valid()) {
                return false;
            }

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                success: function (data) {

                    $('#editar').modal('hide');

                    console.log(data);

                    location.reload();


                }
            });

        });

        $('#submit-editar').click(function () {

            $('#submit-upload').trigger("click");

        });


        $(window).keydown(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                return false;
            }
        });


    });

</script>
