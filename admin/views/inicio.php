
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
					<h3>Experiencias próximas a vencer</h3>
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
									<th>Experiencia</th>
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
									<td><a href="<?= ROOT.'admin/promo/'.$promo->id ?>" target="_blank" title="editar" class="btn btn-default btn-xs"><i class="fa fa-pencil fa-fw"></i></a></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p class="alert alert-info">No hay experiencias próximas a vencer.</p>
					<?php endif; ?>
				</div>

				<div class="col-md-6">
					<h3>Experiencias finalizadas</h3>

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
									<td><a href="<?= ROOT.'admin/promo/'.$promo->id ?>" target="_blank" title="editar" class="btn btn-default btn-xs"><i class="fa fa-pencil fa-fw"></i></a></td>
								</tr>
								<?php endforeach; ?>
							</tbody>

						</table>
					</div>

					<?php else: ?>
					<p class="alert alert-info">No se ecnontraron experiencias vencidas.</p>
					<?php endif; ?>
				</div>
			</div>

		</div>

		<div class="block-white">

			<div class="row">
				<div class="col-md-6">
					<h3>Últimos centros cargados</h3>
					<div class="table-responsive">
						<table class="table table-hover table-bordered sz-10 table-striped">
							<thead>
								<tr>
									<th>Centro</th>
									<th>Cargado el:</th>
									<th>Experiencias:</th>
									<th></th>
								</tr>
							</thead>

							<?php
							if($Clients->data()):
							?>
							<tbody>
								<?php foreach($Clients->data() as $client): ?>
								<tr>
									<td><a href="<?= ROOT.'centros/'.$client->permalink ?>" target="_blank" title="ver" ><?= $client->name ?></a></td>
									<td><?= $client->creado ?></td>
									<td class="text-center"><label class="label label-primary ?>"><?= $client->promos ?></label></td>
									<td><a href="<?= ROOT.'admin/centro/'.$client->id ?>" target="_blank" title="editar" class="btn btn-default btn-xs"><i class="fa fa-pencil fa-fw"></i></a></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
							<?php endif; ?>

						</table>
					</div>
				</div>
				<div class="col-md-6">
					<h3>Últimas experiencias cargadas</h3>
					<div class="table-responsive">
						<table class="table table-hover table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Experiencia</th>
									<th>Agregada el:</th>
									<th>Estatus</th>
									<th></th>
								</tr>
							</thead>

							<?php
							$Promos->expired = false;
							$Promos->sort = 'added';
							$Promos->get();
							if($Promos->data()):
							?>
							<tbody>
								<?php
								foreach($Promos->data() as $promo):
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
								<tr>
									<td><a href="<?= ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title) ?>" target="_blank" ><?= $promo->title ?></a></td>
									<td><?=$promo->creado?></td>
									<td><?=$status?></td>
									<td><a href="<?= ROOT.'admin/promo/'.$promo->id ?>" target="_blank" title="editar" class="btn btn-default btn-xs"><i class="fa fa-pencil fa-fw"></i></a></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
							<?php endif; ?>

						</table>
					</div>
				</div>
			</div>

		</div>


		<!-- Stats -->
		<div class="block-white">

			<div class="row">

				<div class="col-md-6">
					<h4>Plabaras más buscadas</h4>
					<?php if($top_words): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Palabra</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($top_words as $word): ?>
								<tr>
									<td><a href="<?=ROOT.'busqueda/'.Permalink($word->word)?>" target="_blank"><?=$word->word?></a></td>
									<td><?=$word->total?></td>
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
					<h4>Lugares más buscados</h4>
					<?php if($top_locations): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Lugar</th>
									<th>Total</th>
								</tr>
							</thead>
							<?php foreach($top_locations as $location): ?>
								<tr>
									<td><a href="<?=ROOT.'busqueda/-/'.Permalink($location->location)?>" target="_blank"><?=$location->location?></a></td>
									<td><?=$location->total?></td>
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


			<div class="row">

				<div class="col-md-6">
					<h4>Experiencias más visitadas</h4>
					<?php if($top_promos): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Experiencia</th>
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
					<h4>Centros más visitados</h4>
					<?php if($top_clients): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Centro</th>
									<th>Visitas</th>
								</tr>
							</thead>
							<?php foreach($top_clients as $client): ?>
								<tr>
									<td><a href="<?=ROOT.'centros/'.$client->permalink?>" target="_blank"><?=$client->name?></a></td>
									<td><?=number_format($client->views,0,'','.')?></td>
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


			<div class="row">

				<div class="col-md-6">
					<h4>Entradas del blog más visitadas</h4>
					<?php if($top_blog): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Entrada</th>
									<th>Visitas</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($top_blog as $blog): ?>
								<tr>
									<td><a href="<?=ROOT.'blog-pagina/'.$blog->id.'-'.Permalink($blog->title)?>" target="_blank"><?=$blog->title?></a></td>
									<td><?=number_format($blog->views,0,'','.')?></td>
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
					<h4>Etiqueta más visitada</h4>
					<?php if($top_glossary): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Etiqueta</th>
									<th>Visitas</th>
								</tr>
							</thead>
							<?php foreach($top_glossary as $glossary): ?>
								<tr>
									<td><a href="<?=ROOT.'etiqueta/'.$glossary->id.'-'.Permalink($glossary->name)?>" target="_blank"><?=$glossary->name?></a></td>
									<td><?=number_format($glossary->views,0,'','.')?></td>
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


			<div class="row">

				<div class="col-md-6">
					<h4>Experiencias más consultadas</h4>
					<?php if($top_promos_questions): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Experiencia</th>
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

				<div class="col-md-6">
					<h4>Centros más consultados</h4>
					<?php if($top_clients_questions): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Centro</th>
									<th>Visitas</th>
								</tr>
							</thead>
							<?php foreach($top_clients_questions as $client): ?>
								<tr>
									<td><a href="<?=ROOT.'centros/'.$client->permalink?>" target="_blank"><?=$client->name?></a></td>
									<td><?=number_format($client->total,0,'','.')?></td>
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

