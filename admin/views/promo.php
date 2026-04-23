<!-- PROMOS -->
<section id="edit_panel" class="admin-box bg-gray-5">
	<div class="container">
		<h3 class="fw-600">Editar / Agregar Experiencia</h3>
		<hr>

		<div class="block-white">

			<div class="row">
				<div class="col-lg-4 mb-3">
					<label for="fd_title">Nombre/Título</label>
					<input id="fd_title" type="text" class="form-control" maxlength="80" data-toogle="charcount" >
				</div>
				<div class="col-lg-4 mb-3">
					<label for="fd_start">Inicia</label>
					<input id="fd_start" type="text" class="form-control clickable" readonly>
				</div>					
				<div class="col-lg-4 mb-3">
					<label for="fd_finish">Termina</label>
					<input id="fd_finish" type="text" class="form-control clickable" readonly>
				</div>
			</div>


			<div class="row">
				<div class="col-lg-4 mb-3">
					<label for="fd_subtitle">Subtítulo</label>
					<input id="fd_subtitle" type="text" class="form-control">
				</div>
				<div class="col-lg-4 mb-3">
					<label for="fd_label">Etiqueta</label>
					<input id="fd_label" type="text" class="form-control" placeholder="Ej: últimos disponibles!!!">
				</div>
				<div class="col-lg-4 mb-3">
					<label for="fd_label">Categorías</label>
					<?php if($categories): ?>
						<select id="fd_category" class="form-select" >
						<option value="">--Seleccionar Categoría--</option>
						<?php foreach($categories as $category): ?>
							<option value="<?=$category->id?>"><?=$category->name?></option>
						<?php endforeach; ?>
						</select>
					<?php endif; ?>
				</div>
			</div>

			<hr>



			<div class="row" >
				<div class="col-lg-3">
					<div class="mb-3">
						<label for="fd_price">Precio <i class="fa fa-question-circle" data-swal="Es el precio original sin el descuento" ></i></label>
						<input id="fd_price" type="number" class="form-control" value="0" min="0" >
					</div>
				</div>
				<div class="col-lg-3">
					<div class="mb-3">
						<label for="fd_promotypes">Tipo de Experiencia</label>
						<div class="input-group">
							<select id="fd_promotypes" class="form-select"></select>
							<button id="btn_edit_promotypes" class="btn btn-primary">
								<i class="fa fa-pencil" title="Editar listado"></i>
							</button>							
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="mb-3">
						<label for="fd_discount">Descuento <i class="fa fa-question-circle cl-pink-3" data-swal="Si el tipo de experiencia no corresponde a un descuento, dejar en 0"></i></label>
						<div class="input-group">
							<div class="input-group-text"><i class="fa fa-percent"></i></div>
							<input id="fd_discount" type="number" class="form-control" value="0" min="0">
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="mb-3">
						<label for="fd_amount">Cantidad Disponible <i class="fa fa-question-circle cl-pink-3" data-swal="Si queda en 0 no será visible en el sitio." ></i></label>
						<input id="fd_amount" type="number" class="form-control" value="20" min="0">
					</div>
				</div>
			</div>

			<hr>

			<?php if($mp_client): ?>
			<h5><span class="label label-success">Vinculado a MercadoPago</span></h5>
			<?php else: ?>
			<h5><span class="label label-danger">Sin vincular a MercadoPago</span></h5>
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

			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="mb-3">
						<label for="fd_clients">Centro</label>
						<select id="fd_clients" class="form-control" size="16" ></select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<label for="fd_clients">Disponible en: <i class="fa fa-question-circle cl-pink-3" data-swal="Click en cada item para seleccionar o deseleccionar dónde estará disponible la promoción"></i></label>
					<div id="stores" class="well mod-container-sm" style="height:320px"></div>
					<button id="btn_select_stores" data-collapse="false" class="btn btn-xs btn-white"><i class="fa fa-caret-up"></i> Seleccionar Todos</button>
				</div>
			</div>

			<hr>

			<div class="mb-3">
				<label for="fd_description">Descripción General *</label>
				<textarea id="fd_description" rows="8" class="form-control"></textarea>
			</div>
			<div class="mb-3">
				<label for="fd_valid">Validez *</label>
				<input id="fd_valid" type="text" class="form-control" placeholder="Todos los días...">
			</div>

			<div class="row">
				<div class="col-sm-12 col-lg-6">
					<div class="mb-3">
						<label for="fd_includes">¿Que incluye la experiencia?</label>
						<textarea id="fd_includes" rows="5" class="form-control"></textarea>
					</div>
				</div>
				<div class="col-sm-12 col-lg-6">
					<div class="mb-3">
						<label for="fd_recomendations">¿Que recomendamos que lleve?</label>
						<input id="fd_recomendations" type="text" class="form-control">
					</div>
					<div class="mb-3">
						<label for="fd_reservation">¿Requiere reserva y/o algún requisito?</label>
						<input id="fd_reservation" type="text" class="form-control" value="Si. Solicitar Previa Reserva de Turno">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="mb-3">
						<label for="fd_duration">Duración de la actividad</label>
						<input id="fd_duration" type="text" class="form-control">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="mb-3">
						<label for="fd_cancellation">¿Cuál es la política de cancelación?</label>
						<input id="fd_cancellation" type="text" class="form-control" value="24 hs antes del Turno Solicitado">
					</div>
				</div>
			</div>
			<p class="sz-9">Llenar solamente los que correspondan. La descripción general es obligatoria.</p>

			<!-- GIFT -->
			<hr>
			<h5>Mostrar en:</h5>
			<ul class="list-unstyled">
				<?php foreach($main_categories as $mc): ?>
				<li>
					<input type="checkbox" name="main_category" id="fd_main_category_<?=$mc->id?>" value="<?=$mc->id?>">
					<label for="fd_main_category_<?=$mc->id?>"><?=$mc->name?></label>
				</li>
				<?php endforeach; ?>
			</ul>
			<hr>

			<div class="mb-3">
				<label for="">Galería de Imágenes</label>
				<div data-input="gallery">
					<button id="btn_image" class="btn btn-primary btn-sm">Examinar...</button>
					<input type="file" accept="image/*" multiple class="d-none">
				</div>
			</div>

			<div id="gallery" class="p-3 bg-gray-5 border rounded admin-gallery mod-container-sm"></div>
			<div class="small-comment mt-2">&bullet; Puedes subir varias imágenes al mismo tiempo.<br />&bullet; Puedes subir 	hasta un total de 10 imágenes.<br />&bullet; La primer imagen de la galería es la imagen principal de la experiencia.<br />&bullet; Puedes arrastrar y cambiar de lugar las imágenes.</div>



			<hr>
			<h5>Etiquetas</h5>

			<select name="glossary" class="form-control" multiple style="height:300px"></select>

		</div>

		<div class="block-white">
			<div class="row">
				<div class="col-lg-6">
					<button id="btn_cancel" class="btn btn-warning btn-sm">Cancelar</button>			
				</div>
				<div class="col-lg-6 text-end">
					<button id="btn_save" class="btn btn-success">Guardar Experiencia</button>
				</div>
			</div>
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
					<div class="mb-3">
						<label for="fd_promotype_name">Nombre</label>
						<input type="text" id="fd_promotype_name" class="form-control">
					</div>
					<div class="mb-3">
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

