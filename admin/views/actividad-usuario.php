
<!-- PAGE HEADER -->
<section class="page-header">
	<div class="container">
		<h2>Actividad del Usuario</h2>

		<a href="<?=ADMIN.'usuarios'?>" class="btn btn-fucsia btn-xs"><i class="fa fa-angle-double-left fa-fw"></i> Volver al listado de usuarios</a>

	</div>
</section>



<section id="list_panel" class="admin-box bg-gray-5">
	<div class="container">

		<div class="block-white">

			<div class="profile-header">

				<div class="profile-columns">
					<div class="avatar-container">
						<div class="avatar thumb-cover" style="background-image:url(<?= $avatar ? ROOT.'img/users/'.$avatar->photoname.'-o.'.$avatar->extension : '' ?>)"></div>
					</div>
					<div class="user-name">
						<h3 class="name"><?= $user->fullname; ?></h3>
						<p class="sz-14"><a href="mailto:<?=$user->mail?>"><?= $user->mail ?></a></p>
						<p class="small">Usuario desde: <?= date('d/m/Y',strtotime($user->created)) ?></p>
					</div>

				</div>

			</div>

			<hr>


			<div class="user-activity">

				<div class="panel">
					<ul id="nav_activity" class="nav nav-tabs">
						<li class="active nav-link"><a href="#sales">Compras</a></li>
						<li class="nav-link"><a href="#questions">Preguntas</a></li>
						<li class="nav-link"><a href="#views_promo">Visitas</a></li>
						<li class="nav-link"><a href="#newsletters">Newsletters</a></li>
						<li class="nav-link"><a href="#favs">Favoritos</a></li>
					</ul>

					<div class="panel-body">

						<div class="tab-content">

							<!-- COMPRAS -->
							<div id="sales" class="tab-pane active">

								<?php if($sales): foreach($sales as $sale): ?>
								<div id="mod_sale" class="mod-sales">
									<div class="sale-header">

										<?php
										$img = false;
										if($sale->gallery){
											$img = json_decode($sale->gallery);
										}
										?>

										<div class="thumb-container">
											<div class="thumb thumb-cover" <?= $img ? 'style="background-image:url('.ROOT.'img/promos/'.$img[0]->photoname.'-t.'.$img[0]->extension.')"' : '' ?> ></div>
										</div>

										<div class="caption">
											<h4 data-tag="title" class="title-promo">
												<?php if(is_null($sale->title)): ?>
												<i>[la experiencia fue borrada]</i>
												<?php else: ?>
												<a href="<?= ROOT.'promo/'.$sale->permalink.'/'.$sale->idpromo.'-'.Permalink($sale->title) ?>" target="_blank"><?= $sale->title ?></a> -
												<a href="<?= ROOT.'centros/'.$sale->permalink ?>" target="_blank"><?= $sale->clientname ?></a>
												<?php endif; ?>
											</h4>

											<h1 class="title">Orden Nro.: <?=$sale->collection_id?></h1>
											<h4 class="subtitle">Precio Unit.: $ <?= number_format($sale->price-$sale->discountvoucher,0,',','.').' | Cant.: '.$sale->quantity ?></h4>
											<small class="date">Comisión EstiloSPA.com: $ <?= number_format($sale->application_fee,0,',','.').' | Comisión MercadoPago: $ '.number_format($sale->mercadopago_fee,0,',','.'). ' | Fecha de compra: '.$sale->fecha.' hs.'.$sale->vouchertext ?></small>

											<?php if($sale->collection_status!='rejected'): ?>
											<div class="status alert-<?= status_payment($sale->collection_status)->label ?>">

												<div class="payment-status"><?= status_payment($sale->collection_status)->text ?></div>

												<div class="sale-actions">
													<small>Servicio: </small>
													<div class="btn-group">
														<button type="button" class="btn btn-xs dropdown-toggle btn-<?= status_service($sale->status)->btn ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															<span><?= status_service($sale->status)->label ?></span> <span class="caret"></span>
														</button>
														<ul class="dropdown-menu">
															<li><a data-value="1" href="#">Pendiente</a></li>
															<li><a data-value="2" href="#">Brindado</a></li>
															<li><a data-value="3" href="#">Cancelado</a></li>
														</ul>
													</div>

												</div>
											</div>
											<?php endif; ?>

											<div>
												<a href="<?= ADMIN.'detalles-compra/'.$sale->collection_id ?>" target="_blank" >
													<small><i class="fa fa-code"></i> ver detalles</small>
												</a>
											</div>

											<?php if($sale->sales_vouchers): ?>
											<small>Vouchers Generados:</small>
											<ul class="voucher-list">
												<?php foreach($sale->sales_vouchers as $voucher): ?>
												<li>
													<a href="<?= ROOT.'compra-descarga-voucher/'.$voucher->id ?>" target="_blank"><?= $sale->merchant_order_id.'-'.$voucher->id ?></a>
												</li>
												<?php endforeach; ?>
											</ul>
											<?php endif; ?>

										</div>
										<div class="action">
											<button data-button="toggle" class="btn btn-primary btn-block btn-xs"><i class="fa fa-chevron-down"></i></button>
										</div>
									</div>
									<div class="sale-footer">
										<div class="sale-user">
											<div class="feedback" >
												<?php
												$comment = $Sales->get_comment($sale->id,$_userdata->id);
												if(!$comment): ?>
												<i>Todavía no calificó</i>
												<?php else: ?>
												<p><?= nl2br(htmlspecialchars($comment->text,ENT_QUOTES,'utf-8')) ?></p>
												<div class="stars">
													<?php for($i=1; $i<=$comment->rate; $i++): ?>
													<i class="fa fa-star"></i>
													<?php endfor; ?>
													<?php for($i=5; $i>$comment->rate; $i--): ?>
													<i class="fa fa-star-o"></i>
													<?php endfor; ?>
												</div>
												<?php endif; ?>

											</div>
										</div>
									</div>
								</div>
								<?php endforeach; else: ?>
								<p>El usuario no ha efectuado ninguna compra</p>
								<?php endif; ?>


							</div>


							<!-- PREGUNTAS -->
							<div id="questions" class="tab-pane fade">

								<form class="row" method="post">
									<div class="col-xs-4">
										<label for="">Dónde</label>
										<select onchange="this.form.submit()" name="question_where" class="form-control input-sm">
											<option value="">-- Todo el sitio --</option>
											<option value="clients" <?= $_where=='clients' ? 'selected' : '' ?> >En Centros</option>
											<option value="promos" <?= $_where=='promos' ? 'selected' : '' ?> >En Experiencias</option>
											<option value="glossary" <?= $_where=='glossary' ? 'selected' : '' ?> >En Etiquetas</option>
										</select>
									</div>
									<div class="col-xs-4">
										<label for="">Estado</label>
										<select onchange="this.form.submit()" name="question_status" class="form-control input-sm">
											<option value="">-- Todas --</option>
											<option value="answered" <?= $_status=='answered' ? 'selected' : '' ?> >Respondidas</option>
											<option value="unanswered" <?= $_status=='unanswered' ? 'selected' : '' ?> >Sin Responder</option>
										</select>
									</div>
								</form>

								<hr>

								<?php if($questions): foreach($questions as $question): ?>

								<div class="questions-wrapper">
									<div class="question-box">
										<div class="icon">
											<i class="fa fa-user"></i>
										</div>
										<div class="message">
											<p data-content="message" class="caption"><?=$question->message?></p>
											<small data-content="added">Enviada por <?=$question->user_name.' (<a href="mailto:'.$question->user_email.'">'.$question->user_email.'</a> '.($question->user_phone ? ' | '.$question->user_phone : '').')'?>: <?=$question->creado?> hs.

											<?php if($question->type=='promos' && $question->promo): ?>
											a la experiencia <a href="<?= ROOT.'promo/'.$question->promo->permalink.'/'.$question->rowid ?>" class="text-fucsia-3" target="_blank"><?=$question->promo->title?></a>
											<?php endif; ?>

											<?php if($question->type=='clients' && $question->client): ?>
											al centro <a href="<?= ROOT.'centros/'.$question->client->permalink ?>" class="text-fucsia-3" target="_blank"><?=$question->client->name?></a>
											<?php endif; ?>

											<?php if($question->type=='glossary' && $question->glossary): ?>
											a la etiqueta <a href="<?= ROOT.'etiqueta/'.$question->rowid ?>" class="text-fucsia-3" target="_blank"><?=$question->glossary->name?></a>
											<?php endif; ?>


											</small>

										</div>
										<div class="buttons">
											<button class="btn btn-xs btn-white text-pink-3" data-btn="delete-question" data-id="<?=$question->id?>" title="borrar pregunta"><i class="fa fa-trash"></i></button>
										</div>
									</div>

									<?php if($question->responses): ?>
									<!-- RESPONSES -->
									<h5 class="response-title text-gray-50">Respuestas:</h5>
									<?php foreach($question->responses as $response): ?>
									<div class="question-box response">
										<div class="thumb thumb-cover" style="background-image:url(<?=$response->client->imagery->logo?>)"></div>
										<div class="message">
											<a href="<?=ROOT.'centros/'.$response->client->permalink ?>" target="_blank"><?=$response->client->name?></a>
											<p><?=$response->message?></p>
											<small>Enviada: <?=$response->creado?> hs.</small>
										</div>
										<div class="actions">
											<button class="btn btn-xs btn-white text-pink-3" data-btn="delete-response" data-id="<?=$response->id?>" title="borrar respuesta"><i class="fa fa-trash"></i></button>
										</div>
									</div>
									<?php endforeach; endif; ?>
								</div>

								<?php endforeach; endif; ?>



							</div>

							<!-- VISTAS -->
							<div id="views_promo" class="tab-pane fade">

								<?php if($views): foreach($views as $view): ?>

								<div class="activity-wrapper">
									<div class="mod-activity">
										<div class="content">
											<div class="date-wrapper">
												<div class="date"><?= $view->added_date ?></div>
												<small><?= $view->added_time ?> hs.</small>
											</div>
											<div class="log-wrapper">
												<h5 class="title"><a href="<?= $view->link ?>" target="_blank"><?= $view->name ?></a></h5>
												<h5 class="text-muted"><?= $view->type_name ?></h5>
											</div>
										</div>
									</div>
								</div>

								<?php endforeach; endif; ?>

							</div>


							<!-- NEWSLETTERS -->
							<div id="newsletters" class="tab-pane fade">

								<?php if($newsletters): foreach($newsletters as $newsletter): ?>

								<div class="activity-wrapper">
									<div class="mod-activity">
										<div class="content">
											<div class="date-wrapper">
												<div class="date"><?= $newsletter->added_obj->format('d/m/Y') ?></div>
												<small><?= $newsletter->added_obj->format('H:i') ?> hs.</small>
											</div>
											<div class="log-wrapper">
												<a href="<?= ROOT.'preview-newsletter.php?id='.$newsletter->id ?>" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-newspaper-o fa-fw"></i> Ver Newsletter</a>
											</div>
										</div>
									</div>
								</div>

								<?php endforeach; endif; ?>

							</div>


							<!-- FAVS -->
							<div id="favs" class="tab-pane fade">

								<?php if($favs): foreach($favs as $fav): ?>
								<div class="activity-wrapper">
									<div class="mod-activity">
										<div class="content">
											<div class="date-wrapper">
												<div class="date"><?= $fav->added_obj->format('d/m/Y') ?></div>
												<small><?= $fav->added_obj->format('H:i') ?> hs.</small>
											</div>
											<div class="log-wrapper">
												<h5 class="title"><a href="<?= $fav->link ?>" target="_blank"><?= $fav->title ?></a></h5>
												<h5 class="subtitle"><a href="<?= $fav->client_link ?>" target="_blank"><?= $fav->client_name ?></a></h5>
											</div>
										</div>
									</div>
								</div>
								<?php endforeach; endif; ?>
							</div>

						</div>

					</div>

				</div>




			</div>


		</div>


	</div>

</section>