<?php if($experiences): ?>

<section class="search-page">
	<div class="container-fluid">

		<div class="search-container">

			<div class="left-column">
				<?php if($category): ?>
				<h3><?=$category->name?></h3>
				<?php endif; ?>

				<?php if($_idsection): ?>
				<a href="<?=ROOT.'categoria/'.($category ? $category->id.'-'.Permalink($category->name) : '-') ?>" class="tag"><span>en <?=$_idsection?></span> <span class="remove">x</span></a>
				<?php else: ?>

				<?php if($stores): ?>
				<hr>

				<div class="title-zones">
					<a href="#" data-toggle="collapse-zones" class="tag"><span>Zonas</span> <span class="remove"><i class="fa fa-angle-down"></i></span></a>
				</div>

				<div class="form-group">
					<ul id="list_zones" class="list" >
						<?php foreach($stores as $store): ?>
						<li><a href="<?= ROOT.'categoria/'.($category ? $category->id.'-'.Permalink($category->name) : '-').'/'.urlencode($store->city) ?>"><?=$store->city?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>


				<?php endif; endif; ?>

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
				<p>&nbsp;</p>
				<?php if(count($total_experiences)>count($experiences)): ?>
				<hr>
				<form id="load_more">
					<button class="btn btn-fucsia"><i class="fa fa-angle-down fa-fw"></i> Cargar más</button>
					<input type="hidden" name="limit" value="<?=$limit?>">
					<input type="hidden" name="page" value="<?=$page?>">
					<input type="hidden" name="categoryid" value="<?=$category ? $category->id : '' ?>">
					<input type="hidden" name="city" value="<?=$_idsection ?>">
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
		<h3>No pudimos encontrar experiencias por el momento.</h3>
	</div>
</section>
<?php endif; ?>