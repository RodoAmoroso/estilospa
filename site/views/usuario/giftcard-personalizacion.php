<section class="gral-section">

	<div class="container">

		<h3 class="">Personalizá tu GiftCard</h3>
		
		<!-- CUSTOMIZE -->
		<div data-toggle="customize" class="box-content">						
			
			<div class="small-comment">Puedes incluir el mail del agasajado para que reciba una notificación con los pasos a seguir para utilizar esta giftcard.</div>
		

			<hr class="hr-dashed">

			<div class="row">

				<div class="col-lg-4 border-end">
					
					<form data-form="giftcard-personalization" class="form-giftcard">

						<input type="text" name="giftcard_purchase_id" value="<?= $_idsection ?>">
						<input type="text" name="id" value="<?= $giftcard_personalization ? $giftcard_personalization->id : 0 ?>">

						<h5 class="text-aqua-3">Completá los datos del Agasajado</h5>
						<div class="row">

							<div class="col-lg-12 mb-3">
								<div class="form-floating">
									<input type="text" name="from_user" id="from_user" class="form-control" placeholder="De *" required value="<?= $giftcard_personalization ? $giftcard_personalization->from_user : $_userdata->name ?>">
									<label for="from_user">De *</label>
								</div>
							</div>

							<div class="col-lg-12 mb-3">
								<div class="form-floating">
									<input type="text" name="to_user" id="to_user" class="form-control" placeholder="Para *" required value="<?= $giftcard_personalization ? $giftcard_personalization->to_user : '' ?>">
									<label for="to_user">Para *</label>
								</div>
							</div>
							<div class="col-lg-12 mb-3">
								<div class="form-floating">
									<input type="email" name="to_email" id="to_email" class="form-control" placeholder="Email del destinatario" value="<?= $giftcard_personalization ? $giftcard_personalization->to_email : '' ?>">
									<label for="to_email">Email del destinatario</label>
								</div>
							</div>
						</div>
						<div class="form-floating mb-3">
							<textarea name="message" id="message" class="form-control" placeholder="Dedicatoria" required style="height:200px" maxlength="300"><?= $giftcard_personalization ? $giftcard_personalization->message : '' ?></textarea>
							<label for="message">Dedicatoria/Mensaje *</label>
						</div>
						<div class="small-comment">Máx. 300 caracteres</div>



						<hr class="hr-dashed">

						<h5 class="text-aqua-3">(Opcional) Subí tu imagen personalizada</h5>

						<div data-input="image" class="my-3">
							<button type="button" class="btn btn-xs btn-aqua-2">
								<i class="fa fa-upload fa-fw"></i>
								<span>Examinar...</span>
							</button>
							<input type="file" accept="image/*" class="d-none">
						</div>

						<ul data-toggle="user-image-container" class="list-group">
							<?php if($giftcard_personalization && $giftcard_personalization->image): ?>
							<li data-filename="<?= $giftcard_personalization->image->f ?>" data-extension="<?= $giftcard_personalization->image->e ?>" data-hash="<?= $giftcard_personalization->image->h ?>" class="list-group-item d-flex align-items-center justify-content-between">
								<div class="d-flex align-items-center">
									<div class="filename-thumbnail img-thumbnail bg-gray-5 me-3 thumb-80x80 thumb-contain" style="background-image:url(<?= $giftcard_personalization->image->small ?>)"></div>
									<div>
										<div class="filename"><?= $giftcard_personalization->image->f.'.'.$giftcard_personalization->image->e ?></div>										
									</div>
								</div>
								<button type="button" data-toggle="delete-image" class="btn btn-danger btn-xs">
									<i class="fa fa-trash fa-fw"></i>
								</button>
							</li>
							<?php endif; ?>
						</ul>
					
						<hr class="hr-dashed">
						<button data-toggle="form-giftcard-skip" class="btn btn-aqua-4">
							<i class="fa fa-save fa-fw"></i> <span>Guardar & Finalizar</span>
						</button>

					</form>
				</div>
				
				<div class="col-lg-8">

					<h5 class="text-aqua-3">Preview</h5>

					<div class="giftcard-preview-container">
						<div class="giftcard-preview">
							<img src="<?= View::assets('blank-rectangle.gif') ?>" alt="" >

							<div class="content">
								<div class="left-column" ></div>
								<div class="center-column">

									<img src="<?= View::assets('giftcard-logo.jpg') ?>" alt="EstiloSpa" class="giftcard-logo">

									<div class="name" >Para <span data-content="to_user"><?= $giftcard_personalization ? $giftcard_personalization->to_user : '[nombre]' ?></span>:</div>
									<div class="message" data-content="message"><?= $giftcard_personalization ? $giftcard_personalization->message : '[Escribí tu mensaje...]' ?></div>
									<div class="author" >de <span data-content="from_user"><?= $giftcard_personalization ? $giftcard_personalization->from_user : $_userdata->name ?></span></div>	

									
									<div class="code">Código: <span><?= $giftcard_purchase->code ?></span></div>
									

									<div class="phrase">Tu momento con <span>estilo</span> está a punto de comenzar...</div>

									<div class="info">
										<div>1. Ingresá a www.estilospa.com</div>
										<div>2. Buscá el botón "Abrir Tu Regalo"</div>
										<div>3. Ingresá el código de tu GiftCard</div>
										<div>4. ¡Canjeala por el servicio que prefieras!</div>
									</div>

									<img src="<?= View::assets('logo.jpg') ?>" alt="EstiloSpa" class="estilospa-logo">
								</div>
								<div class="right-column" style="background-image:url(<?= $giftcard_personalization ? $giftcard_personalization->image->big : View::img('giftcards','bg-giftcard-1.jpg') ?>)"></div>
							</div>
						</div>
					</div>
				

					<hr class="hr-dashed">

					<?php if($giftcards_galleries): ?>
					<div class="mb-3">
						<h5 class="text-aqua-3">Elegí el motivo</h5>

						<div class="border rounded bg-gray-5 p-4">

							<div data-toggle="giftcard-carousel" class="owl-carousel owl-theme giftcards-bakground-gallery">

								<?php foreach($giftcards_galleries as $k=>$gallery): ?>
								<div class="thumbnail <?= $giftcard_personalization && $giftcard_personalization->gallery_id==$gallery->id ? 'selected' : ($k==0 ? 'selected' : '') ?>" data-img="<?= $gallery->image->big ?>" data-id="<?= $gallery->id ?>" style="background-image:url(<?= $gallery->image->small ?>)">
									<img src="<?= View::assets('blank-square.gif') ?>" alt="" >
								</div>
								<?php endforeach; ?>

							</div>
						</div>
						

					</div>				
					<?php endif; ?>	

					
									
				</div>

			</div>		
			
		</div>	

		<hr class="hr-dashed">	

		<div class="row">
			<div class="col-md-6 text-md-start text-center mb-2">
				<a href="<?= View::url('usuario','mis-giftcards') ?>" class="btn btn-secondary btn-sm">
					<i class="fal fa-angle-double-left"></i>
					<span>Volver a mis GiftCards</span>
				</a>
			</div>
		</div>

	</div>
</section>