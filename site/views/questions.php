<div class="block-white">
	<h4 class="title-bar">Preguntas y Respuestas</h4>

	<form id="form_question" class="question-form">

		<small class="text-gray-50"><i>(No te preocupes, no publicaremos tus datos en el sitio)</i></small>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon">
							<span><i class="fa fa-user"></i></span>
						</div>
						<input name="name" type="text" class="form-control" <?=is_null($_userdata) ? '' : 'readonly' ?> required value="<?= !is_null($_userdata) ? $_userdata->fullname : '' ?>" placeholder="Tu nombre..." >
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon">
							<span><i class="fa fa-envelope"></i></span>
						</div>
						<input name="email" type="text" class="form-control" <?=is_null($_userdata) ? '' : 'readonly' ?> required value="<?=!is_null($_userdata) ? $_userdata->mail : '' ?>" placeholder="Tu email..." >
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<div class="input-group">
						<div class="input-group-addon">
							<span><i class="fa fa-phone"></i></span>
						</div>
						<input name="phone" type="text" class="form-control" required value="<?=!is_null($_userdata) ? $_userdata->phone : '' ?>" placeholder="Tu teléfono..."  >
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<textarea name="message" rows="6" class="form-control" required placeholder="Escribí tu pregunta..." maxlength="500"></textarea>
			<input type="hidden" name="rowid" value="<?=$question_rowid?>">
			<input type="hidden" name="type" value="<?=$question_type?>">
			<small class="text-gray-50">(máx. 500 caracteres)</small>
		</div>
		<div class="form-group">
			<button class="btn btn-default" >Preguntar</button>
		</div>
	</form>


	<?php if(isset($show_responses) && $show_responses): ?>
	<hr>
	<h5>Últimas preguntas:</h5>
	<div id="questions">
		<p>Cargando...</p>
	</div>
	<?php endif; ?>

</div>