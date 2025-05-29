<form data-form="voucher" class="text-center">

	<h4><b>Generar Voucher</b></h4>

	<input type="hidden" name="id">


	<div class="radio-group">
		<label class="radio-item">
			<input value="0" type="radio" name="gift" checked >
			<i class="fa fa-check-square-o fa-fw radio-icon"></i>
			<span class="radio-label">Para mí</span>
		</label>
		<label class="radio-item">
			<input value="1" type="radio" name="gift" >
			<i class="fa fa-square-o fa-fw radio-icon"></i>
			<span class="radio-label">Para regalar</span>
		</label>
	</div>


	<div id="fieldset_gift" class="dp-none">
		<div class="mb-3">
			<label for="">Para:</label>
			<input name="to_user" type="text" class="form-control" placeholder="para quien?">
		</div>
		<div class="mb-3">
			<label for="">Mensaje:</label>
			<textarea name="message" rows="4" class="form-control" placeholder="Tu mensaje..." maxlength="255"></textarea>
		</div>


		<div class="mb-3">
			<label for="">Imagen (Opcional)</label>

			<div data-input="image" class="mb-3">
				<button id="btn_image" class="btn btn-xs btn-default" type="button">Examinar...</button>
				<input type="file" accept="image/*" class="d-none" >
			</div>
			<div id="image" class="thumb-150x150 thumb-cover border-gray-10" style="margin:0 auto"></div>
		</div>

	</div>



	<hr>

	<div class="mb-3">
		<button class="btn btn-success btn-sm"><i class="fa fa-save fa-fw"></i> <span data-content="save-generate">Generar</span> Voucher</button>
	</div>

</form>