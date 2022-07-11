<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/message.php'; ?>

<!-- HEADER -->
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="modalTitle">
        <?php
        if ($T_Tipo == "view") {
            echo "Mensaje";
        }
        else{
            echo "Nuevo Mensaje";
        }?>
    </h4>
</div>



<!-- VIEW -->
<?php if ($T_Tipo == "view") {?>

<div class="modal-body" style="padding-top: 0px;">

<form       class         ="smart-form"
            novalidate    ="novalidate"
            data-async
            method        ="post"
            id            ="editar-form"
            action        ="<?php echo 'ajax/message_inbox.html.php?tipo=add'; ?>" >

    <!-- SUBJECT -->
    <fieldset>
        <legend>Asunto:</legend>

        <div class="row">

            <!-- SUBJECT -->
            <section class="col col-10" style="width: 100%">
                    <label class="input">

                        <i class="icon-prepend fa fa-bullhorn"></i>
                        <input readonly
                               required
                               type         ="text"
                               name         ="men_Subject"
                               placeholder  ="Asunto"
                               value="<?php echo $T_Subject;?>">
                    </label>
            </section>

         </div>
    </fieldset>

    <!-- CHAINED MESSAGES -->
    <fieldset>

        <legend>Mensajes:</legend>

        <?php foreach ($o_Messages as $_messageId => $message) {?>

            <!-- SENDER -->
            <div class="row">
                <section class="col col-10" style="width: 100%">
                        <label class="input">

                            <i class="icon-prepend fa fa fa-user"></i>
                            <input style="border-color: #ffffff"
                                   readonly
                                   required
                                   type         ="text"
                                   value=" <?php    $o_Usuario = Usuario_L::obtenerPorId($message->getSenderId());
                                                    echo $o_Usuario->getNombreCompleto(); ?>">
                        </label>
                </section>
            </div>

               <!-- SENT DATE -->
            <div class="row">
                <section class="col col-10" style="width: 100%">
                        <label class="input">

                            <i class="icon-prepend fa fa-calendar-o"></i>
                            <input style="border-color: #ffffff"
                                   readonly
                                   required
                                   type         ="text"
                                   value="<?php echo $message->getSentDateTime();?>">
                        </label>
                </section>
            </div>

            <!-- MESSAGE -->
            <div class="row">
               <section class="col col-10" style="width: 100%">
                    <label class="textarea textarea-resizable">

                        <i class="icon-prepend fa fa-envelope"></i>

                        <textarea   readonly
                                    name="men_Body"
                                    rows="5"
                                    placeholder="Mensaje"><?php echo stripcslashes($message->getBody()); ?>
                        </textarea>

                    </label>
                </section>
            </div>


             <legend></legend>

            <div class="row">
                <br>
            </div>


        <?php }?>
    </fieldset>

    <!-- NEW MESSAGE -->
    <fieldset>
        <legend>Nuevo Mensaje:</legend>

        <div class="row">
           <section class="col col-10" style="width: 100%">
                <label class="textarea textarea-resizable">

                    <i class="icon-prepend fa fa-envelope"></i>

                    <textarea required name="men_Body" rows="5" placeholder="Mensaje"></textarea>

                </label>
            </section>
        </div>

    </fieldset>

    <!-- SENDER -->
    <input type         ="hidden"
       name         ="men_Sender_Id"
       value        ="<?php echo $T_SenderId;  ?>">

      <!-- SENDER -->
    <input type         ="hidden"
       name         ="men_Receiver_Id"
       value        ="<?php echo $T_ReceiverId; ?>">

    <!-- IS DRAFT -->
    <input type         ="hidden"
       name         ="men_Is_Draft"
       id           ="men_Is_Draft"
       value        ="0">

    <!-- SUBJECT -->
    <input type         ="hidden"
       name         ="men_Subject"
       value        ="<?php echo $T_Subject; ?>">

    <!-- SENDER -->
    <input type         ="hidden"
       name         ="men_Chained_Id"
       value        ="<?php echo $T_ChainedId; ?>">


   </form>

</div>

<?php }?>

