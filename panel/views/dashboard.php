
<section class="page-header">
	<div class="container">
		<h1>Inicio</h1>
		<hr>
		<p>Pantalla principal del panel de control. Aquí se podrán ver a modo de resumen la info más destacada de tu centro como ser visitas totales, visitas del mes actual, mensajes recibidos, etc.</p>

		<a href="<?=ROOT.'centros/'.$_userdata->client_permalink ?>" class="text-aqua-0" target="_blank">Ir a mi página <i class="fa fa-angle-double-right"></i></a>
	</div>
</section>



<section class="gral-section bg-gray-5">
	<div class="container">


		<div class="stats-highlights">

			<div class="stats-block">
				<div class="icon">
					<i class="fa fa-eye"></i>
				</div>
				<div class="number"><?=$total_views?></div>
				<div class="caption">
					Visitas totales de tu página y las experiencias
				</div>
			</div>

			<div class="stats-block">
				<div class="icon">
					<i class="fa fa-star"></i>
				</div>
				<div class="number"><?=round($rating,1)?>/5</div>
				<small><?= Stars($rating); ?></small>
				<div class="caption">
					Promedio de calificaciones recibidas en tus experiencias
				</div>
			</div>

			<div class="stats-block">
				<div class="icon">
					<i class="fa fa-heart"></i>
				</div>
				<div class="number"><?=$total_favs?></div>
				<div class="caption">
					Favoritos totales de tu página y experiencias
				</div>
			</div>

			<div class="stats-block">
				<div class="icon">
					<i class="fa fa-comment"></i>
				</div>
				<div class="number"><?=$total_questions?></div>
				<div class="caption">
					Consultas recibidas en tu página, experiencias y etiquetas
				</div>
			</div>

			<div class="stats-block">
				<div class="icon">
					<i class="fa fa-shopping-bag"></i>
				</div>
				<div class="number"><?=$total_sales?></div>
				<div class="caption">
					Compras efectuadas en tus experiencias
				</div>
			</div>


			<div class="stats-block">
				<div class="icon">
					<i class="fa fa-calendar"></i>
				</div>
				<div class="number"><?=$total_reservations?></div>
				<div class="caption">
					Total turnos acumulados solicitados en tus experiencias
				</div>
			</div>


			<?php if($total_events): foreach($total_events as $event): ?>
			<div class="stats-block">
				<div class="icon">
					<i class="fa fa-<?=$event->icon?>"></i>
				</div>
				<div class="number"><?=$event->total?></div>
				<div class="caption"><?=$event->caption?></div>
			</div>
			<?php endforeach; endif; ?>

		</div>


		<div class="block-white">

			<div class="row">

				<div class="col-sm-6">

					<h4>Experiencias próximas a vencer</h4>
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
									<th>Experiencias</th>
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
					<p class="alert alert-info">No hay experiencias próximas a vencer.</p>
					<?php endif; ?>

				</div>

				<div class="col-sm-6">

					<h4>Experiencias finalizadas</h4>
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
									<th>Experiencia</th>
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
					<p class="alert alert-info">No se encontraron experiencias vencidas.</p>
					<?php endif; ?>

				</div>

			</div>

			<hr>

			<div class="row">

				<div class="col-md-6">
					<h4>Experiencias más visitadas</h4>
					<?php if($top_promos): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Experiencias</th>
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
					<h4>Experiencias más consultada</h4>
					<?php if($top_promos_questions): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Experiencias</th>
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

		</div>


	</div>
</section>