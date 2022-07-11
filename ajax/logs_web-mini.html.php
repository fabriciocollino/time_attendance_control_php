<?php require_once dirname(__FILE__) . '/../_ruta.php'; ?>
<?php require_once APP_PATH . '/controllers/' . basename(__FILE__, '-mini.html.php') . '.php'; ?>



    <!-- row -->
    <div class="row minilistadelogs">


        <?php if (!is_null($o_Listado)): ?>

            <?php foreach ($o_Listado as $key => $item): /* @var $item Logs_Web_O */

                $detalle = $item->getDetalle();
                $detalle = substr($detalle, strpos($detalle, '<b><span class="labelFechaAnterior">'));
                $detalle = substr_replace($detalle, '<br />', strpos($detalle, '<b><span class="labelFechaNueva">'), 0);

            ?>
                <b><?php echo $item->getFechaHora(Config_L::p('f_fecha_corta')); ?></b> por <b><?php echo ($item->getUsuId() == 0) ? 'No Logueado' : Usuario_L::obtenerPorId($item->getUsuId(), true)->getNombreCompleto(); ?></b><br />
                <?php echo $detalle; ?><br />
                <br />

            <?php endforeach; ?>


        <?php else: ?>
        <?php endif; ?>

    </div>

    <!-- end row -->


<script type="text/javascript">

</script>