<section class="page-header">
	<div class="container">
		<h1>Actividad</h1>
		<hr>
		<p>Toda la actividad del sitio.</p>

	</div>
</section>



<section class="bg-gray-5 admin-box">
	<div class="container">
		
		<div class="block-white">
				

			<h3>Actividad reciente</h3>
			<form class="row" method="post">
				<div class="col-md-3">
					<select onchange="this.form.submit()" name="type" class="form-control">
						<option value="">-- Todas --</option>
						<option value="sale" <?= Input::get('type') == 'sale' ? 'selected' : '' ?>>Compras</option>
						<option value="reservation" <?= Input::get('type') == 'reservation' ? 'selected' : '' ?>>Reservas</option>
						<option value="question" <?= Input::get('type') == 'question' ? 'selected' : '' ?>>Preguntas realizadas</option>
						<option value="promo" <?= Input::get('type') == 'promo' ? 'selected' : '' ?>>Promos Cargadas</option>
						
						<option value="reminder" <?= Input::get('type') == 'reminder' ? 'selected' : '' ?>>Recordatorios de reservas</option>
						<option value="promo_status" <?= Input::get('type') == 'promo_status' ? 'selected' : '' ?>>Actualizar el estado de la compra</option>
						<option value="promo_rate" <?= Input::get('type') == 'promo_rate' ? 'selected' : '' ?>>Calificar la compra</option>

					</select>
				</div>
			</form>
			<hr>

			<?php if($_notifications): ?>

			<div class="activity-wrapper">

				<?php foreach($_notifications as $not): ?>

				<div class="mod-activity">
					<div class="content">
						<div class="date-wrapper">
							<div class="date"><?= date('d/m/Y',strtotime($not->added)) ?></div>
							<small><?= date('H:i:s',strtotime($not->added)) ?> hs.</small>
						</div>
						<div class="log-wrapper">
							<h5 class="title"><?=set_type($not->type)?></h5>
							<p class="log"><?=$not->log?></p>
						</div>
					</div>
				</div>
				<?php endforeach; ?>

			</div>

			<?php else: ?>
			<p class="alert alert-info">No se econtraron actividades recientes.</p>
			<?php endif; ?>

		</div>


	</div>
</section>