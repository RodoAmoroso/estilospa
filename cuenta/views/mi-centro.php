
<section class="page-header">
	<div class="container">
		<h1>Mi Centro</h1>
		<hr>
		<p>En esta sección puedes administrar la información de tu comercio.</p>		

	</div>
</section>


<?php if($_USER->data()->idclient == null): ?>
<!-- EDIT -->
<section class="admin-box bg-gray-5">
	<div class="container">
		<div class="block-white">
			<h4 class="">No hay ningún centro asociado a esta cuenta. Comunícate con nosotros para poder asociarte tu centro a esta cuenta.</h4>
			<hr>
			<a class="btn btn-primary" href="<?= ROOTPATH.'contacto' ?>">Contacto</a>
		</div>
	</div>
</section>
<?php else: ?>

<script>
var Clients = {};
Clients.ID = <?= $_USER->data()->idclient ?>;
</script>

<!-- EDIT -->
<section class="admin-box bg-gray-5">
	<div class="container">

		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="#tab_general">General</a></li>
			<li role="presentation"><a href="#tab_stores">Direcciones</a></li>
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
					</div>

				</div>

				<hr> 
				<div class="row">

					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="btn_logo">Logo</label>
							<div id="logo_client" class="thumb-contain thumb-300x300 bd-full-gray-10"></div>
						</div>
						<div class="form-group">
							<button id="btn_logo" class="btn btn-primary btn-sm">Subir Imagen</button>
							<form id="form_logo" action="#" method="POST" class="dp-none">
								<input type="file" accept="image/*">
							</form>
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


			<!-- GALLERY -->
			<div id="tab_gallery" class="tab-panel">
				<h4 class="fw-600">Galería</h4>
				<hr>
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_video">Agregar Imagen</label><br />
							<button id="btn_images" class="btn btn-primary btn-sm">Examinar...</button>
							<form id="form_images" class="dp-none">
								<input type="file" multiple accept="image/*">
							</form>
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
			<button id="btn_save" class="btn btn-lg btn-success pull-right"><i class="fa fa-save"></i> Guardar</button>
			<button id="btn_preview" class="btn btn-warning"><i class="fa fa-eye"></i> Preview</button>
		</div>

	</div>
</section>


<?php include '../admin/views/templates.php' ?>

<?php endif; ?>