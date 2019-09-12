
<script>var IDClient = <?= $clientdata->id ?>;</script>


<!-- OVERVIEW -->
<section class="overview">
	<div class="container">

		<!-- BREADCRUMB -->
		<ul class="breadcrumb sz-9 bg-white">
			<li><a href="<?= ROOT ?>">Home</a></li>
			<li><a href="<?= ROOT ?>busqueda/centros-de-estetica">Centros de Estética</a></li>
			<li><?= $clientdata->name ?></li>
		</ul>
		

		<!-- OVERVIEW HEADER -->
		<div class="overview-header">

			<!-- INFO -->
			<div class="info">

				<div class="header-info">			
					<div class="logo thumb-contain" style="background-image:url(<?= ROOT.'img/clients/'.$logoclient->photoname.'.'.$logoclient->extension ?>)"></div>
					<div class="title">
						<h1 class="fw-700"><?= $clientdata->name ?></h1>
						<p><?= $clientdata->subtitle ?></p>
					</div>			
				</div>
				
				<div class="contact-info">
					<!-- Address -->
					<div class="address sz-12"><i class="fa fa-map-marker fa-fw icon"></i> <?= $FirstAddress ?></div>
					<?php if(count($StoresClient->data())>1): ?>
					<div class="more-stores"><a href="#sucursales" class="sz-10"><i class="fa fa-map-marker fa-fw icon"></i>  más sucursales (ver mapa)</a></div>
					<?php endif; ?>

					<!-- Phones -->
					<?php if(!empty($StoresClient->data()[0]->phones)): ?><div class="phones sz-12"><i class="fa fa-phone fa-fw icon"></i> <a href="<?=ROOT.'tracker/'.$clientdata->id.'-phone/?redirect=tel:'.str_replace(' ', '', $StoresClient->data()[0]->phones)?>" target="_blank"><?= $StoresClient->data()[0]->phones ?></a></div> <?php endif; ?>

					<!-- WhatsApp -->
					<?php if(!empty($StoresClient->data()[0]->whatsapp)): ?><div class="whatsapp sz-12"><i class="fa fa-whatsapp fa-fw icon"></i> <a href="<?=ROOT.'tracker/'.$clientdata->id.'-whatsapp/?redirect='.urlencode('https://api.whatsapp.com/send?phone=549'.str_replace(' ', '', $StoresClient->data()[0]->whatsapp).'&text=Mensaje Enviado desde EstiloSPA') ?>" target="_blank"><?= $StoresClient->data()[0]->whatsapp ?></a></div> <?php endif; ?>

					<!-- Web -->
					<?php 
					if(!empty($clientdata->web)):
						$linkweb = preg_match('((http|https)\:\/\/)',$clientdata->web) ? $clientdata->web : 'http://'.$clientdata->web;
					?>
					<div class="web sz-11"><i class="fa fa-link fa-fw icon"></i> <a href="<?= ROOT.'tracker/'.$clientdata->id.'-web/?redirect='.$linkweb ?>" target="_blank" ><?= $clientdata->web ?></a></div> 
					<?php endif; ?>

					<!-- Schedules -->
					<?php if(!empty($StoresClient->data()[0]->schedules)): ?>
					<div class="sz-10 schedules">
						<span><i class="fa fa-calendar fa-fw icon"></i> <?= $StoresClient->scheduleToday($StoresClient->data()[0]->schedules) ?> <i class="fa fa-caret-down fa-fw"></i></span>
						<div id="schedules_block" class="schedules-block">
							<?php foreach($StoresClient->schedulesList($StoresClient->data()[0]->schedules) as $day): ?>
								<p class="dropdown-item"><?= $day ?></p>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif ?>
				</div>


				<div class="box-icons ">

					<!-- Stars -->
					<div class="item" >
						<div class="outer">
							<div class="inner">
								<div class="rating">
									<?php $promstars = $Clients->rating($clientdata->id); ?>
									<div class="stars">
										<?= Stars($promstars,'fa-lg'); ?>
									</div>
									<div class="punctuation"><?= round($promstars,1) ?>/5</div>
								</div>
							</div>
							
							<!-- FAVS -->
							<div class="inner"><?= Fav(0,$clientdata->id); ?></div>

							<!-- VIEWS -->
							<div class="inner">
								<div class="views text-right">
									<i class="fa fa-eye fa-lg"></i><br /><span><?= number_format($clientdata->views,0,'','.') ?> visitas</span>
								</div>
							</div>
						</div>
					</div>
					<hr>

					<div class="item">
						<div class="outer"><div class="inner">
						<?php
						$socials = json_decode($clientdata->socials);
						if($socials):
							foreach($socials as	$social):
						?>
						<a href="<?= ROOT.'tracker/'.$clientdata->id.'-'.$social->social.'/?redirect='.$social->link ?>" target="_blank" class="fa-stack">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-<?= $social->social ?> fa-stack-1x fa-inverse"></i>
						</a>
						<?php endforeach; endif; ?>
						</div></div>
					</div>
				</div>

				<div class="button-request btn-group-block">
					<button data-toggle="scrollto" data-target="#form_question" class="btn btn-default"><i class="fa fa-envelope fa-fw"></i> Consultar</button>
					<button data-toggle="modal" data-target="#modal_reservation" class="btn btn-default"><i class="fa fa-calendar fa-fw"></i> Reservar</button>
				</div>

			</div>	

			<!-- GALLERY -->
			<div class="gallery gallery-section">
				<?php 
				$gallery = json_decode($clientdata->images);
				if(count($gallery)):
					foreach($gallery as $kg=>$vg):
						$play = '';
						if(isset($vg->video)):
							$ytapi = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$vg->video.'&key=AIzaSyAq3a2AC4jXd9AVmt646ZP_45Vd3oLJn7g&part=snippet'));							
							$img = $ytapi->items[0]->snippet->thumbnails->high->url;
							$play = '<div class="play" data-video="'.$vg->video.'" ><i class="fa fa-play-circle fa-5x"></i></div>';
						else:
							$img = ROOT.'img/clients/'.$vg->photoname.'-o.'.$vg->extension;
						endif;
				?>
				<div class="slide" style="background-image:url(<?= $img ?>);" ><?= $play ?></div>
				<?php endforeach; endif; ?>

			</div>		

		</div>


		<!-- PROMOS -->
		<?php
		$Promos->idclient = $clientdata->id;
		$Promos->get();
		if($Promos->data()):
		?>		
		<div class="block-white">
			<h4 class="title-bar">Promos Vigentes</h4>			
			<div class="promos-highlight">
				<?php
					foreach($Promos->data() as $kp=>$promo): 
						$imgpromo = json_decode($promo->gallery);
						$promolink = ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
						$Stores = new Stores();
						//show_array($promo->stores);
						$Stores->get($clientdata->id,$promo->stores);
						echo '<div class="mod-promo mod-promo-4">';
						include 'mods/mod-promo.php';
						echo '</div>';
					endforeach;
				?>				
			</div>
		</div>
		<?php endif; ?>


		<!-- FEATURES -->	
		<div class="block-white">	
			<h4 class="title-bar">Servicios</h4>
			<ul class="nav nav-tabs" role="tablist">
				<?php if(count($Features->data())): foreach($Features->data() as $kf=>$vf): ?>
				<li role="presentation" class="<?= $kf ? '' : 'active' ?> text-uppercase"><a href="#tab_<?= $kf ?>" aria-controls="tab_<?= $kf ?>" role="tab" data-toggle="tab"><?= $vf->title ?></a></li>
				<?php endforeach; endif; ?>				
			</ul>
			<div class="tab-content">
				<?php if(count($Features->data())): foreach($Features->data() as $kf=>$vf): ?>
				<div role="tabpanel" class="tab-pane fade in <?= $kf ? '' : 'active' ?>" id="tab_<?= $kf ?>"><div class="pad-16"><?= $vf->description ?></div></div>
				<?php endforeach; endif; ?>
			</div>

		</div>



		<!-- MAP -->		
		<div class="block-white">
			<h4 id="sucursales" class="title-bar" >Locales / Mapa</h4>
			<div class="row">
				<div class="<?= count($StoresClient->data())>1 ? 'col-md-8' : 'col-md-12' ?>">

					<div class="stores-highlight">
						<h3 class="city fw-400"></h3>
						<h4 class="address"></h4>
						<h4 class="phones" ></h4>
						<h4 class="whatsapp" ></h4>
						<div class="sz-10 schedules">
							<div class="today"><i class="fa fa-calendar fa-fw icon"></i> <span></span> <i class="fa fa-caret-down fa-fw"></i></div>
							<div class="schedules-block">
								<?php foreach($StoresClient->schedulesList($StoresClient->data()[0]->schedules) as $day): ?>
									<p class="dropdown-item"><?php print_r($day) ?></p>
								<?php endforeach; ?>
							</div>
						</div>
						<hr>
					</div>
					
					<div id="map" class="map">
						<div class="overprint-absolute hover bg-gray-5 op-5"></div>
					</div>					
					
				</div>
				<div class="col-md-4 <?= count($StoresClient->data())>1 ? '' : 'dp-none' ?>">
					
					<div id="stores">
						<div class="list-group">
						<script>var addresses = [];</script>
							<?php 
							if(count($StoresClient->data())): 
								foreach($StoresClient->data() as $ks=>$vs):
									$addresses[] = $vs->address.', '.$vs->city;
							?>
							<script>addresses.push('<?= $vs->address.", ".$vs->city.", ".$vs->name.", AR" ?>');</script>
							<a href="#" data-id="<?= $vs->id ?>" class="list-group-item">
								<h4 class="list-group-item-heading"><?= $vs->city.', '.$Provinces[$vs->idprovince] ?></h4>
								<ol>
									<li><?= $vs->address.(empty($vs->additional) ? '' : ' - '.$vs->additional) ?></li>
								</ol>
							</a>
							<?php endforeach; endif; ?>
						</div>
					</div>

				</div>
			</div>	
			<?php if(count($StoresClient->data())>1): ?>
			<p class="sz-9">Click en cada sucursal para ver la ubicación</p>
			<?php endif; ?>	
		
		</div>


		<!-- QUESTIONS -->
		<div class="block-white">
			<h4 class="title-bar">Preguntas y Respuestas</h4>

			<form id="form_question" class="question-form">
					
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">
									<span><i class="fa fa-user"></i></span>
								</div>
								<input name="name" type="text" class="form-control" <?=is_null($_userdata) ? '' : 'readonly' ?> required value="<?= !is_null($_userdata) ? $_userdata->fullname : '' ?>" placeholder="Tu nombre..." >
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">
									<span><i class="fa fa-envelope"></i></span>
								</div>
								<input name="email" type="text" class="form-control" <?=is_null($_userdata) ? '' : 'readonly' ?> required value="<?=!is_null($_userdata) ? $_userdata->mail : '' ?>" placeholder="Tu email..." >
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">
									<span><i class="fa fa-phone"></i></span>
								</div>
								<input name="phone" type="text" class="form-control" required value="<?=!is_null($_userdata) ? $_userdata->phone : '' ?>" placeholder="Tu teléfono..."  >
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<textarea name="message" rows="6" class="form-control" required placeholder="Escribí tu pregunta..." maxlength="500"></textarea>
					<input type="hidden" name="rowid" value="<?=$clientdata->id?>">
					<input type="hidden" name="type" value="clients">
					<small class="text-gray-50">(máx. 500 caracteres)</small>
				</div>
				<div class="form-group">
					<button class="btn btn-default" >Preguntar</button>
				</div>
			</form>


			<hr>
			<h5>Últimas preguntas:</h5>
			<div id="questions">
				<p>Cargando...</p>
			</div>

		</div>

	</div>
