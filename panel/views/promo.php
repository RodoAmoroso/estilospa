<?php if($User->data()->idclient == null): ?>
<!-- RESTRICT -->
<section class="admin-box bg-gray-5">
	<div class="container">
		<div class="block-white">
			<h4 class="">No hay ningún centro asociado a esta cuenta. Comunicate con nosotros para poder asociarte tu centro a esta cuenta.</h4>
			<hr>
			<a class="btn btn-primary" href="<?= ROOT.'contacto' ?>">Contacto</a>
		</div>
	</div>
</section>
<?php else: ?>

<!-- EDIT -->
<section id="edit_panel" class="admin-box bg-gray-5">
	<div class="container">
		<h3 class="fw-600">Editar / Agregar Experiencia</h3>
		<hr>

		<div class="block-white">

			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_title">Nombre/Título</label>
						<input id="fd_title" type="text" class="form-control" maxlength="80" data-toogle="charcount" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="row">
						<div class="col-xs-6">
							<div class="form-group">
								<label for="fd_start">Inicia</label>
								<input id="fd_start" type="text" class="form-control clickable" readonly>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<label for="fd_finish">Termina</label>
								<input id="fd_finish" type="text" class="form-control clickable" readonly>
							</div>
						</div>
					</div>
				</div>
			</div>



			<div class="row">
				<div class="col-lg-8">
					<div class="form-group">
						<label for="fd_subtitle">Subtítulo</label>
						<input id="fd_subtitle" type="text" class="form-control">
					</div>
				</div>
				<div class="col-lg-4">
					<div class="form-group">
						<label for="fd_label">Categorías</label>
						<?php if($categories): ?>
						<select id="fd_category" class="form-control" >
							<option value="">--Seleccionar Categoría--</option>
							<?php foreach($categories as $category): ?>
								<option value="<?=$category->id?>"><?=$category->name?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="row" >
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_price">Precio <i class="fa fa-question-circle cl-pink-3" title="Es el precio original sin el descuento"></i></label>
						<input id="fd_price" type="number" class="form-control" value="0" min="0" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_promotypes">Tipo de Experiencia</label>
						<select id="fd_promotypes" class="form-control"></select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_discount">Descuento <i class="fa fa-question-circle cl-pink-3" title="Si el tipo de experiencia no corresponde a un descuento, dejar en 0"></i></label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-percent"></i></div>
							<input id="fd_discount" type="number" class="form-control" value="0" min="0">
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_amount">Cantidad Disponible <i class="fa fa-question-circle cl-pink-3" title="Si queda en 0 no será visible en el sitio." ></i></label>
						<input id="fd_amount" type="number" class="form-control" value="20" min="0">
					</div>
				</div>
			</div>


			<?php if($mp_client): ?>
			<h4><span class="label label-success">Vinculado a MercadoPago</span></h4>
			<?php else: ?>
			<h4><span class="label label-danger">Sin vincular a MercadoPago</span></h4>
			<?php endif; ?>

			<div class="alert alert-info">
				<div id="fd_sale" class="clickable active" data-toogle="checkbox">
					<i class="fa fa-check-square"></i> <span>Habilitar para la venta online</span>
				</div>
				<?php if(!$mp_client): ?>
				<hr>
				<p><b>Importante: </b>Para poder habilitar la opción de venta online, deberás vincular tu cuenta de MercadoPago desde la sección <a href="<?= ROOT.'panel/mp' ?>">Vincular con Mercado Pago</a> del menú principal del administrador.</p>
				<?php endif; ?>

			</div>




			<div class="form-group">
				<label for="fd_clients">Disponible en: <i class="fa fa-question-circle cl-pink-3" title="Click en cada item para seleccionar o deseleccionar dónde estará disponible la experiencia"></i></label>
				<div id="stores" class="well mod-container-sm" style="height:320px"></div>
				<button id="btn_select_stores" data-collapse="false" class="btn btn-xs btn-white"><i class="fa fa-caret-up"></i> Seleccionar Todos</button>
			</div>

			<hr>

			<div class="form-group">
				<label for="fd_description">Descripción General</label>
				<textarea id="fd_description" rows="8" class="form-control"></textarea>
			</div>

			<div class="row">
				<div class="col-sm-12 col-md-6">
					<div class="form-group">
						<label for="fd_includes">¿Que incluye la experiencia?</label>
						<textarea id="fd_includes" rows="5" class="form-control"></textarea>
					</div>
				</div>
				<div class="col-sm-12 col-md-6">
					<div class="form-group">
						<label for="fd_recomendations">¿Que recomendamos que lleve?</label>
						<input id="fd_recomendations" type="text" class="form-control">
					</div>
					<div class="form-group">
						<label for="fd_reservation">¿Requiere reserva y/o algún requisito?</label>
						<input id="fd_reservation" type="text" class="form-control"  value="Si. Solicitar Previa Reserva de Turno">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_duration">Duración de la actividad</label>
						<input id="fd_duration" type="text" class="form-control">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_cancellation">¿Cuál es la política de cancelación?</label>
						<input id="fd_cancellation" type="text" class="form-control"  value="24 hs antes del Turno Solicitado">
					</div>
				</div>
			</div>
			<p class="sz-9">Llenar solamente los que correspondan. La descripción general es obligatoria.</p>

			<hr>

			<!-- REGALOS -->
			<div id="fd_gift" class="form-group clickable active" data-toogle="checkbox">
				<i class="fa fa-check-square"></i> <span>Mostrar en la sección regalos</span>
			</div>
			<hr>



			<label for="">Galería de Imágenes</label>
			<div data-input="gallery" class="form-group">
				<button id="btn_image" class="btn btn-primary btn-sm">Examinar...</button>
				<input type="file" accept="image/*" multiple class="d-none">
			</div>

			<div id="gallery" class="well admin-gallery mod-container-sm"></div>
			<small>&bullet; Puedes subir varias imágenes al mismo tiempo.<br />&bullet; Puedes subir hasta un total de 10 imágenes.<br />&bullet; La primer imagen de la galería es la imagen principal de la experiencia.<br />&bullet; Puedes arrastrar y cambiar de lugar las imágenes.</small>

		</div>

		<div class="block-white">
			<button id="btn_save" class="btn btn-success pull-right">Guardar Experiencia</button>
			<button id="btn_cancel" class="btn btn-warning btn-sm">Cancelar</button>
			<?php if($_promodata): ?>
			<button id="btn_delete" class="btn btn-danger btn-sm">Borrar</button>
			<?php endif; ?>
		</div>


	</div>
</section>

<script>var $_id = '<?=intval($_subsection)?>';</script>

<?php include '../admin/views/templates.php' ?>
<?php endif; ?>