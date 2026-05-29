<section class="home-slider">

	<div class="container">

	<div class="sliders owl-carousel owl-theme">
		<?php foreach($banners as $banner): ?>
		<a href="<?= $banner->link->url ? $banner->link->url : '#' ?>" target="<?= $banner->link->blank ? '_self' : '_blank' ?>" class="slide" style="background-image:url(<?= $banner->image->big ?>)">
			<img src="<?= View::assets('blank-banner.gif') ?>" alt="">
		</a>
		<?php endforeach; ?>
	</div>

	</div>

</section>

<section class="home-main-cards">
	<div class="container">
		<h2 class="main-title text-center"><i class="fal fa-spa fa-fw text-aqua-3"></i> ¿Cómo vas a vivir el bienestar hoy?</h2>

		<div class="row mt-4">
			<div class="col-lg-4">
				<div class="main-card">
					<div class="title">Experiencias SPA<br>diseñadas por expertos</div>
					<div class="image" style="background-image:url(<?= View::assets('bg-2.jpg') ?>)">
						<img src="<?= View::assets('blank-square.gif') ?>" alt="">
						<div class="image-bar">La opción más elegida</div>
					</div>
					<div class="description">
						<div class="caption">Sumergite en cientos de experiencias seleccionadas</div>
						<div class="caption italic">Personalizá tu experiencia</div>
						<div class="phrase"><i class="fal fa-spa fa-fw"></i> Ideal para vos o para compartir</div>
					</div>
					<div class="button-outer">
						<a href="#" class="button">
							<i class="fal fa-arrow-right fa-fw"></i> Ver Experiencias
						</a>
					</div>
				</div>
			</div>	
			
			<div class="col-lg-4">
				<div class="main-card">
					<div class="title">GiftCards<br>EstiloSPA</div>
					<div class="image" style="background-image:url(<?= View::assets('bg-3.jpg') ?>)">
						<img src="<?= View::assets('blank-square.gif') ?>" alt="">
						<div class="image-bar">La opción más rápida</div>
					</div>
					<div class="description">
						<div class="caption">Cargás el dinero que deseas regalar</div>
						<div class="caption italic">Tu agasajado lo canjea por la experiencia que desee</div>
						<div class="phrase"><i class="fal fa-spa fa-fw"></i> Ideal para un regalo inolvidable</div>
					</div>
					<div class="button-outer">
						<a href="<?= View::url('giftcard') ?>" class="button">
							<i class="fal fa-arrow-right fa-fw"></i> Crear GiftCard
						</a>
					</div>
				</div>
			</div>	

			<div class="col-lg-4">
				<div class="main-card">
					<div class="title">Pase<br>MultiSPA</div>
					<div class="image" style="background-image:url(<?= View::assets('bg-4.jpg') ?>)">
						<img src="<?= View::assets('blank-square.gif') ?>" alt="">
						<div class="image-bar">La opción más flexible</div>
					</div>
					<div class="description">
						<div class="caption">Elegís el pase ideal</div>
						<div class="caption italic">Quien recibe el pase elige dónde vivir su experiencia.</div>
						<div class="phrase"><i class="fal fa-spa fa-fw"></i> Ideal para un regalo memorable</div>
					</div>
					<div class="button-outer">
						<a href="<?= View::url('pases-multispa') ?>" class="button">
							<i class="fal fa-arrow-right fa-fw"></i> Regalar Pase MultiSPA
						</a>
					</div>
				</div>
			</div>	
		</div>
	</div>
</section>

<hr class="line-dotted">

<?php if($main_categories): ?>
<section class="home-categories">
	<div class="container">

		<h2 class="main-title text-center">
			<i class="fa fa-search fa-fw text-aqua-3"></i> Explora TODAS las Experiencias EstiloSPA
		</h2>

		<div class="home-categories-modules">

			<?php foreach($main_categories as $m_category): ?>
			<div class="home-category-module">
				<div class="category-title <?= $m_category->colour ?>">
					<?= $m_category->name ?>
				</div>
				<?php if($m_category->categories): ?>
				<ul class="category-list">
					<?php
					foreach($m_category->categories as $category):
						if(!$category->visible) continue;
					?>
					<li>
						<a href="<?= $category->permalink ?>"><?= $category->name ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>

		</div>


	</div>
</section>
<?php endif; ?>


