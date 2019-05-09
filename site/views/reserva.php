
<section class="gral-section-min bg-gray-5">
	<div class="container">

		<div class="block-white">			
			
			<div class="event event-modal">
				<div class="event-wrapper">

					<div class="event-body">
						<div class="image thumb-cover thumb-150x150" style="background-image:url(<?=$_reservation->promo->image?>) "></div>
						
						<div class="data">
							<div class="title"><a href="<?=ROOT.'promo/'.$_reservation->client->permalink.'/'.$_reservation->promo->id.'-'.Permalink($_reservation->promo->title)?>" target="_blank"><?=$_reservation->promo->title?></a></div>

							<div class="subtitle"><?=$_reservation->promo->subtitle?></div>
							<div class="subtitle"><a href="<?= ROOT.'centros/'.$_reservation->client->permalink ?>" target="_blank"><?=$_reservation->client->name?></a></div>


							<div class="price">$ <?=number_format($_reservation->promo->price-($_reservation->promo->price*$_reservation->promo->discount/100),2,',','.')?></div>
							
							<div class="date"><i class="fa fa-calendar fa-fw"></i> <span><?=date('d/m/Y H:i',strtotime($_reservation->book_date))?></span> hs.</div>

							<p><div data-status="" class="label bg-<?=reservation_labels($_reservation->status)->color?>"><?=reservation_labels($_reservation->status)->text?></div></p>

						</div>
					</div>
					
					<div class="user">
						<h4>Datos del usuario <a href="<?=ROOT.'perfil'?>" class="btn btn-xs btn-white"><i class="fa fa-pencil"></i></a></h4>
						<div class="user-name"><?= $_reservation->user->name.' '.$_reservation->user->lastname.' ('.$_reservation->user->mail.')' ?></div>
						<div class="user-phone"><i class="fa fa-phone"></i> <?=$_reservation->user->phone?></div>



						<p>&nbsp;</p>
						<p><b>Comentarios</b>: <?= is_null($_reservation->comments) ? 'no ha dejado comentarios' : $_reservation->comments ?></p>
						
					</div>

					<div class="event-footer text-right">

						<form id="form_reservation">
							<input type="hidden" name="id" value="<?=$_reservation->id?>">
							<input type="hidden" name="date" value="<?=$_reservation->book_date?>">
							<input type="hidden" name="promoid" value="<?=$_reservation->promoid?>">
							<input type="hidden" name="clientid" value="<?=$_reservation->client->id?>">
						</form>

						<div id="buttons">
							<button data-btn="cancel" class="btn btn-danger btn-sm" >Cancelar Turno <i class="fa fa-times"></i></button>
						</div>							
						
					</div>

				</div>
				
			</div>			

		</div>



	</div>
</section>