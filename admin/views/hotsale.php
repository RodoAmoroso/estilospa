
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>HotSale / CyberMonday</h1>
		<p>Listado de usuarios registrados para el HotSale y CyberMonday</p>

	</div>
</section>

<!-- LIST -->
<section id="list_panel" class="admin-box bg-gray-5">
	<div class="container">


		<div class="block-white">

			<!-- <a href="<?= ADMIN.'hotsale-export' ?>" class="btn btn-success"><i class="fa fa-file-excel-o fa-fw"></i> Exportar a Excel</a>
			<hr>
			 -->
			<!-- <div class="row">
				<div class="col-lg-4">
					<label for="fd_search">Buscar</label>
					<form class="form-group" autocomplete="off" method="POST">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Buscar por nombre o email...">
							<div class="input-group-btn">
								<button class="btn btn-primary"><i class="fa fa-search"></i></button>
							</div>
						</div>

					</form>
				</div>
			</div> -->

			<table id="hotsale" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Email</th>
						<th>Nombre</th>
						<th style="text-align:right">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php if($hotsale): foreach($hotsale as $user): ?>
					<tr>
						<td><?=$user->email?></td>
						<td><?=$user->name?></td>
						<td align="right">
							<button data-id="<?=$user->id?>" data-action="delete" class="btn btn-danger btn-xs"><i class="fa fa-trash fa-fw"></i> Borrar</button>
						</td>
					</tr>
					<?php endforeach; endif; ?>
				</tbody>
			</table>

		</div>


	</div>
</section>