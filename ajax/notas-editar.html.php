<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/notas.php'; ?>

<!-- HEADER -->
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="modalTitle">
        Notas
    </h4>
</div>



<?php if(!is_null($o_Notas)){ ?>
    <div class="modal-body" style="padding-top: 0px;">

        <form       class         ="smart-form"
                    novalidate    ="novalidate"
                    data-async
                    method        ="post"
                    id            =""
                    action        ="" >

            <fieldset>
                <legend>
                    <?php
                        $o_Persona = Persona_L::obtenerPorId($T_Id);
                        echo "Notas de: ".$o_Persona->getNombreCompleto();
                    ?>
                </legend>
            </fieldset>


            <!-- NOTAS PREVIAS -->
            <?php foreach ($o_Notas as $_notaId => $nota) {?>

                <fieldset>
                    <!-- WRITER -->
                    <div class="row">
                        <section class="col col-10" style="width: 100%">
                            <label class="input">

                                <i class="icon-prepend fa fa fa-user"></i>
                                <input style="border-color: #ffffff"
                                       readonly
                                       required
                                       type         ="text"
                                       value=" <?php    $o_Usuario = Usuario_L::obtenerPorId($nota->getUserWriterId());
                                       echo $o_Usuario->getNombreCompleto(); ?>">
                            </label>
                        </section>
                    </div>

                    <!-- CREATION DATE -->
                    <div class="row">
                        <section class="col col-10" style="width: 100%">
                            <label class="input">

                                <i class="icon-prepend fa fa-calendar-o"></i>
                                <input  style="border-color: #ffffff;outline: none;resize: none"
                                       readonly
                                       required
                                       type         ="text"
                                       value="<?php echo $nota->getCreationDateTime();?>">
                            </label>
                        </section>
                    </div>

                    <!-- NOTA -->
                    <div class="row">
                        <section class="col col-10" style="width: 100%">
                            <label class="textarea textarea-resizable">

                                <i class="icon-prepend fa fa-sticky-note-o"></i>
                                <textarea   style="outline: none;resize: none"
                                            readonly
                                            rows="5"
                                            placeholder="Mensaje"><?php echo stripcslashes($nota->getBody()); ?></textarea>
                            </label>
                        </section>
                    </div>


                </fieldset>

            <?php }?>

        </form>

    </div>
<?php } ?>

<div class="modal-body" style="padding-top: 0px;">

    <form class         =   "smart-form"
          novalidate    =   "novalidate"
          data-async
          method        =   "post"
          id            =   "editar-form"
          action        =   "<?php echo 'ajax/notas-editar.html.php?tipo=add'; ?>" >



        <!-- PERSONA ID -->
        <input type         =   "hidden"
               name         =   "id"
               id           =   "id"
               value        =   "<?php echo $T_Id; ?>">



        <!-- NOTA NUEVA -->
        <fieldset>
            <legend>Nueva nota:</legend>

            <div class="row">
               <section class="col col-10" style="width: 100%">
                    <label class="textarea textarea-resizable">

                        <i class="icon-prepend fa fa-sticky-note-o"></i>

                        <textarea   required
                                    style       =   "resize: none;"
                                    name        =   "not_Body"
                                    rows        =   "5"
                                    placeholder =   "Mensaje"
                                    value = ""></textarea>

                    </label>
                </section>
            </div>

     
        </fieldset>

    </form>

</div>


<!-- FOOTER -->
<div class="modal-footer">


    <!-- CANCEL -->
    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>


    <!-- SAVE -->
    <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-save">
        <?php echo _("Guardar"); ?>
    </button>

</div>





<!-- STYLE -->
<style>
    .clockpicker-popover {
        z-index: 100000;
    }
</style>

<!-- SCRIPT -->
<script type="text/javascript">


    // DATES
    $(document).ready(function () {

        $('#submit-save').click(function () {
            var $form = $('#editar-form');

            if (!$('#editar-form').valid()) {
                return false;
            }
            else {
                $.ajax({
                    type    : $form.attr('method'),
                    url     : $form.attr('action'),
                    data    : $form.serialize(),

                    success: function (data, status) {
                        $('#editar').modal('hide');
                        location.reload();
                    }
                });
            }
        });


        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

    });

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

</script>
