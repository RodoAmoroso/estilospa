<!-- FOOTER -->
<footer class="cl-white">

	<div class="bg-aqua-4 overprint-absolute"></div>
	<div class="overprint-absolute bg" style="background-image:url(<?= ROOT ?>assets/bg-6.jpg)" ></div>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-4">

				<img class="logo" src="<?= ROOT ?>assets/logo-white.png" alt="">

				<div class="socials">
					<a href="http://www.facebook.com/estilospa" target="_blank" class="fa fa-stack fa-lg">
						<i class="fa fa-circle fa-stack-2x cl-aqua-5"></i>
						<i class="fa fa-facebook fa-stack-1x cl-white"></i>
					</a>
					<a href="http://www.twitter.com/estilospa" target="_blank" class="fa fa-stack fa-lg">
						<i class="fa fa-circle fa-stack-2x cl-aqua-5"></i>
						<i class="fa fa-twitter fa-stack-1x cl-white"></i>
					</a>
					<a  href="https://www.instagram.com/estilospa/" target="_blank" class="fa fa-stack fa-lg">
						<i class="fa fa-circle fa-stack-2x cl-aqua-5"></i>
						<i class="fa fa-instagram fa-stack-1x cl-white"></i>
					</a>
					<a  href="http://www.youtube.com/EstiloSpa" target="_blank" class="fa fa-stack fa-lg">
						<i class="fa fa-circle fa-stack-2x cl-aqua-5"></i>
						<i class="fa fa-youtube-play fa-stack-1x cl-white"></i>
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
<section class="bg-aqua-5 cl-white pad-16">
	<div class="container text-center">
		<p><img class="logo" src="<?= ROOT ?>assets/logo-white.png" alt="" style="max-width:240px;padding:26px"></p>
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
<div class="pop overprint-fixed dp-none" id="loading">
	<div class="overprint-absolute op-90 bg-white"></div>
	<div class="dp-table wd-100 hg-100" >
		<div class="dp-table-cell text-center">
			<i class="fa fa-cog fa-spin fa-lg"></i>
			<p class="loading-text"></p>
		</div>
	</div>
</div>


<?php require '../scripts.php'; ?>
<script type="text/javascript" src="<?= ROOT.'js/lib/ckeditor/ckeditor.js' ?>"></script>
<script type="text/javascript" src="<?= ROOT.'js/lib/ckeditor/adapters/jquery.js' ?>"></script>

<?php 
if(file_exists('js/'.$_section.'.js')):
	if(!is_null($_USER->data()->idclient)):
?>
<script type="text/javascript" src="<?= ROOT.'cuenta/js/'.$_section.'.js' ?>"></script>
<?php endif; endif; ?>

</body>
</html>