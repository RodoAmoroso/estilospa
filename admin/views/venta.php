<section class="page-header">
	<div class="container">
		<h1>Mis Ventas</h1>
		<h4>Orden nro: <?=$_sale->collection_id?> </h4>

	</div>
</section>

<section class="gral-section">
	<div class="container">
		
		<div class="sale-container">
			
			<div class="mod-sales" >
				<div class="sale-header">
					<div class="thumb-container">
						<div class="thumb thumb-cover" style="background-image: url(<?= $image ?>);"></div>
					</div>
					<div class="caption">
						<h4 class="title-promo"><a href="<?=ROOT.'promo/'.$_sale->permalink.'/'.$_sale->idpromo.'-'.Permalink($_sale->title) ?>" target="_blank" ><?=$_sale->title?></a></h4>
						<h1 class="title">Orden Nro.: <?=$_sale->collection_id?></h1>
						<h2 class="subtitle">Precio Unit.: $ <?=number_format($_sale->price,2,',','.')?> | Cant.: <?=$_sale->quantity?> | <span class="fw-400">Total: $ <?=number_format($_sale->price*$_sale->quantity,2,',','.')?></span></h2>
	
						<small class="date">Comisión EstiloSPA.com: $ <?=number_format($_sale->application_fee,2,',','.')?> | Comisión MercadoPago: $<?=number_format($_sale->mercadopago_fee,2,',','.')?>  | Fecha de compra: <?=$_sale->fecha?> hs.</small>
						

						<?php 
						$collection_status = new stdClass();
						switch($_sale->collection_status){
							case 'in_process':
								$collection_status->label = 'warning';
								$collection_status->text = 'El pago está siendo revisado';
								break;
							case 'rejected':
								$collection_status->label = 'danger';
								$collection_status->text = 'El pago fué rechazado, el usuario puede intentar nuevamente el pago';
								break;
							case 'approved':
								$collection_status->label = 'success';
								$collection_status->text = 'El pago fue aprobado y acreditado';
								break;
							case 'pending':
								$collection_status->label = 'warning';
								$collection_status->text = 'El usuario no completó el pago';
								break;
							default:
								$collection_status->label = 'danger';
								$collection_status->text = 'El usuario no completó el proceso de pago y no se ha generado ningún pago';
								break;
						}
						?>

						<div class="status alert-<?=$collection_status->label?>">
							<div class="payment-status"><?=$collection_status->text?></div>
							<div class="sale-actions">
								<small>Servicio: </small>
								<div data-group="status" class="btn-group" data-id="<?=$_sale->id?>">

									<?php 
									$status = new stdClass();
									switch($_sale->status){
										case '1':
											$status->btn = 'warning';
											$status->label = 'Pendiente';
											break;
										case '2':
											$status->btn = 'success';
											$status->label = 'Brindado';
											break;
										case '3':
											$status->btn = 'danger';
											$status->label = 'Cancelado';
											break;
									}
									?>

									<button type="button" class="btn btn-xs dropdown-toggle btn-<?=$status->btn?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<span data-tag="status" ><?=$status->label?></span> <span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										<li><a data-value="1" href="#">Pendiente</a></li>
										<li><a data-value="2" href="#">Brindado</a></li>
										<li><a data-value="3" href="#">Cancelado</a></li>
									</ul>
								</div>

							</div>
						</div>

						
					</div>
				</div>

				<div class="sale-footer sale-user-footer">
					<div class="sale-user">
						<div class="user">
							<div class="item">
								<div class="user-thumb thumb-cover" style="background-image: url(<?=View::img('users',$_userimage)?>);"></div>
							</div>
							<div class="item">
								<div data-tag="username" class="name"><?=$_user->fullname?></div>
								<a data-tag="mail" href="#" class="mail"><?=$_user->mail?></a>
							</div>
						</div>
						
						<?php if(!is_null($_sale->text)): ?>
						<div class="feedback">
							<p data-tag="comment"><?=$_sale->text?></p>
							<div class="stars">
								<?php for($i=1; $i<=$_sale->rate; $i++): ?>
								<i class="fa fa-star"></i>
								<?php endfor; ?>
								<?php for($i=5; $i>$_sale->rate; $i--): ?>
								<i class="fa fa-star-o"></i>
								<?php endfor; ?>
							</div>										
						</div>
						<?php else: ?>
						<div class="feedback">
							<p>El usuario todavía no ha calificado</p>
						</div>
						<?php endif; ?>

					</div>		
				</div>

			</div>


		</div>

	</div>
</section>