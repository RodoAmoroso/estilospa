<section class="gral-section qualify">
	<div class="container">

		<h1>Comprar Experiencia</h1>
		<hr>

		<div class="row d-flex flex-wrap">

			<div class="col-lg-4">

				<div class="boxes">
					<div class="box-wrapper">
						<div class="box">
							<div class="box-title">
								<h3 class="title"><?= $promo->title ?></h3>
								<div class="small"><?= nl2br($promo->subtitle) ?></div>
								<a href="#"><?= $client->name ?></a>
							</div>
							<div class="box-content">
								<div class="thumb thumb-cover thumb-fullx180 bd-full-gray-20 bg-aqua-2" style="background-image:url(<?= $promo->image ?>)"></div>
							</div>
							<div class="box-footer form-group">
								<h6>Precio unit. $ <span data-content="unit-price" data-value="<?= $promo->price_w_discount ?>"><?= number_format($promo->price_w_discount,2,',','.') ?></span></h6>

								<!-- AMOUNT -->
								<div class="input-group">
									<div class="input-group-addon">
										<span class="input-group-text">Cantidad</span>
									</div>
									<select name="quantity" class="form-control">
										<?php for($i=1; $i<=($promo->amount>15 ? 15 : $promo->amount); $i++): ?>
										<option value="<?= $i ?>" <?= $sale_temp && $sale_temp->quantity==$i ? 'selected' : '' ?>><?= $i ?></option>
										<?php endfor; ?>
									</select>
								</div>

							</div>

							<?php if($sale_temp && $sale_temp->idcode): ?>
							<!-- APPLIED VOUCHER -->
							<div class="box-footer alert-success">
								<h5><b><?= $sale_temp->voucher->name ?></b></h5>
								<?php if($sale_temp->voucher->ispercent): ?>
								<h3><?= $sale_temp->voucher->value ?>% off</h3>
								<?php else: ?>
								<h3>$ <?= number_format($sale_temp->voucher->value,2,',','.') ?> off</h3>
								<?php endif; ?>

								<div class="small">sobre el valor total de cada experiencia.</div>
							</div>
							<?php elseif($voucher): ?>
							<!-- HAS VOUCHER -->
							<div data-content="voucher" class="box-footer alert-warning">
								<h5>Voucher de Descuento</h5>
								<form data-form="voucher" class="input-group">
									<input type="hidden" name="idpromo" value="<?= $promo->id ?>">
									<input name="code" type="text" class="form-control" value="" required>
									<div class="input-group-btn">
										<button data-toggle="validate-voucher" class="btn btn-primary">Validar</button>
									</div>
								</form>
								<div class="small text-muted"><i>Si tienes un código para esta experiencia puedes aplicarlo para obtener un descuento en la compra.</i></div>
							</div>
							<?php endif; ?>




							<div class="box-footer">
								<h3>Total: $ <span data-content="total"><?= number_format( $sale_temp ? $sale_temp->total : $promo->price_w_discount,2,',','.') ?></span></h3>
							</div>

							<div class="box-footer">
								<a href="<?= $promo->url ?>" class="btn btn-primary" >
									<i class="fa fa-angle-double-left fa-fw"></i> Volver
								</a>
							</div>
						</div>

					</div>
				</div>

			</div>


			<div class="col-lg-8">
				<div class="boxes">
					<div class="box-wrapper">

						<div class="box">

							<div class="box-content">

								<h3>1. Completá tus datos</h3>
								<p>&nbsp;</p>

								<form data-form="user-info">
									<div class="row">
										<div class="col-lg-6 form-group">
											<label for="">Tu Nombre *</label>
											<input name="firstname" type="text" class="form-control" value="<?= $_userdata->name ?>" required>
										</div>
										<div class="col-lg-6 form-group">
											<label for="">Tu Apellido *</label>
											<input name="lastname" type="text" class="form-control" value="<?= $_userdata->lastname ?>" required>
										</div>
										<div class="col-lg-6 form-group">
											<label for="">Celular/Whatsapp (Prefijo + Nro.) *</label>
											<input name="phone" type="text" class="form-control" value="<?= $_userdata->phone ?>" required>
										</div>
										<div class="col-lg-6 form-group">
											<label for="">Email *</label>
											<input name="email" type="email" class="form-control" value="<?= $_userdata->mail ?>" required>
										</div>
									</div>

									<button class="btn btn-primary"><i class="fa fa-save fa-fw"></i> Guardar & Continuar</button>
								</form>
							</div>

							<hr>

							<div data-toggle="payment-box" class="box-content d-none">
								<h3>2. Seleccioná la forma de pago</h3>
								<div id="paymentBrick_container" class="">
									<div data-toggle="form-mp-loader" class="text-center">
										<h2 class="text-aqua-3"><i class="fa fa-cog fa-spin"></i></h2>
										<h4 class="text-center">Cargando Formulario de Pago...</h4>
									</div>
								</div>
								<div class="alert alert-info">

									<div><i class="fa fa-exclamation-circle fa-fw"></i> Por favor verificar que todos los datos sean los correctos, (respetar mayúsculas y minúsculas en el nombre que figura en la tarjeta) en caso que algún dato sea inválido tu compra será rechazada por este motivo.</div>
								</div>
							</div>

						</div>

					</div>
				</div>

			</div>





		</div>


	</div>
</section>