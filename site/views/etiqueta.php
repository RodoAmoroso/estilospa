
<script>var IDGlossary = <?= $Glossary->data()->id ?>;</script>


<section class="glossary-page bg-gray-5">

	<div class="container">

		<div class="section-header bg-aqua-5">
			<div class="bg overprint-absolute parallax" style="background-image:<?= empty($imgheader) ? 'none' : 'url('.ROOT.$imgheader.')' ?>;"></div>
			<div class="container">
				<h1><?=  $Glossary->data()->name ?></h1>
				<h4><a href="<?= View::url('etiquetas#grupo_'.$Glossary->data()->idgroup) ?>"><?= $Glossary->data()->groupname ?></a></h4>
			</div>
		</div>

		<div class="page-content" data-collapse="false">
			<div class="content">
				<?= $Glossary->data()->description ?>
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
						<button class="btn btn-primary" data-toggle="scrollto" data-target="#form_question"><i class="fa fa-envelope"></i> Consultar a los Centros Acerca de <?= $Glossary->data()->name ?></button>
					</div>
				</div>
			</div>
		</div>



		<!-- QUESTIONS -->
		<?php
		$question_rowid = $Glossary->data()->id;
		$question_type = 'glossary';
		$show_responses = $_userdata && $_userdata->idtype == 1 ? true : false;
		include 'questions.php';
		?>


	</div>
</section>



<?php $Promos->get(); if($Promos->data()): ?>
<section>
	<div class="title-bar">
		<div class="container">
			<h3 class="title"><i class="fa fa-shopping-bag"></i> Promos con: <?= $Glossary->data()->name ?></h3>
		</div>
	</div>

	<div class="container">

		<div class="promos-highlight">
		<?php
		foreach($Promos->data() as $kp=>$promo):

			if($Clients->find($promo->idclient)):
			$imgpromo = json_decode($promo->gallery);
			$promolink = ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
			$Stores->get($Clients->data()->id,$promo->stores);

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





<!-- LOGIN OR REGISTER -->
<div class="modal fade" id="modal_not_logged" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Usuario no registrado</h4>
			</div>
			<div class="modal-body ff-futuralight" >
				<p>Para poder usar esta función tenés que ingresar como usuario Registrado.</p>
				<a class="btn btn-primary" href="<?= ROOT.'login#'.View::url($_section,$_subsection) ?>">Login</a>
				<hr>
				<p>Todavía no te registraste???</p>
				<a class="btn btn-primary" href="<?= ROOT.'registro' ?>">Registro</a>
			</div>
		</div>
	</div>
</div>


<!-- CENTROS CON LA ETIQUETA -->
<?php
$Clients->visible = 1;
$Clients->sort = 'rand';
$Clients->limit = '0,12';
$Clients->arrglossary = array($Glossary->data()->id);
$Clients->get();
if($Clients->data()):
?>
<section class="bg-gray-5">
	<div class="title-bar">
		<div class="container">
			<h3 class="title"><i class="fa fa-leaf"></i> Centros con: <?= $Glossary->data()->name ?></h3>
		</div>
	</div>

	<div class="container">
		<div id="clients_carousel" class="clients-carousel dp-none">
		<?php
		foreach($Clients->data() as $client):
			$logo = json_decode($client->logo);
			$clientlink = ROOT.'centros/'.$client->permalink;
			$Stores->get($client->id);
			include 'mods/mod-client.php';
		endforeach;
		?>
		</div>

	</div>
</section>
<?php endif; ?>

<?php include 'mods/mod-socials.php' ?>