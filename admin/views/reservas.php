<section class="page-header">
	<div class="container">
		<h1>Reservas</h1>
		<hr>
		<p>Visualiza todas las reservas hechas en las promos.</p>
	</div>
</section>




<section class="admin-box bg-gray-5">
	<div class="container">

		<div class="block-white">
			
			<div class="form-group">
				<button data-btn-action="" class="btn btn-sm btn-default">Reservas Anteriores</button>
				<button data-btn-action="" class="btn btn-sm btn-default active">Reservas Vigentes</button>
			</div>
			<hr>

			<form class="row" method="post">
				<div class="col-md-4">
					<label for="">Desde</label>
					<input name="date_from" type="text" class="form-control" readonly value="<?=empty(Input::get('date_from')) ? $today_formatted : Input::get('date_from') ?>">
				</div>
				<div class="col-md-4">
					<label for="">Hasta</label>
					<input name="date_to" type="text" class="form-control" readonly value="<?=empty(Input::get('date_to')) ? $next_month_formatted : Input::get('date_to') ?>">
				</div>
				<div class="col-md-4">
					<label for="">&nbsp;</label>
					<button class="btn btn-primary btn-block">Buscar <i class="fa fa-search"></i></button>
				</div>
			</form>

			<hr>


			<div class="table-responsive">
				<?php if($_reservations): ?>
				<table id="reservations" class="table table-striped table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Fecha</th>
							<th>Nombre</th>
							<th>Email</th>
							<th>Promo</th>
							<th>Centro</th>
							<th>Estado</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($_reservations as $k=>$reservation): $fecha = new DateTime($reservation->book_date) ?>						
						<tr>
							<td><?=$k+1?></td>
							<td>
								<div class="date"><?= $fecha->format('d').'-'.Dates::translateMonths($fecha->format('F')).'-'.$fecha->format('Y') ?></div>
								<small><?=$fecha->format('H:i')?> hs.</small>
							</td>
							<td><?=$reservation->user_name?></td>
							<td><a href="mailto:<?=$reservation->user_email?>" ><?=$reservation->user_email?></a></td>
							<td><a href="<?= ROOT.'promo/'.$reservation->permalink.'/'.$reservation->promoid.'-'.Permalink($reservation->title) ?>" target="_blank"><?=$reservation->title?></a></td>
							<td><a href="<?= ROOT.'centros/'.$reservation->permalink ?>" target="_blank"><?=$reservation->client_name?></a></td>
							<td>
								<span class="label bg-<?=$reservation->status_label?>"><?=$reservation->status_name?></span>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else: ?>
				<p>No se encontraron reservas :(</p>
				<?php endif; ?>
			</div>

		</div>

	</div>

</section>