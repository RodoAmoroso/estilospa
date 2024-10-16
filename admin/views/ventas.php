<section class="page-header">
	<div class="container">
		<h1>Mi Cuenta</h1>
		<hr>
		<p>En esta sección puedes visualizar la información de las experiencia vendidas y datos estadísticos de las ventas en el sitio.</p>

	</div>
</section>


<section class="admin-box bg-gray-5">
	<div class="container">


		<div class="boxes">
			<div class="box-wrapper box-3">
				<div class="box">
					<div class="box-title">Ventas de este Mes</div>
					<div class="box-content alert-info">
						<?php
						$_SALES->period='thismonth';
						$_SALES->getOverall();
						$thismonthquantity = $_SALES->overall()->quantity;
						?>
						<h2 class="fw-400">$ <?= !$_SALES->overall()->neto ?? number_format($_SALES->overall()->neto,2,',','.') ?></h2>
						<small>(total en comisiones de venta)</small>
					</div>
					<div class="box-content">
						<h4 class="fw-400">$ <?= number_format($_SALES->overall()->overall??0,2,',','.') ?></h4>
						<small>Volumen Total de Venta</small>
					</div>
					<div class="box-footer">
						<?php
						$_SALES->period='lastmonth';
						$_SALES->getOverall();
						$lastmonthquantity = $_SALES->overall()->quantity;
						$difmonth = $thismonthquantity-$lastmonthquantity;
						?>
						<small class="cl-gray-40"><?= (int) $thismonthquantity ?> experiencia vendidas</small>
						<small>
							(<span class="text-<?= $difmonth < 0 ? 'danger' : 'success' ?> fw-700 ff-opensans"><?= ($difmonth < 0 ? '-' : '+').abs($difmonth) ?> <i class="fa fa-level-<?= $difmonth < 0 ? 'down' : 'up' ?>"></i></span> con respecto al mes anterior)
						</small>
					</div>

				</div>
			</div>

			<div class="box-wrapper box-3">
				<div class="box">
					<div class="box-title">Total Vendido</div>
					<?php
					$_SALES->period='';
					$_SALES->getOverall();
					?>
					<div class="box-content alert-info">
						<h2 class="fw-400">$ <?= number_format($_SALES->overall()->neto,2,',','.') ?></h2>
						<small>(total en comisiones de venta)</small>
					</div>
					<div class="box-content">
						<h4 class="fw-400">$ <?= number_format($_SALES->overall()->overall,2,',','.') ?></h4>
						<small>Volumen Total de Venta</small>
					</div>

					<div class="box-footer">
						<small class="cl-gray-40"><?= $_SALES->overall()->quantity ?> experiencias vendidas en total (desde el 01/08/2017 hasta hoy)</small>
					</div>
				</div>
			</div>

			<div class="box-wrapper box-3">
				<div class="box">
					<div class="box-title">Evolución Mes a Mes</div>
					<div class="box-content">
						<div id="evolution" class="evolution-graph"></div>
					</div>
				</div>
			</div>



		</div>




		<!-- VENTAS -->
		<div class="block-white ">

			<form id="fd_search" class="row" autocomplete="off">

				<div class="col-lg-4">
					<label for="">Desde / Hasta</label>
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
				<div class="col-lg-4">
					<div class="form-group">
						<label for="">Nro. de orden/comprobante</label>
						<div class="input-group">
							<input id="fd_ordernumber" type="text" class="form-control" placeholder="nro. de orden o nro. de comprobante" >
							<div class="input-group-btn">
								<button class="btn btn-primary"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<label>Filtrar por centro</label>
					<select id="fd_clients" class="form-control">
						<option value="0">-- Todos los centros --</option>
						<?php
						$clients = new Clients();
						$clients->sort = 'name';
						if($clients->get()){
							foreach ($clients->data() as $client){
								echo '<option value="'.$client->id.'">'.$client->name.'</option>';
							}
						}
						?>
					</select>
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
			<small>Listado de las últimas 100 Experiencias Vendidas</small>
			<hr>

			<div id="sales"></div>

		</div>

	</div>
</section>


<?php include 'templates.php' ?>