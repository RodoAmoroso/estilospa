<section class="gral-section">
	<div class="container">	


		<div>
			<div>1. Resumen de Compras</div>
			<div>2. Mis Experiencias</div>
			<div>3. Mis GiftCards</div>
		</div>


		<div class="mb-4 d-none">GIFTCARDS COMPRADAS || MIS GIFTCARDS CANJEADAS || REGISTRO USO (MOVIMIENTO)</div>
		
		<div class="gift-balance d-nonee">
			<div class="icon">
				<i class="fa fa-money-bill-wave"></i>
			</div>
			<div class="content">
				<h3>Tu Balance:</h3>
				<h5>Usado: 50.000,00</h5>
				<h5>Disponible: 150.000,00</h5>
				<!-- <h5>Sin Reclamar: 100.000,00</h5> -->
			</div>

			<div class="actions">
				<a href="#" class="btn btn-circle btn-aqua-2">
					<i class="fa fa-list"></i>
					<span>Movimientos</span>
				</a>
			</div>
		</div>		

		<hr>

		<h4>GiftCards Compradas</h4>

		<?php if($giftcards_purchases): ?>
		
		<div class="table-responsive">
			<table class="table table-sm small table-bordered">
				<thead>
					<tr>
						<th>GiftCard</th>
						<th>Fecha de Compra</th>
						<th>Validez Hasta</th>
						<th>Modalidad</th>
						<th>Código</th>
						<th>Estado</th>
						<th>Valor</th>
						<th class="text-end">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($giftcards_purchases as $giftcard_purchase): ?>
					<tr>
						<td class="align-middle" >
							<?= $giftcard_purchase->giftcard ? $giftcard_purchase->giftcard->title : '[giftcard]' ?>
						</td>
						<td class="align-middle"><?= $giftcard_purchase->added ?></td>
						<td class="align-middle">
							<?php if($giftcard_purchase->assignment): ?>
							<span><?= $giftcard_purchase->assignment->expiration ?></span>
							<?php else: ?>
							<span class="badge text-bg-danger">sin activar</span>
							<?php endif; ?>
						</td>
						<td class="align-middle">
							????
						</td>
						<td class="align-middle">
							<code><?= $giftcard_purchase->code ?></code>
							<i data-toggle="copy-code" data-code="<?= $giftcard_purchase->code ?>" class="fal fa-copy fa-fw clickable text-danger" title="Copiar"></i>
						</td>
						<td class="align-middle">
							<?php if($giftcard_purchase->assignment): ?>
							<span class="badge text-bg-success">canjeado</span>
							<?php else: ?>
							<span class="badge text-bg-warning">sin canjear</span>
							<?php endif; ?>
						</td>
						<td class="align-middle">
							<?= $giftcard_purchase->value_formatted ?>
						</td>
						<td class="align-middle text-end">							

							<!-- <a href="<?= View::url('usuario','giftcard') ?>" class="btn btn-green-2 btn-sm">
								<i class="fal fa-memo-circle-info fa-fw"></i>
								<span>Ver Detalles</span>
							</a> -->

							<a href="<?= View::url('usuario','giftcard') ?>" class="btn btn-aqua-5 btn-sm">
								<i class="fal fa-gift fa-fw"></i>
								<span>Personalizar</span>
							</a>

							<!-- <button class="btn btn-aqua-5 btn-sm">
								<i class="fal fa-face-smile-relaxed fa-fw"></i>
								<span>Para Mi</span>
							</button> -->
							
						</td>
					</tr>
					<?php endforeach; ?>


				</tbody>
			</table>
		</div>

		<div>Podés enviar el código para regalarselo a alguien o podés canjearlo para vos desde <a href="<?= View::url('abrir-regalo') ?>">aquí</a></div>
		<hr>

		<div class="small-comment">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quisquam tempora reiciendis quaerat aperiam minus? Cupiditate amet, ratione excepturi delectus dicta id. Dolores asperiores pariatur omnis eum excepturi repudiandae quaerat consectetur. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis doloremque molestias optio repellendus consequuntur quisquam placeat dolor ipsam eveniet? Harum accusamus soluta, reprehenderit perferendis quam id corporis nesciunt minima temporibus.</div>
		
		<?php else: ?>

		<h4>No tenés giftcards compradas :(</h4>	

		<?php endif; ?>


	</div>
</section>
