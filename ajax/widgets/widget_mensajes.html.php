<?php require_once dirname(__FILE__) . '/../../_ruta.php'; ?>

<?php

$o_Listado = Mensaje_L::obtenerTodosHoy();

?>

<style>
    #dt_basic_mensaje tr > td{
        vertical-align:top;
    }
    .plus-mensaje{
        display:none;
        width:60%;
    }
    .minus-mensaje{
        width:60%;
    }
</style>

<?php


$cantidad_mensaje = 0;

?>

<?php if ($o_Listado): ?>
    <div style="" >
        <table id="dt_basic_mensaje" class="table table-striped table-hover dataTable no-footer" style="width: 100%;">
            <thead>
            <tr>
                <th><?php echo _('Titulo') ?></th>
                <th><?php echo _('Hora') ?></th>
                <th><?php echo _('Mensaje') ?></th>
            </tr>
            </thead>
            <tbody>


            <?php foreach ($o_Listado as $key => $item): /*  @var $item Mensaje_O */
                if($item->getUsuId() != 0)
                    if( !($_SESSION['USUARIO']['id'] == $item->getUsuId())) continue; // check permission for me
                $cantidad_mensaje++;
                ?>
                <tr <?php if (($key % 2) != 0): echo ' class="bg"'; endif; ?> >


                    <td class="hidden-xs hidden-sm ">
                        <div>
                            <?php echo $item->getTitulo(); ?>
                        </div>
                    </td>
                    <td class="inbox-data-date hidden-xs">
                        <div title="<?php echo $item->getDisparadorHora(Config_L::p('f_fecha_corta')); ?>">
                            <?php echo date('H:i:s', strtotime($item->getDisparadorHora())); ?>
                        </div>
                    </td>
                    <td class="minus-mensaje">
                        <div>
                            <?php /* // old
                        <span><span class="label bg-color-orange"><?php echo $item->getTipo_S(); ?></span> <?php echo Equipo_L::obtenerPorId($item->getEqId())->getDetalle(); ?></span> <?php echo $item->getMensaje(60);  */?>

                            <?php  // abduls  ?>
                            <?php echo $item->getMensaje(50); ?>

                        </div>
                    </td>
                    <td class="plus-mensaje">
                        <?php echo $item->getMensaje(); ?>
                    </td>
                    <td >
                        <!--a href="javascript:void(0);" onclick="setMensajeVisto(<?php //echo $item->getId(); ?>);return;"
                       class="btn btn-default btn-xs"><i class="fa fa-close"></i></a-->

                        <a style="font-size:18px;" href="javascript:void(0);" onclick="mensajeMinimize(this ,<?php echo $item->getId(); ?>);" class="act-more act-collapsed ">
                            <i class="fa fa-plus-square-o"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>


            </tbody>
        </table>
    </div>
<?php else: ?>
    No hay mensajes
<?php endif; ?>

<script type="text/javascript">


    $("#div_unread_count_mensajes").text("<?php echo $cantidad_mensaje; ?>");
    if (<?php echo $cantidad_mensaje; ?> > 0)
    $("#div_unread_count_mensajes").show();
    else
    $("#div_unread_count_mensajes").hide();

    function mensajeMinimize(object,id){
        // console.log($(object).parent().parent().find('.minus-mensaje').html());
        if($(object).find('.fa').hasClass('fa-plus-square-o')){
            $(object).find('.fa').removeClass('fa-plus-square-o');
            $(object).find('.fa').addClass('fa-minus-square-o');
            $(object).parent().parent().find('.plus-mensaje').show();
            $(object).parent().parent().find('.minus-mensaje').hide();
        }else{
            $(object).find('.fa').removeClass('fa-minus-square-o');
            $(object).find('.fa').addClass('fa-plus-square-o');
            $(object).parent().parent().find('.plus-mensaje').hide();
            $(object).parent().parent().find('.minus-mensaje').show();
        }
    }


</script>


