<section class="profile-header bg-gray-5">

	<div class="container">
		<div class="profile-columns">
			<div class="avatar-container">
				<div id="avatar" class="avatar thumb-cover" style="background-image:url(<?= ROOT.'img/users/'.$_AVATAR ?>)"></div>

				<div data-input="image">
					<button id="btn_image" class="btn btn-xs btn-fucsia">CAMBIAR IMAGEN</button>
					<input type="file" accept="image/*" class="d-none" >
				</div>


			</div>
			<div class="user-name">
				<h1 class="name"><?= $User->data()->name.' '.$User->data()->lastname; ?></h1>
				<p class="sz-14"><?= $User->data()->mail; ?></p>
				<p class="sz-8">Usuario desde: <?= date('d/m/Y',strtotime($User->data()->created)) ?></p>
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
						<input name="name" id="fd_name" type="text" class="form-control" value="<?= $User->data()->name ?>" required>
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_lastname">Apellido</label>
						<input name="lastname" id="fd_lastname" type="text" class="form-control" value="<?= $User->data()->lastname ?>" >
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_dni">DNI <i class="fa fa-question-circle cl-gray-30" title="Necesario para participar de compras y promociones"></i></label>
						<input name="dni" id="fd_dni" type="number" min="0" class="form-control" value="<?= $User->data()->dni ?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="fd_birth">Fecha de Nacimiento</label><br />

							<?php $arrDate = explode('-',$User->data()->birth); ?>

							<div class="col-xs-4" style="padding:0 4px 0 0">
								<select name="birth_day" id="fd_day" class="form-control">
									<option value="">--</option>
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
								<select name="birth_month" id="fd_month" class="form-control">
									<option value="">--</option>
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
								<select name="birth_year" id="fd_year" class="form-control">
									<option value="">--</option>
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
						<input name="phone" id="fd_phone" type="text" class="form-control" value="<?= $User->data()->phone ?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_address">Dirección (Calle y Nro.)</label>
						<input name="address" id="fd_address" type="text" class="form-control" value="<?= $User->data()->address ?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_addressobs">Piso/Depto.</label>
						<input name="addressobs" id="fd_addressobs" type="text" class="form-control" value="<?= $User->data()->addressobs ?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_city">Ciudad/Localidad</label>
						<input name="city" id="fd_city" type="text" class="form-control" value="<?= $User->data()->city ?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_zip">Código Postal</label>
						<input name="zipcode" id="fd_zip" type="text" class="form-control" value="<?= $User->data()->zipcode ?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="fd_provinces">Provincia</label>
						<select name="idprovince" id="fd_provinces" class="form-control">
							<?php
							$provinces = DB::getInstance()->get('provinces',array('id','!=',0));
							if($provinces->count()):
								foreach($provinces->results() as $province):
									$sel = '';
									if($province->id == $User->data()->idprovince):
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
						<label for="fd_passnew">Cambiar Constraseña</label>
						<input name="password_new" id="fd_passnew" type="password" class="form-control">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="form-group">
						<label for="fd_pass">Contraseña Actual </label>
						<input name="password" id="fd_pass" type="password" class="form-control">
					</div>
				</div>
				<div class="col-xs-12">
					<p class="sz-9">Si dejás los campos en blanco la contraseña no se cambiará</p>
				</div>

			</div>

			<hr>

			<label for="fd_newsletter" class="clickable">Deseo recibir mails con ofertas y promociones.</label>

			<div class="onoffswitch">
				<input id="newsletter" type="checkbox" class="onoffswitch-checkbox" <?=$User->data()->newsletter ? 'checked' : ''?>>
				<label for="newsletter" class="onoffswitch-label">
					<span class="onoffswitch-inner"></span>
					<span class="onoffswitch-switch"></span>
				</label>
			</div>

			<hr>

			<div class="text-right">
				<button id="btn_save" class="btn btn-success"><i class="fa fa-save"></i> GUARDAR</button>
			</div>


		</form>

	</div>
</section>


