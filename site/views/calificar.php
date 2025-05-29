
<section class="gral-section qualify">
	<div class="container">

		<h1>Calificar</h1>
		<hr>

		<div id="qualify_wrapper" class="row">
			<div class="col-md-4">

				<div class="boxes">
					<div class="box-wrapper">
						<div class="box">
							<div class="box-title">
								<h3><?= !is_null($Sales->data()->title) ? $Sales->data()->title : 'La experiencia fue borrada' ?></h3>
								<h5><?= !is_null($Sales->data()->clientname) ? $Sales->data()->clientname : '' ?></h5>
							</div>
							<div class="box-content">
								<div class="thumb thumb-cover thumb-fullx180 bd-full-gray-20 bg-aqua-2" style="background-image:<?= $imagepromo ?>"></div>
							</div>
							<div class="box-footer">
								<h4>$ <?= number_format($Sales->data()->price,2,',','.') ?> - Cantidad: <?= $Sales->data()->quantity ?></h4>
								<p class="cl-gray-50"><?= $Sales->data()->fecha ?> hs.</p>
								<small><?= nl2br($Sales->data()->subtitle) ?></small>
							</div>
						</div>

					</div>
				</div>

			</div>
			<div class="col-md-8">

				<div class="boxes">
					<div class="box-wrapper">

						<div class="box">

							<div class="box-content">
								<form id="form_qualify">
									<input type="hidden" name="idsale" value="<?=$idsale?>" >
									<h3>Describinos cómo fue tu experiencia:</h3>
									<p>Podés hacer referencia sobre la higiene general de lugar, atención de la recepcionista, atención del profesional, equipamiento, calificación general, etc.</p>

									<div class="mb-3">
										<textarea name="comment" rows="6" class="form-control" maxlength="500" required></textarea>
										<small>Máximo <maxchar>500</maxchar> caracteres</small>
									</div>

									<h3>¿Cuántas estrellas le darías?</h3>
									<div class="stars">
										<div class="stars-wrapper">
											<input id="star_5" value="5" type="radio" name="rate">
											<label for="star_5" class="fa fa-star-o"></label>
											<input id="star_4" value="4" type="radio" name="rate">
											<label for="star_4" class="fa fa-star-o"></label>
											<input id="star_3" value="3" type="radio" name="rate">
											<label for="star_3" class="fa fa-star-o"></label>
											<input id="star_2" value="2" type="radio" name="rate">
											<label for="star_2" class="fa fa-star-o"></label>
											<input id="star_1" value="1" type="radio" name="rate">
											<label for="star_1" class="fa fa-star-o"></label>
										</div>
										<div class="star-text-wrapper">
											<span>Excelente</span>
											<span>Muy Bueno</span>
											<span>Bueno</span>
											<span>Regular</span>
											<span>Malo</span>
										</div>
									</div>
									<hr>

									<div class="mb-3">
										<button id="btn_qualify" class="btn btn-primary">Enviar <i class="fa fa-paper-plane"></i></button>
									</div>

								</form>
							</div>
						</div>
					</div>

				</div>



			</div>
		</div>

		<hr>
		<a href="<?= View::url('mis-compras') ?>" class="btn btn-fucsia"><i class="fa fa-chevron-left"></i> Volver</a>
	</div>
</section>

