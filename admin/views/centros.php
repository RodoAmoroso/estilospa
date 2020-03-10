
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>Centros</h1>
		<p></p>

	</div>
</section>


<!-- LIST -->
<section id="list_panel" class="admin-box">
	<div class="container">
		<div class="row">

			<div class="col-xs-12 col-sm-6">

				<form id="form_search" class="form-group" autocomplete="off">
					<label for="fd_search">Buscar</label>
					<div class="input-group">
						<input id="fd_search" type="text" class="form-control">
						<div class="input-group-btn">
							<button class="btn btn-primary"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</form>

				<div class="row">
					<div class="col-xs-12 col-sm-8">
						<div class="form-group">
							<select id="select_order" class="form-control input-sm">
								<option value="date">Ordenar por fecha de creación</option>
								<option value="name">Ordenar por orden alfabético</option>
							</select>
						</div>
					</div>
					<div class="col-xs-4">
						<button data-group="views" data-toggle="large" class="btn btn-sm btn-default active"><i class="fa fa-th-large"></i></button>
						<button data-group="views" data-toggle="list" class="btn btn-sm btn-default"><i class="fa fa-th-list"></i></button>
					</div>
				</div>


			</div>
			<div class="col-xs-12 col-sm-6 text-right">
				<div class="form-group">
					<label for="" class="dp-block" >&nbsp;</label>
					<button id="btn_new" class="btn btn-success"><i class="fa fa-plus"></i> Agregar Centro</button>
				</div>
			</div>

		</div>


		<hr>

		<div class="well"><div id="clients" class="row"></div></div>

	</div>
</section>


