
<section class="gral-section">
	<div class="container">

		<div class="card shadow-lg border-0 d-none" style="border-radius: 15px; background: linear-gradient(135deg,#E1D6C4 0%,#AA9568 100%);margin-bottom:2rem">
			<div class="card-body text-center" style="padding:3rem">
				<div class="mb-4">
					<i class="fa fa-heart text-fucsia-4" style="font-size: 3rem;"></i>
				</div>

				<h2 class="card-title fw-bold text-dark mb-3">
					<b>Ritual de Bienestar para Madres</b>
				</h2>

				<p class="lead text-fucsia-4 fw-semibold mb-3">
					<strong>Ser mamá es darlo todo, cada día.</strong>
				</p>

				<p class="text-light mb-3">
					Sabemos que a veces necesitás frenar... y volver a respirar vos. <br ><strong>Mini pausas simples pero poderosas</strong> para recargar energías y conectar con lo importante.
				</p>

				<h4 class="mb-4" style="color:#47142C">
					<b>Tarjetas imprimibles con rituales de bienestar pensados especialmente para madres.</b>	<br> <i>Porque cuando vos estás bien, todo a tu alrededor florece.</i>
				</h4>

				<br>

				<a data-toggle="download-ritual" data-href="<?= View::assets('rituales-mama.pdf') ?>" class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-bold mb-3">
					<i class="fa fa-download fa-fw"></i>
					DESCARGÁ TU RITUAL GRATUITO
				</a>

			
			</div>
		</div>		



		<h1>Mis Compras</h1>
		<hr>

		<div id="sales">
			<?php
			if($sale_data):
				foreach($sale_data as $key=>$sale):
					if(!is_null($sale->title)){
						$img = json_decode($sale->gallery);
						$imgpromo = ROOT.'img/promos/'.$img[0]->photoname.'-t.'.$img[0]->extension;
						$titlepromo = '<a href="'.ROOT.'promo/'.$sale->permalink.'/'.$sale->idpromo.'-'.Permalink($sale->title).'">'.$sale->title.'</a> / <a href="'.ROOT.'centros/'.$sale->permalink.'">'.$sale->clientname.'</a>';
					}else{
						$imgpromo = 'none';
						$titlepromo = 'La Promo fue borrada.';
					}
			?>
			<div data-id="<?= $sale->id ?>" class="mod-sales">
				<div class="sale-header">
					<div class="thumb-container">
						<div class="thumb thumb-cover" style="background-image:url(<?= $imgpromo ?>)"></div>
					</div>
					<div class="caption">
						<h1 class="title">Orden Nro.: <?= $sale->collection_id ?></h1>
						<h2 class="subtitle">Precio Unit.: $ <?= number_format($sale->price,2,',','.') ?> | Cantidad: <?= $sale->quantity ?> | <span class="subtitle" >Total: $ <?= number_format($sale->price*$sale->quantity,2,',','.') ?></span></h2>

						<h2 class="title-promo"><?= $titlepromo ?></h2>

						<small class="date">Fecha de compra: <?= $sale->fecha ?> hs.</small>

						<?php if($sale->collection_status=='approved' && $sale->status!=3): ?>
						<a href="<?=View::url('compra',$sale->id)?>" class="text-fucsia btn-xs "><i class="fa fa-download fa-fw"></i> Descargar Voucher</a>
						<?php endif; ?>

						<hr>

						<?php
						switch ($sale->status) {
							case 1:
								$label = 'warning';
								break;
							case 2:
								$label = 'success';
								break;
							case 3:
								$label = 'danger';
								break;
						}
						$status_payment = status_payment($sale->collection_status);
						?>
						<div class="status alert-<?=$status_payment->label?>">
							<div class="payment-status"><?=$status_payment->text?></div>
							<div class="sale-actions">
								<small>Estado: </small>
								<label class="label label-<?= $label ?>"><?= $sale->statusname ?></label>
							</div>
						</div>



					</div>
				</div>

				<div class="sale-footer sale-user-footer">
					<div class="sale-user">
						<div class="feedback">

						<?php
						$comment = $Sales->get_comment($sale->id,$_userdata->id);
						if(!$comment): ?>
						<p >Todavía no calificaste <a href="<?= ROOT.'calificar/'.$sale->id ?>" class="btn btn-sm btn-success"><i class="fa fa-thumbs-up"></i> Calificar</a></p>
						<?php else: ?>
						<p><?= nl2br(htmlspecialchars($comment->text,ENT_QUOTES,'utf-8')) ?></p>
						<div class="stars">
							<?php for($i=1; $i<=$comment->rate; $i++): ?>
							<i class="fa fa-star"></i>
							<?php endfor; ?>
							<?php for($i=5; $i>$comment->rate; $i--): ?>
							<i class="fa fa-star-o"></i>
							<?php endfor; ?>
						</div>
						<?php endif; ?>


						</div>
					</div>
				</div>



			</div>
			<?php
				endforeach;
			else:
			?>
			<p>Todavía no realizaste ninguna compra :(</p>
			<?php endif; ?>
		</div>

	</div>
</section>