</section>




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
				<a class="btn btn-primary" href="<?= ROOT.'login#'.ROOT.'centros/'.$Clients->data()->permalink ?>">Login</a>
				<hr>
				<p>Todavía no te registraste???</p>
				<a class="btn btn-primary" href="<?= ROOT.'registro' ?>">Registro</a>
			</div>
		</div>
	</div>
</div>


<!-- CENTROS -->
<?php 
$Clients->sort = 'rand';
$Clients->limit = '0,12';
$Clients->visible = 1;
$Clients->exclude = $clientdata->id;
$Clients->get();
$clients_related = $Clients->data();
if($clients_related):
?>
<section class="gral-section">
	<div class="container">
	
		<h3 class="title-bar"><i class="fa fa-heart"></i> Centros Relacionados</h3>
		<div id="clients_carousel" class="clients-carousel">
		<?php			
			foreach($clients_related as $client){
				$logo = json_decode($client->logo);
				$clientlink = ROOT.'centros/'.$client->permalink;
				$Stores->get($client->id);
				include 'mods/mod-client.php';
			}
		?>
		</div>

	</div>	

</section>
<?php endif; ?>

<?php include 'mods/mod-socials.php' ?>







<!-- RESERVA -->
<div class="modal fade" id="modal_reservation" tabindex="-1" role="dialog" >
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3>Reservar un turno en <?= $clientdata->name ?></h3>				
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
							<input type="hidden" name="promoid" value="" >
							<input type="hidden" name="clientid" value="<?=$clientdata->id?>" >
							<input type="hidden" name="sale_hash" value="">

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
