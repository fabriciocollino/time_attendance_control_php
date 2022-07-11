<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php $_SESSION['filtro']['tipo'] = 'Grupos'; ?>

<?php require_once APP_PATH . '/controllers/grupos.php'; ?>

<?php require_once APP_PATH . '/includes/top-mensajes.inc.php';



?>


<!-- Bread crumb is created dynamically -->
<!-- row -->
<div class="row">

    <!-- col -->
    <div class="col-xs-8 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-users"></i>
            <?php echo _('Personas') ?>
            <span>>
                <?php echo _('Grupos') ?>
			</span>
        </h1>
    </div>
    <!-- end col -->
    <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 50) { ?>
        <div class="col-xs-4 col-sm-5 col-md-5 col-lg-8" id="sparkscontainer">
            <div id="sparks">

                <button data-toggle             ="modal"
                        data-backdrop           ="static"
                        data-target             ="#editar"
                        type                    ="button"
                        class                   ="btn btn-sm btn-primary"
                        onclick                 ="ClickOpcionesGrupo('add',0,'ajax/grupos-editar.html.php')" >
                    <?php echo _('Agregar Grupo') ?>
                </button>

            </div>
        </div>
    <?php } ?>


</div>
<!-- end row -->


<!-- widget grid -->
<section id="widget-grid" class="">

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false"
                 data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false"
                 data-widget-sortable="false">

                <header>
                    <span class="widget-icon"> <i class="fa fa-users"></i> </span>
                    <h2><?php echo _('Listado de Grupos') ?></h2>

                </header>

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body no-padding">

                        <table id="dt_basic" class="table table-striped table-hover dataTable no-footer col-12"
                               aria-describedby="dt_basic_info" style="width: 100%;">
                            <thead>
                            <tr>
                                <th data-priority="1" ><?php echo _('Nombre') ?></th>
                                <th data-priority="2" ><?php echo _('En Vivo') ?></th>
                                <th data-priority="3" ><?php echo _('Personas') ?></th>
                                <th data-priority="4" ><?php echo _('Opciones') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!is_null($o_Listado)): ?>

                                <?php foreach ($o_Listado as $key => $item): /* @var $item Grupo_O */ ?>
                                    <tr>

                                        <!-- NOMBRE -->
                                        <td>
                                            <?php
                                            $_dataDetalle = mb_convert_case($item->getDetalle(), MB_CASE_TITLE, "UTF-8");
                                            echo htmlentities($_dataDetalle, ENT_QUOTES, 'utf-8');
                                            ?>
                                        </td>

                                        <!-- EN VIVO -->
                                        <td>
                                            <?php if ($item->getEnVivo() == 1) echo "Si"; else echo "No"; ?>
                                        </td>

                                        <!-- PERSONAS -->
                                        <td>
                                            <?php echo count($item->get_o_Personas_Activas()); ?>
                                        </td>

                                        <!-- OPCIONES -->
                                        <td style="white-space: nowrap;">
                                            <?php if (Registry::getInstance()->Usuario->getTipoUsuarioObject()->getCodigo() >= 50) {?>

                                                <button data-toggle             ="modal"
                                                        data-backdrop           ="static"
                                                        data-target             ="#editar"
                                                        title                   ="<?php echo _('Ver Personas') ?>"
                                                        class                   ="btn btn-default btn-sm fa fa-eye fa-lg"
                                                        style                   ="line-height: .75em;"
                                                        onclick                 ="ClickOpcionesGrupo('view-filtro',<?php echo $item->getId(); ?> ,'ajax/personas-mini.html.php')">
                                                </button>

                                                <button data-toggle             ="modal"
                                                        data-backdrop           ="static"
                                                        data-target             ="#editar"
                                                        data-id                 ="<?php echo $item->getId(); ?>"
                                                        title                   ="<?php echo _('Agregar/Quitar Personas') ?>"
                                                        class                   ="btn btn-default btn-sm fa fa-user fa-lg"
                                                        onclick                 ="ClickOpcionesGrupo('personas',<?php echo $item->getId(); ?> ,'ajax/grupos-personas.html.php')"
                                                        style                   ="line-height: .75em;"><i class="fa fa-plus fa-xs"></i>
                                                </button>

                                                <button data-toggle             ="modal"
                                                        data-backdrop           ="static"
                                                        data-target             ="#editar"
                                                        data-id                 ="<?php echo $item->getId(); ?>"
                                                        title                   ="<?php echo _('Editar Nombre') ?>"
                                                        class                   ="btn btn-default btn-sm fa fa-edit fa-lg"
                                                        onclick                 ="ClickOpcionesGrupo('view',<?php echo $item->getId(); ?> ,'ajax/grupos-editar.html.php')"
                                                style="line-height: .75em;"></button>

                                                <button data-type               ="delete"
                                                        data-target             ="#editar"
                                                        data-id                 ="<?php echo $item->getId(); ?>"
                                                        title                   ="<?php echo _('Eliminar Grupo') ?>"
                                                        class                   ="btn btn-default btn-sm fa fa-trash-o fa-lg"
                                                        onclick                 ="ClickOpcionesGrupo('delete',<?php echo $item->getId(); ?> ,'ajax/grupos.html.php')"
                                                        style="line-height: .75em;">

                                                </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>


                            <?php else: ?>
                            <?php endif; ?>

                            </tbody>
                        </table>

                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->

        </article>
        <!-- WIDGET END -->

    </div>

    <!-- end row -->


</section>
<!-- end widget grid -->


<?php
//INCLUYO los view/edit etc de los cosos
require_once APP_PATH . '/templates/edit-view_modal.html.php';
?>


<script type="text/javascript">


    pageSetUp();

    if ($('.DTTT_dropdown.dropdown-menu').length) {
        $('.DTTT_dropdown.dropdown-menu').remove();
    }

    <?php
    //INCLUYO el js de las datatables
    require_once APP_PATH . '/includes/data_tables_otros.js.php';
    ?>

    // PERSONAS
    function ClickOpcionesGrupo(view_type,data_id,url) {


        if(view_type == 'delete'){

            ExistMsg = 0;//por un error en el plugin smartmessagebox  http://myorange.ca/supportforum/question/smartmessagebox-not-working-after-page-reload-smartmsgboxcount-not-reset

            $.SmartMessageBox({
                title: "Eliminar grupo",
                content: "Está por eliminar el grupo. Esta operación no se puede deshacer. ¿Desea continuar?",
                buttons: '[No][Si]'
            }, function (ButtonPressed) {
                if (ButtonPressed === "Si") {
                    //esto refresca la pagina
                    loadURLwData('<?php echo $Item_Name ?>', $('#content'), {tipo: view_type, id: data_id});
                }
                else if (ButtonPressed === "No") {
                    SmartUnLoading();

                }

            });
        }
        else if(view_type == 'view-filtro'){
            $('.modal-content').html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Cargando...</h1></div>");
            $.ajax({
                cache: false,
                type: 'POST',
                url: url,
                data: {
                    tipo: view_type,
                    f_grupo_id: data_id,
                },
                success: function (data) {
                    $('.modal-content').show().html(data);
                }
            });

        }
        else{
            $('.modal-content').html("<div style=\"padding:15px;height:75px;\"><h1 class=\"ajax-loading-animation\"><i class=\"fa fa-cog fa-spin\"></i> Cargando...</h1></div>");
            $.ajax({
                cache: false,
                type: 'POST',
                url: url,
                data: {
                    tipo: view_type,
                    id: data_id
                },
                success: function (data) {
                    $('.modal-content').show().html(data);
                }
            });

        }

    }




</script>

<?php // require_once APP_PATH . '/includes/chat_widget.php'; ?>

