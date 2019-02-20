
<section class="page-header">
	<div class="container">
		<h1>Inicio</h1>
		<hr>
		<p>Pantalla principal del panel de control. Aquí se podrán ver a modo de resumen la info más destacada del sitio como ser visitas totales, visitas del mes actual, usuarios registrados, etc.</p>		

	</div>
</section>


<section class="admin-box bg-gray-5">
	<div class="container">

		<div class="block-white">

			<div class="row">
				<div class="col-md-6">
					<h3>Promos próximas a vencer</h3>
					<div class="table-responsive">
						<table class="table table-hover table-bordered sz-10 table-striped">
							<thead>
								<tr>
									<th>Promo</th>
									<th>Vence el:</th>
									<th>Quedan:</th>
									<th></th>
								</tr>							
							</thead>
						
							<?php 
							$_promos->expiring = true;
							$_promos->sort = 'finish';
							$_promos->get();
							if($_promos->data()):
							?>
							<tbody>
								<?php foreach($_promos->data() as $promo): ?>
								<tr>
									<td><a href="<?= ROOTPATH.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title) ?>" target="_blank" title="ver" ><?= $promo->title ?></a></td>
									<td><?= $promo->finish.' '.($promo->sale ? ' &bullet; <i class="fa fa-shopping-bag" title="Venta Online"></i>' : '') ?></td>
									<td><label class="label label-<?=dif_labels($promo->dif)?>"><?= $promo->dif ?> días</label></td>
									<td><a href="<?= ROOTPATH.'admin/promo/'.$promo->id ?>" target="_blank" title="editar" class="btn btn-default btn-xs"><i class="fa fa-pencil fa-fw"></i></a></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
							<?php endif; ?>

						</table>
					</div>
				</div>
				<div class="col-md-6">
					<h3>Promos finalizadas</h3>
					<div class="table-responsive">
						<table class="table table-hover table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Promo</th>
									<th>Finalizó el:</th>
									<th></th>
								</tr>							
							</thead>
						
							<?php 
							$_promos->expiring = false;
							$_promos->expired = true;
							$_promos->get();
							if($_promos->data()):
							?>
							<tbody>
								<?php foreach($_promos->data() as $promo): ?>
								<tr>
									<td><?= $promo->title ?></td>
									<td><?= $promo->finish ?></td>
									<td><a href="<?= ROOTPATH.'admin/promo/'.$promo->id ?>" target="_blank" title="editar" class="btn btn-default btn-xs"><i class="fa fa-pencil fa-fw"></i></a></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
							<?php endif; ?>

						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>














<section class="gral-section dp-none">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-3">
				<div class="mod-highlight-panels alert alert-success">
					<div class="item">
						<i class="fa fa-users fa-4x"></i>
					</div>
					<div class="item">
						<h1>100.200</h1>
						<h4>Visitas Totales</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="mod-highlight-panels alert alert-warning">
					<div class="item">
						<i class="fa fa-users fa-4x"></i>
					</div>
					<div class="item">
						<h1>320.124</h1>
						<h4>Visitas Totales</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="mod-highlight-panels alert alert-info">
					<div class="item">
						<i class="fa fa-users fa-4x"></i>
					</div>
					<div class="item">
						<h1>100.200</h1>
						<h4>Visitas Totales</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3">
				<div class="mod-highlight-panels alert alert-danger">
					<div class="item">
						<i class="fa fa-users fa-4x"></i>
					</div>
					<div class="item">
						<h1>100.200</h1>
						<h4>Visitas Totales</h4>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="gral-section">
	<div class="container">


		<div class="row">
			<div class="col-sm-6">

				<div class="well ">
					<h3 class="fw-700">Últimos centros cargados</h3>
					
					<?php if($_clients->data()): foreach($_clients->data() as $client): ?>
					<div class="mod-list">
						<h4><a href="<?= ROOTPATH.'centros/'.$client->permalink ?>" target="_blank"><?= $client->name ?></a></h4>
						<p>Cant. de Promos: <?= $client->promos ?></p>
						<hr>
						<small>Agregado el: <?= $client->creado.' &bullet; '.($client->visible ? '<i class="fa fa-toggle-on" title="Visible"></i>' : '<i class="fa fa-toggle-off" title="Oculto"></i>') ?></small>
					</div>
					<?php endforeach; endif; ?>
				</div>

			</div>

			<div class="col-sm-6">
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
		</div>

				
		<div class="well">
			<h3>Últimas consultas efectuadas</h3>

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
						$where = 'Enviado al Centro: <a href="'.ROOTPATH.'centros/'.$message->permalink.'" target="_blank">'.$message->clientname.'</a>';
					}
			?>

			<div class="mod-list">
				<h4><?= $message->fullname.' - '.$message->mail ?></h4>
				<p><?= $message->message ?></p>
				<p><?= $where ?></p>
				<hr>
				<small>Enviado el: <?= $message->creado ?></small>
				<div class="buttons">
					<a href="mailto:<?= $message->mail ?>" class="btn btn-primary btn-xs" title="Responder" data-placement="left"><i class="fa fa-envelope"></i></a>
				</div>
			</div>

		<?php endforeach; endif; ?>

		</div>

	</div>
</section>