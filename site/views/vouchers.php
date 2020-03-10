

<section class="search-page bg-gray-5">
	<div class="container">


		<!-- PROMOS -->
		<?php if(count($Vouchers->data())): ?>
		<div class="block-white">
			
			<h2 class="fw-600 cl-aqua-5"><?= $voucherdata->name ?></h2>
			<div class="sz-14 ff-fira cl-aqua-5">Vouchers disponibles en estas promos:</div>
			<small class="cl-aqua-5">Válido desde <?= $voucherdata->start ?> hasta <?= $voucherdata->finish ?></small>
			
			<hr>

			<div class="promos-highlight">
				<?php						
				
					
					if($Vouchers->data()): foreach($Vouchers->data() as $kp=>$voucher):

						$Promos->find($voucher->idpromo);
						$promo = $Promos->data();
						
						$promolink = ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title);
						$clientlink = ROOT.'centros/'.$promo->permalink;
						$mp = $MPConfig->find($promo->idclient);
						//$image = json_decode($promo->gallery);
						$imgpromo = json_decode($promo->gallery);
						$Stores->get($promo->idclient);
						echo '<div class="mod-promo mod-promo-4">';
						include 'mods/mod-promo.php';
						echo '</div>';

					endforeach; endif;
				?>					
			</div>
		</div>
		<?php endif; ?>
		
	</div>
</section>