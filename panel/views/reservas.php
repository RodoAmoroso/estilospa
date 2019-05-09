
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>Mis Reservas</h1>
		<p>Visualizá tus reservas obtenidas desde las promos de tu centro.</p>
	</div>
</section>

<section class="gral-section-min bg-gray-5">
	<div class="container">

		<div class="calendar-wrapper">
			<h2>Calendario de reservas</h2>
			<p>Al confirmar una reserva se le enviará un email de aviso al usuario con el link para poder abonar la promo (si está vinculada al sitio de MercadoPago)<br>Podés arrastrar y mover las reservas de día y horario. Un email de aviso llegará al usuario para confirmar el nuevo día y horario.</p>

			<button href="#" data-toggle="modal" class="btn btn-danger btn-xs" data-target="#modal_blocked">Excluir día/horario</button>
			
			<hr>
			<div id="calendar"></div>

			<hr>

			<input name="clientid" type="hidden" value="<?=$_userdata->idclient?>">

			<h5>Referencias:</h5>
			<span class="label bg-green-3">Confirmada</span>
			<span class="label bg-yellow-3">A confirmar</span>
			<span class="label bg-aqua-3">Esperando confirmación del usuario</span>

		</div>


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

<!-- MODAL EVENT -->
<div id="modal_blocked" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" >
				<div class="close" data-dismiss="modal">&times;</div>
			</div>

			<div class="modal-body">

				<h4>Excluir horarios del calendario</h4>
				<p>Podés elegir qué día/horario excluir si ya tenés turnos tomados en tu centro para que los usuarios no puedan seleccionarlo en cada promo.</p>
				
				<div id="blocked_dates" class="calendar-promo">
				<div class="month">
					<div class="prev" data-action="prev"><i class="fa fa-angle-left"></i></div>
					<div class="name" ><span data-month="<?=date('m')?>"><?=Dates::translateMonths(date('M'))?></span> <span data-year="<?=date('Y')?>"><?=date('Y')?></span></div>
					<div class="next" data-action="next"><i class="fa fa-angle-right"></i></div>
				</div>

				<div class="week">
					<ul class="days">
						<li class="day prev" data-action="prev"><i class="fa fa-angle-left"></i></li>
						
						<?php foreach($_arrdays as $k=>$day): ?>
						<li class="day <?=!$k ? 'active' : ''?>" data-day="<?=$day['day']?>" data-dayname="<?=$day['dayname']?>" ><?=$day['name'].' '.$day['day']?></li>
						<?php endforeach; ?>

						<li class="day next" data-action="next"><i class="fa fa-angle-right"></i></li>
					</ul>
				</div>
				
				<div class="schedule">
					<ul class="hours"></ul>
				</div>
			</div>


			</div>

		</div>
	</div>
</div>