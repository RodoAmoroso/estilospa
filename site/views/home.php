
<!-- MAIN SLIDER -->
<section class="home-slider">
	<div class="container-fluid">
		<div class="row">

			<div class="col-left col-xs-12 col-sm-4">
				
				<div class="mod-slide">					
					<img src="<?= ROOT ?>assets/blank-square-2.gif" alt="" class="wd-100 blank">

					<?php 
					$Banners->visible = 1;
					$Banners->type = 'main';
					$Banners->sort = 'position';
					$Banners->limit = '0,1';
					if($Banners->get()): 
						foreach($Banners->data() as $banner): 
							$img = json_decode($banner->image);
							$link = json_decode($banner->link);
							$href = empty($link->url) ? '' : 'href="'.$link->url.'"';
							$target = $link->blank ? '' : 'target="_blank"';
					?>					
					<a <?= $href.' '.$target ?> class="slide" >
						<div class="overprint-absolute" style="background-image:url(<?= ROOT.'img/home/'.$img->photoname.'-o.'.$img->extension ?>);background-position:<?= $img->position ?>" ></div>
						<?php if(!empty($banner->title)): ?>
						<div class="caption">
							<h3><?= $banner->title ?></h3>
							<p><?= $banner->caption ?></p>
						</div>
						<?php endif; ?>
					</a>
					<?php endforeach;	endif; ?>

					
					<!-- <i class="fa fa-chevron-left prev"></i>
					<i class="fa fa-chevron-right next"></i>
					<div class="navigation"></div>-->
					
				</div>

			</div>

			<div class="col-left col-xs-12 col-sm-4">
				
				<div class="mod-slide mod-slide-2">					
					<img src="<?= ROOT ?>assets/blank-square-2.gif" alt="" class="wd-100 blank">

					<?php 
					$Banners->visible = 1;
					$Banners->type = 'main';
					$Banners->sort = 'position';
					$Banners->limit = '1,1';
					if($Banners->get()): 
						foreach($Banners->data() as $banner): 
							$img = json_decode($banner->image);
							$link = json_decode($banner->link);
							$href = empty($link->url) ? '' : 'href="'.$link->url.'"';
							$target = $link->blank ? '' : 'target="_blank"';
					?>					
					<a <?= $href.' '.$target ?> class="slide" >
						<div class="overprint-absolute" style="background-image:url(<?= ROOT.'img/home/'.$img->photoname.'-o.'.$img->extension ?>);background-position:<?= $img->position ?>" ></div>
						<?php if(!empty($banner->title)): ?>
						<div class="caption">
							<h3><?= $banner->title ?></h3>
							<p><?= $banner->caption ?></p>
						</div>
						<?php endif; ?>
					</a>
					<?php endforeach;	endif; ?>

					
					<!-- <i class="fa fa-chevron-left prev"></i>
					<i class="fa fa-chevron-right next"></i>
					<div class="navigation"></div> -->
					
				</div>

			</div>

			<div class="col-right col-xs-12 col-sm-4">
				<?php 
				$Banners->visible = 1;
				$Banners->type = 'side';
				$Banners->sort = 'rand';
				$Banners->limit = '0,2';
				if($Banners->get()):
					foreach($Banners->data() as $banner):
						$img = json_decode($banner->image);
						$link = json_decode($banner->link);
						$href = empty($link->url) ? '' : 'href="'.$link->url.'"';
						$target = $link->blank ? '' : 'target="_blank"';
				?>
				<a <?= $href.' '.$target ?> class="mod-slide ">					
					<img src="<?= ROOT ?>assets/blank-wide.gif" alt="" class="wd-100">
					<div class="overprint-absolute" style="background-image:url(<?= ROOT.'img/home/'.$img->photoname.'-o.'.$img->extension ?>);background-position:<?= $img->position ?>"></div>
				</a>
				<?php endforeach; endif; ?>			

			</div>

		</div>
	</div>
</section>


<!-- PROMOS -->
<section class="home-carousel bg-gray-5">	
	<div class="container text-center cl-gray-60 pad-20">
		<h2><i class="fa fa-shopping-bag"></i> Ofertas Imperdibles!!!</h2>
		<a href="<?=View::url('busqueda')?>">ver más</a>
	</div>

	<div class="container">

		<div class="promos-highlight">
		<?php
		$Promos->status = '1:1';
		$Promos->sort = 'rand';
		$Promos->limit = '0,12';
		$Promos->issale = 1;
		$Promos->get();
		if($Promos->data()):
			$nm = 0;
			foreach($Promos->data() as $kp=>$promo):
				if($Clients->find($promo->idclient)):
					$imgpromo = json_decode($promo->gallery);
					$promolink = ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
					$Stores->get($Clients->data()->id,$promo->stores);
					echo '<div class="mod-promo mod-promo-4">';
					include 'mods/mod-promo.php';
					echo '</div>';
				else:
					echo '<p>No se encontraron promociones vigentes</p>';
				endif;
			endforeach;
		endif;
		?>
		</div>
	</div>
</section>


<!-- SERVICIOS DESTACADOS -->
<section class="home-carousel ">
	
	<div class="container text-center cl-gray-60 pad-20">
		<h2><i class="fa fa-leaf"></i> Servicios y Tratamientos más buscados</h2>
		
	</div>

	<div class="container">

		<div id="glossary_carousel" class="row">
		<?php 
		$Glossary->limit = '0,12';
		$Glossary->sort = 'rand';
		if($Glossary->get('',0,12)):
			$nm = 0;
			foreach($Glossary->data() as $glossary):
				$img = 'img/bg/bg-1.jpg';
				if(!empty($glossary->image)){
				 $imgjson = json_decode($glossary->image);
				 $img = 'img/glossary/'.$imgjson->photoname.'.'.$imgjson->extension;
				}
				include 'mods/mod-glossary.php';
				if(count($colorsequence)-1 == $nm){$nm = 0;}else{$nm++;}
			endforeach; 
		endif; 
		?>
	</div>

	</div>
		
</section>
<p>&nbsp;</p>


<!-- CENTROS -->
<section class="bg-gray-5">
	<div class="container text-center cl-gray-60 pad-20">
		<h2><i class="fa fa-heart"></i> Centros Destacados!!!</h2>
	</div>


	<div class="container">
		<!--<h3 class="title-bar"><i class="fa fa-heart"></i> Centros Destacados!!!</h3>-->

		<div id="clients_carousel" class="clients-carousel dp-none">

			<?php 
			$Clients->sort = 'rand';
			$Clients->limit = '0,12';
			$Clients->visible = 1;
			if($Clients->get()):
				foreach($Clients->data() as $client):
					$logo = json_decode($client->logo);
					$clientlink = ROOT.'centros/'.$client->permalink;
					$Stores->get($client->id);
					include 'mods/mod-client.php';
				endforeach;
			endif;
			?>

		</div>

	</div>

</section>


<!-- NOTICIAS -->
<section class="news-home">

	<div class="container text-center cl-gray-60 pad-20">		
		<h2><i class="fa fa-newspaper-o"></i>  Notas &bullet; Noticias &bullet; Blog</h2>
		<a href="<?=ROOT.'blog'?>">ver todas</a>
	</div>
		
	<div class="container">
		<div id="blog_carousel" class="carousel">
			<?php
			$Blog->limit = '0,8';
			if($Blog->get()):
				foreach($Blog->data() as $blog):
					$img = json_decode($blog->gallery);
					include 'mods/mod-blog.php';
				endforeach;
			endif;
			?>
		</div>
	</div>

</section>