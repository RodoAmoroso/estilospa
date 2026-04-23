<section class="gral-section">
	<div class="container">

		<h1>Mis Compras</h1>
		<hr>

		<div class="table-responsive">
			<table class="table small">
				<thead>
					<tr>
						<th>Nro. de Orden</th>
						<th>Fecha</th>
						<th>Tipo</th>
						<th>Estado</th>
						<th>Total</th>
						<th class="text-end">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php if($sales): foreach($sales as $sale): ?>
					<tr>
						<td><?= $sale->order_number ?: '---' ?></td>
						<td><?= $sale->added ?></td>
						<td>
							<i class="fa <?= $sale->type == 'promo' ? 'fa-spa' : 'fa-gift' ?> fa-fw text-aqua-2" ></i>
							<span><?= $sale->type == 'promo' ? 'Experiencia' : 'GiftCard' ?></span>
						</td>
						<td>
							<span class="badge text-bg-<?= status_payment($sale->payment_status)->label ?>"><?= status_payment($sale->payment_status)->text ?></span>
						</td>
						<td>$ <?= number_format($sale->total,0,',','.') ?></td>
						<td class="text-end">
							<a href="<?= View::url('usuario/'.($sale->type=='promo'?'compra-experiencia':'compra-giftcard'),$sale->id) ?>" class="btn btn-white btn-sm">
								<i class="fal fa-external-link-square fa-fw"></i>
								<span>Ver Detalles</span>
							</a>
						</td>
					</tr>
					<?php endforeach; else: ?>
					<tr>
						<td colspan="6" class="text-center">No tenés compras realizadas.</td>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>