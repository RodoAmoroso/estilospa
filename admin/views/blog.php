
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>Blog / Noticias</h1>

	</div>
</section>



<!-- LIST -->
<section id="list_panel" class="admin-box">
	<div class="container">

		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<label for="fd_search">Buscar</label>

				<form id="form_search" class="form-group" autocomplete="off">
					<div class="input-group">
						<input id="fd_search" type="text" class="form-control">
						<div class="input-group-btn">
							<button class="btn btn-primary"><i class="fa fa-search"></i></button>
						</div>
					</div>
					
				</form>


			</div>
			<div class="col-xs-12 col-sm-6 text-right">
				<div class="form-group">
					<label for="" class="dp-block" >&nbsp;</label>
					<button id="btn_new" class="btn btn-success"><i class="fa fa-plus"></i> Agregar Noticia</button>
				</div>
			</div>

			<div class="col-xs-4">
				<div class="form-group">
					<select id="select_categories" class="form-control input-sm"></select>
				</div>
			</div>
			<div class="col-xs-2">
				<button data-group="views" data-toggle="large" class="btn btn-sm btn-default active"><i class="fa fa-th-large"></i></button>
				<button data-group="views" data-toggle="list" class="btn btn-sm btn-default"><i class="fa fa-th-list"></i></button>
			</div>
			
		</div>

		
		<hr>

		<div id="blog" class="well mod-container-lg"></div>

	</div>
</section>

<!-- EDIT -->
<section id="edit_panel" class="admin-box bg-gray-5 dp-none">
	<div class="container">
		<h3 class="fw-600">Editar / Agregar Noticia</h3>
		<hr>

		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="#tab_general">General</a></li>
			<li role="presentation"><a href="#tab_glossary">Etiquetar</a></li>
			<li role="presentation"><a href="#tab_gallery">Galería</a></li>
		</ul>

		<div id="main_content" class="tab-content">

			<!-- GENERAL -->
			<div id="tab_general" class="tab-panel">

				<div class="row">
					<div class="col-xs-12 col-sm-8">
						<div class="form-group">
							<label for="fd_title">Título</label>
							<input id="fd_title" type="text" class="form-control">
						</div>
					</div>
					<div class="col-xs-12 col-sm-4">
						<div class="form-group">
							<label for="fd_date">Fecha</label>
							<input id="fd_date" type="text" class="form-control">
						</div>
					</div>
					<div class="col-xs-12 col-sm-8">
						<div class="form-group">
							<label for="fd_subtitle">Subtítulo</label>
							<input id="fd_subtitle" type="text" class="form-control">
						</div>
					</div>					
					<div class="col-xs-12 col-sm-4">
						<div class="form-group">
							<label for="fd_category">Categoría</label>
							<div class="input-group">
								<select id="fd_category" type="text" class="form-control"></select>
								<div class="input-group-btn">
									<button id="btn_category" class="btn btn-primary"><i class="fa fa-pencil"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="fd_shortdescription">Descripción Corta <i class="fa fa-question-circle cl-gray-60" title="Es el texto destacado de la noticia que se verá antes de entrar a la nota completa."></i></label>
					<textarea id="fd_shortdescription" rows="4" class="form-control" maxlength="500"></textarea>
					<p class="sz-9" style="margin-top:6px">Max. 500 caracteres. (Quedan: <shortchar>500</shortchar>)</p>					
				</div>
				
				<div class="form-group">
					<label for="fd_content">Contenido</label>
					<textarea id="fd_content" rows="6" class="form-control"></textarea>
				</div>


				<div data-input="image">
					<button class="btn btn-xs btn-fucsia">Insertar Imagen <i class="fa fa-caret-up fa-fw"></i></button>
					<input type="file" accept="image/*" class="d-none" >
				</div>

			</div>

			<!-- GLOSSARY -->
			<div id="tab_glossary" class="tab-panel">
				<h4 class="fw-600">Asociar Etiquetas</h4>
				<hr>
				<div class="buttons">
					<a href="<?= ADMIN.'etiquetas' ?>" class="btn btn-xs btn-primary" title="Editar Listado" target="_blank" ><i class="fa fa-pencil"></i> Editar</a>
					<button id="btn_refresh_glossary" class="btn btn-xs btn-success" title="Refrescar Listado" ><i class="fa fa-refresh"></i> Recargar</button> | 
					<button id="btn_collapse_glossary" data-collapse="false" class="btn btn-xs btn-white" ><i class="fa fa-caret-down"></i> Plegar/Desplegar Todos</button> 
					<button id="btn_check_glossary" data-check="true" class="btn btn-xs btn-white" ><i class="fa fa-check-square"></i> Marcar/Desmarcar Todos</button>
				</div>
				<hr>
				<div id="glossary" class="well mod-container-lg"></div>
			</div>

			<!-- GALLERY --> 
			<div id="tab_gallery" class="tab-panel">
				<h4 class="fw-600">Galería</h4>
				<hr>
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_video">Agregar Imagen</label><br />

							<div data-input="gallery">
								<button class="btn btn-xs btn-fucsia">Examinar...</button>
								<input type="file" accept="image/*" class="d-none" multiple >
							</div>

						</div>
						<small>Puedes subir varias imágenes al mismo tiempo (max. <?= MAXFILES ?>).<br />La primer imagen de la galería es la imagen principal de la nota.</small>
					</div>
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_video">Agregar Video</label>
							<div class="input-group">
								<input id="fd_video" type="text" class="form-control">
								<div class="input-group-btn">
									<button id="btn_add_video" class="btn btn-success"><i class="fa fa-plus"></i></button>
								</div>
							</div>
						</div>
						<p class="sz-9">Agregar sólamente la URL del video de Youtube. Es la que figura en la barra de direcciones. Por ej: https://www.youtube.com/watch?v=puvKF8gmXUM</p>
					</div>
				</div>
				<div id="gallery" class="well admin-gallery mod-container-md"></div>
				<p class="sz-9">Puedes arrastrar y cambiar de lugar las imágenes. </p>
			</div>

		</div>

		<div class="block-white">
			<button id="btn_save" class="btn btn-lg btn-success pull-right"><i class="fa fa-save"></i> Guardar & Cerrar</button>
			<button id="btn_cancel" class="btn btn-warning ">Cancelar</button>
			<button id="btn_delete" class="btn btn-danger ">Borrar</button>
		</div>


	</div>
</section>



<!-- EDIT CATEGORIES -->
<section id="edit_categories" class="admin-box bg-gray-5 dp-none">
	<div class="container">
		<h3 class="fw-600">Categorías</h3>
		<hr>
		<div class="block-white">
			<div class="row">
				
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_category_name">Nombre</label>
						<input id="fd_category_name" type="text" class="form-control">
					</div>
					<div class="form-group">
						<button id="btn_save_category" class="btn btn-sm btn-success">Agregar/Editar</button>
					</div>
				</div>

				<div class="col-xs-12 col-sm-6">
					<div id="list_categories_edit" class="well mod-container-sm"></div>
					<p class="sz-9">Puedes mover los items para cambiarlos de posición.</p>
				</div>
			</div>

			<hr>
			<button id="btn_close_category" class="btn btn-warning btn-sm">Cerrar</button>

		</div>
	</div>
</section>


<?php include 'templates.php' ?>
