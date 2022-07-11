

<?php
if( isset($_SESSION['T_Mensaje'])   ){
    $T_Mensaje              =   $_SESSION['T_Mensaje'];
    unset($_SESSION['T_Mensaje']);
}
if( isset($_SESSION['T_Mensaje_Detalle'])   ){
    $T_Mensaje_Detalle      =   $_SESSION['T_Mensaje_Detalle'];
    unset($_SESSION['T_Mensaje_Detalle']);
}

if (isset($T_Mensaje) && $T_Mensaje!=null):
?>

<div class="alert alert-success fade in">
	<button class="close" data-dismiss="alert">
		×
	</button>
	<i class="fa-fw fa fa-check"></i>
	<strong>Éxito!</strong> <? echo $T_Mensaje;?>
    <?php if( isset($T_Mensaje_Detalle)  ){?>
        <pre>
         <?php echo $T_Mensaje_Detalle;?>
        </pre>
    <?php }?>
</div>
<?php endif; ?>



<?php

if( isset($_SESSION['T_Error'])   ){
    $T_Error      =   $_SESSION['T_Error'];
    unset($_SESSION['T_Error']);
}

if( isset($_SESSION['T_Error_Detalle'])   ){
    $T_Error_Detalle      =   $_SESSION['T_Error_Detalle'];
    unset($_SESSION['T_Error_Detalle']);
}

if (isset($T_Error) && $T_Error!=null && $T_Error!=''):
    $T_Error = "Lo sentimos, hubo un error en la operación.";//
    ?>
<div class="alert alert-danger fade in">
	<button class="close" data-dismiss="alert">
		×
	</button>
	<i class="fa-fw fa fa-times"></i>
	<strong>Error!</strong> <?php echo $T_Error; ?>
    <?php if( isset($T_Error_Detalle)  ){?>
        <pre>
         <?php printear($T_Error_Detalle);//echo $T_Error_Detalle;?>
        </pre>
    <?php }?>

</div>
<?php endif;


?>

<style>

    pre {border: 0; background-color: transparent;}

</style>




