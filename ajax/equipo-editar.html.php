<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '-editar.html.php') . 's' . '.php'; ?>


<!-- ADD DEVICE -->
 <?php if ($o_Equipo->getId() == null){ ?>


     <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
             &times;
         </button>

         <h4 class="modal-title" id="modalTitle">
             <?php echo _("Agregar Equipo"); ?>
         </h4>


     </div>

     <div class="modal-body">

         <form class="smart-form"
               novalidate="novalidate"
               data-async method="post"
               id="editar-form"
               action="<?php echo "ajax/".$Item_Name."s.html.php?tipo=add"; ?>">


             <!-- DEVICE DATA -->
             <fieldset>

                 <!-- DATA HEADER -->
                 <legend>
                     Datos del Equipo
                 </legend>

                 <!-- DEVICE NAME -->
                 <div class="row">
                     <section class="col col-10" style="width: 100%;">

                         <!-- ICON, DEVICE NAME -->
                         <label class="input">

                             <!-- ICON -->
                             <i class="icon-prepend fa fa-hdd-o"></i>

                             <!-- DEVICE NAME -->
                             <input type        ="text"
                                    name        ="detalle"
                                    placeholder ="Nombre del Equipo"
                                    value       ="">
                         </label>


                     </section>
                 </div>

                 <!-- DATA HEADER -->
                 <legend>
                        Identificación del Equipo
                 </legend>

                 <!-- DEVICE NAME -->
                 <div class="row">
                     <section class="col col-10" style="width: 100%;">

                         <!-- ICON, UUID -->
                         <label class="input">

                             <!-- ICON -->
                             <i class="icon-prepend fa fa-hdd-o"></i>

                             <!-- UUID -->
                             <input type        =   "text"
                                    name        =   "uuid"
                                    placeholder =   "UUID"
                                    value       =   "">
                         </label>

                     </section>
                 </div>


             </fieldset>

         </form>

     </div>

     <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">
             Salir
         </button>

         <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar">
             <?php echo _("Agregar"); ?>
         </button>
     </div>


 <?php }?>

<?php if ($o_Equipo->getId() != null){ ?>


     <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
             &times;
         </button>

         <h4 class="modal-title" id="modalTitle">
             <?php echo _("Editar Equipo"); ?>
         </h4>
     </div>
     <div class="modal-body">

         <form class                ="smart-form"
               novalidate           ="novalidate"
               data-async method    ="post"
               id                   ="editar-form"
               action               ="<?php echo "ajax/".$Item_Name."s.html.php?tipo=edit&id=".$o_Equipo->getId(); ?>">


             <input type="hidden" id="ItemID" name="ItemID" value="<?php  $o_Equipo->getId()  ?> ">


             <!-- DEVICE DATA -->
             <fieldset>

                 <!-- DATA HEADER -->
                 <legend>
                     Datos del Equipo
                 </legend>

                 <!-- DEVICE DETAIL -->
                 <div class="row">
                     <section class="col col-10" style="width: 100%;">

                         <label class="input">
                             <!-- ICON -->
                             <i class="icon-prepend fa fa-hdd-o"></i>

                             <!-- DEVICE NAME -->
                             <input type="text"
                                    name="detalle"
                                    placeholder="Nombre del Equipo"
                                    value="<?php echo htmlentities($o_Equipo->getDetalle(), ENT_COMPAT, 'utf-8'); ?>">
                         </label>

                     </section>
                 </div>

                 <!-- DATA HEADER -->
                 <legend>
                     Identificación del Equipo
                 </legend>

                 <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 999) { ?>

                     <!-- DEVICE NAME -->
                     <div class="row">
                         <section class="col col-10" style="width: 100%;">

                             <!-- ICON, UUID -->
                             <label class="input">

                                 <!-- ICON -->
                                 <i class="icon-prepend fa fa-hdd-o"></i>

                                 <!-- UUID -->
                                 <input type        =   "text"
                                        name        =   "uuid"
                                        placeholder =   "UUID"
                                        value       =   "<?php echo htmlentities($o_Equipo->getUUID(), ENT_COMPAT, 'utf-8'); ?>">
                             </label>

                         </section>
                     </div>
                 <?php }?>


             </fieldset>

         </form>

     </div>

     <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">
             Salir
         </button>

         <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar">
             <?php echo _("Guardar"); ?>
         </button>
     </div>



 <?php } ?>



<script type="text/javascript">


    $(function () {
        // Validation


        $("#editar-form").validate({
            // Rules for form validation
            rules: {
                hostname: {
                    required: true,
                    rangelength: [2, 15]
                },
                password: {
                    required: true
                },
                detalle: {
                    required: true
                }
            },

            // Messages for form validation
            messages: {
                hostname: {
                    required: '<?php echo _('Por favor ingrese el hostname') ?>',
                    rangelength: '<?php echo _('El hostname debe tener entre 2 y 15 caracteres') ?>'
                },
                password: {
                    required: '<?php echo _('Por favor ingrese la contraseña') ?>'
                },
                detalle: {
                    required: '<?php echo _('Por favor ingrese una descripción') ?>'
                }
            },

            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            }
        });
    });


    $(document).ready(function () {
        $('#submit-editar').click(function () {
            var $form = $('#editar-form');

            if (!$('#editar-form').valid()) {
                return false;
            } else {

                $.ajax({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),

                    success: function (data, status) {
                        $('#editar').modal('hide');
                        function refreshpage() {
                            $('#content').css({opacity: '0.0'}).html(data).delay(50).animate({opacity: '1.0'}, 300);
                        }

                        setTimeout(refreshpage, 200);
                    }
                });
            }

        });


        $(window).keydown(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                return false;
            }
        });


    });


</script>

