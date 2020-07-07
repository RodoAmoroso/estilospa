<script>
	var IDPromo = <?= $Promos->data()->id ?>;
	var IDClient = <?= $Promos->data()->idclient ?>;
	var islogged = <?= $User->logged() ? 1 : 0 ?>;
</script>

<section class="promo">

	<div class="container">

		<ul class="breadcrumb">
			<li><a href="<?= ROOT ?>">Home</a></li>
			<li><a href="<?= ROOT.'busqueda' ?>">Experiencias</a></li>
			<li><a href="<?= ROOT.'centros/'.$Clients->data()->permalink ?>"><?= $Clients->data()->name ?></a></li>
			<li><?= $Promos->data()->title ?></li>
		</ul>

		<a href="<?= ROOT.'centros/'.$Clients->data()->permalink ?>" class="client-wrapper">
			<div class="logo thumb-contain img-circle" style="background-image:url(<?= $logo ?>);"></div>
			<div class="client-info">
				<h1 class="client-name"><?= $Clients->data()->name ?></h1>
				<?php if($Stores->get($Clients->data()->id)): ?>
				<h2 class="client-location"> <i class="fa fa-map-marker"></i>
				<?php
				if(count($Stores->data())>1){
					echo 'Varias sucursales';
				}else{
					echo $Stores->data()[0]->city;
				}
				?>

				</h2>
				<?php endif; ?>
				<div class="stars"><?= Stars($Clients->rating($Clients->data()->id),''); ?></div>
			</div>
		</a>

		<div class="main-wrapper">

			<?php if(count($gallery)): ?>
			<div class="gallery gallery-section">

				<?php foreach($gallery as $img): ?>
				<div class="overprint-absolute thumb-cover bg-black slide" style="background-image: url(<?= ROOT.'img/promos/'.$img->photoname.'-o.'.$img->extension ?>);"></div>
				<?php endforeach; ?>

			</div>
			<?php endif ?>

			<div class="info-wrapper">

				<h3 class="promo-title"><?= $Promos->data()->title ?></h3>
				<p class="promo-subtitle"><?= $Promos->data()->subtitle ?></p>

				<hr>


				<!-- PRICE -->
				<div class="pricing">

					<!-- PRICING -->
					<?php if($Promos->data()->sale): ?>

						<?php if($Promos->data()->discount): ?>
						<div class="promo-discount"><span class="strikethrough">$ <?= number_format($Promos->data()->price,0,',','.') ?></span> - <span class="sz-11"><?= $Promos->data()->discount ?>% Off</span></div>
						<?php endif; ?>

					<div class="promo-price"><strong>$ <?= number_format($Promos->data()->price-($Promos->data()->price*$Promos->data()->discount/100),0,',','.') ?></strong></div>

					<!-- AMOUNT -->
					<div class="stock"><small><?= $Promos->data()->amount ? $Promos->data()->amount.' disponibles' : 'Lo sentimos, ya no hay más disponibles' ?></small></div>

					<?php endif; ?>


				</div>


				<!-- SHOP -->
				<div class="shop-action">

					<?php if($showsalebuttons): ?>
					<div class="amount">
						<select id="select_amount" type="text" class="form-control">
							<?php for($i=1; $i<=15; $i++): ?>
							<option value="<?= $i ?>"><?= $i ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<?php endif; ?>

					<div class="button-action">
						<?php if($showsalebuttons): ?>
						<div class="highlight-button" >
							<i class="fa fa-shopping-bag fa-fw"></i>
							<span <?= $User->logged() && $showsalebuttons ? 'data-action="sale"' : '' ?> data-toggle="modal" data-target="<?= $User->logged() ? '' : '#modal_not_logged' ?>" >Comprar!</span> <i class="fa fa-caret-down" data-toggle="collapse" data-target="#btn_list" ></i>
						</div>
						<?php else: ?>
						<div class="highlight-button" >
							<i class="fa fa-envelope fa-fw"></i>
							<span data-toggle="scrollto" data-target="#form_question">Consultar!</span>
							<i class="fa fa-caret-down" data-toggle="collapse" data-target="#btn_list" ></i>
						</div>
						<?php endif; ?>

						<ul id="btn_list" class="btn-list collapse">
							<?php if($showsalebuttons): ?>

							<li data-toggle="scrollto" data-target="#form_question" ><i class="fa fa-envelope fa-fw"></i> <span>Consultar</span></li>

							<?php endif; ?>
							<li data-toggle="modal" data-target="#modal_reservation"><i class="fa fa-calendar fa-fw"></i> <span>Reservar</span></li>
						</ul>


					</div>

				</div>

				<hr>

				<!-- ICONS -->
				<div class="box-icons ">

					<!-- Stars -->
					<div class="item">
						<div class="rating">
						<?php $promstars = $Promos->rating($Promos->data()->id); ?>

							<div class="stars">
								<?= Stars($promstars,''); ?><br>
								<small class="punctuation"><?= round($promstars,1) ?>/5</small>
							</div>
						</div>
					</div>

					<!-- FAV -->
					<div class="item cl-gray-10">|</div>
					<div class="item"><?= Fav($Promos->data()->id,$Promos->data()->idclient); ?></div>
					<div class="item cl-gray-10">|</div>

					<!-- VIEWS -->
					<div class="item">
						<div class="views text-right">
							<i class="fa fa-eye fa-lg"></i><br>
							<small><?= number_format($Promos->data()->views,0,'','.') ?> visitas</small>
						</div>
					</div>

				</div>

			</div>
		</div>


		<!-- INFO -->
		<div class="block-white">
			<h4 class="title-bar">Descripción</h4>
			<p><?= nl2br($Promos->data()->description) ?></p>

			<?php if(!empty($Promos->data()->includes)): ?>
			<hr>
			<h5 class="fw-700">¿Qué incluye la experiencia?</h5>
			<p><?= nl2br($Promos->data()->includes) ?></p>
			<?php endif; ?>

			<?php if(!empty($Promos->data()->recomendations)): ?>
			<hr>
			<h5 class="fw-700">¿Que recomendamos que lleve?</h5>
			<p><?= nl2br($Promos->data()->recomendations) ?></p>
			<?php endif; ?>

			<?php if(!empty($Promos->data()->reservation)): ?>
			<hr>
			<h5 class="fw-700">¿Requiere reserva y/o algún requisito?</h5>
			<p><?= nl2br($Promos->data()->reservation) ?></p>
			<?php endif; ?>

			<?php if(!empty($Promos->data()->duration)): ?>
			<hr>
			<h5 class="fw-700">Duración de la actividad</h5>
			<p><?= nl2br($Promos->data()->duration) ?></p>
			<?php endif; ?>

			<?php if(!empty($Promos->data()->cancellation)): ?>
			<hr>
			<h5 class="fw-700">¿Cuál es la política de cancelación?</h5>
			<p><?= nl2br($Promos->data()->cancellation) ?></p>
			<?php endif; ?>

		</div>

		<div class="block-white">
			<h4 class="title-bar">Validez</h4>
			<p class="stores"><i class="fa fa-calendar fa-fw"></i> Disponible online hasta <?= $Promos->data()->fin ?></p>
			<!--<p>La promo tiene una duración de 30 días a partir de la fecha de compra.</p>-->
			<p class="alert alert-danger"><b>COMPRA AHORA</b>, con total confianza, tendrás tiempo para usar tu voucher luego de la apertura.</p>
		</div>

		<?php if($Promos->data()->sale): ?>
		<div class="block-white">
			<h4 class="title-bar">¿Cómo Comprar?</h4>
			<ul class="number-list">
				<li><span class="number">1</span> <span>Click en el botón comprar</span></li>
				<li><span class="number">2</span> <span>Ingresá con tu cuenta o Registrate</span></li>
				<li><span class="number">3</span> <span>Pagás on line en forma rápida y segura con tarjeta de débito/crédito mediante Mercado Pago</span></li>
			</ul>
		</div>

		<?php endif; ?>


		<div class="main-wrapper">
			<div class="info-wrapper">

				<!-- PRICE -->
				<div class="pricing">
					<!-- PRICING -->
					<?php if($Promos->data()->sale): ?>
						<?php if($Promos->data()->discount): ?>
						<div class="promo-discount"><span class="strikethrough">$ <?= number_format($Promos->data()->price,0,',','.') ?></span> - <span class="sz-11"><?= $Promos->data()->discount ?>% Off</span></div>
						<?php endif; ?>
					<div class="promo-price"><strong>$ <?= number_format($Promos->data()->price-($Promos->data()->price*$Promos->data()->discount/100),0,',','.') ?></strong></div>
					<!-- AMOUNT -->
					<div class="stock"><small><?= $Promos->data()->amount ? $Promos->data()->amount.' disponibles' : 'Lo sentimos, ya no hay más disponibles' ?></small></div>
					<?php endif; ?>
				</div>

				<!-- SHOP -->
				<div class="shop-action">

					<?php if($showsalebuttons): ?>
					<button class="btn btn-primary" >
						<i class="fa fa-shopping-bag fa-fw"></i>
						<span <?= $User->logged() && $showsalebuttons ? 'data-action="sale"' : '' ?> data-toggle="modal" data-target="<?= $User->logged() ? '' : '#modal_not_logged' ?>" >Comprar Ahora!</span>
					</button>
					<?php endif; ?>

				</div>

			</div>
		</div>


		<!-- QUESTIONS -->
		<?php
		$question_rowid = $Promos->data()->id;
		$question_type = 'promos';
		$show_responses = true;
		include 'questions.php';
		?>


	</div>
