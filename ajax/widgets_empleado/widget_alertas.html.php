<?php require_once dirname(__FILE__) . '/../../_ruta.php'; ?>
<?php //$alertaOreporte = 'alerta'; ?>
<?php //require_once APP_PATH . '/controllers/' . 'notificaciones' . '.php'; ?>


<?php 
$cantidad_alertas =0;
// $o_Listado_Email = Email_L::obtenerTodosSentToday();
$o_Listado_Mensaje = Notificaciones_L::obtenerTodosNotMensaje("lam_Fecha like '".date('Y-m-d')."%' ");
// $o_Listado_Mensaje = Alert_Mensaje_L::obtenerTodos();
// pre($o_Listado_Email); max-height:300px;overflow-y: auto;

if ($o_Listado_Mensaje): 
	
?>
<div style="" >
	<table id="dt_basic" class="table table-striped table-hover dataTable no-footer"
		   aria-describedby="dt_basic_info" style="width: 100%;">
		<thead>
		<tr>
			<th data-priority="2"><?php echo _('Nombre') ?></th>
			<th data-priority="2"><?php echo _('Hora') ?></th>
			<th data-priority="2"><?php echo _('Horario') ?></th>
			<?php /*<th><?php echo _('Tipo') ?></th> */ ?>
		</tr>
		</thead>
		<tbody>
		<?php /* if ($o_Listado_Email): ?>

			<?php foreach ($o_Listado_Email as $key => $item): /* @var $item Notificaciones_O *
					$data = json_decode($item->getDetalle());
					$Persona = Persona_L::obtenerPorId($data->perID);
					if(isset($data->perID) && $Persona){
					?>
						<tr <?php //if (($key % 2) != 0): echo ' class="bg"'; endif; ?> >
							<td  style="vertical-align:middle;width:40%;"><?php 
									echo $Persona->getNombreCompleto();
								?></td>
							<td style="vertical-align:middle;width:30%;"><?php echo date('H:i:s ',$item->getFecha()).$dias_red[date('w',strtotime($item->getFecha()))]; ?></td>
							<?php /*<td><?php echo _('Email'); ?></td> ?>
							<td style="vertical-align:middle;width:30%;"><?php 
									if(isset($data->Disparador)){
										echo $Notificaciones_Disparadores[$data->Disparador];
									}
								?> 
							</td>
						</tr>
				<?php 
					}
				endforeach; ?>

		<?php endif; */?>
		
		
		<?php if ($o_Listado_Mensaje): ?>
			
			<?php foreach ($o_Listado_Mensaje as $key => $item): /* @var $item Notificaciones_O */ 
				if($item['lam_Usu_Id'] != 0) 
					if( !($_SESSION['USUARIO']['id'] == $item['lam_Usu_Id'])) continue; // check permission for me 
						$cantidad_alertas++;
						?>
					<tr <?php if (($key % 2) != 0): echo ' class="bg"'; endif; ?> >
						<td><?php echo $item['lam_Nombre']; ?></td>
						<td><?php echo date('H:i:s',strtotime($item['lam_Fecha'])); ?></td>
						<?php /*<td><?php echo _('Mensaje'); ?></td>*/ ?>
						<td><?php echo $Notificaciones_Disparadores[$item['lam_Disparador']]; ?></td>
					</tr>
				<?php endforeach; ?>

		<?php endif; ?>

		</tbody>
	</table>

</div>
<?php else: ?>
No hay alertas
<?php endif; ?>

<script type="text/javascript">
	$("#div_unread_count_alertas").text("<?php echo $cantidad_alertas; ?>");
    if (<?php echo $cantidad_alertas; ?> > 0)
		$("#div_unread_count_alertas").show();
    else
		$("#div_unread_count_alertas").hide();
		

</script>


