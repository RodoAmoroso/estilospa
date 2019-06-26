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
			<small>Enviada por <?=$_user->name?> (<?=$_user->mail?>) el <?=$_question->creado?> hs.</small>
		</div>

		<?php if(!$response = $Questions->has_response($_question->id,$_userdata->id)): ?>
		<form id="form_response">
			<div class="form-group">
				<textarea name="message" rows="6" class="form-control" required maxlength="500"></textarea>
				<small class="text-gray-50">(máx. 500 caracteres)</small>
			</div>
			<input type="hidden" name="messageid" value="<?=$_question->id?>">

			<div class="form-group">
				<button class="btn btn-default">Responder</button>
			</div>
		</form>
		<?php else: ?>
		<div class="alert alert-success">
			<h5>Respuesta:</h5>
			<p><?=$response->message?></p>
			<small>Enviada el <?=$response->creado?> hs.</small>
		</div>
		<?php endif; ?>



	</div>
</section>