
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>GiftCards</h1>
		<hr>

		<div class="mb-3">
			<button data-toggle="new" class="btn btn-sm btn-success">
				<i class="fal fa-plus"></i> Nuevo GiftCard
			</button>
		</div>

	</div>
</section>




<!-- LIST -->
<section data-toggle="listing" class="admin-box bg-gray-5">
	<div class="container">

		<div class="block-white">


			<table data-table="giftcards" class="table table-bordered table-striped small">
				<thead>
					<tr>
						<th></th>
						<th>Imagen</th>
						<th>Título</th>
						<th>Valor</th>
						<th>Visibilidad</th>
						<th>Vencimiento <i class="fa fa-info-circle fa-fw" data-swal="Cantidad de días a partir de la compra"></i></th>
						<th>Agregado/Modificado</th>
						<th class="text-end">Acciones</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>


	</div>
</section>




<!-- EDIT -->
<section data-toggle="edition" class="admin-box bg-gray-5 d-none">

	<form  data-form="main" class="container">

		<input type="hidden" name="id" value="0">

		<h4 class="fw-bold">Editar / Agregar GiftCard</h4>
		<hr>

		<div class="block-white">
			<div class="row">

				<div class="col-lg-6">

					<div class="mb-3 input-group">
						<div class="form-floating">
							<input name="title" type="text" class="form-control" placeholder="Título" required>
							<label for="">Título</label>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="mb-3 input-group">
								<div class="form-floating">
									<input name="expiration" type="number" class="form-control" placeholder="Vencimiento" required step="1" >
									<label for="">Vencimiento</label>
								</div>
								<span class="input-group-text">Días</span>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="mb-3 input-group">
								<span class="input-group-text">$</span>
								<div class="form-floating">
									<input name="value" type="number" class="form-control" placeholder="Valor" required step="0.1" >
									<label for="">Valor</label>
								</div>
							</div>
						</div>
					</div>

					<hr>

					<h5>Imagen</h5>

					<div data-input="image" class="mb-3">
						<button class="btn btn-xs btn-dark" type="button">
							<i class="fa fa-upload fa-fw"></i>
							<span>Examinar...</span>
						</button>
						<input type="file" accept="image/*" class="d-none">
					</div>
					<div id="image" class="border thumb-cover bg-gray-5 rounded shadow-sm">
						<img src="<?= View::assets('blank-rectangle.gif') ?>" alt="" class="w-100">
					</div>



					<hr>

					<div class="form-check form-switch">
						<input id="visible" type="checkbox" class="form-check-input" switch checked>
						<label for="visible" class="form-check-label">Visible</label>
					</div>
				</div>

				<div class="col-lg-6 mb-3 border-start">
					<label for="">Descripción</label>
					<textarea name="description" class="form-control" required placeholder="Descripción" style="height:280px;"></textarea>
				</div>

			</div>
		</div>

		<div class="block-white">
			<h5>Experiencias</h5>
			<hr>
			[incluídas]
			[excluídas]
		</div>

		<div class="block-white">

			<div class="row">
				<div class="col-lg-6">
					<button data-btn-action="cancel" type="button" class="btn btn-dark btn-sm">
						<i class="fal fa-times fa-fw"></i>
						<span>Cancelar</span>
					</button>
				</div>
				<div class="col-lg-6 text-end">
					<button class="btn btn-success">
						<i class="fa fa-save fa-fw"></i>
						<span>Guardar</span>
					</button>
				</div>
			</div>
		</div>


	</form>

</section>