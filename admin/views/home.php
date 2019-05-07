
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
			<div class="col-xs-12 col-sm-6 text-right">
				<div class="form-group">
					<button id="btn_new_main" data-type="main" class="btn btn-success"><i class="fa fa-plus"></i> Agregar Banner</button>
				</div>
			</div>
		</div>		
		<p class="sz-9">Puedes arrastrar los banners para cambiarlos de posición</p>
		<hr>
		<div id="main" class="well mod-container-lg"></div>
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
					<div class="form-group">
						<label for="fd_name_main">Nombre* <i class="fa fa-question-circle" title="De uso interno. No será visible en el sitio." ></i></label>
						<input id="fd_name_main" type="text" class="form-control" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_title_main">Título (Opcional)</label>
						<input id="fd_title_main" type="text" class="form-control" >
					</div>
				</div>
			</div>	
			<div class="form-group">
				<label for="fd_caption_main">Descripción (Opcional)</label>
				<textarea id="fd_caption_main" rows="4" class="form-control"></textarea>
			</div>
			<div class="form-group">
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
			
			<div class="form-group">
				<label for="btn_image_main">Imagen</label>

				<div data-input="main">
					<button class="btn btn-sm btn-primary">Examinar...</button>
					<input type="file" class="d-none" accept="image/*">
				</div>

			</div>
			<p class="sz-9">Preferentemente imágenes de 960x960 píxeles.</p>
			<div id="th_image_main" class="thumbnail thumb-cover thumb-400x400 bg-gray-5">
				<img src="<?= ROOT ?>assets/blank-wide.gif" alt="" class="wd-100">
				<img alt="" class="th" >
			</div>

			<br />	

			<div class="col-sm-12 col-md-6">
				<label for="">Centrado Horizontal</label>
				<div id="slider_h_main" data-type="main" class="slider-h"></div>
			</div>
			<div class="col-sm-12 col-md-6">
				<label for="" >Centrado Vertical</label>
				<div id="slider_v_main" data-type="main" class="slider-v"></div>
			</div>
			<br />		

		</div>

		<div class="block-white">
			<button id="btn_save_main" data-type="main"  class="btn btn-lg btn-success pull-right"><i class="fa fa-save"></i> Guardar & Cerrar</button>
			<button id="btn_cancel_main" data-type="main"  class="btn btn-warning ">Cancelar</button>
			<button id="btn_delete_main" data-type="main"  class="btn btn-danger ">Borrar</button>
		</div>

	</div>
</section>

<hr>



<!-- LIST SIDE -->
<section id="list_side" class="admin-box">
	<div class="container">
		<div class="row">			
			<div class="col-xs-12 col-sm-6">
				<h3>Banner Laterales</h3>
			</div>
			<div class="col-xs-12 col-sm-6 text-right">
				<div class="form-group">
					<button id="btn_new_side" data-type="side" class="btn btn-success"><i class="fa fa-plus"></i> Agregar Banner</button>
				</div>
			</div>
		</div>		
		<p class="sz-9">Debes subir al menos 2 banners para que se visualice bien en el sitio. Si subes más de dos, sólo se verán dos por vez de manera aleatoria.</p>
		<hr>
		<div id="side" class="well mod-container-lg"></div>
	</div>
</section>
<!-- EDIT MAIN -->
<section id="edit_side" class="admin-box bg-gray-5 dp-none">
	<div class="container">
		<div class="block-white">
			<h3>Agregar / Editar Banner Lateral</h3>
			<hr>			
			<div class="form-group">
				<label for="fd_name_side">Nombre* <i class="fa fa-question-circle" title="De uso interno. No será visible en el sitio." ></i></label>
				<input id="fd_name_side" type="text" class="form-control" >
			</div>					
			
			<div class="form-group">
				<label for="fd_url_side">URL</label>
				<div class="input-group">
					<input id="fd_url_side" type="text" class="form-control">
					<div class="input-group-btn">
						<button id="btn_url_side_blank" data-toggle="btn-checkbox" class="btn btn-default"><i class="fa fa-square fa-fw"></i> <span class="sz-9">abrir en nueva ventana?</span></button>
					</div>
				</div>
			</div>

			<div id="fd_visible_side" data-toggle="switch" class="clickable fw-600"><i class="fa fa-toggle-on"></i> Visible: <span>Si</span></div>
			<hr>
			
			<div class="form-group">
				<label for="btn_image_side">Imagen</label>
				<div data-input="side">
					<button class="btn btn-sm btn-primary">Examinar...</button>
					<input type="file" class="d-none" accept="image/*">
				</div>
				
			</div>
			<p class="sz-9">Preferentemente imágenes de 768x328 píxeles.</p>
			<div id="th_image_side" class="thumbnail thumb-cover bg-black" style="max-width:634px">
				<img src="<?= View::assets('blank-wide.gif') ?>" alt="" class="wd-100">
			</div>
			<div class="row" style="max-width:634px">
				<div class="col-sm-12 col-md-6">
					<label for="">Centrado Horizontal</label>
					<div id="slider_h_side" data-type="side" class="slider-h"></div>
				</div>
				<div class="col-sm-12 col-md-6">
					<label for="" >Centrado Vertical</label>
					<div id="slider_v_side" data-type="side" class="slider-v"></div>
				</div>
			</div>
			<br />

		</div>

		<div class="block-white">
			<button id="btn_save_side" data-type="side" class="btn btn-lg btn-success pull-right"><i class="fa fa-save"></i> Guardar & Cerrar</button>
			<button id="btn_cancel_side" data-type="side" class="btn btn-warning ">Cancelar</button>
			<button id="btn_delete_side" data-type="side" class="btn btn-danger ">Borrar</button>
		</div>

	</div>
</section>


<?php include 'templates.php' ?>