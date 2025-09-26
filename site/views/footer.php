
<!-- GLOSARIO -->
<section class="glossary-list">
	<div class="container">
		<h3 class="section-title">Servicios y Tratamientos <a href="<?= ROOT.'etiquetas' ?>" class="sz-10">(ver todos)</a></h3>
		<div class="row">
			<?php
			$GlossaryGroups->limit = '0,6';
			$GlossaryGroups->keywords = '';
			if($GlossaryGroups->get()):
				foreach ($GlossaryGroups->data() as $group):
			?>
			<div class="col col-xs-12 col-sm-2">
				<h4 class="glossary-title"><?= $group->name ?></h4>
				<?php
				$Glossary->idgroup = $group->id;
				$Glossary->limit = '0,10';
				$Glossary->keywords = '';
				$Glossary->sort = '';
				if($Glossary->get()):
					foreach ($Glossary->data() as $glossary):
				?>
				<a href="<?= ROOT.'etiqueta/'.$glossary->id.'-'.Permalink($glossary->name) ?>" class="dp-block">
					<?= $glossary->name ?>
				</a>
				<?php endforeach; endif; ?>
			</div>
			<?php endforeach; endif; ?>
		</div>
	</div>
</section>



<!-- FOOTER -->
<footer class="cl-white">

	<div class="overprint-absolute bg" style="background-image:url(<?= View::assets('bg-6.jpg') ?>)" ></div>

	<?php if($_section!='hotsale'): ?>
	<div class="container mb-5 text-center">

		<h4 class="text-light">¡Subscribite y obtené importantes descuentos para tus compras!</h4>
		<h6 class="text-light">Recibí nuestras promociones exclusivas y enterate de todas las novedades del mundo del cuidado personal.</h6>

		<form id="form_newsletter" class="mt-3">
			<div class="row justify-content-center">
				<div class="col-12 col-lg-6">
					
					<div class="input-group">						
						<div class="form-floating">
							<input name="email" id="email_newsletter" type="email" class="form-control" placeholder="Email" required>
							<label for="email_newsletter">Email</label>
						</div>
						<button class="btn btn-aqua-2">
							<i class="fa fa-paper-plane fa-fw"></i> Suscribirme
						</button>
					</div>

				</div>				
			</div>
			<div class="status"></div>
		</form>

		<hr>

	</div>
	<?php endif; ?>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-4">

				<a href="<?= ROOT ?>">
					<img class="logo" src="<?= ROOT ?>assets/logo-white.png" alt="">
				</a>

				<div class="socials">
					<a href="http://www.facebook.com/estilospa" target="_blank" class="icon">
						<i class="fab fa-facebook-f"></i>
					</a>
					<a href="https://www.instagram.com/estilospa/" target="_blank" class="icon">
						<i class="fab fa-instagram"></i>
					</a>
					<a href="http://www.youtube.com/EstiloSpa" target="_blank" class="icon">
						<i class="fab fa-youtube"></i>
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


<div class="payment">
	<div class="container">
		<h6>Medios de Pago</h6>
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
	</div>
</div>

<!-- COPYRIGHT -->
<section class="copyright-footer">	

	<div class="container text-center">
		<p class="sz-9" >&copy; 2006 - <?= date('Y').' '.TITLE ?>.<br >Todos los derechos reservados.</p>
	</div>

	<a href="http://qr.afip.gob.ar/?qr=Hii2ReLLuZMJV76OkTZy1A,," target="_blank" class="afip">
		<img src="<?= ROOT ?>assets/dataweb.jpg" alt="">
	</a>
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



<!-- POPS -->
<div class="modal fade" id="popups" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body ff-futuralight" ></div>
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

<?php if($_section!='promo' && $_section!='centros'): ?>
<div class="float-whatsapp">
	<a href="https://api.whatsapp.com/send?phone=5491158314531" target="_blank" class="icon">
		<i class="fab fa-whatsapp"></i>
	</a>
</div>
<?php endif; ?>