<!-- NEW -->
<?php if ($T_Tipo == "new") {?>

    <div class="modal-body" style="padding-top: 0px;">

        <form class         ="smart-form"
              novalidate    ="novalidate"
              data-async
              method        ="post"
              id            ="editar-form"
              action        ="<?php echo 'ajax/message_inbox.html.php?tipo=add'; ?>" >

            <!-- SUBJECT -->
            <fieldset>
                <legend>Asunto:</legend>

                <div class="row">
                    <section class="col col-10" style="width: 100%">
                        <label class="input">

                            <i class="icon-prepend fa fa-bullhorn"></i>
                            <input required
                                   type         ="text"
                                   name         ="men_Subject"
                                   placeholder  ="Asunto"
                                   value="">
                        </label>
                    </section>
                </div>
            </fieldset>

            <!-- SENDER -->
            <input type         ="hidden"
                   name         ="men_Sender_Id"
                   value        ="<?php $currentUser_Id = Registry::getInstance()->Usuario->getId(); echo $currentUser_Id;  ?>">

            <!-- IS DRAFT -->
            <input type         ="hidden"
                   name         ="men_Is_Draft"
                   id           ="men_Is_Draft"
                   value        ="0">

            <!-- RECEIVER -->
            <fieldset>
                <legend>Para:</legend>

                <!-- HEADER -->

                <div class="row">
                    <section class="col col-6" id="destinatario_usuario"  >
                        <label class="select"> <span class="icon-prepend fa fa-users"></span>
                            <select  id="sel_destinatario_usuario" name="men_Receiver_Id" style="padding-left: 32px;">
                                <?php echo $T_Destinatario_Usuarios; ?>
                            </select> <i></i> </label>
                    </section>
                </div>
            </fieldset>

            <!-- MESSAGE, SCHEDULED CHECKBOX , SCHEDULED DATE -->
            <fieldset>
                <legend>Mensaje:</legend>

                <div class="row">
                   <section class="col col-10" style="width: 100%">
                        <label class="textarea textarea-resizable">

                            <i class="icon-prepend fa fa-envelope"></i>

                            <textarea required name="men_Body" rows="5" placeholder="Mensaje"></textarea>

                        </label>
                    </section>
                </div>

                <!-- SCHEDULED -->
                <div class="row">
                    <section class="col col-10" style="width: 100%" >
                        <label class="checkbox">
                            <input type="checkbox" name="men_Is_Scheduled" id="men_Is_Scheduled">
                            <i></i><?php echo _('Programar envío') ?></label>
                    </section>
                <div>


                <!-- DIA Y HORA  -->
                <div class="row" id="DateTimeDiv" style="padding-left: 15px;padding-right: 15px">
                        <section class="col col-6">

                            <!-- DÍA -->
                            <label class="select">Día</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                           name="fecha" id="fecha" type="text" placeholder="Fecha" autocomplete="off">
                                    <span id="btnDesde" class="input-group-addon">
                                        <i class="fa fa-calendar" style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
                                    </span>
                                </div>
                            </div>

                        </section>

                        <!-- HORA -->
                        <section class="col col-6">
                            <label class="select">Hora</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <input class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                           name="hora" id="hora" type="text" placeholder="Hora" autocomplete="off"
                                           style="text-align: center;"/>
                                    <span style="cursor:pointer;" class="input-group-addon">
                                        <i class="fa fa-clock-o" style="line-height:19px!important;padding-left: 5px;"></i>
                                    </span>
                                </div>
                                <div id="mensaje" style="color:red;display:none;">
                                    No es posible programar un tiempo pasado.
                                </div>
                            </div>
                        </section>
                    </div>
            </fieldset>

        </form>

    </div>

<?php }?>

