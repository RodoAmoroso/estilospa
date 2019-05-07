
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
			<p>Al confirmar una reserva se le enviará un email de aviso al usuario con el link para poder abonar la promo (si está vinculada al sitio de MercadoPago)<br>Podés mover las reservas de día y horario. Un email de aviso llegará al usuario para confirmar el nuevo día y horario.</p>
			
			<hr>
			<div id="calendar"></div>

			<hr>

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