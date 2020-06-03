
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>Experiencias</h1>
	</div>
</section>


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
					<a href="<?=PANEL.'promo/nueva'?>" class="btn btn-success"><i class="fa fa-plus"></i> Agregar Experiencia</a>
				</div>
			</div>
		</div>


		<button data-group="views" data-toggle="large" class="btn btn-sm btn-default active"><i class="fa fa-th-large"></i></button>
		<button data-group="views" data-toggle="list" class="btn btn-sm btn-default"><i class="fa fa-th-list"></i></button>
		<hr>

		<div class="well "><div id="promos" class="row"></div></div>

	</div>
</section>


<?php include '../admin/views/templates.php' ?>

<?php endif; ?>