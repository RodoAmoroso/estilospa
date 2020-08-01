
<section class="page-header">
	<div class="container">
		<h1>Mis Ventas</h1>
		<hr>
		<p>En esta sección puedes visualizar la información de tu cuenta, información de tus experiencias vendidas y datos estadísticos de tus ventas.</p>
		<p>Es importante que al brindar el servicio de la experiencia a cada usuario cambies el estado de la misma. Para ello deberás buscar en el listado por el nro. de orden y establecer el estado 'brindado' o 'cancelado' desde el listado.</p>

	</div>
</section>


<?php if($_userdata->idclient == null): ?>
<!-- EDIT -->
<section class="admin-box bg-gray-5">
	<div class="container">
		<div class="block-white">
			<h4 class="">No hay ningún centro asociado a esta cuenta. Comunícate con nosotros para poder asociarte tu centro a esta cuenta.</h4>
			<hr>
			<a class="btn btn-primary" href="<?= ROOT.'contacto' ?>">Contacto</a>
		</div>
	</div>
</section>
<?php else: ?>



<!-- EDIT -->
<section class="admin-box bg-gray-5">
	<div class="container">


		<?php if($_userdata->idtype==3): ?>

		<div class="boxes">

			<div class="box-wrapper box-4">
				<div class="box">
					<div class="box-title">Ventas de este Mes</div>
					<div class="box-content alert-info">
						<?php
						$Sales->period='thismonth';
						$Sales->getOverall();
						$thismonthquantity = $Sales->overall()->quantity;
						?>
						<h2 class="fw-400">$ <?= number_format($Sales->overall()->overall-$Sales->overall()->neto-$Sales->overall()->mp_fee,2,',','.') ?></h2>
						<small>(Cantidad neta en ventas del mes)</small>
					</div>

					<div class="box-footer">
						<?php
						$Sales->period='lastmonth';
						$Sales->getOverall();
						$lastmonthquantity = $Sales->overall()->quantity;
						$difmonth = $thismonthquantity-$lastmonthquantity;
						?>
						<small class="cl-gray-40"><?= (int) $thismonthquantity ?> experiencias vendidas</small>
						<small>
							(<span class="text-<?= $difmonth < 0 ? 'danger' : 'success' ?> fw-700 ff-opensans"><?= ($difmonth < 0 ? '-' : '+').abs($difmonth) ?> <i class="fa fa-level-<?= $difmonth < 0 ? 'down' : 'up' ?>"></i></span> con respecto al mes anterior)
						</small>
					</div>

				</div>
			</div>

			<div class="box-wrapper box-4">
				<div class="box">
					<div class="box-title">Total Vendido</div>
					<?php
					$Sales->period='';
					$Sales->getOverall();
					?>
					<div class="box-content alert-info">
						<h2 class="fw-400">$ <?= number_format($Sales->overall()->overall-$Sales->overall()->neto-$Sales->overall()->mp_fee,2,',','.') ?></h2>
						<small>(Cantidad neta total en ventas)</small>
					</div>

					<div class="box-footer">
						<small class="cl-gray-40"><?= $Sales->overall()->quantity ?> experiencias vendidas en total (desde el <?= date('d/m/Y', strtotime($User->data()->clientadded)) ?> hasta hoy)</small>
					</div>
				</div>
			</div>

			<div class="box-wrapper box-4">
				<div class="box">
					<div class="box-title">Tipo de Cuenta</div>
					<div class="box-content alert-info">
						<h4 class="fw-400"><?= $User->data()->planname ?></h4>
						<h5>Experiencias disponibles: <?= $User->data()->cantpromos-$total_promos ?>/<?= $User->data()->cantpromos ?></h5>
					</div>
					<div class="box-content">
						<small>Comisión de venta online: <?= $User->data()->fee ?>%</small>
					</div>
					<div class="box-footer">
						<button id="btn_change_plan" class="btn btn-primary btn-sm">Deseo cambiar mi plan</button>
					</div>
				</div>
			</div>

			<div class="box-wrapper box-4">
				<div class="box">
					<div class="box-title">Evolución Mes a Mes</div>
					<div class="box-content">
						<div id="evolution" class="evolution-graph"></div>
					</div>
				</div>
			</div>



		</div>
		<?php endif; ?>


		<div class="block-white ">

			<h5 class="fw-400">Búsqueda</h5>

			<form id="fd_search" class="row" autocomplete="off">

				<div class="col-lg-6">
					<div class="form-group">
						<div class="row">
							<div class="col-xs-6">
								<input id="fd_from" type="text" class="form-control" placeholder="Desde" >
							</div>
							<div class="col-xs-6">
								<input id="fd_to" type="text" class="form-control" placeholder="Hasta">
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<div class="input-group">
							<input id="fd_ordernumber" type="text" class="form-control" placeholder="nro. de orden o nro. de comprobante" >
							<div class="input-group-btn">
								<button class="btn btn-primary"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</div>
				</div>


				<div class="col-lg-8">
					<div class="form-group">
						<label for="">Buscar por Nombre, apellido o email</label>
						<div class="input-group">
							<input id="fd_user" type="text" class="form-control" placeholder="" >
							<div class="input-group-btn">
								<button class="btn btn-primary"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="form-group">
						<label for="">Buscar por nro de voucher</label>
						<div class="input-group">
							<input id="fd_voucher" type="text" class="form-control" placeholder="" >
							<div class="input-group-btn">
								<button class="btn btn-primary"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</div>
				</div>

			</form>

			<small>Listado de las últimas 100 Experiencias Vendidas - Total: <span data-tag="totalmods" >0</span></small>
			<hr>

			<div id="sales"></div>

		</div>

	</div>
</section>



<?php include '../admin/views/templates.php' ?>

<?php endif; ?>