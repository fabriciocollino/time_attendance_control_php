<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/configuracion.php';  ?>

<?php if (isset($T_Mensaje) && $T_Mensaje!=null): ?>
    <div class="alert alert-success fade in">
        <button class="close" data-dismiss="alert">
            ×
        </button>
        <i class="fa-fw fa fa-check"></i>
        <strong>Éxito!</strong> <?=$T_Mensaje?>
    </div>
<?php endif; ?>

<?php if (isset($T_Error) && $T_Error!=null): ?>
    <div class="alert alert-danger fade in">
        <button class="close" data-dismiss="alert">
            ×
        </button>
        <i class="fa-fw fa fa-times"></i>
        <strong>Error!</strong> <?php if(is_array($T_Error)) print_r($T_Error); else echo $T_Error; ?>
    </div>
<?php endif; ?>


<!-- Bread crumb is created dynamically -->
<!-- row -->
<div class="row">

    <!-- col -->
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-cogs"></i>
            <?php echo _('Horarios') ?>
            <span>>
				<?php echo _('Configuración') ?>
			</span>
        </h1>
    </div>
    <!-- end col -->



</div>
<!-- end row -->




<!-- CONFIGURACIONES DE CONTROL -->
<?php if ($Mostrar_Configuraciones_Control )   { ?>
    <section id="widget-grid" class="">

        <div class="row">

            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget jarviswidget-color-blueDark"
                     id="wid-id-123"
                     data-widget-editbutton="false"
                     data-widget-colorbutton="false"
                     data-widget-deletebutton="false"
                     data-widget-sortable="false"
                     data-widget-fullscreenbutton="false">

                    <!-- HEADER -->
                    <header>
                        <!-- ICON -->
                        <span class="widget-icon">
                        <i class="fa fa-cogs"></i>
                    </span>
                        <!-- TITLE -->
                        <h2>
                            <?php echo _('Control de Personal') ?>
                        </h2>
                    </header>

                    <!-- FORM PARAMETERS -->
                    <div>

                        <div class="jarviswidget-editbox"></div>

                        <div class="widget-body no-padding">

                            <form   data-async
                                    class             ="smart-form"
                                    novalidate        ="novalidate"
                                    method            ="post"
                                    id                ="editar-form-control"
                                    action            ="<?php echo 'ajax/'.$Item_Name.'.html.php' ?>?tipo=save" >


                                <fieldset>

                                    <!-- LECTURE TIME BLOCKING  -->
                                    <div class="row">

                                        <section class = "col col-1"> </section>



                                        <section class="col col-5">

                                            <!-- LECTURE TIME BLOCKING LABEL -->
                                            <label class="label">
                                                <?php echo htmlentities("Despreciar Marcaciones Dobles", ENT_QUOTES, 'utf-8'); ?>
                                            </label>

                                            <!-- LECTURE TIME BLOCKING SELECT -->
                                            <label class = "select">

                                                <select name = "data[<?php echo $o_Parametros_Control['tiempo_bloqueo_lectura']->getId();?>]" id="select-tiempo-bloqueo-lectura">

                                                    <option value="" selected disabled>
                                                        Selecciona el Margen para Despreciar Marcaciones Repetidas
                                                    </option>
                                                    <?php foreach($_Listado_MinBloqueo as $minBloqueo_Key => $minBloqueo_Item){ ?>

                                                        <option     value="<?php echo ($minBloqueo_Item * 60); ?>"
                                                            <?php
                                                            $_MinBloqueo_Actual = ceil($o_Parametros_Control['tiempo_bloqueo_lectura']->getValor()/ 60);
                                                            if ( $minBloqueo_Item == $_MinBloqueo_Actual ){ ?>
                                                                selected
                                                            <?php   }   ?>>

                                                            <?php echo $minBloqueo_Item." Minutos"; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select><i></i>

                                            </label>

                                        </section>
                                    </div>

                                    <!-- LATE ARRIVAL TIME MARGIN  -->
                                    <div class="row">
                                        <section class="col col-5">

                                            <!-- LATE ARRIVAL TIME MARGIN LABEL -->
                                            <label class="label">
                                                <?php echo htmlentities("Llegadas Tarde", ENT_QUOTES, 'utf-8'); ?>
                                            </label>

                                            <!-- LATE ARRIVAL TIME MARGIN SELECT -->
                                            <label class = "select">

                                                <select name = "data[<?php echo $o_Parametros_Control['margen_llegada_tarde']->getId();?>]" id="select-margen-llegada-tarde">

                                                    <option value="" selected disabled>
                                                        Selecciona el Margen Para Considerar Llegadas Tarde
                                                    </option>
                                                    <?php foreach($_Listado_MinTarde as $minTarde_Key => $minTarde_Item){ ?>

                                                        <option     value="<?php echo $minTarde_Item; ?>"
                                                            <?php   if ( $minTarde_Item == $o_Parametros_Control['margen_llegada_tarde']->getValor()){ ?>
                                                                selected
                                                            <?php   }   ?>>

                                                            <?php echo $minTarde_Item." Minutos"; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select><i></i>

                                            </label>

                                        </section>
                                    </div>

                                    <!-- ABSENCE TIME MARGIN  -->
                                    <div class="row">
                                        <section class="col col-5">

                                            <!-- EARLY DEPARTURE TIME MARGIN LABEL -->
                                            <label class="label">
                                                <?php echo htmlentities("Ausencias", ENT_QUOTES, 'utf-8'); ?>
                                            </label>

                                            <!-- EARLY DEPARTURE TIME MARGIN SELECT -->
                                            <label class = "select">

                                                <select name = "data[<?php echo $o_Parametros_Control['margen_ausencia']->getId();?>]" id="select-margen-salida">

                                                    <option value="" selected disabled>
                                                        Selecciona el Margen Para Considerar Ausencias
                                                    </option>
                                                    <?php foreach($_Listado_MinAusencia as $minAusencia_Key => $minAusencia_Item){ ?>

                                                        <option     value="<?php echo $minAusencia_Item; ?>"
                                                            <?php   if ( $minAusencia_Item == $o_Parametros_Control['margen_ausencia']->getValor()){ ?>
                                                                selected
                                                            <?php   }   ?>>

                                                            <?php echo $minAusencia_Item." Minutos"; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select><i></i>

                                            </label>

                                        </section>
                                    </div>

                                    <!-- EARLY DEPARTURE TIME MARGIN  -->
                                    <div class="row">
                                        <section class="col col-5">

                                            <!-- EARLY DEPARTURE TIME MARGIN LABEL -->
                                            <label class="label">
                                                <?php echo htmlentities("Salidas Tempranas", ENT_QUOTES, 'utf-8'); ?>
                                            </label>

                                            <!-- EARLY DEPARTURE TIME MARGIN SELECT -->
                                            <label class = "select">

                                                <select name = "data[<?php echo $o_Parametros_Control['margen_salida']->getId();?>]" id="select-margen-salida">

                                                    <option value="" selected disabled>
                                                        Selecciona el Margen Para Considerar Salidas Tempranas
                                                    </option>
                                                    <?php foreach($_Listado_MinSalida as $minSalida_Key => $minSalida_Item){ ?>

                                                        <option     value="<?php echo $minSalida_Item; ?>"
                                                            <?php   if ( $minSalida_Item == $o_Parametros_Control['margen_salida']->getValor()){ ?>
                                                                selected
                                                            <?php   }   ?>>

                                                            <?php echo $minSalida_Item." Minutos"; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select><i></i>

                                            </label>

                                        </section>
                                    </div>

                                </fieldset>

                            </form>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar-control">
                                    <?php echo _("Guardar"); ?>
                                </button>
                            </div>
                        </div><!-- end widget content -->
                    </div><!-- end widget div -->
                </div><!-- end widget -->
            </article><!-- WIDGET END -->
        </div><!-- end row -->

    </section>
<?php }?>
<!-- CONFIGURACIONES DE INTERFAZ -->
<?php if ($Mostrar_Configuraciones_Interfaz)   {  ?>
    <section id="widget-grid" class="">

        <div class="row">

            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="jarviswidget jarviswidget-color-blueDark"
                     id="wid-id-124"
                     data-widget-editbutton="false"
                     data-widget-colorbutton="false"
                     data-widget-deletebutton="false"
                     data-widget-sortable="false"
                     data-widget-fullscreenbutton="false">

                    <!-- HEADER -->
                    <header>
                        <!-- ICON -->
                        <span class="widget-icon">
                            <i class="fa fa-cogs"></i>
                        </span>
                        <!-- TITLE -->
                        <h2>
                            <?php echo _('Interfaz Visual') ?>
                        </h2>
                    </header>

                    <!-- FORM BODY: PARAMETERS -->
                    <div>

                        <div class="jarviswidget-editbox"></div>

                        <div class="widget-body no-padding">

                            <form   data-async
                                    class             ="smart-form"
                                    novalidate        ="novalidate"
                                    method            ="post"
                                    id                ="editar-form-interfaz"
                                    action            ="<?php echo 'ajax/'.$Item_Name.'.html.php' ?>?tipo=save" >

                                <fieldset>

                                    <!-- PARAMETROS -->
                                    <?php

                                    foreach ($o_Parametros_Interfaz as $key => $item){ ?>

                                        <!-- SECTIONS NOT VISIBLES -->
                                        <?php
                                            if(!$item->getVisible()) continue;
                                        ?>

                                        <!-- VISIBLE SECTIONS -->
                                        <div class="row">

                                            <!-- SECTION COLUMN STYLING  -->
                                            <?php if($item->getTipo()=='si_no' || $item->getTipo()=='si_no_invertido') {?>
                                            <!-- STYLE: COL 5   -->
                                            <section class="col col-5">
                                                <?php }else ?>
                                                <!-- STYLE: COL 10  -->
                                                <section class="col col-10" style="width: 100%;">

                                                    <?php
                                                    // SWITCH TYPE
                                                    switch ($item->getTipo()){
                                                        case 'numerico':?>

                                                            <!-- NUMERIC LABEL -->
                                                            <label class="label">
                                                                <?php echo htmlentities($item->getDetalle(), ENT_QUOTES, 'utf-8'); ?>
                                                            </label>

                                                            <!-- NUMBERIC INPUT -->
                                                            <label class="input">
                                                                <input type="text"
                                                                       autocomplete="off"
                                                                       size="5"
                                                                       name="data[<?php echo $item->getId(); ?>]"
                                                                       value="<?php echo (isset($T_CompanyData[$item->getId()]))?$T_CompanyData[$item->getId()]:$item->getValor(); //$item->getValor(); ?>" />
                                                            </label>

                                                            <?php break;

                                                        case 'string': ?>

                                                            <!-- STRING LABEL -->
                                                            <label class="label">
                                                                <?php echo htmlentities($item->getDetalle(), ENT_QUOTES, 'utf-8'); ?>
                                                            </label>

                                                            <!-- STRING INPUT -->
                                                            <label class="input">
                                                                <input  type="text"
                                                                        autocomplete="off"
                                                                        size="35"
                                                                        name="data[<?php echo $item->getId(); ?>]"
                                                                        value="<?php echo (isset($T_CompanyData[$item->getId()]))?$T_CompanyData[$item->getId()]:$item->getValor(); //$item->getValor(); ?>" />
                                                            </label>

                                                            <?php break;

                                                        case 'si_no':?>

                                                            <!-- LABEL -->
                                                            <label  class="label">
                                                                <?php echo htmlentities($item->getDetalle(), ENT_QUOTES, 'utf-8'); ?>
                                                            </label>

                                                            <!-- INPUT -->
                                                            <label class="toggle">

                                                                <!-- HIDDEN QUESTION -->
                                                                <input  type="hidden"
                                                                        name="<?php echo "data[".$item->getId()."]"; ?>"
                                                                        value="" >

                                                                <!-- CHECKBOX INPUT -->
                                                                <input  type="checkbox"
                                                                        name="<?php echo "data[".$item->getId()."]"; ?>"
                                                                    <?php echo ($item->getValor() == '1')?'checked="checked"':'' ?> >

                                                                <!-- TOGGLE ICON -->
                                                                <i      data-swchon-text="Si"
                                                                        data-swchoff-text="No">
                                                                </i>


                                                            </label>

                                                            <?php break;

                                                        case 'password':?>
                                                            <!-- LABEL -->
                                                            <label  class="label">
                                                                <?php echo htmlentities($item->getDetalle(), ENT_QUOTES, 'utf-8'); ?>
                                                            </label>

                                                            <label  class="input">
                                                                <input  type="password"
                                                                        autocomplete="off"
                                                                        size="35"
                                                                        name="data[<?php echo $item->getId(); ?>]"
                                                                        value="<?php echo (isset($T_CompanyData[$item->getId()]))?$T_CompanyData[$item->getId()]:$item->getValor(); //$item->getValor(); ?>" />
                                                            </label>
                                                            <?php
                                                            break;

                                                    }
                                                    echo (isset ($T_Error['e'.$item->getId()]))?'<p class="error">'.htmlentities($T_Error['e'.$item->getId()], ENT_COMPAT, 'utf-8').'</p>':'';
                                                    ?>

                                                </section>
                                        </div>
                                    <?php	} ?>
                                </fieldset>

                            </form>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar-interfaz">
                                    <?php echo _("Guardar"); ?>
                                </button>
                            </div>
                        </div><!-- end widget content -->
                    </div><!-- end widget div -->
                </div><!-- end widget -->
            </article><!-- WIDGET END -->
        </div><!-- end row -->

    </section>

<?php }?>




<script type="text/javascript">



    pageSetUp();

    if($('.DTTT_dropdown.dropdown-menu').length){
        $('.DTTT_dropdown.dropdown-menu').remove();
    }
    <?php
    //INCLUYO el js de las datatables
    require_once APP_PATH . '/includes/data_tables.js.php';
    ?>




    $(document).ready(function() {

        // SUBMIT SAVE FORM: CONTROL DE PERSONAL
        $('#submit-editar-control').click(function(){
            var $form = $('#editar-form-control');

            if (!$form.valid()) {
                return false;
            }

            else{
                $('#content').html('<h1 class="ajax-loading-animation"><i class="fa fa-cog fa-spin"></i> Cargando...</h1>');

                $.ajax({
                    type:   $form.attr('method'),
                    url:    $form.attr('action'),
                    data:   $form.serialize(),

                    success: function(data, status) {
                        $('#content').css({opacity : '0.0'}).html(data).delay(50).animate({opacity : '1.0'}, 300);
                    }
                });
            }

        });

        // SUBMIT SAVE FORM: CONFIGURACIONES DE INTERFAZ
        $('#submit-editar-interfaz').click(function(){
            var $form = $('#editar-form-interfaz');

            if (!$form.valid()) {
                return false;
            }
            else{
                $('#content').html('<h1 class="ajax-loading-animation"><i class="fa fa-cog fa-spin"></i> Cargando...</h1>');
                $.ajax({
                    type:   $form.attr('method'),
                    url:    $form.attr('action'),
                    data:   $form.serialize(),

                    success: function(data, status) {
                        $('#content').css({opacity : '0.0'}).html(data).delay(50).animate({opacity : '1.0'}, 300);
                    }
                });
            }
        });

        // KEYUP KEYPRESS: CONTROL DE PERSONAL
        $('#editar-form-control').bind("keyup keypress", function (e) {
            var code = e.keyCode || e.which;
            if (code == 13) {
                e.preventDefault();
                return false;
            }
        });

        //  KEYUP KEYPRESS: CONFIGURACIONES DE INTERFAZ
        $('#editar-form-interfaz').bind("keyup keypress", function (e) {
            var code = e.keyCode || e.which;
            if (code == 13) {
                e.preventDefault();
                return false;
            }
        });



    });


</script>


<?php // require_once APP_PATH . '/includes/chat_widget.php'; ?>

