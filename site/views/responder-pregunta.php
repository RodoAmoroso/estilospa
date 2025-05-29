<section class="gral-section">
	<div class="container">
		<h3>Responder Pregunta</h3>

		<?php if($_promo): ?>
		<h4>Pregunta para la siguiente promo: <a href="<?=View::url('promo',$_promo->permalink,$_promo->id.'-'.Permalink($_promo->title))?>" target="_blank"><?=$_promo->title?></a></h4>
		<?php endif; ?>

		<?php if($_glossary): ?>
		<h4>Pregunta sobre: <a href="<?=View::url('etiqueta',$_glossary->id.'-'.Permalink($_glossary->name))?>" target="_blank"><?=$_glossary->name?></a></h4>
		<?php endif; ?>

		<hr>

		<div class="alert alert-info">
			<h5>Pregunta:</h5>
			<p><?=$_question->message?></p>
			<small>Enviada por <?=$_user->name?> (<?=obfuscate_email($_user->mail)?>) el <?=$_question->creado?> hs.</small>
		</div>

		<?php if(!$response = $Questions->has_response($_question->id,$_userdata->id)): ?>
		<form id="form_response">
			<div class="mb-3">
				<textarea name="message" rows="6" class="form-control" required maxlength="500"></textarea>
				<small class="text-gray-50">(máx. 500 caracteres)</small>
			</div>
			<input type="hidden" name="messageid" value="<?=$_question->id?>">

			<div class="mb-3">
				<button class="btn btn-default">Responder</button>
			</div>
		</form>
		<?php else: ?>
		<div class="alert alert-success">
			<h5>Respuesta:</h5>
			<p><?=$response->message?></p>
			<small>Enviada el <?=$response->creado?> hs.</small>
		</div>

		<?php if(!$response->approved): ?>
		<div class="small text-danger"><i class="fa fa-exclamation-triangle fa-fw"></i> La respuesta pasará por un proceso de revisión y estará publicada en el sitio una vez que se apruebe.</div>
		<?php endif; ?>

		<?php endif; ?>



	</div>
</section>