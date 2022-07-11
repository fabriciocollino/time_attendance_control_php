<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/vtex_a_facturante.php'; ?>
<?php require_once(APP_PATH . '/libs/random/random.php'); ?>
<?php require_once APP_PATH . '/includes/top-mensajes.inc.php'; ?>
<?php $Filtro_Form_Action = "ajax/" . basename(__FILE__); ?>

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
				<?php echo  _('Vtex a Facturante') ?>
			</span>
        </h1>
    </div>
    <!-- end col -->

</div>
<!-- end row -->



<!-- GRID -->
<section id="widget-grid" class="">

    <!-- FILTER -->
    <div class="row" id="filterWidget"><?php
        require_once APP_PATH . '/includes/widgets/widget_vtex_a_facturante.html.php'; ?>
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
                <div >
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox"><!-- This area used as dropdown edit box --></div>


                    <!-- PERSONAS -->
                    <div class="widget-body no-padding">

                        <table id="dt_basic" class="table table-striped table-hover dataTable no-footer"
                               aria-describedby="dt_basic_info" style="width: 100%;display: none">

                            <?php if($o_Data_out != null){?>
                                <!-- TITULO DE DATOS A IMPORTAR -->
                                <!-- $a_Atributos -->
                                <thead>

                                <tr>


                                    <?php foreach ($a_Nombres as $h_item) { ?>
                                        <th>
                                            <?php echo $h_item;?>
                                        </th>
                                    <?php } ?>
                                </tr>

                                </thead>

                                <!-- DATOS A IMPORTAR -->
                                <tbody >
                                <?php foreach($o_Data_out as $_itemID =>$_item){ ?>

                                    <tr id= "<?php echo $_itemID ;?>">

                                        <?php foreach($_item as $_valueID => $_value) {?>
                                            <td contenteditable="true" class=<?php if($_value == ''){echo "empty_cell";} else{echo "data_cell";} ?> >

                                                <?php echo $_value ;?>

                                            </td>
                                        <?php } ?>

                                    </tr>

                                <?php } ?>
                                </tbody>
                            <?php } ?>
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
    

    $(document).ready(function () {



    });



</script>



