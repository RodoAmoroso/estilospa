
<section class="blog-page">
	<div class="container">

		<div class="blog-container">
			<!-- BREADCRUMB -->
			<ul class="breadcrumb sz-9">
				<li><a href="<?= ROOTPATH ?>">Home</a></li>
				<li><a href="<?= ROOTPATH ?>blog">Blog</a></li>
				<li><?= $_BLOG->data()->title ?></li>
			</ul>

			<div class="title-bar title">
				<h1><?= $_BLOG->data()->title ?></h1>
				<h4><?= $_BLOG->data()->subtitle ?></h4>
				<p class="stores"><i class="fa fa-calendar fa-fw"></i> <?= $_BLOG->data()->fecha ?></p>
			</div>

			<div class="blog-content bg-white">
				<p><?= $_BLOG->data()->shortdescription ?></p>
			</div>

			

			<div class="gallery gallery-section bg-gray-10">
				<?php 
				if(count($bloggallery)):
					foreach($bloggallery as $image):
						$play = '';
						if(isset($image->video)):
							$img = YoutubeAPI($image->video);
							$play = '<div class="play" data-video="'.$image->video.'" ><i class="fa fa-play-circle fa-5x"></i></div>';
						else:
							$img = ROOTPATH.'img/blog/'.$image->photoname.'-o.'.$image->extension;
						endif;
					?>
					<div class="overprint-absolute thumb-contain slide" style="background-image:url(<?= $img ?>);"><?= $play ?></div>
					<?php endforeach; if(count($bloggallery)>1): ?>
					<i class="fa fa-chevron-left prev"></i>
					<i class="fa fa-chevron-right next"></i>
					<?php endif; ?>
					<div class="navigation"></div>
				<?php endif ?>
			</div>			

			

			<div class="blog-content bg-white">
				<?= $_BLOG->data()->content ?>
			</div>

			<?php 
			$arrtags = explode(',',$_BLOG->data()->glossary);
			if(count($arrtags)):
			?>	
			<div class="blog-content bg-white">
				<ul class="button-menu">
					<?php 
					foreach($arrtags as $kt=>$vt):
						if($_GLOSSARY->find($vt)):
					?>
					<li><a href="<?= ROOTPATH.'busqueda/'.Permalink($_GLOSSARY->data()->name) ?>"><?= $_GLOSSARY->data()->name ?></a></li>
					<?php endif; endforeach; 
					?>
				</ul>
			</div>
			<?php endif; ?>

			<div class="comments dp-none">
				<div class="comments-container ">
					<div class="mod-comment">
							<div class="box-user">
								<div class="thumbnail thumb-cover" style="background-image:url(<?= ROOTPATH.'img/users/user-default.png' ?>)">
									<img src="<?= ROOTPATH.'assets/blank-square.gif' ?>" class="wd-100" alt="">
								</div>					
							</div>
							<div class="box-comment">
								<div class="sz-12">USERNAME</div>
								<div class="sz-9">Miembro desde 00/00/0000</div>
								<hr>
								<p class="sz-9">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea accusantium earum voluptates qui corrupti magni consequuntur, est accusamus commodi eius molestias vero dolorum minima molestiae nesciunt. Inventore sunt, eos illum!</p>
								<p class="sz-9">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea accusantium earum voluptates qui corrupti magni consequuntur, est accusamus commodi eius molestias vero dolorum minima molestiae nesciunt. Inventore sunt, eos illum!</p>
							</div>
					</div>
				</div>
			</div>
		</div>

		<!-- BLOG RELATED -->
		<div class="blog-related">
			<h1>Notas Relacionadas</h1>
			<?php 
			$_BLOG->limit = '0,6';
			$_BLOG->arrglossary = $_BLOG->data()->glossary;
			$_BLOG->exclude = $_BLOG->data()->id;
			if($_BLOG->get()):
				foreach($_BLOG->data() as $blog):
			?>
			<a href="<?= ROOTPATH.'blog-pagina/'.$blog->id.'-'.Permalink($blog->title) ?>" class="mod-related">
				<h1><?= $blog->title ?></h1>
				<p><?= $blog->shortdescription ?></p>
			</a>
			<?php endforeach; endif; ?>
			
		</div>

		
		
	</div>
</section>


<!-- OFERTAS -->
<section>

	<div class="container">

		<h3 class="title-bar"><i class="fa fa-shopping-bag"></i> Promos Relacionadas</h3>
		<div class="promos-highlight">
		<?php
		$_PROMOS->status = '1:1';
		$_PROMOS->sort = 'rand';
		$_PROMOS->limit = '0,12';
		if($_PROMOS->get()):
			$nm = 0;
			foreach($_PROMOS->data() as $kp=>$promo):
				if($_CLIENTS->find($promo->idclient)):
					$imgpromo = json_decode($promo->gallery);
					$_STORES->get($_CLIENTS->data()->id,$promo->stores);
					$promolink = ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
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

<!-- CENTROS -->
<section class="gral-section">

	<div class="container">
		
		<h3 class="title-bar"><i class="fa fa-heart"></i> Centros Relacionados</h3>
		<div id="clients_carousel" class="clients-carousel">
		<?php 
			$_CLIENTS->sort = 'rand';
			$_CLIENTS->limit = '0,12';
			$_CLIENTS->visible = 1;
			$_CLIENTS->get();
			foreach($_CLIENTS->data() as $client):
				$logo = json_decode($client->logo);
				$_STORES->get($client->id);
				include 'mods/mod-client.php';
			endforeach;
		?>
		</div>

	</div>

</section>


<?php include 'mods/mod-socials.php' ?>
