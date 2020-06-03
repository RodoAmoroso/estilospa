
<section class="page-header">
	<div class="container">
		<h1>Preguntas</h1>
		<hr>
		<p>Visualiza todas las preguntas y sus respuestas formuladas en el sitio.</p>

		<a href="<?=ADMIN.'preguntas-usuarios' ?>" class="btn btn-default"><i class="fa fa-comments fa-fw"></i> Ver Preguntas x Usuario</a>
	</div>
</section>

<section class="admin-box bg-gray-5">
	<div class="container">


		<div class="block-white">
			<form class="row" method="post">
				<div class="col-xs-4">
					<label for="">Dónde</label>
					<select onchange="this.form.submit()" data-toggle="filter" name="where" class="form-control input-sm">
						<option value="">-- Todo el sitio --</option>
						<option value="clients" <?= $_where=='clients' ? 'selected' : '' ?> >En Centros</option>
						<option value="promos" <?= $_where=='promos' ? 'selected' : '' ?> >En Experiencias</option>
						<option value="glossary" <?= $_where=='glossary' ? 'selected' : '' ?> >En Etiquetas</option>
					</select>
				</div>
				<div class="col-xs-4">
					<label for="">Estado</label>
					<select onchange="this.form.submit()" data-toggle="filter" name="status" class="form-control input-sm">
						<option value="">-- Todas --</option>
						<option value="answered" <?= $_status=='answered' ? 'selected' : '' ?> >Respondidas</option>
						<option value="unanswered" <?= $_status=='unanswered' ? 'selected' : '' ?> >Sin Responder</option>
					</select>
				</div>
			</form>


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

						<?php if($question->type=='promos'): ?>
						a la experiencia <a href="<?= ROOT.'promo/'.$question->promo->permalink.'/'.$question->rowid ?>" class="text-fucsia-3" target="_blank"><?=$question->promo->title?></a>
						<?php endif; ?>

						<?php if($question->type=='clients'): ?>
						al centro <a href="<?= ROOT.'centros/'.$question->client->permalink ?>" class="text-fucsia-3" target="_blank"><?=$question->client->name?></a>
						<?php endif; ?>

						<?php if($question->type=='glossary'): ?>
						a la etiqueta <a href="<?= ROOT.'etiqueta/'.$question->rowid ?>" class="text-fucsia-3" target="_blank"><?=$question->glossary->name?></a>
						<?php endif; ?>


						</small>

					</div>
					<div class="buttons">
						<button class="btn btn-xs btn-white text-pink-3" data-btn="delete-question" data-id="<?=$question->id?>" title="borrar pregunta"><i class="fa fa-trash"></i></button>
					</div>
				</div>

				<?php if($question->responses): ?>
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
				<?php endforeach; endif; ?>
			</div>

			<?php endforeach; endif; ?>




		</div>



	</div>
</section>