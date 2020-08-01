
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>Usuarios</h1>
		<hr>

		<div class="form-group">
			<button id="btn_new" class="btn btn-sm btn-fucsia"><i class="fa fa-plus"></i> Nuevo Usuario</button>
		</div>

	</div>
</section>

<!-- LIST -->
<section id="list_panel" class="admin-box bg-gray-5">
	<div class="container">

		<div class="block-white">


			<table id="table_users" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Email</th>
						<th>Teléfono</th>
						<th>Usuario Desde</th>
						<th>Última Visita</th>
						<th>Rol</th>
						<th>Acciones</th>
					</tr>
				</thead>

				<tbody>
					<?php if($users): foreach($users as $user): ?>
					<tr>
						<td><?= $user->id ?></td>
						<td><?= $user->fullname ?></td>
						<td><?= $user->mail ?></td>
						<td><?= $user->phone ?></td>
						<td><?= $user->created ?></td>
						<td><?= $user->logged ?></td>
						<td><?= $user->type_name ?></td>
						<td class="text-right" style="width:160px">
							<button data-id="<?=$user->id?>" class="btn btn-xs btn-success edit"><i class="fa fa-pencil"></i> Editar</button>
							<a href="<?= ADMIN.'actividad-usuario/'.$user->id ?>" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-line-chart"></i> Actividad</a>
						</td>
					</tr>
					<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>


	</div>
</section>



<!-- EDIT -->
<section id="edit_panel" class="admin-box bg-gray-5 dp-none">
	<div class="container">
		<h3 class="fw-600">Editar / Agregar Usuario</h3>
		<hr>


		<div class="block-white">

			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_name">Nombre <sup class="fa fa-asterisk cl-fucsia-3 sz-6"></sup></label>
						<input id="fd_name" type="text" class="form-control" required >
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_lastname">Apellido</label>
						<input id="fd_lastname" type="text" class="form-control"  >
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_dni">DNI <i class="fa fa-question-circle cl-gray-30" title="" data-original-title="Necesario para participar de compras y promociones"></i></label>
						<input id="fd_dni" type="number" min="0" class="form-control" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_birth">Fecha de Nacimiento</label><br />


						<div class="row">

							<div class="col-xs-4" >
								<input type="text" id="fd_day" class="form-control" placeholder="dd" >
							</div>
							<div class="col-xs-4" >
								<input type="text" id="fd_month" class="form-control" placeholder="mm" >
							</div>
							<div class="col-xs-4" >
								<input type="text" id="fd_year" class="form-control" placeholder="yyyy" >
							</div>
							</div>

					</div>
				</div>

				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_phone">Teléfono (Prefijo + Nro.)</label>
						<input id="fd_phone" type="text" class="form-control" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_address">Dirección (Calle y Nro.)</label>
						<input id="fd_address" type="text" class="form-control" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_addressobs">Piso/Depto.</label>
						<input id="fd_addressobs" type="text" class="form-control" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_city">Ciudad/Localidad</label>
						<input id="fd_city" type="text" class="form-control" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_zip">Código Postal</label>
						<input id="fd_zip" type="text" class="form-control" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_provinces">Provincia/Zona</label>
						<select id="fd_provinces" class="form-control">
							<?php
							$provinces = DB::getInstance()->get('provinces',array('id','!=',0));
							if($provinces->count()):
								foreach($provinces->results() as $province):
							?>
							<option value="<?= $province->id ?>" ><?= $province->name ?></option>
							<?php
								endforeach;
							endif;
							?>
						</select>
					</div>
				</div>
			</div>
			<hr>
			<div id="fd_active" class="clickable"><i class="fa fa-toggle-on"></i> Activo</div>
			<hr>

			<div class="row">

				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_mail">E-mail <sup class="fa fa-asterisk cl-fucsia-3 sz-6"></sup></label>
						<input id="fd_mail" type="text" class="form-control" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_pass">Contraseña <sup class="fa fa-asterisk cl-fucsia-3 sz-6" title="Necesaria al crear el usuario" ></sup></label>
						<input id="fd_pass" type="text" class="form-control" >
					</div>
					<p class="sz-9">Si estás creando el usuario debes poner una contraseña. Si estás editando a un usuario ya creado y dejas el campo en blanco la contraseña no se cambiará.</p>
				</div>

			</div>

			<hr>

			<div class="row">

				<div class="col-xs-12 col-sm-6">
					<label for="">Imagen</label>
					<div class="form-group">
						<div data-input="image">
							<button id="btn_image" class="btn btn-sm btn-primary">Examinar...</button>
							<input type="file" accept="image/*" class="d-none">
						</div>
					</div>
					<div id="avatar" class="thumbnail bg-gray-10 thumb-cover thumb-200x200"></div>
				</div>

				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_types">Permitir editar centro:</label>
						<select id="fd_types" class="form-control">
							<?php
							$db = DB::getInstance();
							if($db->get('usertypes',array('id','!=',0))){
								foreach($db->results() as $type){
									echo '<option value="'.$type->id.'">'.$type->name.'</option>';
								}
							}
							?>
						</select>
					</div>
					<p class="sz-9">Al Elegir el tipo de usuario 'Cliente' se activarán las opciones para vincularlo a un centro ya cargado</p>
					<div id="clients_block" class="form-group dp-none">
						<hr>
						<label for="fd_clients">Vincular usuario a un centro</label>
						<select id="fd_clients" class="form-control">
							<option value="0">-- Elegir Centro --</option>
							<?php
							$clients = new Clients();
							if($clients->get()){
								foreach ($clients->data() as $client){
									echo '<option value="'.$client->id.'">'.$client->name.'</option>';
								}
							}
							?>
						</select>
					</div>
				</div>
			</div>

			<hr>
			<div id="fd_notify" class="clickable"><i class="fa fa-toggle-on"></i> Enviar un mail al usuario con los datos de la cuenta (aplica sólamente cuando se crea un usuario)</div>

			<hr>

			<button id="login_as" class="btn btn-xs btn-primary"><i class="fa fa-sign-in"></i> Ingresar como este usuario</button>

		</div>

		<div class="block-white">
			<button id="btn_save" type="submit" class="btn btn-success pull-right">GUARDAR</button>
			<button id="btn_cancel" class="btn btn-warning btn-sm">CANCELAR</button>
			<button id="btn_delete" class="btn btn-danger btn-sm">BORRAR</button>
		</div>

	</div>
</section>


<?php include 'templates.php' ?>