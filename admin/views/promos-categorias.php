<section class="page-header">
	<div class="container">
		<h1>Categorías</h1>
		<p>Las categorías donde se podrán indexar las experiencias</p>
		<hr>

		<div class="mb-3">
			<a href="<?=ADMIN.'promos-categoria'?>" class="btn btn-fucsia"><i class="fa fa-plus"></i> Agregar Categoría</a>
		</div>

	</div>
</section>



<!-- LIST -->
<section id="list_panel" class="admin-box">
	<div class="container">

		<form class="row" method="POST">
			<div class="col-lg-3">
				<div class="mb-3" autocomplete="off">
					<div class="input-group input-group-sm">
						<input name="search" type="text" class="form-control" value="<?=Input::get('search')?>" placeholder="Buscar por nombre...">
						<button class="btn btn-primary"><i class="fa fa-search"></i></button>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<select name="visible" class="form-select form-select-sm" onchange="this.form.submit()">
					<option value="" >-- Visibles/Ocultos --</option>
					<option value="1" <?= Input::get('visible')==1 ? 'selected' : '' ?>>Visible</option>
					<option value="0" <?= Input::get('visible')==0 ? 'selected' : '' ?>>No Visibles</option>
				</select>
			</div>
			<div class="col-lg-3">
				<select name="main_caterory" class="form-select form-select-sm" onchange="this.form.submit()">
					<option value="" >-- Todas las Categorías --</option>
					<?php if($main_categories): foreach($main_categories as $m_category): ?>
					<option value="<?= $m_category->id ?>" <?= $m_category->id==Input::get('main_category') ? 'selected' : '' ?>><?= $m_category->name ?></option>
					<?php endforeach; endif; ?>
				</select>
			</div>

		</form>

		<hr>

		<div id="categories" class="well">
			<?php if($categories): ?>

			<div class="row">
				<?php foreach($categories as $category): ?>
				<div data-id="<?=$category->id?>" class="mod-card col-lg-4">
					<div class="card-inner">
						<div class="thumb thumb-cover thumb-fullx180" style="background-image:url(<?=$category->image->small?>)" >
							<div class="buttons">
								<button class="btn btn-xs btn-default preview">
									<i class="fa fa-eye"></i>
								</button>
								<button data-id="<?=$category->id?>" class="btn btn-xs btn-danger delete">
									<i class="fa fa-trash"></i>
								</button>
								<a href="<?= ADMIN.'promos-categoria/'.$category->id ?>" class="btn btn-xs btn-primary edit">
									<i class="fa fa-pencil"></i>
								</a>
							</div>
						</div>
						<div class="caption">
							<h1 class="title"><?=$category->name?></h1>
							<h5 class="subtitle"><?= $category->main_category_name ?></h5>

							<p>
								<?php if($category->visible): ?>
								<span class="badge text-bg-success">visible</span>
								<?php else: ?>
								<span class="badge text-bg-danger">oculto</span>
								<?php endif; ?>
							</p>
							<p class="description text-muted">Total Experiencias: <?=$category->total_promos?></p>
						</div>
					</div>
				</div>
				<?php endforeach; ?>

			</div>

			<?php else: ?>
			<h3>No se encontraron categorías :(</h3>
			<?php endif; ?>
		</div>


	</div>
</section>