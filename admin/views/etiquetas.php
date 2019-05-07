
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>Etiquetas</h1>
		<p></p>

	</div>
</section>


<!-- LIST -->
<section id="list_panel" class="admin-box bg-gray-5">
	<div class="container">
		<div class="block-white">
			<div class="row">
				<div class="col-xs-12 col-sm-6">

					<div class="form-group">
						<label for="fd_groups">Filtrar por Categorías</label>
						<div class="input-group">
						<select id="fd_groups_search" class="form-control"></select>					
						<div class="input-group-btn">
							<button id="btn_edit_groups_search" class="btn btn-primary" title="" data-original-title="editar"><i class="fa fa-pencil"></i></button>
						</div>
						</div>
					</div>

				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="form-group text-right">
						<button id="btn_new" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Nuevo Item</button>
					</div>
				</div>
			</div>		
			<hr>
			<div id="mod_glossary" class="well mod-container-lg"></div>

			<p class="sz-9">Puedes mover los items para cambiar de posición dentro de una misma categoría.</p>
			<hr>
			<p class="sz-9"><span id="select_all" class="clickable"><i class="fa fa-square"></i> Seleccionar todos</span> || <button id="btn_delete_selected" class="btn btn-xs btn-danger">Borrar Seleccionados</button></p>
		</div>
	</div>
</section>

<!-- EDIT -->
<section id="edit_panel" class="admin-box bg-gray-5 dp-none">
	<div class="container">
		<h3 class="fw-600">Editar / Agregar Item</h3>
		<hr>
		<div class="block-white">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_name">Nombre</label>
						<input id="fd_name" type="text" class="form-control">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_groups">Categoría</label>
						<div class="input-group">
						<select id="fd_groups" class="form-control">
							<option value="0">Cat 1</option>
							<option value="0">Cat 1</option>
							<option value="0">Cat 1</option>
							<option value="0">Cat 1</option>
						</select>
						<div class="input-group-btn">
							<button id="btn_edit_groups" class="btn btn-primary" title="editar"><i class="fa fa-pencil"></i></button>
						</div>
						</div>
					</div>
				</div>
			</div>

			<hr>
			
			<div class="form-group">
				<label class="dp-block" for="btn_header">Imagen Cabecera</label>
				<div data-input="header">
					<button class="btn btn-primary btn-sm">Examinar...</button>
					<input type="file" accept="image/*" class="d-none" >
				</div>		
			</div>
			<div id="header" class="thumbnail thumb-cover thumb-fullx280 bg-gray-10"></div>


			<hr>

			<div class="form-group">
				<label for="fd_description">Descripción</label>
				<textarea id="fd_description" rows="10" class="form-control"></textarea>
			</div>

			<hr>
			<div class="form-group">
				<div data-input="image">
					<button id="btn_image" class="btn btn-sm btn-primary"><i class="fa fa-chevron-up"></i> Agregar Imagen...</button>
					<input type="file" accept="image/*" class="d-none" >
				</div>
			</div>
		</div>

		<div class="block-white">
			<button id="btn_save" class="btn btn-success pull-right">GUARDAR</button>
			<button id="btn_cancel" class="btn btn-warning btn-sm">CANCELAR</button>
			<button id="btn_delete" class="btn btn-danger btn-sm">BORRAR</button>
		</div>


	</div>
</section>


<!-- GROUPS -->
<section id="groups_panel" class="admin-box bg-gray-5 dp-none">
	<div class="container">
		<h3 class="fw-600">Editar / Agregar Categorías del Glosario</h3>
		<hr>
		<div class="block-white">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_group_name">Nombre</label>
						<input type="text" id="fd_group_name" class="form-control">
					</div>
					<div class="form-group">
						<button id="btn_save_group" class="btn btn-success btn-sm">Guardar</button>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<label for="">Listado de Categorías</label>
					<div id="mod_groups" class="mod-container-sm well"></div>
					<p class="sz-9">Puedes mover los items para cambiarlos de posición.</p>
				</div>
			</div>

			<hr>
			<button id="btn_close_groups" class="btn btn-warning">Cerrar</button>
			
		</div>
	</div>
</section>


<?php include 'templates.php' ?>
