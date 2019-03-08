
<script>var IDPromo = <?= $_PROMOS->data()->id ?>; var islogged = <?= $_USER->logged() ? 1 : 0 ?>;</script>

<section class="promo">

	<div class="container">

		<ul class="breadcrumb">
			<li><a href="<?= ROOTPATH ?>">Home</a></li>
			<li><a href="<?= ROOTPATH.'busqueda' ?>">Promos</a></li>
			<li><a href="<?= ROOTPATH.'centros/'.$_CLIENTS->data()->permalink ?>"><?= $_CLIENTS->data()->name ?></a></li>
			<li><?= $_PROMOS->data()->title ?></li>
		</ul>

		<a href="<?= ROOTPATH.'centros/'.$_CLIENTS->data()->permalink ?>" class="client-wrapper">
			<div class="logo thumb-contain img-circle" style="background-image:url(<?= $logo ?>);"></div>
			<div class="client-info">
				<h1 class="client-name"><?= $_CLIENTS->data()->name ?></h1>
				<?php if($_STORES->get($_CLIENTS->data()->id)): ?>
				<h2 class="client-location"> <i class="fa fa-map-marker"></i>
				<?php 
				if(count($_STORES->data())>1){
					echo 'Varias sucursales';
				}else{
					echo $_STORES->data()[0]->city;
				} 
				?>

				</h2>
				<?php endif; ?>
				<div class="stars"><?= Stars($_CLIENTS->rating($_CLIENTS->data()->id),''); ?></div>
			</div>
		</a>

		<div class="main-wrapper">
		
			<?php if(count($gallery)): ?>
			<div class="gallery gallery-section">
				
				<?php foreach($gallery as $img): ?>
				<div class="overprint-absolute thumb-cover bg-black slide" style="background-image: url(<?= ROOTPATH.'img/promos/'.$img->photoname.'-o.'.$img->extension ?>);"></div>
				<?php endforeach; ?>

				<?php if(count($gallery)>1): ?>
				<i class="fa fa-chevron-left prev"></i>
				<i class="fa fa-chevron-right next"></i>
				<?php endif; ?>
				<div class="navigation"></div>
				
			</div>
			<?php endif ?>

			<div class="info-wrapper">

				<h3 class="promo-title"><?= $_PROMOS->data()->title ?></h3>
				<p class="promo-subtitle"><?= $_PROMOS->data()->subtitle ?></p>

				<hr>


				<!-- PRICE -->
				<div class="pricing">

					<!-- PRICING -->
					<?php if($_PROMOS->data()->sale): ?>
						
						<?php if($_PROMOS->data()->discount): ?>
						<div class="promo-discount"><span class="strikethrough">$ <?= number_format($_PROMOS->data()->price,0,',','.') ?></span> - <span class="sz-11"><?= $_PROMOS->data()->discount ?>% Off</span></div>
						<?php endif; ?>

					<div class="promo-price"><strong>$ <?= number_format($_PROMOS->data()->price-($_PROMOS->data()->price*$_PROMOS->data()->discount/100),2,',','.') ?></strong></div>

					<!-- AMOUNT -->
					<div class="stock"><small><?= $_PROMOS->data()->amount ? $_PROMOS->data()->amount.' disponibles' : 'Lo sentimos, ya no hay más disponibles' ?></small></div>
					
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
							<span <?= $_USER->logged() && $showsalebuttons ? 'id="btn_sale"' : '' ?> data-toggle="modal" data-target="<?= $_USER->logged() ? '' : '#modal_not_logged' ?>" >Comprar!</span> <i class="fa fa-caret-down" data-toggle="collapse" data-target="#btn_list" ></i>
						</div>
						<?php else: ?>
						<div class="highlight-button" >
							<i class="fa fa-envelope fa-fw"></i> 
							<span data-toggle="modal" data-target="#modal_promo_request">Consultar!</span> 
							<i class="fa fa-caret-down" data-toggle="collapse" data-target="#btn_list" ></i>
						</div>
						<?php endif; ?>

						<ul id="btn_list" class="btn-list collapse">
							<?php if($showsalebuttons): ?>


							<li data-toggle="modal" data-target="#modal_promo_request" ><i class="fa fa-envelope fa-fw"></i> <span>Consultar</span></li>
							<li data-toggle="modal" data-target="<?= $_USER->logged() ? '#modal_gift' : '#modal_not_logged' ?>" ><i class="fa fa-gift fa-fw"></i> <span>Regalar!</span></li>
							<?php endif; ?>
							<li data-toggle="modal" data-target="#modal_promo_request"><i class="fa fa-calendar fa-fw"></i> <span>Solicitar Turno</span></li>
						</ul>
						

					</div>

				</div>

				<hr>

				<!-- ICONS -->
				<div class="box-icons ">

					<!-- Stars -->					
					<div class="item">
						<div class="rating">
						<?php $promstars = $_PROMOS->rating($_PROMOS->data()->id); ?>
						
							<div class="stars">
								<?= Stars($promstars,''); ?><br>
								<small class="punctuation"><?= round($promstars,1) ?>/5</small>
							</div>
						</div>
					</div>

					<!-- FAV -->
					<div class="item cl-gray-10">|</div>
					<div class="item"><?= Fav($_PROMOS->data()->id); ?></div>
					<div class="item cl-gray-10">|</div>

					<!-- VIEWS -->
					<div class="item">
						<div class="views text-right">
							<i class="fa fa-eye fa-lg"></i><br>
							<small><?= number_format($_PROMOS->data()->views,0,'','.') ?> visitas</small>
						</div>
					</div>				
					
				</div>				

			</div>
		</div>
		

		<!-- OVERVIEW -->
		<div class="overview-header">
			<div class="info">
			<!-- STORES -->
				<!--<p>Disponible en:</p>
				<ul class="simple-list">
					<?php 
					if($_STORES->get($_CLIENTS->data()->id,$_PROMOS->data()->stores)):
						foreach($_STORES->data() as $store):
							$_STORES->find($store->id);
					?>
					<li><i class="fa fa-map-marker"></i> <?= $_STORES->data()->address.' - '.$_STORES->data()->city.', '.$_PROVINCES[$_STORES->data()->idprovince] ?></li>
					<?php endforeach; endif; ?>
				</ul><hr>--->
			</div>
		</div>


		<!-- INFO -->
		<div class="block-white">
			<h4 class="title-bar">Descripción</h4>
			<p><?= nl2br($_PROMOS->data()->description) ?></p>

			<?php if(!empty($_PROMOS->data()->includes)): ?>
			<hr>
			<h5 class="fw-700">¿Qué incluye la experiencia?</h5>
			<p><?= nl2br($_PROMOS->data()->includes) ?></p>
			<?php endif; ?>

			<?php if(!empty($_PROMOS->data()->recomendations)): ?>
			<hr>
			<h5 class="fw-700">¿Que recomendamos que lleve?</h5>
			<p><?= nl2br($_PROMOS->data()->recomendations) ?></p>
			<?php endif; ?>

			<?php if(!empty($_PROMOS->data()->reservation)): ?>
			<hr>
			<h5 class="fw-700">¿Requiere reserva y/o algún requisito?</h5>
			<p><?= nl2br($_PROMOS->data()->reservation) ?></p>
			<?php endif; ?>

			<?php if(!empty($_PROMOS->data()->duration)): ?>
			<hr>
			<h5 class="fw-700">Duración de la actividad</h5>
			<p><?= nl2br($_PROMOS->data()->duration) ?></p>
			<?php endif; ?>

			<?php if(!empty($_PROMOS->data()->cancellation)): ?>
			<hr>
			<h5 class="fw-700">¿Cuál es la política de cancelación?</h5>
			<p><?= nl2br($_PROMOS->data()->cancellation) ?></p>
			<?php endif; ?>

		</div>

		<div class="block-white">
			<h4 class="title-bar">Validez</h4>
			<p class="stores"><i class="fa fa-calendar fa-fw"></i> Disponible online hasta <?= $_PROMOS->data()->fin ?></p>
			<p>La promo tiene una duración de 30 días a partir de la fecha de compra.</p>
		</div>

		<?php if($_PROMOS->data()->sale):  ?>

		<div class="block-white">
			<h4 class="title-bar">Promociones de cuotas sin interés</h4>
			<p>Podés pagar en cuotas sin interés. La financiación con tarjeta de crédito está a cargo de MercadoPago. Consultá condiciones <a href="https://www.mercadopago.com.ar/promociones" target="_blank">aquí</a>.</p>
		</div>

		<?php endif; ?>

		
	</div>	
