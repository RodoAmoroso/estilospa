<section class="gral-section">
	<div class="container">
		<h3>Responder Pregunta</h3>
		<h4>pregunta para la siguiente promo: <a href="<?=View::url('promo',$_promo->permalink,$_promo->id.'-'.Permalink($_promo->title))?>" target="_blank"><?=$_promo->title?></a></h4>
		<hr>

		<div class="alert alert-info">
			<h5>Pregunta:</h5>
			<p><?=$_question->message?></p>
			<small>Enviada por <?=$_user->name?> el <?=$_question->creado?> hs.</small>
		</div>

		<?php if(!$response = $Questions->has_response($_question->id,$User->data()->id)): ?>
		<form id="form_response">
			<div class="form-group">
				<textarea name="message" rows="6" class="form-control" required></textarea>
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