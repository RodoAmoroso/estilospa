<section class="profile-header bg-gray-5">

	<div class="container">
		<div class="profile-columns">
			<div class="avatar-container">
				<div id="avatar" class="avatar thumb-cover" style="background-image:url(<?= ROOTPATH.'img/users/'.$_AVATAR ?>)"></div>
				<button id="btn_image" class="btn btn-xs btn-fucsia">CAMBIAR IMAGEN</button>
				<form id="form_image" class="dp-none">
					<input type="file" accept="image/*" >
				</form>
			</div>
			<div class="user-name">
				<h1><?= $_USER->data()->name.' '.$_USER->data()->lastname; ?></h1>
				<p class="sz-14"><?= $_USER->data()->mail; ?></p>
				<p class="sz-8">Usuario desde: <?= date('d/m/Y',strtotime($_USER->data()->created)) ?></p>
			</div>
			
		</div>
	</div>
</section>


<section class="profile-body gral-section">
	<div class="container">		

		<h2>Perfil</h2>
		<hr>

		<form id="form_user">

			<div class="row">
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_name">Nombre</label>
						<input id="fd_name" type="text" class="form-control" value="<?= $_USER->data()->name ?>" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_lastname">Apellido</label>
						<input id="fd_lastname" type="text" class="form-control" value="<?= $_USER->data()->lastname ?>" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_dni">DNI <i class="fa fa-question-circle cl-gray-30" title="" data-original-title="Necesario para participar de compras y promociones"></i></label>
						<input id="fd_dni" type="number" min="0" class="form-control" value="<?= $_USER->data()->dni ?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_birth">Fecha de Nacimiento</label><br />

							<?php 
							$arrDate = explode('-',$_USER->data()->birth);
							?>
						
							<div class="col-xs-4" style="padding:0 4px 0 0">
								<select id="fd_day" class="form-control">
									<?php 
									for($i=1; $i<=31; $i++): 
										$sel = '';
										if($i == intval($arrDate[2])):
											$sel = 'selected';
										endif;
									?>
									<option value="<?= $i ?>" <?= $sel ?> ><?= $i>9 ? $i : '0'.$i ?></option>
									<?php endfor; ?>
								</select>
							</div>
							<div class="col-xs-4" style="padding:0 4px 0 0">
								<select id="fd_month" class="form-control">
									<?php 
									$arrMonths = array('Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
									foreach($arrMonths as $km=>$vm):
										$sel = '';
										if($km+1 == intval($arrDate[1])):
											$sel = 'selected';
										endif;
									?>
									<option value="<?= $km+1 ?>" <?= $sel ?> ><?= $vm ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-xs-4" style="padding:0 4px 0 0">
								<select id="fd_year" class="form-control">
									<?php 
									for($i=date('Y'); $i>1900; $i--):
										$sel = '';
										if($i == intval($arrDate[0])):
											$sel = 'selected';
										endif;
									?>
									<option value="<?= $i ?>" <?= $sel ?> ><?= $i ?></option>
									<?php endfor; ?>
								</select>
							</div>

					</div>	
				</div>

				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_phone">Teléfono (Prefijo + Nro.)</label>
						<input id="fd_phone" type="text" class="form-control" value="<?= $_USER->data()->phone ?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_address">Dirección (Calle y Nro.)</label>
						<input id="fd_address" type="text" class="form-control" value="<?= $_USER->data()->address ?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_addressobs">Piso/Depto.</label>
						<input id="fd_addressobs" type="text" class="form-control" value="<?= $_USER->data()->addressobs ?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_city">Ciudad/Localidad</label>
						<input id="fd_city" type="text" class="form-control" value="<?= $_USER->data()->city ?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_zip">Código Postal</label>
						<input id="fd_zip" type="text" class="form-control" value="<?= $_USER->data()->zipcode ?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_provinces">Provincia</label>
						<select id="fd_provinces" class="form-control">
							<?php 
							$provinces = DB::getInstance()->get('provinces',array('id','!=',0));
							if($provinces->count()):
								foreach($provinces->results() as $province):
									$sel = '';
									if($province->id == $_USER->data()->idprovince):
										$sel = 'selected';
									endif;
							?>
							<option value="<?= $province->id ?>" <?= $sel ?> ><?= $province->name ?></option>
							<?php 
								endforeach;
							endif;
							?>
						</select>
					</div>
				</div>
			</div>

			<div class="row">

				<hr>

				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_pass">Contraseña Actual </label>
						<input id="fd_pass" type="password" class="form-control">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_passnew">Contraseña Nueva </label>
						<input id="fd_passnew" type="password" class="form-control">		
					</div>
				</div>
				<div class="col-xs-12">
					<p class="sz-9">Si dejás los campos en blanco la contraseña no se cambiará</p>
				</div>

			</div>

			<hr>

			<i id="fd_newsletter" class="fa fa-check-square clickable"></i> <label for="fd_newsletter" class="clickable">Deseo recibir mails con ofertas y promociones.</label>

			<hr>

			<div id="status"></div>

			<button id="btn_save" class="btn btn-fucsia"><i class="fa fa-save"></i> GUARDAR</button>

		</form>

	</div>
</section>


