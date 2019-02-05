
<section class="page-header">
	<div class="container">
		<h1>Inicio</h1>
		<hr>
		<p>Pantalla principal del panel de control. Aquí se podrán ver a modo de resumen la info más destacada de tu centro como ser visitas totales, visitas del mes actual, mensajes recibidos, etc.</p>
	</div>
</section>



<section class="gral-section">
	<div class="container">

		<div class="row">
			<div class="col-sm-4">
				<div class="well ">
					<h3 class="fw-700">Últimas promos cargadas</h3>
					
					<?php
					$_promos->sort = 'added';
					$_promos->get();
					if($_promos->data()): 
						foreach($_promos->data() as $promo):
							if($promo->statusstart == 0){
								$status = '<span class="label label-warning">no inició</span>';
							}
							if($promo->statusstart == 1 && $promo->statusfinish == 0){
								$status = '<span class="label label-danger">finalizada</span>';
							}
							if($promo->statusstart == 1 && $promo->statusfinish == 1){
								$status = '<span class="label label-success">en curso</span>';
							}
					?>
					<div class="mod-list">
						<h4><a href="<?= ROOTPATH.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title) ?>" target="_blank" ><?= $promo->title ?></a></h4>
						<?= $status ?>
						<hr>
						<small>Agregada el: <?= $promo->creado.($promo->sale ? ' &bullet; <i class="fa fa-shopping-bag" title="Venta Online"></i>' : '') ?></small>
					</div>
					<?php endforeach; endif; ?>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="well">
					<h3 class="fw-700">Promos próximas a vencer</h3>
					<?php
					$_promos->expiring = true;
					$_promos->sort = 'finish';
					$_promos->get();
					//print_r($_promos);
					if($_promos->data()):
						foreach($_promos->data() as $promo):
					?>
					<div class="mod-list">
						<h4><a href="<?= ROOTPATH.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title) ?>" target="_blank" ><?= $promo->title ?></a></h4>
						<hr>
						<small>Vence el: <?= $promo->finish.' '.($promo->sale ? ' &bullet; <i class="fa fa-shopping-bag" title="Venta Online"></i>' : '') ?> &bullet; Quedan: <?= $promo->dif ?> días</small>
					</div>
					<?php endforeach; else: ?>
					<p>No se encontraron promociones próximas a vencer en los últimos 10 días.</p>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="well">
					<h3 class="fw-700">Promos finalizadas</h3>
					<?php
					$_promos->expiring = false;
					$_promos->expired = true;
					$_promos->get();
					if($_promos->data()): 
						foreach($_promos->data() as $promo):
					?>
					<div class="mod-list">
						<h4><a href="<?= ROOTPATH.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title) ?>" target="_blank" ><?= $promo->title ?></a></h4>
						<hr>
						<small>Finalizó el: <?= $promo->finish.' &bullet; '.($promo->sale ? '<i class="fa fa-shopping-bag" title="Venta Online"></i>' : '') ?></small>
					</div>
					<?php endforeach; else: ?>
					<p>No se encontraron promociones finalizadas</p>
					<?php endif; ?>
				</div>
			</div>

		</div>

		
		<div class="well">
			<h3>Últimas consultas recibidas</h3>

			<?php 
			if($_messages->data()): 
				foreach($_messages->data() as $message): 
					$where = '';
					if(!is_null($message->glossaryname)){
						$where = 'Enviado a la Etiqueta: <a href="'.ROOTPATH.'etiqueta/'.$message->idglossary.'-'.Permalink($message->glossaryname).'" target="_blank">'.$message->glossaryname.'</a>';
					}
					if(!is_null($message->promotitle)){
						$where = 'Enviado a la Promo: <a href="'.ROOTPATH.'promo/'.$message->permalink.'/'.$message->idpromo.'-'.Permalink($message->promotitle).'" target="_blank">'.$message->promotitle.'</a>';
					}
					if(!is_null($message->clientname)){
						$where = 'Enviado a tu página del centro: <a href="'.ROOTPATH.'centros/'.$message->permalink.'" target="_blank">'.$message->clientname.'</a>';
					}
			?>

			<div class="mod-list">
				<h4><?= $message->fullname.' - '.$message->mail ?></h4>
				<p><?= $message->message ?></p>
				<p><?= $where ?></p>
				<hr>
				<small>Enviado el: <?= $message->creado ?></small>
				<div class="buttons">
					<a href="mailto:<?= $message->mail.'?subject=Re: Respuesta desde EstiloSpa.com&body=%0D%0A%0D%0A%0D%0A%0D%0A'.$message->message ?>" class="btn btn-primary btn-xs" title="Responder" data-placement="left"><i class="fa fa-envelope"></i></a>
				</div>
			</div>

		<?php endforeach; endif; ?>

		</div>

	</div>
</section>