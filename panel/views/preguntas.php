
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
				<div class="col-xs-4">
					<label for="">Dónde</label>
					<select onchange="this.form.submit()" data-toggle="filter" name="where" class="form-control input-sm">
						<option value="">-- Todo el sitio --</option>
						<option value="clients" <?= $_where=='clients' ? 'selected' : '' ?> >En mi centro</option>						
						<option value="promos" <?= $_where=='promos' ? 'selected' : '' ?> >En mis promos</option>						
						<option value="glossary" <?= $_where=='glossary' ? 'selected' : '' ?> >En etiquetas</option>						
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
						<small data-content="added">Enviada por <?=$question->user_name.' ('.$question->user_email.')'?>: <?=$question->creado?> hs. 						
							
						<?php if($question->type=='promos'): ?>
						a la promo <a href="<?= ROOT.'promo/'.$question->permalink_promo.'/'.$question->rowid ?>" class="text-fucsia-3" target="_blank"><?=$question->promo->title?></a>
						<?php endif; ?>

						<?php if($question->type=='clients'): ?>
						al centro <a href="<?= ROOT.'centros/'.$question->permalink ?>" class="text-fucsia-3" target="_blank"><?=$question->client->name?></a>
						<?php endif; ?>

						<?php if($question->type=='glossary'): ?>
						a la etiqueta <a href="<?= ROOT.'etiqueta/'.$question->rowid ?>" class="text-fucsia-3" target="_blank"><?=$question->glossary->name?></a>
						<?php endif; ?>
						
						</small>

					</div>
					<div class="buttons">
						<?php if(!$question->responses): ?>
						<a href="<?=ROOT.'responder-pregunta/'.$question->id?>" class="btn btn-primary btn-xs" target="_blank">Responder <i class="fa fa-comments"></i></a>
						<?php endif ?>
					</div>
				</div>

				<?php if($question->responses): ?>
				<!-- RESPONSES -->				
				<h5 class="response-title text-gray-50">Respuestas:</h5>
				<?php foreach($question->responses as $response): if($response->client->id == $_userdata->idclient): ?>

				<div class="question-box response">
					<div class="thumb thumb-cover" style="background-image:url(<?=$response->client->imagery->logo?>)"></div>
					<div class="message">
						<a href="<?=ROOT.'centros/'.$response->client->permalink ?>" target="_blank"><?=$response->client->name?></a>
						<p><?=$response->message?></p>
						<small>Envidada: <?=$response->creado?> hs.</small>
					</div>
				</div>
				<?php endif; endforeach; endif; ?>
			</div>

			<?php endforeach; endif; ?>




		</div>



	</div>
</section>