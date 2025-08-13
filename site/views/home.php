<section class="home-slider">

	<div class="containerx">

	<div class="sliders owl-carousel owl-theme">
		<?php foreach($banners as $banner): ?>
		<a href="<?= $banner->link->url ? $banner->link->url : '#' ?>" target="<?= $banner->link->blank ? '_self' : '_blank' ?>" class="slide" style="background-image:url(<?= $banner->image->big ?>)">
			<img src="<?= View::assets('blank-banner.gif') ?>" alt="">
		</a>
		<?php endforeach; ?>
	</div>

	</div>

</section>

<?php if($main_categories): ?>
<section class="home-categories">
	<div class="container">

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
	
		<div class="title">
			<div class="title-bar"></div>
			<div class="title-button">¿Cómo Funciona?</div>
		</div>

		<div class="text-center py-3">
			<div class="caption">Podés seleccionar una de las diferentes experiencias especialmentes diseñadas</div>
			<div class="caption">ó</div>
			<div class="caption">Podés regalar una <b class="ff-birthstone">Gift Card</b> para que el agasajado canjee el valor por la experiencia que desee.</div>
		</div>

		<div class="row justify-content-center my-3">
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

<!-- PROMOS -->
<section class="home-carousel bg-gray-5">


	<div class="container">

		<div class="text-center">
			<h2 class="main-title">
				<i class="fa fa-heart text-fucsia-3 fa-fw"></i> <span>Experiencias más elegidas</span>
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
			<a href="<?=View::url('busqueda')?>" class="btn btn-sm btn-outline-dark">
				<i class="fal fa-angle-right fa-fw"></i> ver más
			</a>
		</div>

	</div>
</section>
