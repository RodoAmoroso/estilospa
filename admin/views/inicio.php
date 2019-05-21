
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
									<td><a href="<?= ROOT.'admin/promo/'.$promo->id ?>" target="_blank" title="editar" class="btn btn-default btn-xs"><i class="fa fa-pencil fa-fw"></i></a></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p class="alert alert-info">No hay promos próximas a vencer.</p>
					<?php endif; ?>
				</div>

				<div class="col-md-6">
					<h3>Promos finalizadas</h3>

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
									<td><a href="<?= ROOT.'admin/promo/'.$promo->id ?>" target="_blank" title="editar" class="btn btn-default btn-xs"><i class="fa fa-pencil fa-fw"></i></a></td>
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

		</div>
	</div>
</section>


<section class="admin-box bg-gray-5">
	<div class="container">

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
									<th>Promos:</th>
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
					<h3>Últimas promos cargadas</h3>
					<div class="table-responsive">
						<table class="table table-hover table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Promo</th>
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
	</div>
</section>



<!-- QUESTIONS -->
<section class="admin-box bg-gray-5">
	<div class="container">
		

		<div class="row">
			<div class="col-md-6">

				<div class="block-white" >
				
					<h3>Preguntas sin responder</h3>
					<hr>

					<?php if($questions): foreach($questions as $question): ?>

					<div class="questions-wrapper">
						<div class="question-box">
							<div class="icon">
								<i class="fa fa-user"></i>
							</div>
							<div class="message">
								<p data-content="message" class="caption"><?=$question->message?></p>
								<small data-content="added">Enviada por <?=$question->user_name.' ('.$question->user_email.')'?>: <?=$question->creado?> hs.</small>

								<div class="actions">
									
									<?php if($question->type=='promos'): ?>
									Enviada a la promo <a href="<?= ROOT.'promo/'.$question->permalink_promo.'/'.$question->rowid ?>" class="text-fucsia-3" target="_blank"><?=$question->promo_title?></a>
									<?php endif; ?>

									<?php if($question->type=='clients'): ?>
									Enviada al centro <a href="<?= ROOT.'centros/'.$question->permalink ?>" class="text-fucsia-3" target="_blank"><?=$question->client_name?></a>
									<?php endif; ?>

									<?php if($question->type=='glossary'): ?>
									Enviada a la etiqueta <a href="<?= ROOT.'etiqueta/'.$question->rowid ?>" class="text-fucsia-3" target="_blank"><?=$question->glossary_name?></a>
									<?php endif; ?>

								</div>

							</div>
							<div class="buttons">
								<button class="btn btn-xs btn-white text-pink-3" data-btn="delete-question" data-id="<?=$question->id?>" title="borrar pregunta"><i class="fa fa-trash"></i></button>
							</div>
						</div>
					</div>
					<?php endforeach; else: ?>

					<p class="alert alert-info">No se encontraron preguntas sin responder</p>
					<?php endif; ?>

				</div>

			</div>
			<div class="col-md-6">

				<div class="block-white" >
				
					<h3>Últimas respuestas</h3>
					<hr>

					<?php if($questions_responses): foreach($questions_responses as $question): ?>

					<div class="questions-wrapper">
						<div class="question-box">
							<div class="icon">
								<i class="fa fa-user"></i>
							</div>
							<div class="message">
								<p data-content="message" class="caption"><?=$question->message?></p>
								<small data-content="added">Enviada por <?=$question->user_name.' ('.$question->user_email.')'?>: <?=$question->creado?> hs.</small>

								<div class="actions">
									
									<?php if($question->type=='promos'): ?>
									Enviada a la promo <a href="<?= ROOT.'promo/'.$question->permalink_promo.'/'.$question->rowid ?>" class="text-fucsia-3" target="_blank"><?=$question->promo_title?></a>
									<?php endif; ?>

									<?php if($question->type=='clients'): ?>
									Enviada al centro <a href="<?= ROOT.'centros/'.$question->permalink ?>" class="text-fucsia-3" target="_blank"><?=$question->client_name?></a>
									<?php endif; ?>

									<?php if($question->type=='glossary'): ?>
									Enviada a la etiqueta <a href="<?= ROOT.'etiqueta/'.$question->rowid ?>" class="text-fucsia-3" target="_blank"><?=$question->glossary_name?></a>
									<?php endif; ?>

								</div>

							</div>
							<div class="buttons">
								<button class="btn btn-xs btn-white text-pink-3" data-btn="delete-question" data-id="<?=$question->id?>" title="borrar pregunta"><i class="fa fa-trash"></i></button>
							</div>
						</div>

						<!-- RESPONSES -->
						<h5 class="response-title text-gray-50">Respuestas:</h5>
						<?php foreach($question->responses as $response): ?>
						<div class="question-box response">
							<div class="thumb thumb-cover" style="background-image:url(<?=$response->client->imagery->logo?>)"></div>
							<div class="message">
								<a href="<?=ROOT.'centros/'.$response->client->permalink ?>" target="_blank"><?=$response->client->name?></a>
								<p><?=$response->message?></p>
								<small>Envidada: <?=$response->creado?> hs.</small>
							</div>
							<div class="actions">
								<button class="btn btn-xs btn-white text-pink-3" data-btn="delete-response" data-id="<?=$response->id?>" title="borrar respuesta"><i class="fa fa-trash"></i></button>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
					<?php endforeach; else: ?>

					<p class="alert alert-info">No se encontraron preguntas sin responder</p>
					<?php endif; ?>

				</div>


			</div>
		</div>


		<div class="block-white">
				
			<h3>Actividad reciente</h3>
			<hr>

			<?php if($_notifications): ?>

			<div class="activity-wrapper">

				<?php foreach($_notifications as $not): ?>

				<div class="mod-activity">
					<div class="content">
						<div class="date-wrapper">
							<div class="date"><?= date('d/m/Y',strtotime($not->added)) ?></div>
							<small><?= date('H:i:s',strtotime($not->added)) ?> hs.</small>
						</div>
						<div class="log-wrapper">
							<p class="log"><?=$not->log?></p>
						</div>
					</div>
				</div>
				<?php endforeach; ?>

			</div>

			<?php else: ?>
			<p class="alert alert-info">No se econtraron actividades recientes.</p>
			<?php endif; ?>

		</div>


		
	</div>
</section>


