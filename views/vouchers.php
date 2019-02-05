

<section class="search-page bg-gray-5">
	<div class="container">


		<!-- PROMOS -->
		<?php if(count($_VOUCHERS->data())): ?>
		<div class="block-white">
			
			<h2 class="fw-600 cl-aqua-5"><?= $voucherdata->name ?></h2>
			<div class="sz-14 ff-fira cl-aqua-5">Vouchers disponibles en estas promos:</div>
			<small class="cl-aqua-5">Válido desde <?= $voucherdata->start ?> hasta <?= $voucherdata->finish ?></small>
			
			<hr>

			<div class="row">
				<?php						
				
					$nm = 0;
					foreach($_VOUCHERS->data() as $kp=>$voucher):
						$_PROMOS->find($voucher->idpromo);
						$promo = $_PROMOS->data();						
						if($promo->statusstart && $promo->statusfinish):				
							echo '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">';
								if($_CLIENTS->find($promo->idclient)):
									$imgpromo = json_decode($promo->gallery);
									$_STORES->get($_CLIENTS->data()->id,$promo->stores);
									include 'mods/mod-promo.php';
									if(count($colorsequence)-1 == $nm){$nm = 0;}else{$nm++;}
								endif;
							echo '</div>';
						endif;
					endforeach;				
				?>					
			</div>
		</div>
		<?php endif; ?>
		
	</div>
</section>