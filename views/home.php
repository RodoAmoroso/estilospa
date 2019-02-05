
<!-- MAIN SLIDER -->
<section class="home-slider">
	<div class="container-fluid">
		<div class="row">

			<div class="col-left col-xs-12 col-sm-4">
				
				<div class="mod-slide">					
					<img src="<?= ROOTPATH ?>assets/blank-square-2.gif" alt="" class="wd-100 blank">

					<?php 
					$_BANNERS->visible = 1;
					$_BANNERS->type = 'main';
					$_BANNERS->sort = 'position';
					$_BANNERS->limit = '0,1';
					if($_BANNERS->get()): 
						foreach($_BANNERS->data() as $banner): 
							$img = json_decode($banner->image);
							$link = json_decode($banner->link);
							$href = empty($link->url) ? '' : 'href="'.$link->url.'"';
							$target = $link->blank ? '' : 'target="_blank"';
					?>					
					<a <?= $href.' '.$target ?> class="slide" >
						<div class="overprint-absolute" style="background-image:url(<?= ROOTPATH.'img/home/'.$img->photoname.'-o.'.$img->extension ?>);background-position:<?= $img->position ?>" ></div>
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
					<img src="<?= ROOTPATH ?>assets/blank-square-2.gif" alt="" class="wd-100 blank">

					<?php 
					$_BANNERS->visible = 1;
					$_BANNERS->type = 'main';
					$_BANNERS->sort = 'position';
					$_BANNERS->limit = '1,1';
					if($_BANNERS->get()): 
						foreach($_BANNERS->data() as $banner): 
							$img = json_decode($banner->image);
							$link = json_decode($banner->link);
							$href = empty($link->url) ? '' : 'href="'.$link->url.'"';
							$target = $link->blank ? '' : 'target="_blank"';
					?>					
					<a <?= $href.' '.$target ?> class="slide" >
						<div class="overprint-absolute" style="background-image:url(<?= ROOTPATH.'img/home/'.$img->photoname.'-o.'.$img->extension ?>);background-position:<?= $img->position ?>" ></div>
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
				$_BANNERS->visible = 1;
				$_BANNERS->type = 'side';
				$_BANNERS->sort = 'rand';
				$_BANNERS->limit = '0,2';
				if($_BANNERS->get()):
					foreach($_BANNERS->data() as $banner):
						$img = json_decode($banner->image);
						$link = json_decode($banner->link);
						$href = empty($link->url) ? '' : 'href="'.$link->url.'"';
						$target = $link->blank ? '' : 'target="_blank"';
				?>
				<a <?= $href.' '.$target ?> class="mod-slide ">					
					<img src="<?= ROOTPATH ?>assets/blank-wide.gif" alt="" class="wd-100">
					<div class="overprint-absolute" style="background-image:url(<?= ROOTPATH.'img/home/'.$img->photoname.'-o.'.$img->extension ?>);background-position:<?= $img->position ?>"></div>
				</a>
				<?php endforeach; endif; ?>			

			</div>

		</div>
	</div>
</section>


<!-- OFERTAS -->
<section class="home-carousel">	
	<div class="container">
		<h3 class="title-bar"><i class="fa fa-shopping-bag"></i> Ofertas Imperdibles!!!</h3>
		<div class="promos-highlight">
		<?php
		$_PROMOS->status = '1:1';
		$_PROMOS->sort = 'rand';
		$_PROMOS->limit = '0,12';
		$_PROMOS->issale = 1;
		if($_PROMOS->get()):
			$nm = 0;
			foreach($_PROMOS->data() as $kp=>$promo):
				if($_CLIENTS->find($promo->idclient)):
					$imgpromo = json_decode($promo->gallery);
					$_STORES->get($_CLIENTS->data()->id,$promo->stores);
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


<!-- SERVICIOS DESTACADOS -->
<section class="home-carousel ">
	<div class="container">

		<h3 class="title-bar" ><i class="fa fa-leaf"></i> Servicios y Tratamientos más buscados</h3>
		<div id="glossary_carousel" class="row">
		<?php 
		$_GLOSSARY->limit = '0,12';
		$_GLOSSARY->sort = 'rand';
		if($_GLOSSARY->get('',0,12)):
			$nm = 0;
			foreach($_GLOSSARY->data() as $glossary):
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
<section class="home-carousel">
	<div class="container">
		<h3 class="title-bar"><i class="fa fa-heart"></i> Centros Destacados!!!</h3>

		<div id="clients_carousel" class="clients-carousel">

			<?php 
			$_CLIENTS->sort = 'rand';
			$_CLIENTS->limit = '0,12';
			$_CLIENTS->visible = 1;
			if($_CLIENTS->get()):
				foreach($_CLIENTS->data() as $client):
					$logo = json_decode($client->logo);
					$clientlink = ROOTPATH.'centros/'.$client->permalink;
					$_STORES->get($client->id);
					include 'mods/mod-client.php';
				endforeach;
			endif;
			?>

		</div>

	</div>

</section>


<!-- NOTICIAS -->
<section class="news-home">
	<div class="container">
		
		<h3 class="title-bar"><i class="fa fa-newspaper-o"></i> Notas &bullet; Noticias &bullet; Blog</h3>
		
		<div id="blog_carousel" class="carousel">
			<?php
			$_BLOG->limit = '0,8';
			if($_BLOG->get()):
				foreach($_BLOG->data() as $blog):
					$img = json_decode($blog->gallery);
					include 'mods/mod-blog.php';
				endforeach;
			endif;
			?>
		</div>
	</div>

</section>