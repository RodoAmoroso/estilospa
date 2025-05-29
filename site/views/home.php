<section class="home-slider">

	<div class="sliders owl-carousel owl-theme">
		<?php foreach($banners as $banner): ?>
		<a href="<?= $banner->link->url ? $banner->link->url : '#' ?>" target="<?= $banner->link->blank ? '_self' : '_blank' ?>" class="slide" style="background-image:url(<?= $banner->image->big ?>)">
			<img src="<?= View::assets('blank-banner.gif') ?>" alt="">
		</a>
		<?php endforeach; ?>
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
