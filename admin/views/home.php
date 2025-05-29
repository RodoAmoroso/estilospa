
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>Home</h1>
		<hr>
		<p>Aquí se podrán establecer los banners del inicio del sitio.</p>

	</div>
</section>




<!-- LIST -->
<section id="list_main" class="admin-box">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<h3>Banner Principal</h3>
			</div>
			<div class="col-xs-12 col-sm-6 text-end">
				<div class="mb-3">
					<button id="btn_new_main" data-type="main" class="btn btn-success"><i class="fa fa-plus"></i> Agregar Banner</button>
				</div>
			</div>
		</div>
		<p class="sz-9">Puedes arrastrar los banners para cambiarlos de posición</p>
		<hr>
		<div id="main" class="row mod-container-lg"></div>
	</div>
</section>


<!-- EDIT MAIN -->
<section id="edit_main" class="admin-box bg-gray-5 dp-none">
	<div class="container">
		<div class="block-white">

			<h3>Agregar / Editar Banner Principal</h3>
			<hr>

			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="mb-3">
						<label for="fd_name_main">Nombre* <i class="fa fa-question-circle" title="De uso interno. No será visible en el sitio." ></i></label>
						<input id="fd_name_main" type="text" class="form-control" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="mb-3">
						<label for="fd_title_main">Título (Opcional)</label>
						<input id="fd_title_main" type="text" class="form-control" >
					</div>
				</div>
			</div>
			<div class="mb-3">
				<label for="fd_caption_main">Descripción (Opcional)</label>
				<textarea id="fd_caption_main" rows="4" class="form-control"></textarea>
			</div>
			<div class="mb-3">
				<label for="fd_url_main">URL</label>
				<div class="input-group">
					<input id="fd_url_main" type="text" class="form-control">
					<div class="input-group-btn">
						<button id="btn_url_main_blank" data-toggle="btn-checkbox" class="btn btn-default"><i class="fa fa-square fa-fw"></i> <span class="sz-9">abrir en nueva ventana?</span></button>
					</div>
				</div>
			</div>

			<div id="fd_visible_main" data-toggle="switch" class="clickable fw-600"><i class="fa fa-toggle-on"></i> Visible: <span>Si</span></div>
			<hr>

			<div class="mb-3">
				<label for="btn_image_main">Imagen</label>

				<div data-input="main">
					<button class="btn btn-sm btn-primary">Examinar...</button>
					<input type="file" class="d-none" accept="image/*">
				</div>

			</div>
			<p class="sz-9">Preferentemente imágenes de 1920x600 píxeles.</p>
			<div id="th_image_main" class="thumbnail thumb-cover thumb-fullx380 bg-gray-5"></div>


		</div>

		<div class="block-white">
			<div class="row">
				<div class="col-lg-6">
					<button id="btn_cancel_main" data-type="main" class="btn btn-warning ">Cancelar</button>
				</div>
				<div class="col-lg-6 text-end">
					<button id="btn_save_main" data-type="main"  class="btn btn-lg btn-success">
						<i class="fa fa-save"></i> Guardar & Cerrar
					</button>
				</div>
			</div>
		</div>

	</div>
</section>




<?php include 'templates.php' ?>