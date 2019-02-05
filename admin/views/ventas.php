<section class="page-header">
	<div class="container">
		<h1>Mi Cuenta</h1>
		<hr>
		<p>En esta sección puedes visualizar la información de las promos vendidas y datos estadísticos de las ventas en el sitio.</p>

	</div>
</section>


<section class="admin-box bg-gray-5">
	<div class="container">

		<div class="row">

			<!-- LEFT COLUMN -->
			<div class="col-xs-12 col-sm-4">

				<div class="block-box">
					<div class="box-title">Ventas de este Mes</div>
					<div class="box-content">
						<?php
						$_SALES->period='thismonth';
						$_SALES->getOverall();
						$thismonthquantity = $_SALES->overall()->quantity;
						?>
						<h1 class="fw-400">$ <?= number_format($_SALES->overall()->overall,2,',','.') ?></h1>
						<small class="cl-gray-40">Promos Vendidas: <?= $thismonthquantity ?></small>
						
					</div>
					<div class="alert alert-success">
						<h3 class="fw-400">Comisiones: $ <?= number_format($_SALES->overall()->neto,2,',','.') ?></h3>
						<small>Calculado en base a los porcentajes de comisión de cada centro según el plan elegido para cada uno.</small>
					</div>
					<div class="box-footer">
						<?php
						$_SALES->period='lastmonth';
						$_SALES->getOverall();
						$lastmonthquantity = $_SALES->overall()->quantity;
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
						$_SALES->period='';
						$_SALES->getOverall(); 
						?>
						<h3 class="fw-400">$ <?= number_format($_SALES->overall()->overall,2,',','.') ?></h3>
						<small class="cl-gray-40">Promos Totales Vendidas: <?= $_SALES->overall()->quantity ?></small>
					</div>
					<div class="alert alert-success">
						<h3 class="fw-400">Comisiones: $ <?= number_format($_SALES->overall()->neto,2,',','.') ?></h3>
						<small>Calculado en base a los porcentajes de comisión de cada centro según el plan elegido para cada uno.</small>
					</div>
					<div class="box-footer">
						<small class="cl-gray-40">Calculado desde el 01/08/2017 hasta hoy</small>
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
						<div class="col-xs-12">
							<h5 >Filtrar por centro</h5>
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
					</form>

					<hr>

					<h4>Listado de Promos Vendidas - Total: <span data-tag="totalmods" >0</span> </h4>

					<div id="sales" class="well mod-container-lg"></div>

				</div>
			</div>

		</div>	

	</div>
</section>


<?php include 'templates.php' ?>