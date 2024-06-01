<form data-form="edit-response">

	<h4>Editar Respuesta</h4>
	<input type="hidden" name="responseid" >

	<div class="form-group">
		<textarea name="response" rows="10" class="form-control" required maxlength="500"></textarea>
		<small class="text-gray-50">(máx. 500 caracteres)</small>
	</div>

	<div class="small text-muted">Cuando editás la respuesta se aprobará automáticamente.</div>
	<hr>

	<div class="form-group">
		<button class="btn btn-success"><i class="fa fa-save fa-fw"></i> Guardar</button>
	</div>
</form>