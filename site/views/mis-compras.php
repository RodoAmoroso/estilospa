
<section class="gral-section">
	<div class="container">
		<h1>Mis Compras</h1>
		<hr>

		<div id="sales" class="well">
			<?php 
			$Sales->get();
			if(count($Sales->data())):
				foreach($Sales->data() as $key=>$sale):
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
						<h1 data-tag="ordernumber" class="sz-16 fw-400">Orden Nro.: <?= $sale->merchant_order_id ?></h1>
						<h2 data-tag="price" class="sz-14">Precio Unit.: $ <?= number_format($sale->price,2,',','.') ?> | Cantidad: <?= $sale->quantity ?> | <span class="fw-400" >Total: $ <?= number_format($sale->price*$sale->quantity,2,',','.') ?></span></h2>
						
						<h2 data-tag="title" class="sz-11"><?= $titlepromo ?></h2>
						
						<small data-tag="date" class="cl-gray-40">Fecha de compra: <?= $sale->fecha ?> hs.</small>
					
						<div class="sale-actions">
							<small>Estado: </small>
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
							?>
							<label class="label label-<?= $label ?>"><?= $sale->statusname ?></label>
						</div>
					</div>
				</div>
				<div class="sale-footer sale-user-footer">
					<div class="sale-user">
						<div class="feedback" >
							<?php if(is_null($sale->text)): ?>
							<p data-tag="comment" >Todavía no has calificado </p>
							<p><a href="<?= ROOT.'calificar/'.$sale->id ?>" class="btn btn-sm btn-success"><i class="fa fa-thumbs-up"></i> Calificar</a></p>
							<?php else: ?>
							<p data-tag="comment" ><?= nl2br(htmlspecialchars($sale->text,ENT_QUOTES,'utf-8')) ?></p>
							<div class="stars">
								<?php for($i=1; $i<=$sale->rate; $i++): ?>
								<i class="fa fa-star"></i>
								<?php endfor; ?>
								<?php for($i=5; $i>$sale->rate; $i--): ?>
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