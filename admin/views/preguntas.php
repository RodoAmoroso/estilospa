
<section class="page-header">
	<div class="container">
		<h1>Preguntas</h1>
		<hr>
		<p>Visualiza todas las preguntas y sus respuestas formuladas en el sitio.</p>

	</div>
</section>

<section class="admin-box bg-gray-5">
	<div class="container">

		<div class="block-white">
			<form class="row" method="post">
				<div class="col-sm-6 col-lg-3">
					<label for="">Dónde</label>
					<select onchange="this.form.submit()" data-toggle="filter" name="where" class="form-control input-sm">
						<option value="">-- Todo el sitio --</option>
						<option value="clients" <?= $_where=='clients' ? 'selected' : '' ?> >En Centros</option>
						<option value="promos" <?= $_where=='promos' ? 'selected' : '' ?> >En Experiencias</option>
						<option value="glossary" <?= $_where=='glossary' ? 'selected' : '' ?> >En Etiquetas</option>
					</select>
				</div>
				<div class="col-sm-6 col-lg-3">
					<label for="">Estado</label>
					<select onchange="this.form.submit()" data-toggle="filter" name="status" class="form-control input-sm">
						<option value="">-- Todas --</option>
						<option value="answered" <?= $_status=='answered' ? 'selected' : '' ?> >Respondidas</option>
						<option value="unanswered" <?= $_status=='unanswered' ? 'selected' : '' ?> >Sin Responder</option>
					</select>
				</div>
				<div class="col-sm-6 col-lg-3">
					<label for="">Desde</label>
					<div class="input-group input-group-sm">
						<input name="from_date" type="date" class="form-control" value="<?= Input::get('from_date') ?>">
						<div class="input-group-btn">
							<button class="btn btn-success"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<label for="">Hasta</label>
					<div class="input-group input-group-sm">
						<input name="to_date" type="date" class="form-control input-sm" value="<?= Input::get('to_date') ?>">
						<div class="input-group-btn">
							<button class="btn btn-success"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
			</form>

			<div class="small text-muted">Los resultados están limitados a 250 por cuestiones de performance</div>

			<hr>

			<?php if($questions): foreach($questions as $question): ?>

			<div class="questions-wrapper">
				<div class="question-box">
					<div class="icon">
						<i class="fa fa-user"></i>
					</div>
					<div class="message">
						<p data-content="message" class="caption"><?=$question->message?></p>
						<small data-content="added">Enviada por <?=$question->user_name.' (<a href="mailto:'.$question->user_email.'">'.$question->user_email.'</a> '.($question->user_phone ? ' | '.$question->user_phone : '').')'?>: <?=$question->creado?> hs.

						<?php if($question->type=='promos' && $question->promo): ?>
						a la experiencia <a href="<?= ROOT.'promo/'.$question->promo->permalink.'/'.$question->rowid ?>" class="text-fucsia-3" target="_blank"><?=$question->promo->title?></a>
						<?php endif; ?>

						<?php if($question->type=='clients' && $question->client): ?>
						al centro <a href="<?= ROOT.'centros/'.$question->client->permalink ?>" class="text-fucsia-3" target="_blank"><?=$question->client->name?></a>
						<?php endif; ?>

						<?php if($question->type=='glossary' && $question->glossary): ?>
						a la etiqueta <a href="<?= ROOT.'etiqueta/'.$question->rowid ?>" class="text-fucsia-3" target="_blank"><?=$question->glossary->name?></a>
						<?php endif; ?>


						</small>

					</div>
					<div class="actions">
						<button class="btn btn-danger btn-xs" data-btn="delete-question" data-id="<?=$question->id?>" title="borrar pregunta"><i class="fa fa-trash fa-fw"></i></button>
						<button class="btn btn-primary btn-xs" data-btn="reply-question" data-id="<?=$question->id?>" title="responder pregunta"><i class="fa fa-comments fa-fw"></i></button>
					</div>
				</div>

				<?php if($question->responses): ?>
				<!-- RESPONSES -->
				<h5 class="response-title text-gray-50">Respuestas:</h5>

				<?php foreach($question->responses as $response): ?>
				<div class="question-box response">

					<?php if($response->client): ?>
					<div class="thumb thumb-contain" style="background-image:url(<?=$response->client->imagery->logo?>)"></div>
					<?php else: ?>
					<div class="thumb thumb-contain" style="background-image:url(<?= View::assets('estilospa-logo-square.jpg'); ?>)"></div>
					<?php endif; ?>

					<div class="message">
						<?php if($response->client): ?>
						<a href="<?=ROOT.'centros/'.$response->client->permalink ?>" target="_blank"><?=$response->client->name?></a>
						<?php else: ?>
						<div>EstiloSpa</div>
						<?php endif; ?>

						<p><?=$response->message?></p>
						<div class="small">Enviada: <?=$response->creado?> hs.</div>

						<div>
							<?php if($response->approved): ?>
							<span class="label label-success" ><i class="fa fa-eye"></i> respuesta visible</span>
							<?php else: ?>
							<span class="label label-danger" ><i class="fa fa-eye-slash"></i> respuesta oculta</span>
							<?php endif; ?>
						</div>
					</div>
					<div class="actions">
						<button class="btn btn-xs btn-white text-pink-3" data-btn="delete-response" data-id="<?=$response->id?>" title="borrar respuesta"><i class="fa fa-trash"></i></button>
						<button class="btn btn-xs btn-white text-cyan-3" data-btn="edit-response" data-id="<?=$response->id?>" title="editar respuesta"><i class="fa fa-pencil"></i></button>

						<?php if(!$response->approved): ?>
						<button class="btn btn-xs btn-white text-aqua-4" data-btn="approve-response" data-id="<?=$response->id?>" title="Aprobar respuesta"><i class="fa fa-check"></i></button>
						<?php else: ?>
						<button class="btn btn-xs btn-white text-pink-3" data-btn="disapprove-response" data-id="<?=$response->id?>" title="Ocultar respuesta"><i class="fa fa-times"></i></button>
						<?php endif; ?>

					</div>
				</div>
				<?php endforeach; endif; ?>
			</div>

			<?php endforeach; endif; ?>




		</div>



	</div>
</section>