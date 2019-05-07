
<section class="page-header">
	<div class="container">
		<h1>Mis Ventas</h1>
		<hr>
		<p>En esta sección puedes visualizar la información de tu cuenta, información de tus promos vendidas y datos estadísticos de tus ventas.</p>
		<p>Es importante que al brindar el servicio de la promo a cada usuario cambies el estado de la misma. Para ello deberás buscar en el listado por el nro. de orden y establecer el estado 'brindado' o 'cancelado' desde el listado. El usuario podrá calificar la experiencia y podrás verla desplegando la solapa que contiene la información del usuario a través de la flecha <i class="fa fa-chevron-down"></i></p>

	</div>
</section>

<?php if($User->data()->idclient == null): ?>
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

		<div class="row">

			<!-- LEFT COLUMN -->
			<div class="col-xs-12 col-sm-4">

				<div class="block-box">
					<div class="box-title">Ventas de este Mes</div>
					<div class="box-content">
						<?php
						$Sales->period='thismonth';
						$Sales->getOverall();
						$thismonthquantity = $Sales->overall()->quantity;
						?>
						<h1 class="fw-400">$ <?= number_format($Sales->overall()->overall,2,',','.') ?></h1>
						<small class="cl-gray-40">Promos Vendidas: <?= $thismonthquantity ?></small>					
					</div>
					<div class="alert alert-success">
						<h4 class="fw-400">Comisiones de EstiloSPA: $ <?= number_format($Sales->overall()->neto,2,',','.') ?></h4>
						<small>Calculado en base a los porcentajes de comisión según el plan elegido.</small>
					</div>
					<div class="box-footer">
						<?php
						$Sales->period='lastmonth';
						$Sales->getOverall();
						$lastmonthquantity = $Sales->overall()->quantity;
						$difmonth = $thismonthquantity-$lastmonthquantity;
						?>
						<small>
							<span class="text-<?= $difmonth < 0 ? 'danger' : 'success' ?> fw-700 ff-opensans"><?= ($difmonth < 0 ? '-' : '+').abs($difmonth) ?> <i class="fa fa-level-<?= $difmonth < 0 ? 'down' : 'up' ?>"></i></span> con respecto al mes anterior
						</small>
					</div>
				</div>
				
				<div class="block-box">
					<div class="box-title">Total Vendido</div>
					<div class="box-content">
						<?php
						$Sales->period='';
						$Sales->getOverall(); 
						?>
						<h3 class="fw-400">$ <?= number_format($Sales->overall()->overall,2,',','.') ?></h3>
						<small class="cl-gray-40">Promos Totales Vendidas: <?= $Sales->overall()->quantity ?></small>
					</div>
					<div class="box-footer">
						<small class="cl-gray-40">Calculado desde el <?= date('d/m/Y', strtotime($User->data()->clientadded)) ?> hasta hoy</small>
					</div>
				</div>

				

				<div class="block-box">
					<div class="box-title">Datos de mi cuenta</div>
					<div class="box-content">
						<h3 class="fw-400">Tipo de Cuenta: <?= $User->data()->planname ?></h3>
						<h4>Promos disponibles: 1/<?= $User->data()->cantpromos ?></h4>
						<small>Comisión de venta online: <?= $User->data()->fee ?>%</small>
					</div>
					<div class="box-footer">
						<button id="btn_change_plan" class="btn btn-primary btn-sm btn-block">Deseo cambiar mi plan</button>
					</div>								
				</div>				

			</div>

			<!-- RIGHT COLUMN -->
			<div class="col-xs-12 col-sm-8">
				<div class="block-white ">

					<h4 class="fw-400">Búsqueda</h4>

					<form id="fd_search" class="row" autocomplete="off">					
						
						<div class="col-xs-12 col-sm-6">							
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
						<div class="col-xs-12 col-sm-6">
							<div class="form-group">
								<div class="input-group">
									<input id="fd_ordernumber" type="text" class="form-control" placeholder="nro. de orden o nro. de comprobante" >
									<div class="input-group-btn">
										<button class="btn btn-primary"><i class="fa fa-search"></i></button>
									</div>
								</div>
							</div>
						</div>
					</form>

					<hr>

					<h4>Listado de Promos Vendidas - Total: <span data-tag="totalmods" >0</span> </h4>

					<div id="sales" class="well mod-container-lg"></div>

				</div>
			</div>

		</div>	

	</div>
</section>



<?php include '../admin/views/templates.php' ?>

<?php endif; ?>