</section>


<!-- RESERVA -->
<div class="modal fade" id="modal_reservation" tabindex="-1" role="dialog" >
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3>Reservar un turno en <?= $Promos->data()->title ?></h3>
				<p><?= $Clients->data()->name ?></p>
			</div>
			<div class="modal-body ff-futuralight" >

				<form id="form_reservation">

					<div class="row">
						<div class="col-md-6">

							<div class="form-group">
								<label for="fd_name">Nombre</label>
								<input id="fd_name" type="text" name="name" class="form-control" required value="<?= !is_null($_userdata) ? $_userdata->name : '' ?>">
							</div>
							<div class="form-group">
								<label for="fd_name">Apellido</label>
								<input id="fd_name" type="text" name="lastname" class="form-control" required value="<?= !is_null($_userdata) ? $_userdata->lastname : '' ?>">
							</div>
							<div class="form-group">
								<label for="fd_phone">Teléfono</label>
								<input id="fd_phone" type="text" name="phone" class="form-control" required value="<?= !is_null($_userdata) ? $_userdata->phone : '' ?>">
							</div>
							<div class="form-group">
								<label for="fd_mail">E-mail</label>
								<input id="fd_mail" type="email" name="email" class="form-control" required value="<?= !is_null($_userdata) ? $_userdata->mail : '' ?>">
							</div>

							<div class="form-group">
								<label for="fd_message">Mensaje <i>(opcional)</i></label>
								<textarea id="fd_message" type="text" name="message" class="form-control" rows="5" placeholder="Indicar la cantidad de personas en caso que sean más de una." ></textarea>
							</div>

						</div>

						<div class="col-md-6">
							<label>Elegir día y horario</label>

							<div class="calendar-promo">

								<div class="month">
									<div class="prev" data-action="prev"><i class="fa fa-angle-left"></i></div>
									<div class="name" ><span data-month="<?=date('m')?>"><?=Dates::translateMonths(date('M'))?></span> <span data-year="<?=date('Y')?>"><?=date('Y')?></span></div>
									<div class="next" data-action="next"><i class="fa fa-angle-right"></i></div>
								</div>

								<div class="week">
									<ul class="days">
										<li class="day prev" data-action="prev"><i class="fa fa-angle-left"></i></li>

										<?php foreach($_arrdays as $k=>$day): ?>
										<li class="day <?=!$k ? 'active' : ''?>" data-day="<?=$day['day']?>" data-dayname="<?=$day['dayname']?>" ><?=$day['name'].' '.$day['day']?></li>
										<?php endforeach; ?>

										<li class="day next" data-action="next"><i class="fa fa-angle-right"></i></li>
									</ul>
								</div>

								<div class="schedule">
									<ul class="hours"></ul>
								</div>

								<div id="selected_schedule" class="pad-10 text-center">Seleccioná un día y horario</div>
							</div>

							<input type="hidden" name="date" >
							<input type="hidden" name="promoid" value="<?=$Promos->data()->id?>" >
							<input type="hidden" name="clientid" value="<?=$Promos->data()->idclient?>" >
							<input type="hidden" name="sale_hash" value="<?=$_vars?>">

						</div>

					</div>

					<hr>

					<div class="form-group">
						<button type="submit" class="btn btn-primary" >ENVIAR</button>
					</div>

				</form>



			</div>
		</div>
	</div>
