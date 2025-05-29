<section class="page-header">
	<div class="container">
		<h1>Feriados</h1>
		<hr>
		<p>Definí los feriados nacionales. Al definir un feriado ese día queda excluído para reservas salvo que cada centro indique lo contrario.</p>
	</div>
</section>




<section class="admin-box bg-gray-5">
	<div class="container">

		<div class="block-white">

			<div class="row">
				<div class="col-md-4">
					<div class="mb-3">
						<label for="">Elegir año</label>
						<select name="year" class="form-control">
							<option value="<?=date('Y')?>"><?=date('Y')?></option>
							<option value="<?=date('Y')+1?>"><?=date('Y')+1?></option>
						</select>
					</div>
				</div>
			</div>


			<hr>


			<div id="holidays" class="table-responsive">
				<table id="table_holidays" class="table table-striped">
					<thead>
						<tr>
							<td>Fecha (yyyy-mm-dd)</td>
							<td>Feriado</td>
							<td class="text-center">Borrar</td>
						</tr>
					</thead>
					<tbody>
						<?php if($holidays): foreach($holidays as $holiday): ?>
						<tr>
							<td><?=$holiday->date?></td>
							<td><?=$holiday->name?></td>
							<td class="text-center">
								<button data-id="<?=$holiday->id?>" class="btn btn-danger btn-xs delete"><i class="fa fa-trash fa-fw"></i></button>
							</td>
						</tr>
						<?php endforeach; endif; ?>
					</tbody>
				</table>
			</div>


		</div>


		<div class="block-white">

			<h4>Agregar Feriado</h4>
			<small>Al definir un feriado nuevo ese día queda excluído para reservas en los centros y las experiencias, salvo que cada centro indique lo contrario.</small>
			<hr>

			<form id="form_holiday" class="row">
				<div class="col-md-4">
					<div class="mb-3">
						<input name="date" type="text" class="form-control" placeholder="Fecha" required readonly>
					</div>
				</div>
				<div class="col-md-4">
					<div class="mb-3">
						<input name="name" type="text" class="form-control" placeholder="Nombre" required>
					</div>
				</div>
				<div class="col-md-4">
					<div class="mb-3">
						<button class="btn btn-success"><i class="fa fa-plus fa-fw"></i> Agregar Feriado</button>
					</div>
				</div>


			</form>

		</div>


	</div>

</section>