<!-- EDIT -->
<section id="edit_panel" class="admin-box bg-gray-5 dp-none">
	<div class="container">
		<h3 class="fw-600">Editar / Agregar Centro</h3>
		<hr>

		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="#tab_general">General</a></li>
			<li role="presentation"><a href="#tab_stores">Direcciones</a></li>
			<li role="presentation"><a href="#tab_categories">Categorías</a></li>
			<li role="presentation"><a href="#tab_gallery">Imágenes</a></li>
			<li role="presentation"><a href="#tab_features">Descripción/Características</a></li>
			<li role="presentation"><a href="#tab_socials">Redes Sociales</a></li>
		</ul>


		<div id="main_content" class="tab-content">

			<!-- GENERAL -->
			<div id="tab_general" class="tab-panel ">
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_name">Nombre</label>
							<input id="fd_name" type="text" class="form-control">
						</div>

						<div class="form-group">
							<label for="fd_web">Web</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="fa fa-link"></i></div>
								<input id="fd_web" type="text" class="form-control">
							</div>
						</div>

						<div class="form-group">
							<label class="dp-block" for="fd_plans">Tipo de Plan</label>
							<div class="input-group">
								<select id="fd_plans" class="form-control">
									<?php
									$plans = DB::getInstance()->get('clientplans',array('id','!=',0));
									if($plans->count()): foreach($plans->results() as $kp=>$vp):
									?>
									<option value="<?= $vp->id ?>"><?= $vp->name.' (Comisión '.$vp->fee.'%)' ?></option>
									<?php endforeach; endif; ?>
								</select>
								<div class="input-group-btn">
									<button id="btn_plans" class="btn btn-primary"><i class="fa fa-pencil"></i></button>
								</div>
							</div>
						</div>

					</div>
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_subtitle">Subtítulo/Copete/Epígrafe</label>
							<input id="fd_subtitle" type="text" class="form-control">
						</div>
						<div class="form-group">
							<label for="fd_mail">Mail</label>
							<input id="fd_mail" type="text" class="form-control">
						</div>
						<div class="form-group">
							<label for="fd_permalink">Enlace Permanente	<i class="fa fa-question-circle cl-pink-3" data-toggle="tooltip" title="" data-original-title="El enlace permanente permite identificar a una página de manera fácil y rápida. Se genera automáticamente a partir del título. Si decides editarlo no debe contener espacios, acentos o caracteres especiales para que funcione correctamente."></i></label>
							<div class="input-group">
								<div class="input-group-addon"><span id="root_root"><?= ROOT.'centro/' ?></span></div>
								<input type="text" class="form-control" id="fd_permalink">
								<div class="input-group-btn">
									<button id="btn_preview" class="btn btn-success" title="Previsualizar centro" ><i class="fa fa-external-link-square"></i></button>
								</div>
							</div>
						</div>
					</div>

				</div>

				<hr>

				<i id="fd_visible" class="fa fa-toggle-on clickable"></i> <label for="fd_visible" class="clickable">Visible</label>

				<hr>
				<div class="row">


					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_users_search">Usuarios Asignados</label>

							<form id="form_search_users" class="form-group" autocomplete="off">
								<input id="fd_users_search" type="text" class="form-control" placeholder="Buscar usuarios...">
							</form>

							<div id="users" class="well mod-container-sm"></div>

							<p class="sz-8">Puedes asignar 1 o más usuarios registrados para editar la info de su propio negocio. Sólo aparecerán en la búsqueda los usuarios del tipo 'Cliente'</p>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="btn_logo">Logo</label>
							<div id="logo_client" class="thumb-contain thumb-200x200 border-gray-10"></div>
						</div>

						<div class="form-group">
							<div data-input="logo">
								<button class="btn btn-xs btn-primary">Examinar...</button>
								<input type="file" accept="image/*" class="d-none" >
							</div>
						</div>

					</div>

				</div>
			</div>


			<!-- ADDRESS -->
			<div id="tab_stores" class="tab-panel">
				<h4 class="fw-600">Direcciones / Sucursales</h4>
				<hr>

				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
									<label for="fd_store_address">Dirección y Nro. <i class="fa fa-question-circle cl-pink-3" title="Agregar sólo la calle y el número."></i></label>
									<input id="fd_store_address" type="text" class="form-control">
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<label for="fd_store_additional">Adicionales <i class="fa fa-question-circle cl-pink-3" title="Agregar si corresponde piso, local, altura, entre calles, etc."></i></label>
									<input id="fd_store_additional" type="text" class="form-control">
								</div>
							</div>

							<div class="col-xs-6">
								<div class="form-group">
									<label for="fd_store_city">Localidad/Barrio</label>
									<input id="fd_store_city" type="text" class="form-control">
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<label for="fd_store_province">Provincia/Zona</label>
									<select id="fd_store_province" class="form-control">
										<?php
										$province = DB::getInstance()->get('provinces',array('id','!=',0));
										if($province->count()):
											foreach($province->results() as $kp=>$vp):
										?>
										<option value="<?= $vp->id ?>"><?= $vp->name ?></option>
										<?php endforeach; endif; ?>
									</select>
								</div>
							</div>

							<div class="col-xs-6">
								<div class="form-group">
									<label for="fd_store_phones">Teléfonos <i class="fa fa-question-circle cl-pink-3" title="Agregar el código de área + el nro. de teléfono"></i></label>
									<input id="fd_store_phones" type="text" class="form-control">
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<label for="fd_store_whatsapp">WhatsApp <i class="fa fa-question-circle cl-pink-3" title="Agregar el código de área + el nro. de teléfono"></i></label>
									<div class="input-group">
										<div class="input-group-addon">54 9</div>
										<input id="fd_store_whatsapp" type="text" class="form-control">
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="">Código de inserción de google maps</label>
							<textarea id="fd_store_map" rows="5" class="form-control"></textarea>
							<small><a href="https://support.google.com/maps/answer/144361?co=GENIE.Platform%3DDesktop&hl=es" target="_blank">¿cómo hago?</a></small>
						</div>

						<div class="form-group">
							<label for="fd_store_schedules" class="dp-block">Horarios </label>
							<button id="btn_schedules" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Editar</button>
							<!-- <input id="fd_store_schedules" type="text" class="form-control"> -->

						</div>
						<div id="schedules" class="well mod-container-sm">
							<div class="list-group sz-9"></div>
						</div>

						<hr>

						<button id="btn_save_store" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Editar/Agregar</button>
						<button id="btn_cancel_store" class="btn btn-sm btn-warning">Cancelar</button>

					</div>

					<div class="col-xs-12 col-sm-6">

						<div id="stores" class="well mod-container-lg"></div>

					</div>

				</div>

				<!-- SCHEDULES -->
				<div id="pop_schedules" class="pop pop-schedules dp-none">
					<h1>Establecer Horarios</h1>
					<p class="sz-9">Selecciona el el día de la semana para establecer una franja horaria. Para establecer una franja horaria 'pinta' los recuadros con las horas haciendo click en ellos. Puedes establecer una franja horaria para varios días de una sola vez haciendo click en los botones 'Establecer este horario para Lunes a Viernes' o 'Establecer este horario para todos los días'</p>
					<hr>
					<div class="row-days">
						<?php foreach(Dates::$days as $kd=>$day): ?>
						<button data-day="<?= Dates::$shortdays[$kd] ?>" class="btn btn-white <?= $kd==0?'active':'' ?>"><?= Dates::translateDays($day).($day=='Sunday'?'/Feriado':'') ?></button>
						<?php endforeach; ?>
					</div>
					<div class="hours">
						<?php foreach(Dates::getHours() as $hour): ?>
						<button data-hour="<?= $hour ?>" class="btn btn-white btn-xs"><?= $hour ?></button>
						<?php endforeach; ?>
					</div>
					<button id="btn_schedules_lunvie" class="btn btn-success btn-sm">Establecer este horario para Lunes a Viernes <i class="fa fa-check fa-fw op-0"></i></button>
					<button id="btn_schedules_alldays" class="btn btn-success btn-sm">Establecer este horario para todos los días <i class="fa fa-check fa-fw op-0"></i></button>
					<hr>
					<p class="sz-11 alert alert-info">Horario Establecido: <span id="schedules_text">No se estableció ningún horario aún.</span></p>

					<hr>
					<div id="btn_close_schedules" class="text-right"><button class="btn btn-warning btn-sm">Cerrar</button></div>

				</div>

			</div>


			<!-- TYPES & GLOSSARY -->
			<div id="tab_categories" class="tab-panel">
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<h4 class="fw-600">Tipo de Centro</h4>
						<div class="buttons">
							<button id="btn_type" class="btn btn-xs btn-primary" title="Editar Listado" ><i class="fa fa-pencil"></i> Editar</button>
						</div>
						<hr>
						<div class="well mod-container-md">
							<div id="list_types" class="list-group">
								<button class="list-group-item"><i class="fa fa-square"></i> Category Name </button>
							</div>
						</div>
					</div>

					<div class="col-xs-12 col-sm-6">
						<h4 class="fw-600">Etiquetas</h4>
						<div class="buttons">
							<a href="<?= ADMIN.'etiquetas' ?>" class="btn btn-xs btn-primary" title="Editar Listado" target="_blank" ><i class="fa fa-pencil"></i> Editar</a>
							<button id="btn_refresh_glossary" class="btn btn-xs btn-success" title="Refrescar Listado" ><i class="fa fa-refresh"></i> Recargar</button> |
							<button id="btn_collapse_glossary" data-collapse="false" class="btn btn-xs btn-white" ><i class="fa fa-caret-down"></i> Plegar/Desplegar Todos</button>
							<button id="btn_check_glossary" data-check="true" class="btn btn-xs btn-white" ><i class="fa fa-check-square"></i> Marcar/Desmarcar Todos</button>
						</div>

						<hr>
						<div id="glossary" class="well mod-container-md"></div>


					</div>
				</div>
			</div>


			<!-- GALLERY -->
			<div id="tab_gallery" class="tab-panel">
				<h4 class="fw-600">Galería</h4>
				<hr>
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_video">Agregar Imagen</label>
							<div data-input="gallery">
								<button class="btn btn-xs btn-primary">Examinar...</button>
								<input type="file" accept="image/*" class="d-none" multiple>
							</div>
						</div>
						<p class="sz-9">Puedes subir varias imágenes al mismo tiempo (max. <?= MAXFILES ?>).<br />La primer imagen de la galería es la imagen principal del centro.</p>
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


				<div id="gallery" class="well admin-gallery"></div>
				<p class="sz-9">Puedes arrastrar y cambiar de lugar las imágenes.</p>

			</div>


			<!-- FEATURES -->
			<div id="tab_features" class="tab-panel">
				<h4 class="fw-600">Características</h4>
				<hr>
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_feature_name">Título</label>
							<input id="fd_feature_name" type="text" class="form-control">
						</div>
						<div class="form-group">
							<label for="fd_feature_description">Descripción</label>
							<textarea id="fd_feature_description" rows="8" class="form-control" ></textarea>
						</div>
						<hr>
						<button id="btn_save_feature" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Editar / Agregar</button>
						<button id="btn_cancel_feature" class="btn btn-warning btn-sm">Cancelar</button>
					</div>

					<div class="col-xs-12 col-sm-6">
						<div id="features" class="well mod-container-lg" ></div>
					</div>

				</div>
			</div>


			<!-- REDES SOCIALES -->
			<div id="tab_socials" class="tab-panel">

				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_social_type">Red Social</label>
							<select id="fd_social_type" class="form-control">
								<option value="facebook">Facebook</option>
								<option value="twitter">Twitter</option>
								<option value="instagram">Instagram</option>
								<option value="linkedin">Linkedin</option>
								<option value="pinterest">Pinterest</option>
								<option value="google-plus">Google+</option>
							</select>
						</div>
						<div class="form-group">
							<label for="fd_social_link">Link</label>
							<div class="input-group">
								<div class="input-group-addon"><i class="fa fa-link"></i></div>
								<input id="fd_social_link" type="text" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<button id="btn_social_save" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Editar/Agregar</button>
							<button id="btn_social_cancel" class="btn btn-sm btn-warning">Cancelar</button>
						</div>
					</div>

					<div class="col-xs-12 col-sm-6">
						<div id="socials" class="well"></div>
					</div>

				</div>
			</div>


		</div>


		<div class="block-white block-buttons">
			<div class="inactive-block" ></div>
			<button id="btn_save" class="btn btn-lg btn-success pull-right"><i class="fa fa-save"></i> Guardar & Cerrar</button>
			<button id="btn_cancel" class="btn btn-warning ">Cancelar</button>
			<button id="btn_delete" class="btn btn-danger ">Borrar</button>
		</div>


	</div>
