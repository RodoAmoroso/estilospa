
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

		
						<div class="row">

							<?php if(!$vouchers || count($vouchers) < $sale->quantity): ?>
							<div class="col-md-6">

								<div class="form-group">Vouchers disponibles: <?= $vouchers ? $sale->quantity-count($vouchers) : $sale->quantity ?></div>

								
								<form id="form_voucher" action="">

									<input type="hidden" name="saleid" value="<?=$sale->id?>">


									<div class="radio-group">
										<label class="radio-item">
											<input value="0" type="radio" name="gift" checked >
											<i class="fa fa-check-square-o fa-fw radio-icon"></i>
											<span class="radio-label">Para mí</span>
										</label>
										<label class="radio-item">
											<input value="1" type="radio" name="gift" >
											<i class="fa fa-square-o fa-fw radio-icon"></i>
											<span class="radio-label">Para regalar</span>
										</label>
									</div>
								

									<div id="fieldset_gift" class="dp-none">
									
										<div class="form-group">
											<label for="">Para:</label>
											<input name="to_user" type="text" class="form-control" placeholder="para quien?">
										</div>
										<div class="form-group">
											<label for="">Mensaje:</label>
											<textarea name="message" rows="4" class="form-control" placeholder="Tu mensaje..." maxlength="255"></textarea>
										</div>

										<div class="form-group">
											<label for="">Imagen (Opcional)</label>

											<div data-input="image" class="form-group">
												<button id="btn_image" class="btn btn-xs btn-default" type="button">Examinar...</button>
												<input type="file" accept="image/*" class="d-none" >
											</div>
											<div id="image" class="thumb-150x150 thumb-cover border-gray-10"></div>
										</div>
									</div>

									<button class="btn btn-success btn-sm"><i class="fa fa-plus-square fa-fw"></i> Generar Voucher</button>


								</form>
								

							</div>

							<?php endif; ?>


							<div class="col-md-6">
								<div class="form-group">Vouchers Generados: <?= $vouchers ? count($vouchers) : 'ninguno' ?></div>
								<?php if($vouchers): foreach($vouchers as $voucher): ?>
								<a href="<?=View::url('compra-descarga-voucher',$voucher->id)?>" target="_blank" class="text-fucsia-3 btn-block"><i class="fa fa-download fa-fw"></i> Descargar Voucher <?=$voucher->gift ? ' - Para '.$voucher->to_user.' -' : '' ?> (<?= $sale->merchant_order_id.'-'.$voucher->id ?>)</a>
								<?php endforeach; endif; ?>								
							</div>
						</div>

						<p>&nbsp;</p>

					</div>
				
				</div>

			</div>

		</div>


		<hr>

		<a href="<?=View::url('mis-compras')?>" class="btn btn-default btn-sm"> <i class="fa fa-angle-double-left fa-fw"></i> Volver</a>



	</div>

</section>


