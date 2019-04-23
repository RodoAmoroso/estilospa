
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>Vinculaciones con MercadoPago</h1>
		<p>A continuación se muestran los centros que han vinculado su cuenta de MercadoPago con la cuenta de EstiloSPA</p>

	</div>
</section>


<!-- LIST -->
<section id="block_list" class="admin-box bg-gray-5">
	<div class="container">

		<div class="block-white">

			<div id="vinculaciones" class="well mod-container-lg">

				<?php if($_mp): foreach($_mp as $mp): ?>
				<div class="mod-list">
					<h4><a href="<?= ROOT.'centros/'.$mp->permalink ?>" target="_blank" ><?= $mp->name ?></a></h4>
					<p>Vinculado el <?= $mp->creado ?> &bullet; Fecha de expiración: <?= $mp->expira ?></p>
					<div class="buttons">
						<button class="btn btn-xs btn-danger unlink" data-id="<?= $mp->idclient ?>" ><i class="fa fa-unlink fa-fw"></i> Desvincular</button>
						<button class="btn btn-xs btn-success renew" data-id="<?= $mp->idclient ?>" ><i class="fa fa-refresh fa-fw"></i> Renovar Token</button>
					</div>
				</div>
				<?php endforeach; endif; ?>

			</div>


		</div>


	</div>
</section>