</section>


<!-- TYPES -->
<section id="type_panel" class="admin-box bg-gray-5 dp-none">

	<div class="container">
		<h3 class="fw-600">Tipos de Centros</h3>
		<hr>

		<div class="block-white">
			<div class="row">

				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_type_name">Nombre</label>
						<input id="fd_type_name" type="text" class="form-control">
					</div>
					<div class="form-group">
						<button id="btn_save_type" class="btn btn-sm btn-success">Agregar/Editar</button>
					</div>
				</div>

				<div class="col-xs-12 col-sm-6">
					<div class="well mod-container-sm">

						<div id="list_types_edit" class="list-group"></div>
						<p class="sz-9">Puedes mover los items para cambiarlos de posición.<br />
						Recuerda que estas categorías conforman el menú principal del sitio.</p>
					</div>
				</div>

			</div>
			<hr>
			<button id="btn_close_types" class="btn btn-warning">Cerrar</button>
		</div>

	</div>

</section>


<!-- PLANS -->
<section id="plans_panel" class="admin-box bg-gray-5 dp-none">
	<div class="container">
		<h3 class="fw-600">Editar / Agregar Planes</h3>
		<hr>
		<div class="block-white">
			<div class="row">

				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_plan_name">Nombre</label>
						<input id="fd_plan_name" type="text" class="form-control">
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<div class="form-group">
								<label for="fd_plan_fee">Comisión <i class="fa fa-question-circle cl-pink-3" title="Sólo agregar el valor sin la comisión de Mercado Pago"></i></label>
								<div class="input-group">
									<input id="fd_plan_fee" type="number" min="0" max="100" value="0" class="form-control">
									<div class="input-group-addon"><i class="fa fa-percent"></i></div>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group">
								<label for="fd_plan_promos">Cant. de promos disponibles <i class="fa fa-question-circle cl-pink-3" title="Sólo aplica a las promos de venta online"></i></label>
								<input id="fd_plan_promos" type="number" min="0" max="1000" value="0" class="form-control">
							</div>
						</div>
					</div>

					<div class="form-group">
						<button id="btn_save_plan" class="btn btn-sm btn-success">Agregar/Editar</button>
					</div>
				</div>

				<div class="col-xs-12 col-sm-6">
					<div class="well mod-container-sm">
						<div id="list_plans_edit" class="list-group"></div>
					</div>
				</div>

			</div>
			<hr>
			<button id="btn_close_plans" class="btn btn-warning">Cerrar</button>
		</div>
	</div>
</section>


<?php include 'templates.php' ?>
