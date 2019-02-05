
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>Promos</h1>
	</div>
</section>


<?php if($_USER->data()->idclient == null): ?>
<!-- RESTRICT -->
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
var IDClient = <?= $_USER->data()->idclient ?>;
</script>


<!-- LIST -->
<section id="list_panel" class="admin-box">
	<div class="container">

	
		<div class="row">
			<div class="col-xs-4">
				<label for="fd_select_status">Filtrar por status</label>
				<select id="fd_select_status" class="form-control input-sm">
					<option value="">-- Todas --</option>
					<option value="0:0">No iniciada</option>
					<option value="1:1">En curso</option>
					<option value="1:0">Finalizadas</option>
				</select>
			</div>
			<div class="col-xs-12 col-sm-4">
				<div class="form-group">
					<label for="fd_select_order">Ordernar</label>
					<select id="fd_select_order" class="form-control input-sm">
						<option value="added">Ordenar por fecha de creación (desc.)</option>
						<option value="title">Ordenar por orden alfabético (a-z)</option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 text-right">
				<div class="form-group">
					<label for="" class="dp-block" >&nbsp;</label>
					<button id="btn_new" class="btn btn-success"><i class="fa fa-plus"></i> Agregar Promo</button>
				</div>
			</div>
		</div>


		<button data-group="views" data-toggle="large" class="btn btn-sm btn-default active"><i class="fa fa-th-large"></i></button>
		<button data-group="views" data-toggle="list" class="btn btn-sm btn-default"><i class="fa fa-th-list"></i></button>	
		<hr>

		<div class="well "><div id="promos" class="row"></div></div>

	</div>
</section>


<!-- EDIT -->
<section id="edit_panel" class="admin-box bg-gray-5 dp-none">
	<div class="container">
		<h3 class="fw-600">Editar / Agregar Promo</h3>
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

			<div class="form-group">
				<label for="fd_subtitle">Subtítulo <span>(opcional)</span></label>
				<input id="fd_subtitle" type="text" class="form-control">
			</div>

			<div class="form-group">
				<label for="fd_clients">Disponible en: <i class="fa fa-question-circle cl-pink-3" title="Click en cada item para seleccionar o deseleccionar dónde estará disponible la promoción"></i></label>
				<div id="stores" class="well mod-container-sm"></div>
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

			<!-- ONLINE SALE -->
			<?php
			$MPConfig = new MPConfig();
			if($MPConfig->find($_USER->data()->idclient)):
			?>
			<hr>
			<div id="fd_sale" class="form-group clickable active" data-toogle="checkbox">
				<i class="fa fa-check-square"></i> <span>Venta Online</span>
			</div>
			
			<div id="sale_box" class="row" >
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_price">Precio <i class="fa fa-question-circle cl-pink-3" title="Es el precio original sin el descuento"></i></label>
						<input id="fd_price" type="number" class="form-control" value="0" min="0" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_promotypes">Tipo de Promo</label>						
						<select id="fd_promotypes" class="form-control"></select>						
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_discount">Descuento <i class="fa fa-question-circle cl-pink-3" title="Si el tipo de promo no corresponde a un descuento, dejar en 0"></i></label>
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
			
			<?php else: ?>

			<hr>
			<div class="form-group clickable inactive" data-toogle="checkbox">
				<i class="fa fa-square"></i> <span>Venta Online</span>
			</div>
			<div class="alert alert-danger sz-12">
				<h4 class="fw-600">Importante:</h4>
				<p >Para poder habilitar la opción de venta online, debes vincular tu cuenta de MercadoPago desde la sección <a href="<?= ROOTPATH.'cuenta/mp' ?>">Vincular con Mercado Pago</a> del menú principal del administrador.</p>
			</div>
			<?php endif; ?>
			<hr>


			<label for="">Galería de Imágenes</label>
			<div class="form-group">
				<button id="btn_image" class="btn btn-primary btn-sm">Examinar...</button>
			</div>
			<form id="form_image" class="dp-none">
				<input type="file" accept="image/*" multiple>
			</form>
			<div id="gallery" class="well admin-gallery mod-container-sm"></div>
			<p class="sz-9">&bullet; Puedes subir varias imágenes al mismo tiempo.<br />&bullet; Puedes subir hasta un total de 10 imágenes.<br />&bullet; La primer imagen de la galería es la imagen principal de la promo.<br />&bullet; Puedes arrastrar y cambiar de lugar las imágenes.</p>

		</div>

		<div class="block-white">
			<button id="btn_save" class="btn btn-success pull-right">Guardar Promo</button>
			<button id="btn_cancel" class="btn btn-warning btn-sm">Cancelar</button>
			<button id="btn_delete" class="btn btn-danger btn-sm">Borrar</button>
		</div>


	</div>
</section>

<?php include '../admin/views/templates.php' ?>

<?php endif; ?>