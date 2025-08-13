<section class="gral-section">

  <div class="container">

    <h4>Personalizá tu GiftCard</h4>
    <div class="row mt-4">
      <div class="col-lg-4">
        <form action="#" method="post" class="form-giftcard">
          <div class="form-floating mb-3">
            <input type="text" name="name" id="name" class="form-control" placeholder="Nombre del destinatario" required>
            <label for="name">Nombre del destinatario</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" name="your_name" id="your_name" class="form-control" placeholder="Tu Nombre" required>
            <label for="your_name">Tu Nombre</label>
          </div>
          <div class="form-floating mb-3">
            <textarea name="comments" id="comments" class="form-control" placeholder="Dedicatoria" required style="height:200px"></textarea>
            <label for="comments">Dedicatoria</label>
          </div>
          <hr>
          <h5>Elegí el motivo</h5>
          <div class="gallery-carousel">
            <div class="border rounded shadow" >
              <img src="<?= View::assets('blank-wide-2.gif') ?>" alt="" class="w-100">
            </div>
          </div>

        </form>
      </div>
      <div class="col-lg-8 border-start">
        <h5>Preview</h5>
        <div class="border rounded shadow" >
          <img src="<?= View::assets('blank-wide-2.gif') ?>" alt="" class="w-100">
        </div>
        <hr>
        <button class="btn btn-aqua-4">
          <i class="fa fa-dollar-sign fa-fw"></i>
          <span>Comprar GiftCard</span>
        </button>
      </div>
    </div>
  </div>
</section>