<!-- PROMOS -->
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
				<div class="col-lg-4">
					<div class="form-group">
						<label for="fd_subtitle">Subtítulo</label>
						<input id="fd_subtitle" type="text" class="form-control">
					</div>
				</div>
				<div class="col-lg-4">
					<div class="form-group">
						<label for="fd_label">Etiqueta</label>
						<input id="fd_label" type="text" class="form-control" placeholder="Ej: últimos disponibles!!!">
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

			<hr>



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
						<div class="input-group">
							<select id="fd_promotypes" class="form-control"></select>
							<div class="input-group-btn">
								<button id="btn_edit_promotypes" class="btn btn-primary"><i class="fa fa-pencil" title="Editar listado"></i></button>
							</div>
						</div>
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
				<p><b>Importante: </b>Si el cliente no ha vinculado su cuenta de MercadoPago, los usuarios no podrán comprarla en el sitio. <br> </p>
				<?php endif; ?>

			</div>
			<hr>


			<hr>



			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_clients">Centro</label>
						<select id="fd_clients" class="form-control" size="16" ></select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<label for="fd_clients">Disponible en: <i class="fa fa-question-circle cl-pink-3" title="Click en cada item para seleccionar o deseleccionar dónde estará disponible la promoción"></i></label>
					<div id="stores" class="well mod-container-sm" style="height:320px"></div>
					<button id="btn_select_stores" data-collapse="false" class="btn btn-xs btn-white"><i class="fa fa-caret-up"></i> Seleccionar Todos</button>
				</div>
			</div>

			<hr>

			<div class="form-group">
				<label for="fd_description">Descripción General *</label>
				<textarea id="fd_description" rows="8" class="form-control"></textarea>
			</div>
			<div class="form-group">
				<label for="fd_valid">Validez *</label>
				<input id="fd_valid" type="text" class="form-control" placeholder="Todos los días...">
			</div>

			<div class="row">
				<div class="col-sm-12 col-lg-6">
					<div class="form-group">
						<label for="fd_includes">¿Que incluye la experiencia?</label>
						<textarea id="fd_includes" rows="5" class="form-control"></textarea>
					</div>
				</div>
				<div class="col-sm-12 col-lg-6">
					<div class="form-group">
						<label for="fd_recomendations">¿Que recomendamos que lleve?</label>
						<input id="fd_recomendations" type="text" class="form-control">
					</div>
					<div class="form-group">
						<label for="fd_reservation">¿Requiere reserva y/o algún requisito?</label>
						<input id="fd_reservation" type="text" class="form-control" value="Si. Solicitar Previa Reserva de Turno">
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
						<input id="fd_cancellation" type="text" class="form-control" value="24 hs antes del Turno Solicitado">
					</div>
				</div>
			</div>
			<p class="sz-9">Llenar solamente los que correspondan. La descripción general es obligatoria.</p>

			<!-- GIFT -->
			<hr>
			<div id="fd_gift" class="form-group clickable active" data-toogle="checkbox">
				<i class="fa fa-check-square"></i> <span>Mostrar en sección regalos</span>
			</div>
			<hr>

			<div class="form-group">
				<label for="">Galería de Imágenes</label>
				<div data-input="gallery">
					<button id="btn_image" class="btn btn-primary btn-sm">Examinar...</button>
					<input type="file" accept="image/*" multiple class="d-none">
				</div>
			</div>

			<div id="gallery" class="well admin-gallery mod-container-sm"></div>
			<small>&bullet; Puedes subir varias imágenes al mismo tiempo.<br />&bullet; Puedes subir hasta un total de 10 imágenes.<br />&bullet; La primer imagen de la galería es la imagen principal de la experiencia.<br />&bullet; Puedes arrastrar y cambiar de lugar las imágenes.</p>



			<hr>
			<h4>Etiquetas</h4>

			<select name="glossary" class="form-control" multiple style="height:300px"></select>

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

<!-- PROMO TYPES -->
<section id="promotypes_panel" class="admin-box bg-gray-5 dp-none">
	<div class="container">

		<h3 class="fw-600">Editar / Agregar Tipo de Experiencia</h3>
		<hr>
		<div class="block-white">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_promotype_name">Nombre</label>
						<input type="text" id="fd_promotype_name" class="form-control">
					</div>
					<div class="form-group">
						<button id="btn_save_promotype" class="btn btn-success btn-sm pull-right">Guardar</button>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<label for="">Listado de Tipos de Experiencias</label>
					<div id="mod_promotype" class="mod-container-sm well"></div>
				</div>
			</div>

			<hr>
			<button id="btn_close_promotype" class="btn btn-warning">Cerrar</button>

	</div>
</section>


<?php include 'templates.php' ?>

<script>var $_id = '<?=intval($_subsection)?>';</script>

