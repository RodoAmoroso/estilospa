<?php if($experiences): ?>

<section class="search-page">
	<div class="container-fluid">

		<div class="search-container">

			<div class="left-column hidden-xs hidden-sm">
				<?php if($category): ?>
				<h3><?=$category->name?></h3>
				<?php endif; ?>

				<?php if($_idsection): ?>
				<h4>en <?=$_idsection?></h4>
				<?php endif; ?>



				<?php if($stores): ?>
				<hr>
				<ul id="list_zones" class="list collapse in">
					<?php foreach($stores as $store): ?>
					<li><a href="<?= ROOT.'categoria/'.($category ? $category->id.'-'.Permalink($category->name) : '-').'/'.urlencode($store->city) ?>"><?=$store->city?></a></li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>

			</div>


			<div class="right-column">



				<div class="promos-highlight">

					<?php
					foreach($experiences as $kp=>$promo):
						$imgpromo = $promo->image;
						$Stores->get($promo->idclient);
						$promolink = $promo->link;
					?>
					<div class="mod-promo mod-promo-5">
					<?php include PATH.'mods/mod-promo.php'; ?>
					</div>
					<?php endforeach; ?>


				</div>
				<?php if(count($total_experiences)>count($experiences)): ?>
				<hr>
				<form id="load_more">
					<button class="btn btn-fucsia"><i class="fa fa-angle-down fa-fw"></i> Cargar más</button>
					<input type="hidden" name="limit" value="<?=$limit?>">
					<input type="hidden" name="page" value="<?=$page?>">
					<input type="hidden" name="categoryid" value="<?=$category ? $category->id : '' ?>">
				</form>
				<p>&nbsp;</p>
				<?php endif; ?>

			</div>

		</div>

	</div>

</section>

<?php else: ?>

<section class="gral-section">
	<div class="container text-center">
		<h3>No pudimos encontrar experiencias que coincidan con <?= $category->name ?></h3>
	</div>
</section>
<?php endif; ?>