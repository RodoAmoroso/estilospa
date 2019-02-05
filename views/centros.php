
<script>var IDClient = <?= $_CLIENTS->data()->id ?>;</script>


<!-- OVERVIEW -->
<section class="overview">
	<div class="container">

		<!-- BREADCRUMB -->
		<ul class="breadcrumb sz-9 bg-white">
			<li><a href="<?= ROOTPATH ?>">Home</a></li>
			<li><a href="<?= ROOTPATH ?>busqueda/centros-de-estetica">Centros de Estética</a></li>
			<li><?= $_CLIENTS->data()->name ?></li>
		</ul>
		

		<!-- OVERVIEW HEADER -->
		<div class="overview-header">

			<!-- INFO -->
			<div class="info">

				<div class="header-info">			
					<div class="logo thumb-contain" style="background-image:url(<?= ROOTPATH.'img/clients/'.$logoclient->photoname.'.'.$logoclient->extension ?>)"></div>
					<div class="title">
						<h1 class="fw-700"><?= $_CLIENTS->data()->name ?></h1>
						<p><?= $_CLIENTS->data()->subtitle ?></p>
					</div>			
				</div>
				
				<div class="contact-info">
					<!-- Address -->
					<div class="address sz-12"><i class="fa fa-map-marker fa-fw icon"></i> <?= $_FIRSTADDRESS ?></div>
					<?php if(count($_STORESCLIENT->data())>1): ?>
					<div class="more-stores"><a href="#sucursales" class="sz-10"><i class="fa fa-map-marker fa-fw icon"></i>  más sucursales (ver mapa)</a></div>
					<?php endif; ?>

					<!-- Phones -->
					<?php if(!empty($_STORESCLIENT->data()[0]->phones)): ?><div class="phones sz-12"><i class="fa fa-phone fa-fw icon"></i> <a href="tel:<?=str_replace(' ', '', $_STORESCLIENT->data()[0]->phones)?>"><?= $_STORESCLIENT->data()[0]->phones ?></a></div> <?php endif; ?>

					<!-- WhatsApp -->
					<?php if(!empty($_STORESCLIENT->data()[0]->whatsapp)): ?><div class="whatsapp sz-12"><i class="fa fa-whatsapp fa-fw icon"></i> <a href="https://api.whatsapp.com/send?phone=549<?= str_replace(' ', '', $_STORESCLIENT->data()[0]->whatsapp) ?>&text=Mensaje Enviado%20desde%20EstiloSPA%20"><?= $_STORESCLIENT->data()[0]->whatsapp ?></a></div> <?php endif; ?>

					<!-- Web -->
					<?php 
					if(!empty($_CLIENTS->data()->web)):
						$linkweb = preg_match('/(https)(http)/',$_CLIENTS->data()->web) ? $_CLIENTS->data()->web : 'http://'.$_CLIENTS->data()->web;
					?>
					<div class="web sz-11"><i class="fa fa-link fa-fw icon"></i> <a href="<?= $linkweb ?>" target="_blank" ><?= $_CLIENTS->data()->web ?></a></div> 
					<?php endif; ?>

					<!-- Schedules -->
					<?php if(!empty($_STORESCLIENT->data()[0]->schedules)): ?>						
					<div class="sz-10 schedules">
						<span><i class="fa fa-calendar fa-fw icon"></i> <?= $_STORESCLIENT->scheduleToday($_STORESCLIENT->data()[0]->schedules) ?> <i class="fa fa-caret-down fa-fw"></i></span>
						<div id="schedules_block" class="schedules-block">
							<?php foreach($_STORESCLIENT->schedulesList($_STORESCLIENT->data()[0]->schedules) as $day): ?>
								<p class="dropdown-item"><?php print_r($day) ?></p>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif ?>
				</div>


				<div class="box-icons ">

					<!-- Stars -->
					<div class="item pad-10" >
						<div class="outer">
							<div class="inner">
								<div class="rating">
								<?php $promstars = $_CLIENTS->rating($_CLIENTS->data()->id); ?>
								<div class="punctuation"><?= round($promstars,1) ?>/5</div>
								<div class="stars">
									<?= Stars($promstars,'fa-lg'); ?>
								</div>
								</div>
							</div>
							
							<!-- FAVS -->
							<div class="inner"><?= Fav(); ?></div>

							<!-- VIEWS -->
							<div class="inner">
								<div class="views">
									<i class="fa fa-eye fa-lg"></i><br /><span><?= number_format($_CLIENTS->data()->views,0,'','.') ?> visitas</span>
								</div>
							</div>
						</div>
					</div>

					<div class="item">
						<div class="outer"><div class="inner">
						<?php
						$socials = json_decode($_CLIENTS->data()->socials);
						if(count($socials)):
							foreach($socials as	$social):
						?>
						<a href="<?= $social->link ?>" target="_blank" class="fa-stack">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-<?= $social->social ?> fa-stack-1x fa-inverse"></i>
						</a>
						<?php endforeach; endif; ?>
						</div></div>
					</div>
				</div>

				<div class="pad-10 ">
					<button data-mail="<?= $_CLIENTS->data()->mail ?>" data-toggle="modal" data-target="#modal_client_request" class="btn btn-cyan btn-block"><i class="fa fa-envelope fa-fw"></i> Consultar</button>
				</div>

			</div>	

			<!-- GALLERY -->
			<div class="gallery gallery-section">
				<?php 
				$gallery = json_decode($_CLIENTS->data()->images);
				if(count($gallery)):
					foreach($gallery as $kg=>$vg):
						$play = '';
						if(isset($vg->video)):
							$ytapi = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$vg->video.'&key=AIzaSyAq3a2AC4jXd9AVmt646ZP_45Vd3oLJn7g&part=snippet'));							
							$img = $ytapi->items[0]->snippet->thumbnails->high->url;
							$play = '<div class="play" data-video="'.$vg->video.'" ><i class="fa fa-play-circle fa-5x"></i></div>';
						else:
							$img = ROOTPATH.'img/clients/'.$vg->photoname.'-o.'.$vg->extension;
						endif;
				?>
				<div class="overprint-absolute thumb-contain slide" style="background-image:url(<?= $img ?>);" ><?= $play ?></div>
				<?php endforeach; if(count($gallery)>1): ?>
				<i class="fa fa-chevron-left prev"></i>
				<i class="fa fa-chevron-right next"></i>
				<?php endif; ?>
				<div class="navigation"></div>
				<?php endif ?>


			</div>		

		</div>


		<!-- PROMOS -->
		<?php
		$_PROMOS->idclient = $_CLIENTS->data()->id;
		if($_PROMOS->get()):
		?>
		<h4 class="title-bar overview-title">Promos Vigentes</h4>
		<div class="overview-promos">
			
			<div class="row">
				<?php
					$nm = 0;
					foreach($_PROMOS->data() as $promo): 
						echo '<div class="col-sm-4">';
						$imgpromo = json_decode($promo->gallery);
						$_STORES = new Stores();
						$_STORES->get($_CLIENTS->data()->id,$promo->stores);
						include 'mods/mod-promo.php';
						if(count($colorsequence)-1 == $nm){$nm = 0;}else{$nm++;}
						echo '</div>';
					endforeach;
				?>				
			</div>
		</div>
		<?php endif; ?>


		<!-- FEATURES -->	
		<div class="overview-info">			

			<ul class="nav nav-tabs" role="tablist">
				<?php if(count($_FEATURES->data())): foreach($_FEATURES->data() as $kf=>$vf): ?>
				<li role="presentation" class="<?= $kf ? '' : 'active' ?> text-uppercase"><a href="#tab_<?= $kf ?>" aria-controls="tab_<?= $kf ?>" role="tab" data-toggle="tab"><?= $vf->title ?></a></li>
				<?php endforeach; endif; ?>				
			</ul>
			<div class="tab-content">
				<?php if(count($_FEATURES->data())): foreach($_FEATURES->data() as $kf=>$vf): ?>
				<div role="tabpanel" class="tab-pane fade in <?= $kf ? '' : 'active' ?>" id="tab_<?= $kf ?>"><?= $vf->description ?></div>
				<?php endforeach; endif; ?>
			</div>

		</div>



		<!-- MAP -->
		<h4 id="sucursales" class="title-bar title" >Locales / Mapa</h4>
		<div class="overview-info">
			<div class="row">
				<div class="col-xs-12 <?= count($_STORESCLIENT->data())>1 ? 'col-sm-8' : '' ?>">

					<div class="stores-highlight">
						<h3 class="city fw-400"></h3>
						<h4 class="address"></h4>
						<h4 class="phones" ></h4>
						<h4 class="whatsapp" ></h4>
						<div class="sz-10 schedules">
							<div class="today"><i class="fa fa-calendar fa-fw icon"></i> <span></span> <i class="fa fa-caret-down fa-fw"></i></div>
							<div class="schedules-block">
								<?php foreach($_STORESCLIENT->schedulesList($_STORESCLIENT->data()[0]->schedules) as $day): ?>
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
				<div class="col-xs-12 col-sm-4 <?= count($_STORESCLIENT->data())>1 ? '' : 'dp-none' ?>">
					
					<div id="stores">
						<div class="list-group">
						<script>var addresses = [];</script>
							<?php 
							if(count($_STORESCLIENT->data())): 
								foreach($_STORESCLIENT->data() as $ks=>$vs):
									$addresses[] = $vs->address.', '.$vs->city;
							?>
							<script>addresses.push('<?= $vs->address.", ".$vs->city.", ".$vs->name.", AR" ?>');</script>
							<a href="#" data-id="<?= $vs->id ?>" class="list-group-item">
								<h4 class="list-group-item-heading"><?= $vs->city.', '.$_PROVINCES[$vs->idprovince] ?></h4>
								<ol>
									<li><?= $vs->address.(empty($vs->additional) ? '' : ' - '.$vs->additional) ?></li>
								</ol>
							</a>
							<?php endforeach; endif; ?>
						</div>
					</div>

				</div>
			</div>	
			<?php if(count($_STORESCLIENT->data())>1): ?>
			<p class="sz-9">Click en cada sucursal para ver la ubicación</p>
			<?php endif; ?>	
		
		</div>

		<?php 
			$arrtags = explode(',',$_CLIENTS->data()->glossary);
			if(count($arrtags)):
			?>	
			<h4 class="title-bar title" >Etiquetas</h4>
			<div class="overview-info">
				<ul class="button-menu">
					<?php 
					foreach($arrtags as $kt=>$vt):
						if($_GLOSSARY->find($vt)):
					?>
					<li><a href="<?= ROOTPATH.'busqueda/'.Permalink($_GLOSSARY->data()->name).'/' ?>"><?= $_GLOSSARY->data()->name ?></a></li>
					<?php endif; endforeach; 
					?>
				</ul>
			</div>
			<?php endif; ?>


		<!-- COMMENTS -->
		<h4 class="title-bar title dp-none">Opiniones</h4>
		<div class="comments dp-none">
			<div class="comments-container">

				<div class="mod-comment">
						<div class="box-user">
							<div class="thumbnail">
								<img src="<?= ROOTPATH.'assets/blank-square.gif' ?>" class="wd-100" alt="">
							</div>					
						</div>
						<div class="box-comment bg-white">
							<div class="sz-12">USERNAME</div>
							<div class="sz-9">Miembro desde 00/00/0000</div>
							<hr>
							<p class="sz-9">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea accusantium earum voluptates qui corrupti magni consequuntur, est accusamus commodi eius molestias vero dolorum minima molestiae nesciunt. Inventore sunt, eos illum!</p>
							<p class="sz-9">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea accusantium earum voluptates qui corrupti magni consequuntur, est accusamus commodi eius molestias vero dolorum minima molestiae nesciunt. Inventore sunt, eos illum!</p>
						</div>
				</div>

				<div class="mod-comment">
						<div class="box-user">
							<div class="thumbnail">
								<img src="<?= ROOTPATH.'assets/blank-square.gif' ?>" class="wd-100" alt="">
							</div>					
						</div>
						<div class="box-comment bg-white">
							<div class="sz-12">USERNAME</div>
							<div class="sz-9">Miembro desde 00/00/0000</div>
							<hr>
							<p class="sz-9">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea accusantium earum voluptates qui corrupti magni consequuntur, est accusamus commodi eius molestias vero dolorum minima molestiae nesciunt. Inventore sunt, eos illum!</p>
						</div>
				</div>

				</div>
		</div>


	</div>
