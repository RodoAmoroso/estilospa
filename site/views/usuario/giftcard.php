<section class="gral-section">

	<div class="container">

		<h4>Personalizá tu GiftCard</h4>
		
		<!-- CUSTOMIZE -->
		<div data-toggle="customize" class="box-content d-nonee">
						
			<h3>Completá los datos del Agasajado</h3>
			<div class="small-comment">Le enviaremos los pasos a seguir para utilizar esta giftcard luego de la compra.</div>
		

			<hr class="hr-dashed">

			<div class="row">

				<div class="col-lg-5">
					<form action="#" method="post" class="form-giftcard">
						<div class="row">

							<div class="col-lg-12 mb-3">
								<div class="form-floating">
									<input type="text" name="name" id="from_name" class="form-control" placeholder="De *" required>
									<label for="from_name">De *</label>
								</div>
							</div>


							<div class="col-lg-12 mb-3">
								<div class="form-floating">
									<input type="text" name="name" id="to_name" class="form-control" placeholder="Para *" required>
									<label for="to_name">Para *</label>
								</div>
							</div>
							<!-- <div class="col-lg-12 mb-3">
								<div class="form-floating">
									<input type="text" name="name" id="to_email" class="form-control" placeholder="Email del destinatario" required>
									<label for="to_email">Email del destinatario</label>
								</div>
							</div> -->
						</div>
						<div class="form-floating mb-3">
							<textarea name="comments" id="comments" class="form-control" placeholder="Dedicatoria" required style="height:200px"></textarea>
							<label for="comments">Dedicatoria/Mensaje</label>
						</div>
						<hr class="hr-dashed">

						<div class="align-items-center">

							<div class="mb-3">
								<h5>Elegí el motivo</h5>

								<div class="border bg-gray-5 p-4">

									<div data-toggle="giftcard-carousel" class="owl-carousel owl-theme giftcards-bakground-gallery">
										<div class="thumbnail selected" style="background-image:url(<?= View::img('giftcards','bg-giftcard-1.jpg') ?>)">
											<img src="<?= View::assets('blank-vertical.gif') ?>" alt="" >
										</div>
										<div class="thumbnail" style="background-image:url(<?= View::img('giftcards','bg-giftcard-2.jpg') ?>)">
											<img src="<?= View::assets('blank-vertical.gif') ?>" alt="" >
										</div>
										<div class="thumbnail" style="background-image:url(<?= View::img('giftcards','bg-giftcard-3.webp') ?>)">
											<img src="<?= View::assets('blank-vertical.gif') ?>" alt="" >
										</div>
										<div class="thumbnail" style="background-image:url(<?= View::img('giftcards','bg-giftcard-4.jpg') ?>)">
											<img src="<?= View::assets('blank-vertical.gif') ?>" alt="" >
										</div>
										<div class="thumbnail" style="background-image:url(<?= View::img('giftcards','bg-giftcard-5.png') ?>)">
											<img src="<?= View::assets('blank-vertical.gif') ?>" alt="" >
										</div>
										<div class="thumbnail" style="background-image:url(<?= View::img('giftcards','bg-giftcard-6.jpg') ?>)">
											<img src="<?= View::assets('blank-vertical.gif') ?>" alt="" >
										</div>
									</div>
								</div>
								

							</div>

							<hr class="hr-dashed">
							<div class="mb-3">
								<h5>(Opcional) Subí tu imagen personalizada</h5>

								<div data-input="giftcard-image" class="my-3">
									<button class="btn btn-sm btn-aqua-3">
										<i class="fa fa-upload fa-fw"></i>
										<span>Examinar...</span>
									</button>
									<input type="file" accept="image/*" class="d-none">
								</div>

								<div class="border rounded thumb-cover thumb-300x300 bg-gray-5"></div>
							</div>
						</div>


						<hr class="hr-dashed">

						<button class="btn btn-primary">
							<i class="fa fa-save fa-fw"></i> <span>Guardar & Continuar</span>
						</button>
	
					</form>
				</div>
				
				<div class="col-lg-7 border-start">
					<h4>Preview</h4>

					<div class="rounded border shadow thumb-cover" style="background-image:url(<?= View::img('giftcards','preview-2.jpg') ?>)">
						<img src="<?= View::assets('blank-rectangle.gif') ?>" alt="" class="w-100">
					</div>
				</div>

			</div>
			
			<button data-toggle="form-giftcard-skip" class="btn btn-primary d-none">
				<i class="fa fa-save fa-fw"></i> <span>Guardar & Continuar</span>
			</button>						

		</div>	


		<hr>

		<a href="<?= View::url('usuario','mis-giftcards') ?>" class="btn btn-aqua-2">
			<i class="fal fa-caret-left"></i>
			<span>Volver a mis GiftCards</span>
		</a>
	</div>
</section>