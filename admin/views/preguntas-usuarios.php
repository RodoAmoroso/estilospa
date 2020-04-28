
<section class="page-header">
	<div class="container">
		<h1>Preguntas x Usuario</h1>
		<hr>
		<p>Visualiza todas las preguntas realizada por cada usuario.</p>

	</div>
</section>


<section class="admin-box bg-gray-5">
	<div class="container">

		<div class="block-white">

			<div class="row">
				<div class="col-lg-4">
					<div class="form-group">
						<label for="">Centros</label>
						<select name="clients" class="form-control">
							<option value="">--Todos los Centros--</option>
							<?php if($clients): foreach($clients as $client): ?>
							<option value="<?=$client->id?>"><?=$client->name?></option>
							<?php endforeach; endif; ?>
						</select>
					</div>
				</div>
			</div>

			<hr>

			<?php if($users): foreach($users as $user): ?>

			<div class="users-questions">
				<div class="uq-wrapper">
					<div class="uq-header">
						<div class="title"><?=$user->fullname?></div>
						<div class="subtitle"><a href="mailto:<?=$user->mail?>"><i class="fa fa-envelope fa-fw"></i> <?=$user->mail?></a> <?php if($user->phone): ?> | <i class="fa fa-phone fa-fw"></i> <?=$user->phone?> <?php endif; ?></div>
						<small>Usuario desde: <?=date('d/m/Y',strtotime($user->created))?> | Último acceso: <?=date('d/m/Y H:i',strtotime($user->logged))?> hs.</small>

						<div class="arrow" data-toggle="collapse" data-target="#mod_user_<?=$user->id?>">
							<i class="fa fa-angle-down"></i>
						</div>
					</div>

					<?php if($user->promos_questions): ?>
					<div id="mod_user_<?=$user->id?>" class="uq-body-wrapper collapse in">
					<?php foreach($user->promos_questions as $promo): ?>
					<div class="uq-body">
						<div class="title-bar">
							<div class="title"><a href="<?=ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-preview' ?>" target="_blank"><?=$promo->title?></a></div>
							<div class="subtitle"><a href="<?=ROOT.'centros/'.$promo->permalink ?>" target="_blank"><?=$promo->client_name?></a></div>
						</div>
						<div class="uq-content">


							<div class="questions">
								<div class="title">Preguntas</div>

								<?php if($promo->questions): foreach($promo->questions as $question): ?>
								<div class="questions-wrapper">
									<div class="date"><?=date('d/m/Y',strtotime($question->added))?></div>
									<div class="question small"><?=$question->message?></div>
									<div class="status">
										<?php if(!is_null($question->response)): ?>
										<span class="label label-success">respondido</span>
										<?php else: ?>
										<span class="label label-danger">sin responder</span>
										<?php endif; ?>
									</div>
								</div>
								<?php endforeach; endif; ?>

							</div>


							<div class="sales">
								<div class="title">Compras</div>

								<?php
								if($promo->sales):
									foreach($promo->sales as $sale):

										$discountvoucher = 0;

										if(!is_null($sale->voucher_id)){
											if($sale->voucher_percent==1){
												$discountvoucher = $salevoucher_value*$sale->price/100;
											}else{
												$discountvoucher = $salevoucher_value;
											}
										}
								?>
								<div class="sales-wrapper">
									<div class="date"><?=date('d/m/Y',strtotime($sale->added))?></div>
									<div class="sale">
										<a href="<?=ADMIN.'venta/'.$sale->id ?>" target="_blank" class="order">Orden nro: <?=$sale->collection_id?> | $ <?= number_format(($sale->price-$discountvoucher)*$sale->quantity,2,',','.') ?></a>
										<div class="info">Precio Unit.: <?= number_format(($sale->price-$discountvoucher),2,',','.') ?> | Cant.: <?=$sale->quantity?></div>
									</div>
								</div>
								<?php endforeach; endif; ?>

							</div>

						</div>
					</div>
					<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>

			<?php endforeach; else: ?>
			<p class="alert alert-info">No se encontraron usuarios que hayan hecho preguntas :(</p>
			<?php endif; ?>

		</div>

	</div>
</section>