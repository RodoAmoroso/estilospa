
<script>var IDGlossary = <?= $_GLOSSARY->data()->id ?>;</script>


<section class="glossary-page bg-gray-5">
	
	<div class="container">
		
		<div class="section-header bg-aqua-5">
			<div class="bg overprint-absolute parallax" style="background-image:<?= empty($imgheader) ? 'none' : 'url('.ROOTPATH.$imgheader.')' ?>;"></div>
			<div class="container">
				<h1><?=  $_GLOSSARY->data()->name ?></h1>
				<h4><a href="<?= ROOTPATH.'etiquetas/'.$_GLOSSARY->data()->idgroup.'-'.Permalink($_GLOSSARY->data()->groupname) ?>"><?= $_GLOSSARY->data()->groupname ?></a></h4>
			</div>
		</div>

		<div class="page-content" data-collapse="false">
			<div class="content">
				<?= $_GLOSSARY->data()->description ?>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
			</div>
			<div class="view-more">
				
				<div class="row">
					<div class="col-sm-6">
						<button id="view_more" class="btn btn-default"><span>leer más</span> <i class="fa fa-caret-down"></i></button>
					</div>
					<div class="col-sm-6 text-right">
						<button class="btn btn-primary" data-toggle="modal" data-target="#modal_glossary_request"><i class="fa fa-envelope"></i> Consultar a los Centros Acerca de <?= $_GLOSSARY->data()->name ?></button>
					</div>
				</div>				
			</div>
		</div>


	</div>
</section>


<?php if($_PROMOS->get()): ?>
<section>
	<div class="title-bar">
		<div class="container">
			<h3 class="title"><i class="fa fa-shopping-bag"></i> Promos con: <?= $_GLOSSARY->data()->name ?></h3>
		</div>
	</div>

	<div class="container">

		<div class="promos-highlight">
		<?php 				
		foreach($_PROMOS->data() as $kp=>$promo):
			
			if($_CLIENTS->find($promo->idclient)):
			$imgpromo = json_decode($promo->gallery);
			$promolink = ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
			$_STORES->get($_CLIENTS->data()->id,$promo->stores);

			echo '<div class="mod-promo mod-promo-4">';
			include 'mods/mod-promo.php';
			echo '</div>';

			endif;
		endforeach;
		?>

		</div>
	</div>

</section>
<?php endif; ?>


<!-- MESSAGES -->
<div class="modal fade" id="modal_glossary_request" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Consultar a los Centros Acerca de <?= $_GLOSSARY->data()->name ?></h4>
			</div>
			<div class="modal-body ff-futuralight" >
				<form id="form_glossary_request">
					<div class="form-group">
						<label for="fd_name">Nombre y Apellido</label>
						<input id="fd_name" type="text" name="name" class="form-control" required value="<?php if($_USER->logged()) echo $_USER->data()->name ?>">
					</div>
					<div class="row">
						<div class="col-xs-12 col-xs-6">
							<div class="form-group">
								<label for="fd_phone">Teléfono</label>
								<input id="fd_phone" type="text" name="phone" class="form-control" required>
							</div>
						</div>
						<div class="col-xs-12 col-xs-6">
							<div class="form-group">
								<label for="fd_mail">E-mail</label>
								<input id="fd_mail" type="email" name="mail" class="form-control" required value="<?php if($_USER->logged()) echo $_USER->data()->mail ?>">
							</div>
						</div>
					</div>					
					<div class="form-group">
						<label for="fd_message">Consulta</label>
						<textarea id="fd_message" type="text" name="message" class="form-control" rows="5" required></textarea>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary" >ENVIAR</button>
					</div>
					<div class="status"></div>
				</form>
			</div>
		</div>
	</div>
</div>



<!-- CENTROS CON LA ETIQUETA -->
<?php 
$_CLIENTS->visible = 1;
$_CLIENTS->sort = 'rand';
$_CLIENTS->limit = '0,12';
$_CLIENTS->arrglossary = array($_GLOSSARY->data()->id);
if($_CLIENTS->get()): 
?>
<section class="bg-gray-5">
	<div class="title-bar">
		<div class="container">
			<h3 class="title"><i class="fa fa-leaf"></i> Centros con: <?= $_GLOSSARY->data()->name ?></h3>
		</div>
	</div>

	<div class="container">
		<div id="clients_carousel" class="clients-carousel dp-none">
		<?php 	
		foreach($_CLIENTS->data() as $client):
			$logo = json_decode($client->logo);
			$clientlink = ROOTPATH.'centros/'.$client->permalink;
			$_STORES->get($client->id);
			include 'mods/mod-client.php';
		endforeach;
		?>
		</div>

	</div>
</section>
<?php endif; ?>

<?php include 'mods/mod-socials.php' ?>