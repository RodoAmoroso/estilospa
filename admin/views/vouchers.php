
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>VOUCHERS</h1>

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
							<input id="fd_search" type="text" class="form-control" placeholder="Buscar por código o nombre...">
							<div class="input-group-btn">
								<button class="btn btn-primary"><i class="fa fa-search"></i></button>
							</div>
						</div>
						
					</form>
				</div>

				<div class="col-xs-12 col-sm-6 text-right">
					<div class="form-group">
						<label for="" class="dp-block" >&nbsp;</label>
						<button id="btn_new" class="btn btn-success"><i class="fa fa-plus"></i> Agregar Voucher</button>
					</div>
				</div>
				
			</div>
			<hr>
			<div class="row">
				<div class="col-sm-4">
					<label for="fd_filter_status">Filtrar por status</label>
					<select id="fd_filter_status" class="form-control input-sm">
						<option value="">-- Todas --</option>
						<option value="0:0">No iniciada</option>
						<option value="1:1">En curso</option>
						<option value="1:0">Finalizadas</option>
					</select>
				</div>
				<div class="col-sm-4">
					<label for="fd_filter_client">Filtrar por cliente</label>
					<?php 
					$_CLIENTS = new Clients();
					$_CLIENTS->sort = 'name';
					if($_CLIENTS->get()):
					?>
					<select id="fd_filter_client" class="form-control input-sm">
						<option value="0">-- Todos --</option>
						<?php foreach($_CLIENTS->data() as $client): ?>
						<option value="<?= $client->id ?>"><?= $client->name ?></option>
						<?php endforeach; ?>
					</select>
					<?php endif; ?>
					
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label for="fd_filter_promo">Filtrar por promo</label>
						<select id="fd_filter_promo" class="form-control input-sm">
							<option value="0">-- Todas --</option>
						</select>
					</div>
				</div>
			</div>

			
			<hr>
			<h4>Listado de vouchers creados</h4>

			<div id="vouchers" class="well mod-container-lg"></div>

		</div>
	</div>
</section>

<!-- EDIT -->
<section id="block_edit" class="admin-box bg-gray-5 dp-none ">
	<div class="container">
		<div class="block-white">
			<h3 class="fw-600">Agregar / Editar Voucher</h3>
			<hr>

			<div class="form-group">

			<div class="btn-group" data-toggle="buttons">
				<label class="btn btn-default active">
					<input type="radio" name="group_code_type" value="unique" autocomplete="off" checked> Voucher de Código Único
				</label>
				<label class="btn btn-default">
					<input type="radio" name="group_code_type" value="multiple" autocomplete="off"> Voucher de Códigos Multiples
				</label>
			</div>
			</div>
			<p class="alert alert-info">
				<small>Los vouchers de código único pueden ser usados por varios usuarios (cada usuario registrado sólo podrá usarlo una vez) hasta la fecha de finalización. Los códigos de los vouchers múltiples sólo pueden ser usados una sola vez sin importar el usuario y son válidos hasta la fecha de finalización.</small>
			</p>

			
			<hr>
			<div class="row">
				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label for="fd_name">Nombre</label>
						<input id="fd_name" type="text" class="form-control">
					</div>
				</div>
				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label for="fd_type">Tipo de Descuento</label>
						<select id="fd_type" type="text" class="form-control">
							<option value="percent">Porcentaje</option>
							<option value="amount">Valor Fijo</option>
						</select>
					</div>
				</div>
				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label for="fd_value">Valor</label>
						<div  class="input-group">
							<div id="fd_value_symbol" class="input-group-addon">%</div>
							<input id="fd_value" type="number" class="form-control" value="0" min="0">
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-4">

					<!-- UNIQUE -->
					<div id="block_unique">
						<div class="form-group">
							<label for="fd_code">Código</label>
							<input id="fd_code" type="text" class="form-control">
						</div>
					</div>

					<!-- MULTIPLE -->
					<div id="block_multiple" class="dp-none">
						<div class="form-group">
							<label for="">Cantidad</label>
							<div class="input-group">
								<input id="fd_code_quantity" type="number" class="form-control" value="2" min="1" max="5000">
								<div class="input-group-btn">
									<button id="btn_generate" class="btn btn-primary"><i class="fa fa-refresh"></i> Generar</button>
								</div>
							</div>
						</div>
						
						<div id="code_list" class="well list-group mod-container-sm"></div>

						<form action="<?= ADMIN.'views/exportar-codigos.php' ?>" method="post" id="form_export_codes" class="form-group" target="_blank">
							<button class="btn btn-xs btn-success"><i class="fa fa-download fa-fw"></i> Exportar códigos</button>
							<input type="hidden" name="codes" value="[]" >
						</form>

						<p class="sz-9">Una vez establecido el/los códigos no podrán cambiarse al editar el voucher.</p>

					</div>

				</div>
				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label for="fd_start">Inicia</label>
						<input id="fd_start" type="text" class="form-control" readonly>
					</div>
				</div>
				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label for="fd_finish">Termina</label>
						<input id="fd_finish" type="text" class="form-control" readonly>
					</div>
				</div>
			</div>

			<hr>


			<h3>Asociar Voucher a Promos</h3>
			<p>Click en cada promo para agregarla en la lista de seleccionadas. Para remover cada promo, hacer click en la promo de la lista de seleccionadas</p>
			<div class="row">
				<div class="col-sm-6">
					<div class="row">
						

						<div class="col-sm-6">
							<div class="form-group">
								<label for="fd_filter_status_assoc">Filtrar por status</label>
								<select id="fd_filter_status_assoc" class="form-control input-sm">
									<option value="">-- Todas --</option>
									<option value="0:0">No iniciada</option>
									<option value="1:1">En curso</option>
									<option value="1:0">Finalizadas</option>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="fd_filter_client_assoc">Filtrar por cliente</label>
								<select id="fd_filter_client_assoc" class="form-control input-sm">
									<option value="0">-- Todos --</option>
									<?php
									if($_CLIENTS->get()):
										foreach($_CLIENTS->data() as $client):
									?>
									<option value="<?= $client->id ?>"><?= $client->name ?></option>
									<?php endforeach; endif; ?>
								</select>
							</div>
						</div>


					</div>
					<div id="promos" class="well mod-container-md list-group"></div>

					<button id="btn_check_all" data-check="true" class="btn btn-xs btn-white">Incluir Todas <i class="fa fa-arrow-right fa-fw"></i></button>
					<p>&nbsp;</p>

				</div>
				<div class="col-sm-6">
					<label>Promos Seleccionadas</label>
					<div id="promos_selected" class="well mod-container-md list-group"></div>

					<button id="btn_remove_all" data-check="true" class="btn btn-xs btn-white"><i class="fa fa-arrow-left fa-fw"></i> Quitar Todas</button>

				</div>
			</div>


		</div>

		<div class="block-white">
			<div class="row">
				<div class="col-sm-6">
					<button id="btn_cancel" class="btn btn-warning btn-sm"><i class="fa fa-fw fa-times"></i> Cancelar</button>
					<button id="btn_delete" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Borrar</button>
				</div>
				<div class="col-sm-6 text-right">
					<button id="btn_save" class="btn btn-success"><i class="fa fa-fw fa-save"></i> Guardar</button>
				</div>
			</div>
		</div>
	</div>
</section>