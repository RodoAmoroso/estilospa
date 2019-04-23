
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>SUBSCRIPTORES</h1>

	</div>
</section>


<!-- LIST -->
<section id="block_list" class="admin-box bg-gray-5">
	<div class="container">

		<div class="block-white">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<label for="fd_search">Buscar</label>

					<form id="form_search" class="form-group" autocomplete="off">
						<div class="input-group">
							<input id="fd_search" type="text" class="form-control" placeholder="Buscar por email...">
							<div class="input-group-btn">
								<button class="btn btn-primary"><i class="fa fa-search"></i></button>
							</div>
						</div>
						
					</form>
				</div>

				<div class="col-xs-12 col-sm-6 text-right">
					<div class="form-group" >
						<label for="" class="dp-block" >&nbsp;</label>
						<a href="<?= ADMIN.'views/exportar-subscriptores.php' ?>" target="_blank" class="btn btn-success btn-sm" type="submit"><i class="fa fa-file-excel-o fa-fw"></i> Exportar Listado</a>
					</div>
				</div>
				
			</div>
			
			<hr>
			<h4>Listado de subscriptores</h4>

			<div id="subscribers" class="well mod-container-lg"></div>

		</div>
	</div>
</section>