<?php if($banners): ?>
<section class="slider-wide">

	<div class="container">

		<div class="sliders">
			<?php foreach($banners as $banner): ?>
			<a href="<?= $banner->link->url ? $banner->link->url : '#' ?>" target="<?= $banner->link->blank ? '_self' : '_blank' ?>" class="slide" style="background-image:url(<?= View::img('home',$banner->image->photoname.'-o.'.$banner->image->extension) ?>);background-position:<?= $banner->image->position ?>">
				<!-- <img src="" alt="" class="w-100"> -->
			</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
<?php endif; ?>



<?php if($promo_categories): ?>
<!-- CATEGORIES -->
<section class="gral-section" style="padding:1.5rem 0">
	<div class="container">
		<div class="categories">

			<?php foreach($promo_categories as $category): ?>
			<div class="category">
				<a href="<?=ROOT.'categoria/'.$category->id.'-'.permalink($category->name) ?>" class="wrapper">
					<div class="image" style="background-image:url(<?= $category->image->small ?>)"></div>
					<div class="caption"><?=$category->name?></div>
				</a>
			</div>
			<?php endforeach; ?>

		</div>
	</div>
</section>
<?php endif; ?>




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
					echo '<p>No se encontraron experiencia vigentes</p>';
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