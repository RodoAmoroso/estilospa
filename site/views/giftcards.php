<section class="gral-section border-bottom">
	<div class="container">

		<div class="text-center">

			<h1 class="text-gold-2"><i class="fa fa-gift"></i></h1>
			
			<h2>Regalá bienestar</h2>
			
			<h5>Sorprendé a quien quieras con una giftcard de experiencias de estética. <br >Masajes, faciales, spa y más, para que cada persona elija su momento de relax.</h5>
		</div>
		
		<h4 class="text-center mt-5">
			Nuestras GiftCards
		</h4>

		<?php if($giftcards): ?>          
		<div class="row my-4 justify-content-center">
			<?php foreach($giftcards as $giftcard): ?>
			<div class="col-lg-3 mb-3">
				<div class="gift-card shadow">
					<img src="<?= View::assets('blank-vertical.gif') ?>" alt="" class="w-100">
					<div class="image" style="background-image: url(<?= $giftcard->image->small ?>);"></div>
					
					<div class="border"></div>

					<div class="card-inside">
						<div class="card-content">
							<h3 class="title"><?= $giftcard->title ?></h3>          
							<div class="price"><?= $giftcard->value_formatted ?></div>
							<a href="<?= $giftcard->permalink ?>" class="btn btn-fucsia-4 btn-sm">
								<i class="fa fa-shopping-bag fa-fw"></i> Comprar
							</a>
						</div>
					</div>  
					
					
				</div>
			</div>

			<?php endforeach; ?>
		</div>
		<?php else: ?> 
		<div class="no-giftcards">
			<p>No hay gift cards disponibles en este momento.</p>
		</div>
		<?php endif; ?>
		
	</div>
</section>