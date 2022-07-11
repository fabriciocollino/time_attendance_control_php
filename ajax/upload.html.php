<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/upload.php'; ?>
<?php require_once(APP_PATH . '/libs/random/random.php'); ?>
<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; ?>

<!-- PAGE HEADER -->

<!-- row -->
<div class="row">

    <!-- col -->
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-file-code-o"></i>
            <?php echo _('Importar') ?>
            <span>>
				<?php echo  _('Importar Archivo') ?>
			</span>
        </h1>
    </div>
    <!-- end col -->

</div>
<!-- end row -->


<!-- GRID -->
<section id="widget-grid" class="">

    <!-- FILTER -->
    <div class="row" id="filterWidget">
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

                            <fieldset>
                                <form id = "otro" action="/upload.php" enctype="multipart/form-data" method="post">
                                    Files to upload: <br>
                                    <input type="file" name="uploaded_files" size="40">
                                    <input type="submit" value="Send">
                                </form>
                            </fieldset

                    </div>
                </div>


            </div>
            <!-- END: TABLE DIV -->

        </article>


    </div>

    <!-- TABLA DIV ROW -->
    <div class="row">

        <!-- TABLE ARTICULE -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <!-- FORM DIV -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false"
                 data-widget-sortable="false">

                <!-- TITULO -->
                <header>
                    <span class="widget-icon"> <i class="fa fa-user"></i></span>
                    <h2>
                        <?php echo _('Previsualización') ?>
                    </h2>
                </header>



                <!-- TABLE DIV -->
                <div>
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox"><!-- This area used as dropdown edit box --></div>


                    <!-- PERSONAS -->
                    <div class="widget-body no-padding">

                        <table id="dt_basic" class="table table-striped table-hover dataTable no-footer"
                               aria-describedby="dt_basic_info" style="width: 100%;">


                            <thead>

                            </thead>

                            <!-- DATOS A IMPORTAR -->
                            <tbody>

                                <?php

                                if (isset($o_Listado)){
                                    printear($o_Listado);
                                }

                                ?>

                            </tbody>
                            <!-- FIN: DATOS A IMPORTAR -->
                        </table>
                    </div>
                    <!-- FIN: PERSONAS -->
                </div>
                <!-- FIN: TABLE DIV -->




            </div>
            <!-- FIN: FORM DIV -->

        </article>
        <!-- FIN: TABLE ARTICULE -->
    </div>
    <!-- FIN: TABLA DIV ROW -->
</section>
<!-- FIN: GRID -->


<?php
//INCLUYO los view/edit etc de los cosos
require_once APP_PATH . '/templates/edit-view_modal.html.php';
?>


<script type="text/javascript">

    pageSetUp();

    if ($('.DTTT_dropdown.dropdown-menu').length) {
        $('.DTTT_dropdown.dropdown-menu').remove();
    }

    <?php require_once APP_PATH . '/includes/data_tables.js.php';?>


    $(document).ready(function () {


        // DELETE
        $('a[data-type=delete], button[data-type=delete]').click(function () {



        });




    });




</script>