<section class="how-it-works">
	<div class="container">
	
		<div class="title d-none">
			<div class="title-bar"></div>
			<div class="title-button">¿Cómo Funciona?</div>
		</div>

		<div class="text-center py-3 d-none">
			<div class="caption">Podés seleccionar una de las diferentes experiencias especialmentes diseñadas</div>
			<div class="caption">ó</div>
			<div class="caption">Podés regalar una <b class="ff-birthstone">Gift Card</b> para que el agasajado canjee el valor por la experiencia que desee.</div>
		</div>

		<div class="row justify-content-center my-3 d-none">
			<div class="col-lg-4 mb-3">
				<a href="#" class="gift-card shadow">
					<img src="<?= View::assets('blank-wide-2.gif') ?>" alt="" class="w-100">
					<div class="border"></div>
					<div class="icon">
						<i class="fal fa-spa"></i>
					</div>
					<div class="card-caption">
						<div class="card-caption-inside">
							<div class="title-caption">Seleccioná una<br><b class="ff-birthstone">Experiencia Única</b></div>
							<div class="small-caption">Vos o tu agasajado disfruta la experiencia que seleccionaste</div>
						</div>
					</div>
				</a>
			</div>
			
			<div class="col-lg-4 mb-3">
				<a href="<?= View::url('giftcards') ?>" class="gift-card shadow">
					<img src="<?= View::assets('blank-wide-2.gif') ?>" alt="" class="w-100">
					<div class="border"></div>
					<div class="icon">
						<i class="fal fa-gift-card"></i>
					</div>
					<div class="card-caption">
						<div class="card-caption-inside">
							<div class="title-caption">Regalá una <br><b class="ff-birthstone">Gift Card</b></div>
							<div class="small-caption">Tu agasajado canjea el valor de la Gift Card por la experiencia que desee</div>
						</div>
					</div>
				</a>
			</div>

		</div>



		<div class="title mt-4">
			<div class="title-bar"></div>
			<div class="title-button">¿Cómo descubro mi regalo?</div>
		</div>
		<div class="row justify-content-center align-items-center mt-4">
			<div class="col-lg-4 text-center">
				<div class="step">
					<i class="fal fa-gift-card fa-fw"></i>
					<span>Ingresá tu código</span>
				</div>
			</div>
			<div class="col-lg-4 text-center">
				<div class="step">
					<i class="fal fa-gift fa-fw"></i>
					<span>Descubrí tu Regalo</span>
				</div>
			</div>
			<div class="col-lg-4 text-center">
				<div class="step">
					<i class="fal fa-gift-card fa-fw"></i>
					<span>Reservá tu Experiencia</span>
				</div>
			</div>
		</div>

	</div>
</section>


<section class="giftcard-section border-top">
	<div class="container">		

		<div class="row mb-5 align-items-center justify-content-centerx ">

			<div class="col-lg-5">

				<h1 class="main-title"><i class="fal fa-gift fa-fw"></i> GiftCard EstiloSPA</h1>
				<h4>La opción más rápida</h4>
				<h5>Ideal para un regalo inolvidable</h5>

				<h5 class="main-title mt-5">Seleccioná el valor de la GiftCard</h5>
				<input type="range" min="100" max="10000" value="500" class="giftcard-price-range w-100">


				<h5 class="main-title">Acceso a +36 de Experiencias</h5>
				
			</div>

			<div class="col-lg-2"></div>

			<div class="col-lg-5 text-center">
				

				<div class="gift-card shadow my-3">
					<img src="<?= View::assets('blank-wide-2.gif') ?>" alt="" class="w-100">
					<div class="border"></div>
					<div class="icon">
						<i class="fal fa-spa"></i>
					</div>
					<div class="card-caption">
						<div class="card-caption-inside">
							<div class="subtitle-caption">Seleccioná una<br><b class="ff-birthstone">Experiencia Única</b></div>
							<h3 class="price-selection">$ 100.000</h3>
							<div class="small-caption">Vos o tu agasajado disfruta la experiencia que seleccionaste</div>
						</div>
					</div>
				</div>
				
				<button class="btn btn-aqua-2">
					<i class="fal fa-shopping-bag fa-fw"></i> Comprar GiftCard
				</button>				
				
			</div>			
			
		</div>		

	</div>
</section>




<!-- PROMOS -->
<section class="home-carousel bg-gray-5">


	<div class="container">

		<div class="text-center">
			<h2 class="main-title">
				<i class="fal fa-heart text-aqua-3 fa-fw"></i> <span>Experiencias más elegidas</span>
			</h2>
		</div>

		<div class="promos-highlight mt-4">
		<?php
		if($experiencies_featured):
			$nm = 0;
			foreach($experiencies_featured as $kp=>$promo):
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

		<div class="text-center mt-4">
			<a href="<?=View::url('busqueda')?>" class="btn btn-sm btn-aqua-4">
				<i class="fal fa-angle-right fa-fw"></i> ver más
			</a>
		</div>

	</div>
</section>
