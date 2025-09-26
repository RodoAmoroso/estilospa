<div class="gral-section border-bottom">
	<div class="container">


		<div class="boxes">
			<div class="box-wrapper">
		
				<div class="box">

					<div class="box-title">
						<div class="d-flex">
							<div class="border rounded shadow mb-3 thumb-cover thumb-100x100 me-4" style="background-image:url(<?= $giftcard->image->big ?>)"></div>
							<div class="x">
								<h5 class="title"><?= $giftcard->title ?></h5>
								<h4><?= $giftcard->value_formatted ?></h4>	
								<div class="small-comment">Precio sin impuesto nacionales: <?= $giftcard->value_novat_formatted ?></div>
							</div>
						</div>
					</div>

					<!-- USER INFO -->		
					<div data-toggle="user-info" class="box-content">

						<h3>1. Completá tus datos</h3>
						<hr class="hr-dashed">

						<form data-form="user-info">
							<div class="row">
								<div class="col-lg-6 mb-3">
									<div class="form-floating">
										<input name="firstname" id="user_firstname" type="text" class="form-control" value="<?= $_userdata->name ?>" required placeholder="Tu Nombre *">
										<label for="user_firstname">Tu Nombre *</label>
									</div>
								</div>
								<div class="col-lg-6 mb-3">
									<div class="form-floating">
										<input name="lastname" id="user_lastname" type="text" class="form-control" value="<?= $_userdata->lastname ?>" required placeholder="Tu Apellido *">
										<label for="user_lastname">Tu Apellido *</label>
									</div>
								</div>
								<div class="col-lg-6 mb-3">
									<div class="form-floating">
										<input name="phone" id="user_phone" type="text" class="form-control" value="<?= $_userdata->phone ?>" required placeholder="Celular/Whatsapp (Prefijo + Nro.) *">
										<label for="user_phone">Celular/Whatsapp (Prefijo + Nro.) *</label>
									</div>
								</div>
								<div class="col-lg-6 mb-3">
									<div class="form-floating">
										<input name="email" id="user_email" type="email" class="form-control" value="<?= $_userdata->mail ?>" required placeholder="Email *">
										<label for="user_email">Email *</label>
									</div>
								</div>
							</div>
		
							<button class="btn btn-primary">
								<i class="fa fa-save fa-fw"></i> <span>Guardar & Continuar</span>
							</button>
						</form>
					</div>
					
					

					<!-- PAYMENT -->					
					<div data-toggle="payment" class="box-content d-none">
						<h3>2. Seleccioná la forma de pago</h3>

						<hr class="hr-dashed">
						<div data-toggle="payment-box" class="">
							<div id="paymentBrick_container" class="">
								<div data-toggle="form-mp-loader" class="text-center">
									<h2 class="text-aqua-3"><i class="fa fa-cog fa-spin"></i></h2>
									<h4 class="text-center">Cargando Formulario de Pago...</h4>
								</div>
							</div>
							<div class="alert alert-warning">			
								<div><i class="fa fa-exclamation-triangle fa-fw"></i> Por favor verificar que todos los datos sean los correctos, (respetar mayúsculas y minúsculas en el nombre que figura en la tarjeta) en caso que algún dato sea inválido tu compra será rechazada por este motivo.</div>
							</div>
						</div>
					</div>
		
				</div>
		
			</div>
		</div>

	</div>
</div>
