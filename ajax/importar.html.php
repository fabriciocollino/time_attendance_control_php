<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/importar.php'; ?>
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
            <?php echo _('Configuración') ?>
            <span>>
				<?php echo  _('Importar CSV') ?>
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
        require_once APP_PATH . '/includes/widgets/widget_filtro_importar.php'; ?>
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

                            <!-- TITULO DE DATOS A IMPORTAR -->
                            <!-- $a_Atributos -->
                            <thead>
                            <?php if (!is_null($a_Nombres)){ ?>

                                <tr>
                                    <?php if($T_Tipo == IMPORT_PERSONAS){?>

                                        <th>
                                            <?php echo _('Foto') ?>
                                        </th>

                                    <?php } ?>


                                    <?php foreach ($a_Nombres as $h_item) { ?>
                                        <th>
                                            <?php echo $h_item;?>
                                        </th>
                                    <?php } ?>

                                    <th>
                                        Estado de Importación
                                    </th>

                                    <th>
                                        <?php echo _('Opciones') ?>
                                    </th>

                                </tr>

                            <?php } ?>
                            </thead>

                            <!-- DATOS A IMPORTAR -->
                            <tbody >
                            <?php if (!is_null($o_Data)   ){ ?>
                                <?php foreach($o_Data as $_itemID =>$_item){ ?>

                                    <tr id= "<?php echo $_itemID ;?>">
                                        <?php if($T_Tipo == IMPORT_PERSONAS){?>

                                            <td>
                                                <div class="smallImageThumb">
                                                    <?php echo '<img style="border-radius: 50%;" src="https://static.enpuntocontrol.com/app/v1/img/avatars/male-big.png" />'; ?>
                                                </div>
                                            </td>
                                        <?php } ?>
                                        <?php foreach($a_Atributos as $_Key) {?>
                                            <td class=
                                                <?php if($_item[$_Key]== ''){
                                                    echo "empty_cell";
                                                }
                                                else{
                                                    echo "data_cell";
                                                } ?>
                                                contenteditable="true">

                                                <?php echo $_item[$_Key] ;?>
                                            </td>
                                        <?php } ?>

                                        <td>
                                            <?php foreach ($_item['imp_status'] as $is_item) {
                                                echo '. '.$is_item.'<br>';
                                            }
                                            ?>
                                        </td>

                                        <td style="white-space: nowrap;">
                                            <!-- <button
                                                        title="<?php //echo _('Editar') ;?>"
                                                        data-type="edit"
                                                        class="btn btn-default btn-sm btn-edit fa fa-edit fa-lg"
                                                        style="line-height: .75em;display: none;"
                                                        onclick="">
                                                </button> -->
                                            <button
                                                    title="<?php echo _('Eliminar') ;?>"
                                                    data-type="delete"
                                                    id="<?php echo $_itemID;?>"
                                                    class="btn btn-default btn-sm btn-delete fa fa-trash-o fa-lg"
                                                    style="line-height: .75em;"
                                                    onclick="">
                                            </button>
                                            <!--<button
                                                        title="<?php //echo _('Actualizar') ;?>"
                                                        data-type="update"
                                                        class="btn btn-default btn-sm btn-update fa fa-check-square-o fa-lg"
                                                        style="line-height: .75em;display: none;"
                                                        onclick="">
                                                </button> -->
                                        </td>
                                    </tr>

                                <?php } ?>
                            <?php } ?>
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

            var table = $('#dt_basic').DataTable();
            table.row( $(this).parents('tr') ).remove().draw();

            $('#selFecha').trigger("change");

        });

        /**


         // EDIT
         $('a[data-type=edit], button[data-type=edit]').click(function () {

            var cell_n;
            var cell_key;
            var cell_text;

            for (var x = 0; x <  <?php //echo count($a_Atributos); ?>; x++) {
                cell_n = (x+1).toString() ;
                cell_key= "td:eq("+cell_n+")";

                cell_text = $(this).closest("tr").find(cell_key).text();
                $(this).closest("tr").find(cell_key).html(
                    '<input  ' +
                    'type="text" ' +
                    'name="name_'+cell_text.trim()+'" ' +
                    'value="'+cell_text.trim()+'" ' +
                    'class="form-control" >');
            }

            $(this).parents("tr").find(".btn-edit").hide();
            $(this).parents("tr").find(".btn-delete").hide();
            $(this).parents("tr").find(".btn-update").show();

        });
         */

        /**

         // UPDATE
         $('a[data-type=update], button[data-type=update]').click(function () {

            var cell_n;
            var cell_key;
            var cell_value;


            for (var x = 0; x <  <?php //echo count($a_Atributos); ?>; x++) {
                cell_n = (x+1).toString() ;
                cell_key= "td:eq("+cell_n+")";

                cell_value = $(this).closest("tr").find(cell_key).val();
                $(this).closest("tr").find(cell_key).text(cell_value.trim());
            }
            cell_n = (x+1).toString() ;
            cell_key= "td:eq("+cell_n+")";


            $(this).parents("tr").find(".btn-edit").show();
            $(this).parents("tr").find(".btn-delete").show();
            $(this).parents("tr").find(".btn-update").hide();
        });
         */

    });



</script>

<?php // require_once APP_PATH . '/includes/chat_widget.php'; ?>


