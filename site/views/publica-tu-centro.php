<section class="gral-section">
	<div class="container">
		<h2>Publicá tu Centro</h2>
		<hr>
		<p>Completá el formulario y en breve te responderemos.</p>
		<p>&nbsp;</p>
		<form id="form_publish" class="row">

			<div class="col-xs-12 col-sm-4">
				<h3>EstilosSpa.com</h3>
				<p><i class="fa fa-phone"></i> <a href="tel:011 4643 0556">011 4643 0556</a></p>
				<p><i class="fa fa-envelope"></i> <a href="mailto:estilospa.com@gmail.com">estilospa.com@gmail.com</a></p>
			</div>


			<div id="fields" class="col-xs-12 col-sm-6">
				<div class="form-group">
					<label for="fd_name">Nombre y Apellido</label>
					<input name="fullname" type="text" id="fd_name" class="form-control" required></input>
				</div>
			
				<div class="form-group">
					<label for="fd_mail">Email</label>
					<input name="email" id="fd_mail" type="email" class="form-control" required></input>
				</div>

				<div class="form-group">
					<label for="fd_web">Web</label>
					<input name="web" id="fd_web" type="text" class="form-control" placeholder="https://..." ></input>
				</div>
			
				<div class="form-group">
					<label for="fd_phone">Teléfono (cód. área + nro.)</label>
					<input name="phone" id="fd_phone" type="text" class="form-control" required></input>
				</div>

				<div class="form-group">
					<label for="fd_company">Empresa</label>
					<input name="company" type="text" id="fd_company" class="form-control" required></input>
				</div>
			
				<div class="form-group">
					<label for="fd_how">¿Cómo nos conociste?</label>
					<select name="knowus" class="form-control" id="fd_how" required>
						<option value="">Seleccioná una opción...</option>
						<option value="Por buscadores">Por buscadores</option>
						<option value="Clarín Pyme">Clarín Pyme</option>
						<option value="Diario Clarín">Diario Clarín</option>
						<option value="Diario La Nación">Diario La Nación</option>
						<option value="Recomendación">Recomendación</option>
						<option value="Desde un Sitio Web">Desde un Sitio Web</option>
						<option value="Recepción de mail publicitario">Recepción de mail publicitario</option>
						<option value="Ya es cliente">Ya es cliente</option>
						<option value="Otro">Otro</option>
					</select>
				</div>

				<div class="form-group">
					<label for="fd_message">Mensaje</label>
					<textarea name="message" id="fd_message" class="form-control" rows="5" required ></textarea>
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-primary"><i class="fa fa-envelope"></i> Enviar</button>
				</div>
			</div>

		</form>

	</div>
</section>