</section>


<!-- MESSAGES -->
<div class="modal fade" id="modal_promo_request" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3><?= $_PROMOS->data()->title ?></h3>
				<p><?= $_CLIENTS->data()->name ?></p>
				
				<!-- Schedules -->
				<?php
				$_stores = new Stores();
				$_stores->get($_CLIENTS->data()->id);
				if(!empty($_stores->data()[0]->schedules)):
				?>
				<div class="sz-10 schedules">
					<span><i class="fa fa-calendar fa-fw icon"></i> <?= $_stores->scheduleToday($_stores->data()[0]->schedules) ?> <i class="fa fa-caret-down fa-fw"></i></span>
					<div id="schedules_block" class="schedules-block">
						<?php foreach($_stores->schedulesList($_stores->data()[0]->schedules) as $day): ?>
							<p class="dropdown-item"><?php print_r($day) ?></p>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endif ?>


			</div>
			<div class="modal-body ff-futuralight" >
				<h4 class="modal-title">Consultar acerca de esta promo</h4>
				<br />
				<form id="form_promo_request">
					<div class="form-group">
						<label for="fd_name">Nombre y Apellido</label>
						<input id="fd_name" type="text" name="name" class="form-control" required>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<div class="form-group">
								<label for="fd_phone">Teléfono</label>
								<input id="fd_phone" type="text" name="phone" class="form-control" required>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group">
								<label for="fd_mail">E-mail</label>
								<input id="fd_mail" type="email" name="mail" class="form-control" required>
							</div>
						</div>
					</div>
					<div id="schedules_input">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="fd_preference_day">Día de Preferencia</label>
									<input id="fd_preference_day" type="text" class="form-control">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="fd_preference_schedule">Horario de Preferencia</label>
									<select id="fd_preference_schedule" type="text" class="form-control">
										<option value="mañana">Por la Mañana</option>
										<option value="tarde">Por la Tarde</option>
										<option value="noche">Por la Noche</option>
									</select>
								</div>
							</div>
						</div>
					</div>					
					<div class="form-group">
						<label for="fd_message">Consulta</label>
						<textarea id="fd_message" type="text" name="message" class="form-control" rows="5" required></textarea>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary" >ENVIAR</button>
					</div>
					<div class="status"></div>
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
				<a class="btn btn-primary" href="<?= ROOTPATH.'login#'.ROOTPATH.'promo/'.$_CLIENTS->data()->permalink.'/'.$_PROMOS->data()->id.'-'.Permalink($_PROMOS->data()->title) ?>">Login</a>
				<hr>
				<p>Todavía no te registraste???</p>
				<a class="btn btn-primary" href="<?= ROOTPATH.'registro' ?>">Registro</a>
			</div>
		</div>
	</div>
