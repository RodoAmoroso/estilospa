
<section class="gral-section">
	<div class="container">


		<h3 class="fw-600 text-aqua-5">Detalles de tu compra</h3>


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

								<hr>

								<div class="form-group">
									<h4><strong>Total: $ <?=number_format($sale->price*$sale->quantity,0,',','.')?></strong></h4>
									<h5>Precio Unit.: $ <?= number_format($sale->price,2,',','.') ?> (x <?= $sale->quantity ?>)</h5>
								</div>

								<div class="form-group">
									<h4 class="title">Orden Nro.: <?=$sale->collection_id?></h4>
									<small class="date">Fecha de compra: <?=$sale->fecha ?> hs.</small>
								</div>

							</div>
						</div>
					</div>

					<div class="box-footer">

						<h4>Descargá tu voucher y presentalo en el centro.</h4>
						<p>&nbsp;</p>


						<?php if(!$vouchers || count($vouchers) < $sale->quantity): ?>

						<div class="form-group">Vouchers disponibles: <?= $vouchers ? $sale->quantity-count($vouchers) : $sale->quantity ?></div>

						<button data-toggle="generate-voucher" class="btn btn-sm btn-success"><i class="fa fa-plus fa-fw"></i> Generar Voucher</button>

						<?php endif; ?>


						<hr>

						<table class="table small table-bordered">
							<thead>
								<tr>
									<th>Vouchers Generados: <?= $vouchers ? count($vouchers) : 'ninguno' ?></th>
									<th>Acciones</th>
								</tr>
							</thead>

							<?php if($vouchers): foreach($vouchers as $voucher): ?>
							<tbody>
								<tr>
									<td>
										<a href="<?=View::url('compra-descarga-voucher',$voucher->id)?>" target="_blank" class="text-fucsia-3 btn-block"><i class="fa fa-download fa-fw"></i> Descargar Voucher <?=$voucher->gift ? ' - Para '.$voucher->to_user.' -' : '' ?> (<?= $sale->merchant_order_id.'-'.$voucher->id ?>)</a>
									</td>
									<td>
										<button data-toggle="edit-voucher" data-voucherid="<?= $voucher->id ?>" class="btn btn-xs btn-primary"><i class="fa fa-pencil fa-fw"></i> Editar</button>
									</td>
								</tr>
							</tbody>
							<?php endforeach; endif; ?>

						</table>

						<p>&nbsp;</p>

					</div>

				</div>

			</div>

		</div>


		<hr>

		<a href="<?=View::url('mis-compras')?>" class="btn btn-default btn-sm"> <i class="fa fa-angle-double-left fa-fw"></i> Volver</a>



	</div>

</section>


