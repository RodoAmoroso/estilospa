<section class="gral-section bg-gray-5">
	<div class="container">
		
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="#">Inicio</a>
			</li>
			<li class="breadcrumb-item">
				<a href="#">GiftCards</a>
			</li>
			<li class="breadcrumb-item">
				<span><?= $giftcard->title ?></span>
			</li>
		</ol>

		<div class="boxes">
			<div class="box-wrapper">
				<div class="box">

					<div class="box-content">
						
						<div class="row align-items-center">
							<div class="col-lg-3">
								<div class="border rounded shadow mb-3 thumb-cover" style="background-image:url(<?= $giftcard->image->big ?>)">
									<img src="<?= View::assets('blank-vertical.gif') ?>" alt="" class="w-100">
								</div>
							</div>
							<div class="col-lg-8 p-lg-5">

								<h3 class="title"><?= $giftcard->title ?></h3>
								<h2><?= $giftcard->value_formatted ?></h2>	

								<div class="small-comment">Precio sin impuesto nacionales: <?= $giftcard->value_novat_formatted ?></div>

								<div class="my-4"><?= $giftcard->description ?></div>
								<hr>
								<a href="<?= View::url('giftcard-compra',$giftcard->id) ?>" class="btn btn-aqua-4">
									<i class="fa fa-gift fa-fw"></i>
									<span>Regalá Ahora</span>
								</a>

							</div>
						</div>
					</div>							

					<div class="box-footer">
						<!-- <a href="<?= View::url('giftcards') ?>" class="btn btn-primary btn-xs" >
							<i class="fal fa-angle-double-left fa-fw"></i> Volver
						</a> -->

						<div class="small-comment">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis temporibus animi omnis beatae asperiores molestiae a quia quaerat? Sed velit ratione dolorem odit error magnam ipsam quisquam hic maxime recusandae?  Doloremque, eum! Eum repellat perferendis expedita sequi ex rerum? Ullam explicabo reprehenderit corrupti sapiente neque architecto qui commodi animi repudiandae voluptatibus ipsam molestias beatae dicta sed culpa, alias illum error. Exercitationem cumque minima sit expedita mollitia necessitatibus labore, reiciendis itaque voluptate unde odio dicta culpa aliquam nemo consectetur reprehenderit, debitis beatae nam! Eos perferendis fugit, sapiente eligendi recusandae doloribus cum. Facilis officia aliquam, esse consectetur dolorem vero. Harum molestias saepe, voluptates quis veritatis, labore quam repudiandae placeat vero, laudantium sequi ab impedit. Aut assumenda cumque dolore beatae nostrum, rem sunt? Id fugit, esse voluptas architecto odio magnam deserunt at eius recusandae laboriosam earum asperiores sunt excepturi quibusdam et perferendis consequatur quasi nam nobis distinctio consectetur culpa saepe! Voluptates, eaque delectus. Quae at deleniti culpa nisi rerum qui ducimus? Iusto excepturi nihil explicabo dolorem impedit ea. Amet ex maiores sint dicta cumque quod earum odit ipsa, perspiciatis quas ab vitae excepturi?</div>
					</div>

				</div>

			</div>
		</div>			

	</div>		
</section>


<section class="gral-section border-bottom">
	<div class="container">		
		<h3 class="title">Experiencias disponibles para esta GiftCard:</h3>	
		[EXPERIENCIAS]
	</div>
</section>