</div>


<!-- GIFT -->
<div id="modal_gift" class="modal fade ">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
				<h3 class="modal-title">Regalar esta promo</h3>
				<p>Completa los datos de la persona a la que quieres regalar esta promo.</p>
			</div>
			<form class="modal-body">
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_gift_to">Para:</label>
							<input id="fd_gift_to" type="text" class="form-control" placeholder="Ingresa el nombre del destinatario" required>
						</div>
						<div class="form-group">
							<label for="fd_gift_mail">Email:</label>
							<input id="fd_gift_mail" type="email" class="form-control" placeholder="Ingresa el email del destinatario" required>
						</div>
						<div class="form-group">
							<label for="fd_gift_from">De:</label>
							<input id="fd_gift_from" type="text" class="form-control" value="<?php if($_USER->logged()) echo $_USER->data()->name ?>" placeholder="Ingresa tu nombre" required>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label for="fd_gift_message">Mensaje (Opcional):</label>
							<textarea id="fd_gift_message" rows="8" class="form-control" placeholder="Incluye algún mensaje" maxlength="255" ></textarea>
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
				<iframe frameborder="0" src="<?= ROOTPATH.'views/cargando.php' ?>"></iframe>
			</div>
		</div>
	</div>
</div>


<?php 
$_VOUCHERS->status = '1:1';
if($_VOUCHERS->getpromo($_PROMOS->data()->id)):
?>
<!-- MODAL VOUCHER -->
<div id="modal_voucher" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
			</div>
			<div class="modal-body">
				
					<div class="cl-fucsia-5">
						<h2>Voucher de Descuento</h2>
						<p class="sz-11">Si tienes un código para esta promo puedes aplicarlo para obtener un descuento en la compra de esta promoción.</p>
						<hr>
						<div class="row">
							<div class="col-sm-6">
							
								<form id="form_voucher_apply" class="form-group">
									<label for="fd_voucher_code">Ingresar código</label>
									<div class="input-group">
										<input id="fd_voucher_code" type="text" class="form-control" autocomplete="off">
										<div class="input-group-btn">
											<button id="btn_voucher_apply" data-loading-text="Validando..." class="btn btn-fucsia">Aplicar</button>
										</div>
									</div>
								</form>
							</div>
						</div>	
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
<section>
	<div class="title-bar">
		<div class="container">
			<h3 class="title"><i class="fa fa-shopping-bag"></i> Promos Relacionadas</h3>
		</div>
	</div>

	<div class="container">
		<div class="promos-highlight">
		<?php
		$_PROMOS->status = '1:1';
		$_PROMOS->sort = 'rand';
		$_PROMOS->exclude = $_PROMOS->data()->id;
		$_PROMOS->limit = '0,8';
		if($_PROMOS->get()):
			$nm = 0;
			foreach($_PROMOS->data() as $kp=>$promo):
				if($_CLIENTS->find($promo->idclient)):
					$imgpromo = json_decode($promo->gallery);
					$_STORES->get($promo->idclient,$promo->stores);
					$promolink = ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
					echo '<div class="mod-promo mod-promo-4">';
					include 'mods/mod-promo.php';
					echo '</div>';
					if(count($colorsequence)-1 == $nm){$nm = 0;}else{$nm++;}
				else:
					echo '<p>No se encontraron promociones vigentes</p>';
				endif;
			endforeach;
		endif;
		?>
		</div>

	</div>

</section>






<?php include 'mods/mod-socials.php' ?>



