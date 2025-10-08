<section class="gral-section bg-gray-5">
	<div class="container">
		
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="#">Inicio</a>
			</li>
			<li class="breadcrumb-item">
				<a href="#">GiftCards</a>
			</li>
			<li class="breadcrumb-item">
				<span><?= $giftcard->title ?></span>
			</li>
		</ol>

		<div class="row">
			
			<div class="col-lg-4">
				<div class="boxes">
					<div class="box-wrapper">
						<div class="box">

							<div class="box-content">

								<input type="hidden" name="giftcard_id" value="<?= $giftcard->id ?>">
								
								<div class="row align-items-center">
									<div class="col-12">
										<div class="border rounded shadow mb-3 thumb-cover" style="background-image:url(<?= $giftcard->image->big ?>)">
											<img src="<?= View::assets('blank-vertical.gif') ?>" alt="" class="w-100">
										</div>
									</div>
									<div class="col-12">

										<h3 class="title"><?= $giftcard->title ?></h3>
										<h2><?= $giftcard->value_formatted ?></h2>	

										<div class="small-comment">Precio sin impuestos nacionales: <?= $giftcard->value_novat_formatted ?></div>

										<div class="my-4 small"><?= $giftcard->description ?></div>

									</div>
								</div>
							</div>							

							<div class="box-footer">							

								<div class="small-comment">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis temporibus animi omnis beatae asperiores molestiae a quia quaerat? Sed velit ratione dolorem odit error magnam ipsam quisquam hic maxime recusandae?  Doloremque, eum! Eum repellat perferendis expedita sequi ex rerum? Ullam explicabo reprehenderit corrupti sapiente neque architecto qui commodi animi repudiandae voluptatibus ipsam molestias beatae dicta sed culpa, alias illum error. Exercitationem cumque minima sit expedita mollitia necessitatibus labore, reiciendis itaque voluptate unde odio dicta culpa aliquam nemo consectetur reprehenderit, debitis beatae nam! Eos perferendis fugit, sapiente eligendi recusandae doloribus cum. Facilis officia aliquam, esse consectetur dolorem vero.</div>
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
								<h2>Proceso de Compra</h2>
								<p>Estás a un paso de regalar una experiencia única 💝</p>
							</div>
							
							<!-- USER INFO -->
							<div data-toggle="user-info" class="box-content">

								<h4>1. Completá tus datos para continuar con la compra de tu GiftCard</h4>
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
							<div data-toggle="payment" class="box-content border-top d-none">
								<h4>2. Seleccioná la forma de pago</h4>

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

		<hr>
		<a href="<?= View::url('giftcards') ?>" class="btn btn-primary btn-xs" >
			<i class="fal fa-angle-double-left fa-fw"></i> Ver más GiftCards
		</a>
					

	</div>		
</section>


<section class="gral-section border-bottom">
	<div class="container">		
		<h3 class="title">Experiencias disponibles para esta GiftCard:</h3>	
		[EXPERIENCIAS]
	</div>
</section>