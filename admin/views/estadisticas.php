
<section class="page-header">
	<div class="container">
		<h1>Estadísticas</h1>

	</div>
</section>



<section class="admin-box bg-gray-5">
	<div class="container">

		<div class="block-white">

			<div class="row">

				<div class="col-md-6">
					<h4>Plabaras más buscadas</h4>
					<?php if($top_words): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Palabra</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($top_words as $word): ?>
								<tr>
									<td><a href="<?=ROOT.'busqueda/'.Permalink($word->word)?>" target="_blank"><?=$word->word?></a></td>
									<td><?=$word->total?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p>No hay registros todavía</p>
					<?php endif; ?>
				</div>

				<div class="col-md-6">
					<h4>Lugares más buscados</h4>
					<?php if($top_locations): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Lugar</th>
									<th>Total</th>
								</tr>
							</thead>
							<?php foreach($top_locations as $location): ?>
								<tr>
									<td><a href="<?=ROOT.'busqueda/-/'.Permalink($location->location)?>" target="_blank"><?=$location->location?></a></td>
									<td><?=$location->total?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p>No hay registros todavía</p>
					<?php endif; ?>
				</div>

			</div>


			<hr>


			<div class="row">

				<div class="col-md-6">
					<h4>Promos más visitadas</h4>
					<?php if($top_promos): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Promo</th>
									<th>Visitas</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($top_promos as $promo): ?>
								<tr>
									<td><a href="<?=ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title)?>" target="_blank"><?=$promo->title?></a></td>
									<td><?=number_format($promo->views,0,'','.')?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p>No hay registros todavía</p>
					<?php endif; ?>
				</div>

				<div class="col-md-6">
					<h4>Centros más visitados</h4>
					<?php if($top_clients): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Centro</th>
									<th>Visitas</th>
								</tr>
							</thead>
							<?php foreach($top_clients as $client): ?>
								<tr>
									<td><a href="<?=ROOT.'centros/'.$client->permalink?>" target="_blank"><?=$client->name?></a></td>
									<td><?=number_format($client->views,0,'','.')?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p>No hay registros todavía</p>
					<?php endif; ?>
				</div>

			</div>



			<hr>


			<div class="row">

				<div class="col-md-6">
					<h4>Entradas del blog más visitadas</h4>
					<?php if($top_blog): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Entrada</th>
									<th>Visitas</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($top_blog as $blog): ?>
								<tr>
									<td><a href="<?=ROOT.'blog-pagina/'.$blog->id.'-'.Permalink($blog->title)?>" target="_blank"><?=$blog->title?></a></td>
									<td><?=number_format($blog->views,0,'','.')?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p>No hay registros todavía</p>
					<?php endif; ?>
				</div>

				<div class="col-md-6">
					<h4>Etiqueta más visitada</h4>
					<?php if($top_glossary): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Etiqueta</th>
									<th>Visitas</th>
								</tr>
							</thead>
							<?php foreach($top_glossary as $glossary): ?>
								<tr>
									<td><a href="<?=ROOT.'etiqueta/'.$glossary->id.'-'.Permalink($glossary->name)?>" target="_blank"><?=$glossary->name?></a></td>
									<td><?=number_format($glossary->views,0,'','.')?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p>No hay registros todavía</p>
					<?php endif; ?>
				</div>

			</div>




			<hr>


			<div class="row">

				<div class="col-md-6">
					<h4>Promos más consultadas</h4>
					<?php if($top_promos_questions): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Promo</th>
									<th>Consultas</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($top_promos_questions as $promo): ?>
								<tr>
									<td><a href="<?=ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title)?>" target="_blank"><?=$promo->title?></a></td>
									<td><?=number_format($promo->total,0,'','.')?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p>No hay registros todavía</p>
					<?php endif; ?>
				</div>

				<div class="col-md-6">
					<h4>Centros más consultados</h4>
					<?php if($top_clients_questions): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped sz-10">
							<thead>
								<tr>
									<th>Centro</th>
									<th>Visitas</th>
								</tr>
							</thead>
							<?php foreach($top_clients_questions as $client): ?>
								<tr>
									<td><a href="<?=ROOT.'centros/'.$client->permalink?>" target="_blank"><?=$client->name?></a></td>
									<td><?=number_format($client->total,0,'','.')?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<?php else: ?>
					<p>No hay registros todavía</p>
					<?php endif; ?>
				</div>

			</div>

		</div>

	</div>

</section>