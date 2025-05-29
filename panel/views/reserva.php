
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h1>Reserva</h1>
		<p>Confirmá, cancelá o modificá una reserva creada.</p>
	</div>
</section>



<section class="gral-section-min bg-gray-5">
	<div class="container">

		<div class="block-white">

			<div class="row">
				<div class="col-md-6">

					<div class="event event-modal">
						<div class="event-wrapper">

							<?php if($_reservation->promo): ?>

							<div class="event-body">
								<div class="image thumb-cover thumb-200x200" style="background-image:url(<?=$_reservation->promo->image?>) "></div>

								<div class="data">
									<div class="title"><a href="<?=ROOT.'promo/'.$_reservation->client->permalink.'/'.$_reservation->promo->id.'-'.Permalink($_reservation->promo->title)?>" target="_blank"><?=$_reservation->promo->title?></a></div>
									<div class="subtitle"><?=$_reservation->promo->subtitle?></div>
									<div class="price">$ <?=number_format($_reservation->promo->price-($_reservation->promo->price*$_reservation->promo->discount/100),2,',','.')?></div>

									<div class="date"><i class="fa fa-calendar fa-fw"></i> <span><?=Dates::translateDays(date('l',$reservation_bookdate)).' '.date('d/m/Y H:i',$reservation_bookdate)?></span> hs.</div>

									<p><span data-status="" class="label bg-<?=reservation_labels($_reservation->status)->color?>"><?=reservation_labels($_reservation->status)->text?></span></p>

								</div>
							</div>
							<?php endif; ?>

							<div class="user">
								<h4>Datos del usuario</h4>
								<div class="user-name"><?= $_reservation->user->name.' '.$_reservation->user->lastname.' ('.$_reservation->user->mail.')' ?></div>
								<div class="user-phone"><i class="fa fa-phone"></i> <?=$_reservation->user->phone?></div>
								<p>&nbsp;</p>

								<p><b>Comentarios</b>: <?= is_null($_reservation->comments) ? 'no ha dejado comentarios' : $_reservation->comments ?></p>

							</div>

							<div class="event-footer">

								<form id="form_reservation">
									<input type="hidden" name="id" value="<?=$_reservation->id?>">
									<input type="hidden" name="date" value="<?=$_reservation->book_date?>">
									<input type="hidden" name="promoid" value="<?=$_reservation->promoid?>">
									<input type="hidden" name="clientid" value="<?=$_reservation->client->id?>">
								</form>

								<div id="buttons">
									<button data-btn="cancel" class="btn btn-danger btn-sm" >Cancelar Turno <i class="fa fa-times"></i></button>

									<?php if($_reservation->status==0): ?>
									<button data-btn="confirm" class="btn btn-primary btn-sm" >Confirmar Turno <i class="fa fa-check"></i></button>
									<?php endif; ?>
								</div>

							</div>

						</div>

					</div>

				</div>

				<div class="col-md-6">
					<h4>Elegir otro día/horario</h4>

					<div id="calendar_promo" class="calendar-promo">
						<div class="month">
							<div class="prev" data-action="prev"><i class="fa fa-angle-left"></i></div>
							<div class="name" ><span data-month="<?=date('m')?>"><?=Dates::translateMonths(date('M'))?></span> <span data-year="<?=date('Y')?>"><?=date('Y')?></span></div>
							<div class="next" data-action="next"><i class="fa fa-angle-right"></i></div>
						</div>

						<div class="week">
							<ul class="days">
								<li class="day prev" data-action="prev"><i class="fa fa-angle-left"></i></li>

								<?php foreach($_arrdays as $k=>$day): ?>
								<li class="day <?=!$k ? 'active' : ''?>" data-day="<?=$day['day']?>" data-dayname="<?=$day['dayname']?>" ><?=$day['name'].' '.$day['day']?></li>
								<?php endforeach; ?>

								<li class="day next" data-action="next"><i class="fa fa-angle-right"></i></li>
							</ul>
						</div>

						<div class="schedule">
							<ul class="hours"></ul>
						</div>
					</div>

					<p>&nbsp;</p>

					<div class="mb-3">
						<button data-btn="update" class="btn btn-success btn-sm d-none">Sugerir Nuevo Día/Horario <i class="fa fa-calendar"></i></button>
					</div>

				</div>

			</div>


		</div>


		<div class="block-white">
		<a href="<?=PANEL.'reservas'?>" class="btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> Volver</a>
		</div>


	</div>
</section>