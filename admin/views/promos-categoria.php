<section class="page-header">
	<div class="container">
		<h1>Agregar Categoría</h1>
	</div>
</section>



<!-- PROMOS -->
<section id="edit_panel" class="admin-box bg-gray-5">
	<div class="container">
		<h3 class="fw-600">Editar / Agregar Categoría</h3>
		<hr>

		<form id="form_category" class="block-white">

			<input type="hidden" name="id" value="<?=intval($_subsection)?>">

			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<label for="">Nombre</label>
						<input name="name" type="text" class="form-control" maxlength="127" data-toogle="charcount" required value="<?= $category ? $category->name : '' ?>">
					</div>

					<div class="form-group">
						<label for="">Descripción</label>
						<textarea name="caption" rows="12" type="text" class="form-control" ><?= $category ? $category->caption : '' ?></textarea>
					</div>

					<div class="checkbox">
						<label>
							<input name="visible" type="checkbox" <?= $category && $category->visible ? 'checked' : '' ?>> <span>Visible</span>
						</label>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="form-group">
						<label for="">Imagen</label>

						<div data-input="image">
							<button type="button" class="btn btn-sm btn-primary">Examinar...</button>
							<input type="file" class="d-none" accept="image/*">
						</div>

					</div>

					<div id="thumb" class="thumbnail thumb-contain thumb-fullx280 bg-gray-5" data-filename="<?= $category ? $category->image->f : '' ?>" data-extension="<?= $category ? $category->image->f : '' ?>" style="background-image:url(<?= $category ? $category->image->small : ''; ?>)" >
						<img src="<?= ROOT ?>assets/blank-wide.gif" alt="" class="wd-100">
					</div>


				</div>

			</div>

			<hr>

			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<a href="<?=ADMIN.'promos-categorias'?>" class="btn btn-warning btn-sm"><i class="fa fa-times fa-fw"></i> Cancelar</a>
					</div>
				</div>
				<div class="col-lg-6 text-right">
					<div class="form-group">
						<button class="btn btn-success"><i class="fa fa-save fa-fw"></i> Guardar</button>
					</div>
				</div>
			</div>

		</form>

	</div>

</section>