</section>


<!-- MESSAGES -->
<div class="modal fade" id="modal_client_request" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Consultar a <?= $_CLIENTS->data()->name ?></h4>
			</div>
			<div class="modal-body ff-futuralight" >
				<form id="form_client_request">
					<div class="form-group">
						<label for="fd_name">Nombre y Apellido</label>
						<input id="fd_name" type="text" name="name" class="form-control" required>
					</div>
					<div class="row">
						<div class="col-xs-12 col-xs-6">
							<div class="form-group">
								<label for="fd_phone">Teléfono</label>
								<input id="fd_phone" type="text" name="phone" class="form-control" required>
							</div>
						</div>
						<div class="col-xs-12 col-xs-6">
							<div class="form-group">
								<label for="fd_mail">E-mail</label>
								<input id="fd_mail" type="email" name="mail" class="form-control" required>
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



<!-- CENTROS -->
<section class="gral-section">
	<div class="container">
	
		<h3 class="title-bar"><i class="fa fa-heart"></i> Centros Relacionados</h3>
		<div id="clients_carousel" class="clients-carousel">
		<?php 
			$_CLIENTS->sort = 'rand';
			$_CLIENTS->limit = '0,12';
			$_CLIENTS->visible = 1;
			$_CLIENTS->exclude = $_CLIENTS->data()->id;
			$_CLIENTS->get();
			foreach($_CLIENTS->data() as $client):
				$logo = json_decode($client->logo);
				$clientlink = ROOTPATH.'centros/'.$client->permalink;
				$_STORES->get($client->id);
				include 'mods/mod-client.php';
			endforeach;
		?>
		</div>

	</div>	

</section>

<?php include 'mods/mod-socials.php' ?>



<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2m93XcFMuCAPZSjBUNsZO24UJOSPSF1M" sync defer></script>