<!-- EDIT -->
<?php if ($T_Tipo == "edit") {?>

    <div class="modal-body" style="padding-top: 0px;">

        <form class         ="smart-form"
              novalidate    ="novalidate"
              data-async
              method        ="post"
              id            ="editar-form"
              action        ="<?php echo 'ajax/message_scheduled.html.php?tipo=save'; ?>" >

            <!-- SUBJECT -->
            <fieldset>
                <legend>Asunto:</legend>

                <div class="row">
                    <section class="col col-10" style="width: 100%">
                        <label class="input">

                            <i class="icon-prepend fa fa-bullhorn"></i>
                            <input required
                                   type         ="text"
                                   name         ="men_Subject"
                                   placeholder  ="Asunto"
                                   value="<?php echo $T_Subject;?>">
                        </label>
                    </section>
                </div>
            </fieldset>

            <!-- SENDER -->
            <input type         ="hidden"
                   name         ="men_Sender_Id"
                   value        ="<?php $currentUser_Id = Registry::getInstance()->Usuario->getId(); echo $currentUser_Id;  ?>">

            <!-- IS DRAFT -->
            <input type         ="hidden"
                   name         ="men_Is_Draft"
                   id           ="men_Is_Draft"
                   value        ="0">

                 <!-- IS DRAFT -->
            <input type         ="hidden"
                   name         ="men_Id"
                   id           ="men_Id"
                   value        ="<?php echo $T_Id; ?>">


            <!-- RECEIVER -->
            <fieldset>
                <legend>Para:</legend>

                <!-- HEADER -->

                <div class="row">
                    <section class="col col-6" id="destinatario_usuario"  >
                        <label class="select"> <span class="icon-prepend fa fa-users"></span>
                            <select  id="sel_destinatario_usuario" name="men_Receiver_Id" style="padding-left: 32px;">
                                <?php echo $T_Destinatario_Usuarios; ?>
                            </select> <i></i> </label>
                    </section>
                </div>
            </fieldset>

            <!-- MESSAGE, SCHEDULED CHECKBOX , SCHEDULED DATE -->
            <fieldset>
                <legend>Mensaje:</legend>

                <div class="row">
                   <section class="col col-10" style="width: 100%">
                        <label class="textarea textarea-resizable">

                            <i class="icon-prepend fa fa-envelope"></i>

                            <textarea   required
                                        name="men_Body"
                                        rows="5"
                                        placeholder="Mensaje"><?php echo stripcslashes($T_Body); ?>
                            </textarea>

                        </label>
                    </section>
                </div>

                <!-- SCHEDULED -->
                <div class="row">
                    <section class="col col-10" style="width: 100%" >
                        <label class="checkbox">
                            <input type="checkbox" name="men_Is_Scheduled" id="men_Is_Scheduled" checked>
                            <i></i><?php echo _('Programar envío') ?></label>
                    </section>
                <div>


                <!-- DIA Y HORA  -->
                <div class="row" id="DateTimeDiv" style="padding-left: 15px;padding-right: 15px">
                        <section class="col col-6">

                            <!-- DÍA -->
                            <label class="select">Día</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <input  class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                            name="fecha" id="fecha" type="text" placeholder="Fecha" autocomplete="off"
                                            value="<?php echo $T_Fecha;?>"/>
                                    <span id="btnDesde" class="input-group-addon">
                                        <i class="fa fa-calendar" style="cursor:pointer;line-height: 19px!important;padding-left: 5px;"></i>
                                    </span>
                                </div>
                            </div>

                        </section>

                        <!-- HORA -->
                        <section class="col col-6">
                            <label class="select">Hora</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <input  class="form-control " style="padding-left: 5px;font-size: 12px;height: 31px;"
                                            name="hora" id="hora" type="text" placeholder="Hora" autocomplete="off"
                                            style="text-align: center;"
                                            value="<?php echo $T_Hora;?>"/>
                                    <span style="cursor:pointer;" class="input-group-addon">
                                        <i class="fa fa-clock-o" style="line-height:19px!important;padding-left: 5px;"></i>
                                    </span>
                                </div>
                                <div id="mensaje" style="color:red;display:none;">
                                    No es posible programar un tiempo pasado.
                                </div>
                            </div>
                        </section>
                    </div>
            </fieldset>

        </form>

    </div>

<?php }?>




<!-- FOOTER -->
<div class="modal-footer">

    <!-- CANCEL -->
    <button type="button" class="btn btn-default" data-dismiss="modal">
        Salir
    </button>

    <!-- SEND -->
    <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-send">
        <?php echo _("Enviar"); ?>
    </button>

    <!-- SAVE DRAFT
    <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-draft">
        <?php //echo _("Guardar Borrador"); ?>
    </button>
    -->
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

        <?php if($T_Tipo != 'edit'){?>
            $('#DateTimeDiv').hide();
        <?php };?>


        $('#men_Is_Scheduled').change(function(){
            if(this.checked)
                $('#DateTimeDiv').show();
            else{
                $('#Fecha').removeAttr('value');
                $('#Hora').removeAttr('value');
                $('#DateTimeDiv').hide();
            }
        });

        $("#fecha").datepicker({
            changeMonth: true,
            dateFormat: "yy-mm-dd",
            changeYear: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            minDate: new Date
        });

        $('#hora').clockpicker({
            placement: 'bottom',
            donetext: 'Aceptar',
            autoclose: 'true'
        });


        $('#submit-send').click(function () {
            var $form = $('#editar-form');

            $("#men_Is_Draft").val("0");
            var d = new Date();

            if(Date.parse(formatDate(d.toDateString())+' '+d.getHours() + ":" + d.getMinutes()) > Date.parse($('#fecha').val() + ' ' + $('#hora').val())){
                $("#mensaje").show();
                setTimeout(function () {$("#mensaje").hide("slow")}, 1000);
                return false;
            }

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

        /*
        $('#submit-draft').click(function () {
            var $form = $('#editar-form');

            $("#men_Is_Draft").val("1");

            if (!$('#editar-form').valid()) {
                return false;
            } else {

                $.ajax({
                    type    : $form.attr('method'),
                    url     : $form.attr('action'),
                    data    : $form.serialize(),

                    success: function (data, status) {
                        $('#editar').modal('hide');
                        function refreshpage() {
                            $('#content').css({opacity: '0.0'}).html(data).delay(50).animate({opacity: '1.0'}, 300);
                            $('body').removeData('bs.modal');

                        }

                        setTimeout(refreshpage, 200);
                    }
                });
            }

        });
        */

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