</div>


<!-- LOGIN OR REGISTER -->
<div class="modal fade" id="modal_not_logged" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Usuario no registrado</h4>
			</div>
			<div class="modal-body ff-futuralight" >
				<p>Para poder usar esta función tenés que ingresar como usuario Registrado.</p>
				<a class="btn btn-primary" href="<?= ROOT.'login#'.ROOT.'promo/'.$Clients->data()->permalink.'/'.$Promos->data()->id.'-'.Permalink($Promos->data()->title) ?>">Login</a>
				<hr>
				<p>Todavía no te registraste???</p>
				<a class="btn btn-primary" href="<?= ROOT.'registro' ?>">Registro</a>
			</div>
		</div>
	</div>
</div>


<!-- MODAL GIFT -->
<div id="modal_gift" class="modal fade ">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
				<h3 class="modal-title">Regalar esta experiencia</h3>
				<p>Completá los datos de la persona a la que quieres regalar esta experiencia.</p>
			</div>
			<form class="modal-body">
				<input type="hidden" name="idpromo" value="<?=$Promos->data()->id?>">
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_gift_to">Para:</label>
							<input name="to" id="fd_gift_to" type="text" class="form-control" placeholder="Ingresá el nombre del destinatario" required>
						</div>
						<div class="form-group">
							<label for="fd_gift_mail">Email:</label>
							<input name="email" id="fd_gift_mail" type="email" class="form-control" placeholder="Ingresá el email del destinatario" required>
						</div>
						<div class="form-group">
							<label for="fd_gift_from">De:</label>
							<input name="from" id="fd_gift_from" type="text" class="form-control" value="<?php if($User->logged()) echo $User->data()->name.' '.$User->data()->lastname ?>" placeholder="Ingresá tu nombre" required>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_gift_message">Mensaje (Opcional):</label>
							<textarea name="message" id="fd_gift_message" rows="8" class="form-control" placeholder="Incluí algún mensaje" maxlength="255" required ></textarea>
							<small><span id="gift_left_characters">255</span> caracteres restantes</small>
						</div>
					</div>
				</div>
				<hr>
				<div class="form-group text-right">
					<button id="btn_gift_next" class="btn btn-primary">Siguiente <i class="fa fa-angle-double-right"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- MODAL MP -->
