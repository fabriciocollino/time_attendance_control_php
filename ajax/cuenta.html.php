<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '.html.php') . '.php'; ?>


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
			<i class="fa-fw fa fa-star"></i>
				<?php echo _('Mi Cuenta') ?>
			<span>>
				<?php echo _('Perfil Empresa') ?>
			</span>
		</h1>
	</div>
	<!-- end col -->
	
	

</div>
<!-- end row -->


<!-- COMPANY DETAILS -->
<section id="widget-grid" class="">

    <div class="row">
        <article class = "col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
                    <i class="fa fa-star"></i>
                </span>
                <!-- TITLE -->
                <h2>
                    <?php echo _('Perfil Empresa') ?>
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
                            id                ="editar-form"
                            action            ="<?php echo 'ajax/'.$Item_Name.'.html.php' ?>?tipo=save" >


                        <fieldset>

                            <!-- NAME  -->
                            <div class="row">
                                <section class="col col-5">

                                    <!-- NAME LABEL -->
                                    <label class="label">
                                        <?php echo htmlentities($o_Datos_Empresa['empresa_nombre']->getDetalle(), ENT_QUOTES, 'utf-8'); ?>
                                    </label>

                                    <!-- NAME INPUT -->
                                    <label class="input">
                                        <input  type="text"
                                                placeholder="Razón Social"
                                                autocomplete="off"
                                                name="data[<?php echo $o_Datos_Empresa['empresa_nombre']->getId();?>]"
                                                value="<?php echo $o_Datos_Empresa['empresa_nombre']->getValor();?>" />
                                    </label>

                                </section>
                            </div>

                            <!-- WEB  -->
                            <div class="row">
                                <section class="col col-5">

                                    <!-- WEB LABEL -->
                                    <label class="label">
                                        <?php echo htmlentities($o_Datos_Empresa['empresa_web']->getDetalle(), ENT_QUOTES, 'utf-8'); ?>
                                    </label>

                                    <!-- WEB INPUT -->
                                    <label class="input">
                                        <input  type="url"
                                                placeholder="Página Web"
                                                autocomplete="off"
                                                name="data[<?php echo $o_Datos_Empresa['empresa_web']->getId();?>]"
                                                value="<?php echo $o_Datos_Empresa['empresa_web']->getValor(); ?>" />
                                    </label>

                                </section>
                            </div>

                            <!-- EMAIL  -->
                            <div class="row">
                                <section class="col col-5">

                                    <!-- EMAIL LABEL -->
                                    <label class="label">
                                        <?php echo htmlentities($o_Datos_Empresa['empresa_email']->getDetalle(), ENT_QUOTES, 'utf-8'); ?>
                                    </label>

                                    <!-- EMAIL INPUT -->
                                    <label class="input">
                                        <input  type="email"
                                                placeholder="Correo Electrónico"
                                                autocomplete="off"
                                                name="data[<?php echo $o_Datos_Empresa['empresa_email']->getId();?>]"
                                                value="<?php echo $o_Datos_Empresa['empresa_email']->getValor(); ?>" />
                                    </label>

                                </section>
                            </div>

                            <!-- PHONE  -->
                            <div class="row">
                                <section class="col col-5">

                                    <!-- TELEFONO LABEL -->
                                    <label class="label">
                                        <?php echo htmlentities($o_Datos_Empresa['empresa_telefono']->getDetalle(), ENT_QUOTES, 'utf-8'); ?>
                                    </label>

                                    <!-- TELEFONO INPUT -->
                                    <label class="input">
                                        <input  type="tel"
                                                placeholder="Número de Contacto"
                                                autocomplete="off"
                                                name="data[<?php echo $o_Datos_Empresa['empresa_telefono']->getId();?>]"
                                                value="<?php echo $o_Datos_Empresa['empresa_telefono']->getValor(); ?>" />
                                    </label>

                                </section>
                            </div>

                            <!-- COUNTRY  -->
                            <div class="row">
                                <section class="col col-5">

                                    <!-- COUNTRY LABEL -->
                                    <label class="label">
                                        <?php echo htmlentities($o_Datos_Empresa['empresa_pais']->getDetalle(), ENT_QUOTES, 'utf-8'); ?>
                                    </label>

                                    <!-- COUNTRY SELECT -->
                                    <label class = "select">

                                        <select name = "data[<?php echo $o_Datos_Empresa['empresa_pais']->getId();?>]" id="select-pais">

                                            <option value="" selected disabled>
                                                Selecciona un País
                                            </option>
                                            <?php foreach($_Listado_Paises as $pais_Key => $pais_Item){ ?>

                                                <option     value="<?php echo $pais_Item; ?>"
                                                            <?php   if ( $pais_Item == $o_Datos_Empresa['empresa_pais']->getValor()){ ?>
                                                            selected
                                                            <?php   }   ?>>

                                                    <?php echo $pais_Item; ?>

                                                </option>

                                            <?php } ?>

                                        </select><i></i>

                                    </label>

                                </section>
                            </div>

                            <!-- STATE  -->
                            <div class="row">
                                <section class="col col-5">

                                    <!-- NAME LABEL -->
                                    <label class="label">
                                        <?php echo htmlentities("Estado", ENT_QUOTES, 'utf-8'); ?>
                                    </label>

                                    <!-- NAME INPUT -->
                                    <label class="input">
                                        <input  type="text"
                                                placeholder="Provincia"
                                                autocomplete="off"
                                                name="data[<?php echo $o_Datos_Empresa['empresa_provincia']->getId();?>]"
                                                value="<?php echo $o_Datos_Empresa['empresa_provincia']->getValor();?>" />
                                    </label>

                                </section>
                            </div>

                            <!-- CITY  -->
                            <div class="row">
                                <section class="col col-5">

                                    <!-- CITY LABEL -->
                                    <label class="label">
                                        <?php echo htmlentities($o_Datos_Empresa['empresa_localidad']->getDetalle(), ENT_QUOTES, 'utf-8'); ?>
                                    </label>

                                    <!-- CITY INPUT -->
                                    <label class="input">
                                        <input  type="text"
                                                placeholder="Ciudad de Radicación"
                                                autocomplete="off"
                                                name="data[<?php echo $o_Datos_Empresa['empresa_localidad']->getId();?>]"
                                                value="<?php echo $o_Datos_Empresa['empresa_localidad']->getValor();?>" />
                                    </label>

                                </section>
                            </div>

                            <!-- ADDRESS  -->
                            <div class="row">
                                <section class="col col-5">

                                    <!-- ADDRESS LABEL -->
                                    <label class="label">
                                        <?php echo htmlentities($o_Datos_Empresa['empresa_direccion']->getDetalle(), ENT_QUOTES, 'utf-8'); ?>
                                    </label>

                                    <!-- ADDRESS INPUT -->
                                    <label class="input">
                                        <input  type="text"
                                                placeholder="Domicilio Fiscal"
                                                autocomplete="off"
                                                name="data[<?php echo $o_Datos_Empresa['empresa_direccion']->getId();?>]"
                                                value="<?php echo $o_Datos_Empresa['empresa_direccion']->getValor();?>" />
                                    </label>

                                </section>
                            </div>

                            <!-- POST CODE  -->
                            <div class="row">
                                <section class="col col-5">

                                    <!-- POST CODE  LABEL -->
                                    <label class="label">
                                        <?php echo htmlentities("CP", ENT_QUOTES, 'utf-8'); ?>
                                    </label>

                                    <!-- POST CODE  INPUT -->
                                    <label class="input">
                                        <input  type="text"
                                                placeholder="Código Postal"
                                                autocomplete="off"
                                                name="data[<?php echo $o_Datos_Empresa['empresa_codigo_postal']->getId();?>]"
                                                value="<?php echo $o_Datos_Empresa['empresa_codigo_postal']->getValor();?>" />
                                    </label>

                                </section>
                            </div>




                        </fieldset>

                    </form>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit-editar">
                            <?php echo _("Guardar"); ?>
                        </button>
                    </div>



                </div><!-- end widget content -->
            </div><!-- end widget div -->
        </div><!-- end widget -->
        </article><!-- WIDGET END -->
	</div><!-- end row -->

</section>
<!-- end widget grid -->







<script type="text/javascript">

	pageSetUp();
	
	if($('.DTTT_dropdown.dropdown-menu').length){
		$('.DTTT_dropdown.dropdown-menu').remove();
	}

<?php require_once APP_PATH . '/includes/data_tables.js.php'; ?>

	
$(document).ready(function() {

    // SUBMIT SAVE
    $('#submit-editar').click(function(){

        var $form = $('#editar-form');
		
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

    // FORM KEYUP KEYPRESS
    $('#editar-form').bind("keyup keypress", function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });


});


</script>


<?php // require_once APP_PATH . '/includes/chat_widget.php'; ?>

