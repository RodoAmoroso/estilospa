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
							<th>Estado</th>
							<th></th>
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

							<?php if(!is_null($reservation->title)): ?>							
							<td>
								<a href="<?= ROOT.'promo/'.$reservation->permalink.'/'.$reservation->promoid.'-'.Permalink($reservation->title) ?>" target="_blank"><?=$reservation->title?></a>
							</td>
							<?php else: ?>
							<td>
								<small class="text-gray-40"><i>[no indicada]</i></small>
							</td>
							<?php endif; ?>

							<td>
								<a href="#" data-id="<?=$reservation->id?>" class="label bg-<?=$reservation->status_label?>"><?=$reservation->status_name?></a>								
							</td>
							<td>
								<?php if($reservation_sale = $Reservations->get_reservation_sale($reservation->id)): ?>
								<a href="<?=View::url('panel/venta',$reservation_sale->saleid)?>" target="_blank">Pagada</a>
								<?php endif; ?>
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

		<small>Toca el estado de cada reserva para más información</small>

	</div>

</section>



<!-- MODAL EVENT -->
<div id="modal_event" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" >
				<div class="close" data-dismiss="modal">&times;</div>
			</div>

			<div class="modal-body"></div>

		</div>
	</div>
</div>
