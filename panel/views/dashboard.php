
<section class="page-header">
	<div class="container">
		<h1>Inicio</h1>
		<hr>
		<p>Pantalla principal del panel de control. Aquí se podrán ver a modo de resumen la info más destacada de tu centro como ser visitas totales, visitas del mes actual, mensajes recibidos, etc.</p>
	</div>
</section>



<section class="gral-section bg-gray-5">
	<div class="container">

		<div class="block-white">

			<div class="row">
				
				<div class="col-sm-6">
				
					<h4>Promos próximas a vencer</h4>
					<?php 
					$Promos->expiring = true;
					$Promos->sort = 'finish';
					$Promos->get();
					if($promos_expiring = $Promos->data()): 
					?>
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
							<tbody>
								<?php foreach($promos_expiring as $promo): ?>
								<tr>
									<td>
										<a href="<?= ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title) ?>" target="_blank" title="ver" ><?=($promo->sale ? '<i class="fa fa-shopping-bag" title="Venta Online"></i>' : '')?> <?= $promo->title ?></a><br>

									</td>
									<td><?= $promo->finish ?></td>
									<td><label class="label label-<?=dif_labels($promo->dif)?>"><?= $promo->dif ?> días</label></td>
									<td><a href="<?= PANEL.'promo/'.$promo->id ?>" target="_blank" title="editar" class="btn btn-default btn-xs"><i class="fa fa-pencil fa-fw"></i></a></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p class="alert alert-info">No hay promos próximas a vencer.</p>
					<?php endif; ?>
					
				</div>

				<div class="col-sm-6">
					
					<h4>Promos finalizadas</h4>
					<?php 
					$Promos->expiring = false;
					$Promos->expired = true;
					$Promos->sort = 'added';
					$Promos->get();
					if($promos_expired = $Promos->data()):
					?>

					<div class="table-responsive">
						<table class="table table-hover table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Promo</th>
									<th>Finalizó el:</th>
									<th></th>
								</tr>							
							</thead>				
							
							<tbody>
								<?php foreach($promos_expired as $promo): ?>
								<tr>
									<td><?= $promo->title ?></td>
									<td><?= $promo->finish ?></td>
									<td><a href="<?= PANEL.'promo/'.$promo->id ?>" target="_blank" title="editar" class="btn btn-default btn-xs"><i class="fa fa-pencil fa-fw"></i></a></td>
								</tr>
								<?php endforeach; ?>
							</tbody>

						</table>
					</div>

					<?php else: ?>
					<p class="alert alert-info">No se ecnontraron promos vencidas.</p>
					<?php endif; ?>
					
				</div>

			</div>

			<hr>

			<div class="row">
				
				<div class="col-md-6">
					<h4>Promos más visitadas</h4>
					<?php if($top_promos): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Promo</th>
									<th>Visitas</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($top_promos as $promo): ?>
								<tr>
									<td><a href="<?=ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title)?>" target="_blank"><?=$promo->title?></a></td>
									<td><?=number_format($promo->views,0,'','.')?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p>No hay registros todavía</p>
					<?php endif; ?>
				</div>

				<div class="col-md-6">
					<h4>Promos más consultada</h4>
					<?php if($top_promos_questions): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Promo</th>
									<th>Consultas</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($top_promos_questions as $promo): ?>
								<tr>
									<td><a href="<?=ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title)?>" target="_blank"><?=$promo->title?></a></td>
									<td><?=number_format($promo->total,0,'','.')?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p>No hay registros todavía</p>
					<?php endif; ?>
				</div>

			</div>


			<hr>

			<h4>Preguntas recibidas sin responder</h4>

			<?php if($questions): foreach($questions as $question): ?>

			<div class="questions-wrapper">
				<div class="question-box">
					<div class="icon">
						<i class="fa fa-user"></i>
					</div>
					<div class="message">
						<p data-content="message" class="caption"><?=$question->message?></p>
						<small data-content="added">Enviada: <?=$question->creado?> hs.</small>
						<div class="actions">
							<a href="<?=ROOT.'responder-pregunta/'.$question->id?>" class="btn btn-primary btn-xs" target="_blank">Responder <i class="fa fa-comments"></i></a>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; else: ?>

			<p class="alert alert-info">Aún no recibiste preguntas</p>
			<?php endif; ?>



			


		</div>



	</div>
</section>