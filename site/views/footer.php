<?php if($_section!='hotsale'): ?>
<!-- NEWSLETTER -->
<section class="newsletter">
	<!-- <div class="overprint-absolute" style="background-image:url(<?= ROOT ?>assets/bg-1.jpg)" ></div> -->
	<!-- <div class="bg-gradient overprint-absolute op-80"></div> -->

	<div class="container">

		<h2>¡Subscribite y obtené importantes descuentos para tus compras!</h2>
		<p class="sz-14">Recibí nuestras promociones exclusivas y enterate de todas las novedades del mundo del cuidado personal.</p>

		<form id="form_newsletter">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="input-group">
						<span class="input-group-addon bg-aqua-3 cl-white"><i class="fa fa-at"></i></span>
						<input name="email" type="email" class="form-control" placeholder="Email..." required>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<button class="btn btn-default btn-block"><i class="fa fa-paper-plane"></i> SUBSCRIBIRME</button>
				</div>
			</div>
			<div class="status"></div>
		</form>

	</div>
</section>
<?php endif; ?>

<!-- INDICE -->
<section class="gral-section dp-none">
	<div class="container">
		<h3>Últimos Centros</h3>
		<div class="row">

			<?php if($Clients->get('','','0,12','',0,1)): foreach ($Clients->data() as $client): ?>
			<div class="col col-xs-12 col-sm-2">
				<a href="<?= ROOT.'centros/'.$client->permalink ?>" class="dp-block"><?= $client->name ?></a>
			</div>
			<?php endforeach; endif; ?>

		</div>
	</div>
</section>


<!-- GLOSARIO -->
<section class="glossary-list">
	<div class="container">
		<h3>Servicios y Tratamientos <a href="<?= ROOT.'etiquetas' ?>" class="sz-10">(ver todos)</a></h3>
		<div class="row">
			<?php
			$GlossaryGroups->limit = '0,6';
			$GlossaryGroups->keywords = '';
			if($GlossaryGroups->get()):
				foreach ($GlossaryGroups->data() as $group):
			?>
			<div class="col col-xs-12 col-sm-2">
				<h4 class="fw-400"><?= $group->name ?></h4>
				<?php
				$Glossary->idgroup = $group->id;
				$Glossary->limit = '0,10';
				$Glossary->keywords = '';
				$Glossary->sort = '';
				if($Glossary->get()):
					foreach ($Glossary->data() as $glossary):
				?>
				<a href="<?= ROOT.'etiqueta/'.$glossary->id.'-'.Permalink($glossary->name) ?>" class="dp-block"><?= $glossary->name ?></a>
				<?php endforeach; endif; ?>
			</div>
			<?php endforeach; endif; ?>
		</div>
	</div>
</section>


<!-- MEDIOS DE PAGO -->
<section class="payment">
	<div class="container">
		<h3>Medios de Pago</h3>
		<div class="cards">
			<img src="<?= ROOT ?>assets/visa@2x.png" alt="">
			<img src="<?= ROOT ?>assets/mastercard@2x.png" alt="">
			<img src="<?= ROOT ?>assets/amex@2x.png" alt="">
			<img src="<?= ROOT ?>assets/banelco@2x.png" alt="">
			<img src="<?= ROOT ?>assets/cabal@2x.png" alt="">
			<img src="<?= ROOT ?>assets/tarjeta-naranja@2x.png" alt="">
			<img src="<?= ROOT ?>assets/tarjeta-shopping@2x.png" alt="">
			<img src="<?= ROOT ?>assets/mercadopago@2x.png" alt="">
		</div>
		<a href="http://qr.afip.gob.ar/?qr=Hii2ReLLuZMJV76OkTZy1A,," target="_blank" class="afip">
			<img src="<?= ROOT ?>assets/dataweb.jpg" alt="">
		</a>
	</div>
</section>



<!-- FOOTER -->
<footer class="cl-white">

	<div class="overprint-absolute bg" style="background-image:url(<?= ROOT.'assets/bg-6.jpg?id='.rand(111,999) ?>)" ></div>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-4">

				<img class="logo" src="<?= ROOT ?>assets/logo-white.png" alt="">

				<div class="socials">
					<a href="http://www.facebook.com/estilospa" target="_blank" class="icon">
						<i class="fa fa-facebook"></i>
					</a>
					<a href="http://www.twitter.com/estilospa" target="_blank" class="icon">
						<i class="fa fa-twitter"></i>
					</a>
					<a href="https://www.instagram.com/estilospa/" target="_blank" class="icon">
						<i class="fa fa-instagram"></i>
					</a>
					<a href="http://www.youtube.com/EstiloSpa" target="_blank" class="icon">
						<i class="fa fa-youtube-play"></i>
					</a>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4">
				<ul>
					<li><a href="<?= ROOT.'sobre-estilospa' ?>">Sobre EstiloSPA</a></li>
					<li><a href="<?= ROOT.'como-funciona' ?>">¿Cómo Funciona?</a></li>
					<li><a href="<?= ROOT.'como-comprar' ?>">¿Cómo Comprar?</a></li>
					<li><a href="<?= ROOT.'dudas-frecuentes' ?>">Dudas Frecuentes</a></li>
				</ul>
			</div>
			<div class="col-xs-12 col-sm-4">
				<ul>
					<li><a href="<?= ROOT.'contacto' ?>">Contacto</a></li>
					<li><a href="https://www.mercadopago.com.ar/promociones" target="_blank">Promociones Bancarias</a></li>
					<li><a href="<?= ROOT.'publica-tu-centro' ?>">Publicá tu Centro</a></li>
					<li><a href="<?= ROOT.'terminos-condiciones' ?>">Términos y Condiciones</a></li>
				</ul>
			</div>
		</div>
	</div>
</footer>



<!-- COPYRIGHT -->
<section class="copyright-footer">
	<div class="container text-center">
		<p class="sz-9" >&copy; 2006 - <?= date('Y').' '.TITLE ?>. Todos los derechos reservados.</p>
	</div>
</section>



<!-- MESSAGES -->
<div class="modal fade" id="messages" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Info</h4>
			</div>
			<div class="modal-body ff-futuralight" ></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- LOADING -->
<div id="loading" >
	<div class="loading-wrapper" >
		<i class="fa fa-cog fa-spin fa-lg"></i>
		<p class="loading-text">Trabajando...</p>
	</div>
</div>

<!-- POP PHOTO -->
<div class="overprint-fixed dp-none" id="pop_photo">
	<div class="overprint-absolute op-90 bg-white"></div>
	<div class="pop-photo-container thumb-contain"></div>
	<div class="fa fa-times fa-2x hover-fucsia clickable"></div>
</div>

