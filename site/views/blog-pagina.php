
<section class="blog-page">
	<div class="container">

		<div class="blog-container">
			<!-- BREADCRUMB -->
			<ul class="breadcrumb sz-9">
				<li><a href="<?= ROOT ?>">Home</a></li>
				<li><a href="<?= ROOT ?>blog">Blog</a></li>
				<li><?= $Blog->data()->title ?></li>
			</ul>

			<div class="title-bar title">
				<h1><?= $Blog->data()->title ?></h1>
				<h4><?= $Blog->data()->subtitle ?></h4>
				<p class="stores"><i class="fa fa-calendar fa-fw"></i> <?= $Blog->data()->fecha ?></p>
			</div>

			<div class="blog-content bg-white">
				<p><?= $Blog->data()->shortdescription ?></p>
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
							$img = ROOT.'img/blog/'.$image->photoname.'-o.'.$image->extension;
						endif;
					?>
					<div class="slide" style="background-image:url(<?= $img ?>);background-size:contain;background-repeat:no-repeat;"><?= $play ?></div>
					<?php endforeach; endif; ?>

			</div>



			<div class="blog-content bg-white">
				<?= $Blog->data()->content ?>
			</div>

			<?php
			$arrtags = explode(',',$Blog->data()->glossary);
			if(count($arrtags)):
			?>
			<div class="blog-content bg-white">
				<ul class="button-menu">
					<?php
					foreach($arrtags as $kt=>$vt):
						if($Glossary->find($vt)):
					?>
					<li><a href="<?= ROOT.'busqueda/'.Permalink($Glossary->data()->name) ?>"><?= $Glossary->data()->name ?></a></li>
					<?php endif; endforeach;
					?>
				</ul>
			</div>
			<?php endif; ?>

			<div class="comments dp-none">
				<div class="comments-container ">
					<div class="mod-comment">
							<div class="box-user">
								<div class="thumbnail thumb-cover" style="background-image:url(<?= ROOT.'img/users/user-default.png' ?>)">
									<img src="<?= ROOT.'assets/blank-square.gif' ?>" class="wd-100" alt="">
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
			$Blog->limit = '0,6';
			$Blog->arrglossary = $Blog->data()->glossary;
			$Blog->exclude = $Blog->data()->id;
			if($Blog->get()):
				foreach($Blog->data() as $blog):
			?>
			<a href="<?= ROOT.'blog-pagina/'.$blog->id.'-'.Permalink($blog->title) ?>" class="mod-related">
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

		<h3 class="title-bar"><i class="fa fa-shopping-bag"></i> Experiencias Relacionadas</h3>
		<div class="promos-highlight">
		<?php
		$Promos->status = '1:1';
		$Promos->sort = 'rand';
		$Promos->limit = '0,12';
		if($Promos->get()):
			$nm = 0;
			foreach($Promos->data() as $kp=>$promo):
				if($Clients->find($promo->idclient)):
					$imgpromo = json_decode($promo->gallery);
					$Stores->get($Clients->data()->id,$promo->stores);
					$promolink = ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
					echo '<div class="mod-promo mod-promo-4">';
					include 'mods/mod-promo.php';
					echo '</div>';
				else:
					echo '<p>No se encontraron experiencias relacionadas</p>';
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
			$Clients->sort = 'rand';
			$Clients->limit = '0,12';
			$Clients->visible = 1;
			$Clients->get();
			foreach($Clients->data() as $client):
				$logo = json_decode($client->logo);
				$Stores->get($client->id);
				include 'mods/mod-client.php';
			endforeach;
		?>
		</div>

	</div>

</section>


<?php include 'mods/mod-socials.php' ?>
