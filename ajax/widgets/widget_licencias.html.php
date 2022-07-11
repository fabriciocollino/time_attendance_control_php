<?php require_once dirname(__FILE__) . '/../../_ruta.php'; ?>

<?php


$T_SelIntervalo         = (isset($_REQUEST['selIntervalo'])) ? $_REQUEST['selIntervalo'] : 'F_Hoy';


switch ($T_SelIntervalo) {
    case 'F_Hoy':
        $_Fecha_Desde   = date('Y-m-d 00:00:00');
        $_Fecha_Hasta   = date('Y-m-d 23:59:59');
        break;
    case 'F_Semana':
        $_Fecha_Desde   = date('Y-m-d 00:00:00', strtotime('-' . date('w') . ' days'));
        $_Fecha_Hasta   = date('Y-m-d 23:59:59', strtotime('+' . (6 - date('w')) . ' days'));
        break;
    default:
        $_Fecha_Desde   = date('Y-m-d 00:00:00');
        $_Fecha_Hasta   = date('Y-m-d 23:59:59');
        break;
}

$_Persona           = 'TodasLasPersonas';
$_Grupo             = 0;

$o_Licencias        = Licencias_L::obtenerTodosEntreFechasArray($_Fecha_Desde, $_Fecha_Hasta, $_Persona, $_Grupo);
$cantidad           = count($o_Licencias);



if ($cantidad > 0) {?>

    <div>
        <table id="dt_basic"
               class="table table-striped table-hover dataTable no-footer"
               aria-describedby="dt_basic_info"
               style="width: 100%;">

            <thead>
            <th><?php echo _('Legajo');   ?></th>
            <th><?php echo _('Nombre');   ?></th>
            <th><?php echo _('Duración');  ?></th>
            <th><?php echo _('Motivo');  ?></th>
            </thead>

            <tbody class="addNoWrap">

            <?php foreach ($o_Licencias as $per_Id => $item){

                $o_Persona = Persona_L::obtenerPorId($per_Id);

                ?>

                <tr>
                    <!-- LEGAJO -->
                    <td>
                        <?php echo $o_Persona->getLegajo(); ?>
                    </td>

                    <!-- NOMBRE -->
                    <td>
                        <?php echo mb_convert_case($o_Persona->getApellido().", ".$o_Persona->getNombre(), MB_CASE_TITLE, "UTF-8");
                        ?>
                    </td>

                    <!-- DURACIÓN -->
                    <td>
                        <?php foreach ($item as $key => $licencia){ ?>

                            <div>
                                <span class="">
                                    <?php

                                    if($licencia['lic_Duracion'] == 1 || $licencia['lic_Duracion'] == ""){
                                        echo $licencia['lic_Duracion']." día";
                                    }
                                    else{
                                        echo $licencia['lic_Duracion']." días";
                                    }

                                    ?>
                                </span><br>
                            </div>
                        <?php } ?>
                    </td>


                    <!-- MOTIVO -->
                    <td>
                        <?php foreach ($item as $key => $licencia){ ?>

                            <div>
                                <span class="">
                                    <?php echo $licencia['lic_Motivo'];?>
                                </span><br>
                            </div>
                        <?php } ?>
                    </td>

                </tr>

            <?php } ?>


            </tbody>



        </table>
    </div>

    <?php
}
else {
    echo _('No hay licencias');
}
?>

<style>
    td{
        vertical-align:middle;
    }
    th{
        vertical-align:middle;
        width:25%;
    }
</style>

<!-- WIDGET TOTAL ICON -->
<script type="text/javascript">

    $("#div_unread_count_licencias").text("<?php echo $cantidad; ?>");

    if (<?php echo $cantidad; ?> > 0)
    $("#div_unread_count_licencias").show();
    else
    $("#div_unread_count_licencias").hide();

</script>


