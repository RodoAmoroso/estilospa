<section class="gral-section">
  <div class="container">
    <h3>GiftCard</h3>
    <div class="row">
      <div class="col-lg-4">

        <div class="boxes">
          <div class="box-wrapper">
            <div class="box">
              <div class="box-title">
                <h3 class="title"><?= $giftcard->title ?></h3>
                <div class="border rounded shadow mb-3 thumb-cover" style="background-image:url(<?= $giftcard->image->big ?>)">
                  <img src="<?= View::assets('blank-vertical.gif') ?>" alt="" class="w-100">
                </div>
                <div><?= $giftcard->description ?></div>
              </div>
              <div class="box-content">
                <h2><?= $giftcard->value_formatted ?></h2>
              </div>

              <div class="box-footer">
								<a href="<?= View::url('giftcards') ?>" class="btn btn-primary" >
									<i class="fal fa-angle-double-left fa-fw"></i> Volver
								</a>
							</div>

            </div>

          </div>
        </div>
      </div>
      
      <div class="col-lg-8">
				<div class="boxes">
					<div class="box-wrapper">

						<div class="box">

							<div class="box-content">

								<h3>1. Completá tus datos</h3>
								<p>&nbsp;</p>

								<form data-form="user-info">
									<div class="row">
										<div class="col-lg-6 mb-3">
											<label for="">Tu Nombre *</label>
											<input name="firstname" type="text" class="form-control" value="<?= $_userdata->name ?>" required>
										</div>
										<div class="col-lg-6 mb-3">
											<label for="">Tu Apellido *</label>
											<input name="lastname" type="text" class="form-control" value="<?= $_userdata->lastname ?>" required>
										</div>
										<div class="col-lg-6 mb-3">
											<label for="">Celular/Whatsapp (Prefijo + Nro.) *</label>
											<input name="phone" type="text" class="form-control" value="<?= $_userdata->phone ?>" required>
										</div>
										<div class="col-lg-6 mb-3">
											<label for="">Email *</label>
											<input name="email" type="email" class="form-control" value="<?= $_userdata->mail ?>" required>
										</div>
									</div>

									<button class="btn btn-primary"><i class="fa fa-save fa-fw"></i> Guardar & Continuar</button>
								</form>
							</div>

							<hr>

							<div data-toggle="payment-boxxx" class="box-content d-none">
                <h3>2. Completá los datos del Agasajado</h3>
                <form action="#" method="post" class="form-giftcard">
                  <div class="form-floating mb-3">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nombre del destinatario" required>
                    <label for="name">Nombre del destinatario</label>
                  </div>
                  <div class="form-floating mb-3">
                    <textarea name="comments" id="comments" class="form-control" placeholder="Dedicatoria" required style="height:200px"></textarea>
                    <label for="comments">Dedicatoria</label>
                  </div>
                  <hr>
                  <h5>Elegí el motivo</h5>
                  [CAROUSEL]

                </form>
              </div>

              
							<div data-toggle="payment-box" class="box-content d-nonee">
								<h3>2. Seleccioná la forma de pago</h3>
								<div id="paymentBrick_container" class="">
									<div data-toggle="form-mp-loader" class="text-center">
										<h2 class="text-aqua-3"><i class="fa fa-cog fa-spin"></i></h2>
										<h4 class="text-center">Cargando Formulario de Pago...</h4>
									</div>
								</div>
								<div class="alert alert-info">

									<div><i class="fa fa-exclamation-circle fa-fw"></i> Por favor verificar que todos los datos sean los correctos, (respetar mayúsculas y minúsculas en el nombre que figura en la tarjeta) en caso que algún dato sea inválido tu compra será rechazada por este motivo.</div>
								</div>
							</div>

						</div>

					</div>
				</div>

			</div>

    </div>
    <hr>


    <div class="small-comment">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis temporibus animi omnis beatae asperiores molestiae a quia quaerat? Sed velit ratione dolorem odit error magnam ipsam quisquam hic maxime recusandae?
    Doloremque, eum! Eum repellat perferendis expedita sequi ex rerum? Ullam explicabo reprehenderit corrupti sapiente neque architecto qui commodi animi repudiandae voluptatibus ipsam molestias beatae dicta sed culpa, alias illum error.
    Exercitationem cumque minima sit expedita mollitia necessitatibus labore, reiciendis itaque voluptate unde odio dicta culpa aliquam nemo consectetur reprehenderit, debitis beatae nam! Eos perferendis fugit, sapiente eligendi recusandae doloribus cum.
    Facilis officia aliquam, esse consectetur dolorem vero. Harum molestias saepe, voluptates quis veritatis, labore quam repudiandae placeat vero, laudantium sequi ab impedit. Aut assumenda cumque dolore beatae nostrum, rem sunt?
    Id fugit, esse voluptas architecto odio magnam deserunt at eius recusandae laboriosam earum asperiores sunt excepturi quibusdam et perferendis consequatur quasi nam nobis distinctio consectetur culpa saepe! Voluptates, eaque delectus.
    Quae at deleniti culpa nisi rerum qui ducimus? Iusto excepturi nihil explicabo dolorem impedit ea. Amet ex maiores sint dicta cumque quod earum odit ipsa, perspiciatis quas ab vitae excepturi?
    Saepe repellendus eum, beatae quisquam laborum facere aspernatur tempore voluptatem error obcaecati quibusdam animi commodi, nobis reprehenderit atque qui nemo iusto dolorum quasi velit aut vitae et consequuntur! Quis, quaerat.
    Amet minus laboriosam magni reiciendis sint error asperiores, officiis ab nulla. Cumque exercitationem, laudantium facilis aperiam suscipit dolorum id maiores modi voluptates, culpa odit, architecto harum. Repellendus autem consectetur a!
    Non et temporibus magni! Harum deleniti, perferendis mollitia exercitationem ullam similique laudantium nostrum ratione vitae aut. Tenetur sint nemo deleniti dolor deserunt quas atque consequatur rem eveniet, laborum soluta vel?
    Neque velit magni, temporibus, aliquam quod vel quia consectetur quibusdam reiciendis itaque, eum quae! Impedit odit non rem provident fugit velit sit hic, ipsum porro ipsa nemo sint et molestiae?</div>
  </div>
</section>


<section class="gral-section bg-gray-5">

  <div class="container">
    
    <h3 class="title">Experiencias disponibles para esta GiftCard:</h3>
  
    [EXPERIENCIAS]
  </div>


</section>