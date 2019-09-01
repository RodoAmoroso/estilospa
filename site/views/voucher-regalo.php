

<section class="gral-section">
	<div class="container">

		
		<h3 class="fw-600 text-aqua-5">Descargar Voucher</h3>
		
		<div class="boxes">
			<div class="box-wrapper">
				<div class="box">
					<div class="box-content">
						<div class="row">
							<div class="col-md-4">
								<div class="thumb thumb-cover" style="background-image:url(<?=$promo->image?>);height:200px"></div>	
							</div>
							<div class="col-md-8">
								<h3 class="title-promo"><a href="<?=View::url('promo',$promo->permalink,$promo->id.'-'.Permalink($promo->title))?>" target="_blank"><?=$promo->title?></a></h3>
								<div class="form-group">
									<h5><a href="<?=View::url('centros',$promo->permalink)?>" target="_blank"><?=$promo->clientname?></a></h5>
								</div>

								<div class="form-group">
									<h4>Precio Unit.: $ <?= number_format($sale->price,2,',','.') ?> | Cantidad: <?= $sale->quantity ?> | <strong>Total: $ <?=number_format($sale->price*$sale->quantity,0,',','.')?></strong></h4>
									<h5 class="title">Orden Nro.: <?=$sale->collection_id?></h5>
								</div>
								<hr>

								<h5>Esta experiencia es para: <strong><?=$gift->to_user->name.' '.$gift->to_user->lastname?></strong></h5>

								<p><a href="<?=View::url('voucher-descarga',$gift->hash)?>" class="btn btn-fucsia btn-sm"><i class="fa fa-download fa-fw"></i> Descargar Voucher</a></p>
								
							</div>
						</div>
					</div>

					<div class="box-footer">						
						<small class="date">Fecha de compra: <?=$gift->added ?> hs.</small>						
					</div>

				</div>

			</div>

		</div>

		<small>Descargá el voucher de regalo y enviáselo a <strong><?=$gift->to_user->name.' '.$gift->to_user->lastname.' ('.$gift->to_user->mail.')'?></strong> para que lo pueda usar en el centro.</small>

	
	</div>

</section>