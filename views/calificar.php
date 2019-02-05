
<section class="gral-section qualify">
	<div class="container">
		
		<h1>Calificar</h1>
		<hr>

		<div id="qualify_wrapper" class="row">
			<div class="col-sm-4">
				<div class="block-box">
					<div class="box-title">
						<h3><?= !is_null($_SALES->data()->title) ? $_SALES->data()->title : 'La Promo fue borrada' ?></h3>
						<h5><?= !is_null($_SALES->data()->clientname) ? $_SALES->data()->clientname : '' ?></h5>
					</div>
					<div class="box-content">						
						<div class="thumb thumb-cover thumb-fullx180 bd-full-gray-20 bg-aqua-2" style="background-image:<?= $imagepromo ?>"></div>						
					</div>
					<div class="box-footer">
						<h4>$ <?= number_format($_SALES->data()->price,2,',','.') ?> - Cantidad: <?= $_SALES->data()->quantity ?></h4>
						<p class="cl-gray-50"><?= $_SALES->data()->fecha ?> hs.</p>
						<small><?= nl2br($_SALES->data()->description) ?></small>
					</div>
				</div>
			</div>
			<div class="col-sm-8">
				<div class="block-white">
					<h3>Descríbenos cómo fue tu experiencia:</h3>
					<p>Puedes hacer referencia sobre la higiene general de lugar, atención de la recepcionista, atención del profesional, equipamiento, calificación general, etc.</p>
					<div class="form-group">
						<textarea id="fd_comment" rows="6" class="form-control" maxlength="500" ></textarea>
						<small>Máximo <maxchar>500</maxchar> caracteres</small>
					</div>
					<h3>¿Cuántas estrellas le darías?</h3>
					<div class="stars">
						<div class="stars-wrapper">
							<input id="star_5" value="5" type="radio" name="rating">
							<label for="star_5" class="fa fa-star-o"></label>
							<input id="star_4" value="4" type="radio" name="rating">
							<label for="star_4" class="fa fa-star-o"></label>
							<input id="star_3" value="3" type="radio" name="rating">
							<label for="star_3" class="fa fa-star-o"></label>
							<input id="star_2" value="2" type="radio" name="rating">
							<label for="star_2" class="fa fa-star-o"></label>
							<input id="star_1" value="1" type="radio" name="rating">
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
					<div class="form-group">
						<button id="btn_qualify" class="btn btn-primary">Enviar <i class="fa fa-paper-plane"></i></button>
					</div>
				</div>
			</div>
		</div>

		<hr>
		<a href="<?= ROOTPATH.'mis-compras' ?>" class="btn btn-fucsia"><i class="fa fa-chevron-left"></i> Volver</a>
	</div>
</section>

<script>
var idsale = '<?= $idsale ?>';
</script>