<div id="modal_mp" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" src="<?= ROOT.'views/cargando.php' ?>"></iframe>
			</div>
		</div>
	</div>
</div>


<?php
$Vouchers->status = '1:1';
if($Vouchers->getpromo($Promos->data()->id)):
?>
<!-- MODAL VOUCHER -->
<div id="modal_voucher" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
			</div>
			<div class="modal-body">

					<div class="cl-fucsia-5">
						<h2>Voucher de Descuento</h2>
						<p class="sz-11">Si tienes un código para esta experiencia puedes aplicarlo para obtener un descuento en la compra.</p>
						<hr>

						<form id="form_voucher_apply" class="form-group">
							<input type="hidden" name="idpromo" value="<?=$Promos->data()->id?>">
							<label for="fd_voucher_code">Ingresar código</label>
							<div class="input-group">
								<input name="code" id="fd_voucher_code" type="text" class="form-control" autocomplete="off" required>
								<div class="input-group-btn">
									<button id="btn_voucher_apply" data-loading-text="Validando..." class="btn btn-success">Aplicar</button>
								</div>
							</div>
						</form>

						<div id="voucher_status" class="alert"></div>
						<hr>
						<button id="btn_voucher_cancel" class="btn btn-primary pull-right">No tengo un código promocional <i class="fa fa-angle-double-right"></i></button>
						<div class="clearfix"></div>
					</div>

			</div>
		</div>
	</div>
</div>
<?php endif; #has voucher ?>


<!-- OFERTAS -->
<?php
$Promos->status = '1:1';
$Promos->sort = 'rand';
$Promos->exclude = $Promos->data()->id;
$Promos->limit = '0,8';
$Promos->get();
if($Promos->data()):
?>
<section>
	<div class="title-bar">
		<div class="container">
			<h3 class="title"><i class="fa fa-shopping-bag"></i> Experiencias Relacionadas</h3>
		</div>
	</div>

	<div class="container">
		<div class="promos-highlight">
		<?php

			$nm = 0;
			foreach($Promos->data() as $kp=>$promo):
				if($Clients->find($promo->idclient)):
					$imgpromo = json_decode($promo->gallery);
					$Stores->get($promo->idclient,$promo->stores);
					$promolink = ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
					echo '<div class="mod-promo mod-promo-4">';
					include 'mods/mod-promo.php';
					echo '</div>';
					if(count($colorsequence)-1 == $nm){$nm = 0;}else{$nm++;}
				else:
					echo '<p>No se encontraron experiencias vigentes</p>';
				endif;
			endforeach;
		?>
		</div>

	</div>

</section>
<?php endif; ?>

<?php include 'mods/mod-socials.php' ?>

