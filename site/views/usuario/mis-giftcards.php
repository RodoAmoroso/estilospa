<section class="gral-section">
	<div class="container">	
		
		<div class="gift-balance">
			<div class="icon">
				<i class="fa fa-money-bill-wave"></i>
			</div>
			<div class="content">
				<h3>Tu Balance:</h3>
				<h5>Usado: 50.000,00</h5>
				<h5>Disponible: 150.000,00</h5>
				<h5>Sin Reclamar: 100.000,00</h5>
			</div>

			<div class="actions">
				<a href="#" class="btn btn-circle btn-aqua-2">
					<i class="fa fa-list"></i>
					<span>Movimientos</span>
				</a>
			</div>
		</div>		

		<hr>
		<code><?= generate_code() ?></code>
		<h4>GiftCards Comprados</h4>
		
		<div class="table-responsive">
			<table class="table table-sm small table-bordered">
				<thead>
					<tr>
						<th>GiftCard</th>
						<th>Fecha de Compra</th>
						<th>Validez</th>
						<th>Modalidad</th>
						<th>Código</th>
						<th>Estado</th>
						<th>Valor</th>
						<th class="text-end">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="align-middle" >
							<div class="title">Test</div>
							<!-- <div class="border rounded thumb-cover">
								<img src="<?= View::assets('blank-square.gif') ?>" alt="" class="w-100">
							</div> -->
							
						</td>
						<td class="align-middle">00/00/0000</td>
						<td class="align-middle">
							<span class="badge text-bg-danger">sin activar</span>
						</td>
						<td class="align-middle">						

							<a href="<?= View::url('usuario','giftcard') ?>" class="btn btn-aqua-5 btn-sm">
								<i class="fal fa-gift fa-fw"></i>
								<span>Para Regalar</span>
							</a>

							<button class="btn btn-aqua-5 btn-sm">
								<i class="fal fa-face-smile-relaxed fa-fw"></i>
								<span>Para Mi</span>
							</button>

							
						</td>
						<td class="align-middle">--</td>
						<td class="align-middle">
							<span class="badge text-bg-warning">sin canjear</span>
						</td>
						<td class="align-middle">
							<div>$ 100.000</div>
						</td>
						<td class="align-middle text-end">							

							<a href="<?= View::url('usuario','giftcard') ?>" class="btn btn-green-2 btn-sm">
								<i class="fal fa-memo-circle-info fa-fw"></i>
								<span>Ver Detalles</span>
							</a>
							
						</td>
					</tr>

					<tr class="d-nonew"></tr>
						<td class="align-middle" >
							<div class="title">Test</div>
							<!-- <div class="border rounded thumb-cover">
								<img src="<?= View::assets('blank-square.gif') ?>" alt="" class="w-100">
							</div> -->
						</td>
						<td class="align-middle">00/00/0000</td>
						<td class="align-middle">00/00/0000</td>
						<td class="align-middle">Para Mi</td>
						<td class="align-middle">ABC123456</td>
						<td class="align-middle">
							<span class="badge text-bg-success">activo</span>
						</td>
						<td class="align-middle">
							<div>$ 100.000,00</div>
							<div class="small">Usado: $ 0,00</div>
							<div class="small">Restan: $ 100.000,00</div>
						</td>
						<td class="align-middle text-end">
							<a href="<?= View::url('usuario','giftcard') ?>" class="btn btn-green-2 btn-sm">
								<i class="fal fa-memo-circle-info fa-fw"></i>
								<span>Ver Detalles</span>
							</a>
						</td>
					</tr>

					<tr class="d-nonew">
						<td class="align-middle" >
							<div class="title">Test</div>
							<!-- <div class="border rounded thumb-cover">
								<img src="<?= View::assets('blank-square.gif') ?>" alt="" class="w-100">
							</div> -->
						</td>
						<td class="align-middle">00/00/0000</td>
						<td class="align-middle">00/00/0000</td>
						<td class="align-middle">Para Mi</td>
						<td class="align-middle">ABC123456</td>
						<td class="align-middle">
							<span class="badge text-bg-success">activo</span>
						</td>
						<td class="align-middle">
							<div>$ 100.000,00</div>
							<div class="small">Usado: $ 50.000,00</div>
							<div class="small">Restan: $ 50.000,00</div>
						</td>
						<td class="align-middle text-end">
							<a href="<?= View::url('usuario','giftcard') ?>" class="btn btn-green-2 btn-sm">
								<i class="fal fa-memo-circle-info fa-fw"></i>
								<span>Ver Detalles</span>
							</a>
						</td>
					</tr>


					<tr class="d-nonew">
						<td class="align-middle" >
							<div class="title">Test</div>
							<!-- <div class="border rounded thumb-cover">
								<img src="<?= View::assets('blank-square.gif') ?>" alt="" class="w-100">
							</div> -->
						</td>
						<td class="align-middle">00/00/0000</td>
						<td class="align-middle">00/00/0000</td>
						<td class="align-middle">Para Mi</td>
						<td class="align-middle">ABC123456</td>
						<td class="align-middle">
							<span class="badge text-bg-danger">vencido</span>
						</td>
						<td class="align-middle">
							<div>$ 100.000,00</div>
							<div class="small">Sin usar: $ 50.000,00</div>
							<div class="small">Usados: $ 50.000,00</div>
						</td>
						<td class="align-middle text-end">
							<a href="<?= View::url('usuario','giftcard') ?>" class="btn btn-green-2 btn-sm">
								<i class="fal fa-memo-circle-info fa-fw"></i>
								<span>Ver Detalles</span>
							</a>
						</td>
					</tr>


					<tr class="d-nonew">
						<td class="align-middle" >
							<div class="title">Otra</div>
							<!-- <div class="border rounded thumb-cover">
								<img src="<?= View::assets('blank-square.gif') ?>" alt="" class="w-100">
							</div> -->
						</td>
						<td class="align-middle">00/00/0000</td>
						<td class="align-middle">00/00/0000</td>
						<td class="align-middle">Regalado a: XXX XXXX</td>
						<td class="align-middle">ABC123456</td>
						<td class="align-middle">							
							<span class="badge text-bg-warning">
								Sin Canjear
							</span>
						</td>
						<td class="align-middle">
							<div>$ 100.000,00</div>
							<div class="small">Sin usar: $ 100.000,00</div>
						</td>
						<td class="align-middle text-end">
							<a href="<?= View::url('usuario','giftcard') ?>" class="btn btn-aqua-5 btn-sm">
								<i class="fal fa-pencil fa-fw"></i>
								<span>Editar GiftCard</span>
							</a>
							<a href="<?= View::url('usuario','giftcard') ?>" class="btn btn-green-2 btn-sm">
								<i class="fal fa-memo-circle-info fa-fw"></i>
								<span>Ver Detalles</span>
							</a>
						</td>
					</tr>



					<tr class="alert-warning d-nonew">
						<td class="align-middle" >
							<div class="title">Otra</div>
							<!-- <div class="border rounded thumb-cover">
								<img src="<?= View::assets('blank-square.gif') ?>" alt="" class="w-100">
							</div> -->
						</td>
						<td class="align-middle">00/00/0000</td>
						<td class="align-middle">00/00/0000</td>
						<td class="align-middle">Me lo regaló: xxxx xxxx</td>
						<td class="align-middle">ABC123456</td>
						<td class="align-middle">							
							<span class="badge text-bg-success">
								activo
							</span>
						</td>
						<td class="align-middle">
							<div>$ 100.000,00</div>
							<div class="small">Sin usar: $ 100.000,00</div>
						</td>
						<td class="align-middle text-end">						
							<a href="<?= View::url('usuario','giftcard') ?>" class="btn btn-aqua-5 btn-sm">
								<i class="fal fa-download fa-fw"></i>
								<span>Descargar GiftCard</span>
							</a>	
							<a href="<?= View::url('usuario','giftcard') ?>" class="btn btn-green-2 btn-sm">
								<i class="fal fa-memo-circle-info fa-fw"></i>
								<span>Ver Detalles</span>
							</a>
						</td>
					</tr>

				</tbody>
			</table>
		</div>

		


	</div>
</section>
