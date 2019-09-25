
<section class="gral-section">
	<div class="container">
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
						<h1 class="title">Orden Nro.: <?= $sale->merchant_order_id ?></h1>
						<h2 class="subtitle">Precio Unit.: $ <?= number_format($sale->price,2,',','.') ?> | Cantidad: <?= $sale->quantity ?> | <span class="subtitle" >Total: $ <?= number_format($sale->price*$sale->quantity,2,',','.') ?></span></h2>
						
						<h2 class="title-promo"><?= $titlepromo ?></h2>
						
						<small class="date">Fecha de compra: <?= $sale->fecha ?> hs.</small> 

						<a href="<?=View::url('compra',$sale->id)?>" class="text-fucsia btn-xs "><i class="fa fa-download fa-fw"></i> Descargar Voucher</a>
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