<?php
$vars = explode('-',$_SUBSECTION);
if(count($vars) && count($vars)==1):
?>

<section class="gral-section">
	<div class="container">
	<h1>Lo sentimos :(</h1>
	<hr>
	<p>Hubo problemas al procesar la solicitud. Intenta nuevamente más tarde.<br /><br /> Si el problema persiste comunícate con nosotros <a href="<?= ROOTPATH ?>contacto"><?= ROOTPATH ?>contacto</a> </p>
	</div>
</section>


<?php
else:
$db = DB::getInstance();
$db->query("SELECT id FROM spa_users WHERE id={$vars[0]} AND hash='{$vars[1]}'");
if(!$db->count()):
?>

<section class="gral-section">
	<div class="container">
		<h1>Algo ocurrió mal.</h1>
		<hr>
		<p>Hubo problemas al procesar la solicitud. Intenta nuevamente más tarde.<br /><br /> Si el problema persiste comunícate con nosotros <a href="<?= ROOTPATH ?>contacto"><?= ROOTPATH ?>contacto</a> </p>
	</div>
</section>

<?php else: ?>

<section class="gral-section">
	<div class="container">
		<h1>Nueva Contraseña!</h1>
		<p>Ingresa una nueva contraseña para poder ingresar a EstiloSPA.com.</p>
		<hr>
		<div class="row">
			<div class="col-xs-12 col-sm-4">
				<form id="form_reset">
					<div class="form-group">
						<label for="fd_pass">Nuevo Password</label>
						<input id="fd_password" type="password" class="form-control">
					</div>
					<div id="status"></div>
					<div class="form-group">
						<button class="btn btn-fucsia">Enviar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<script>
var id = '<?= $vars[0] ?>';
var hash = '<?= $vars[1] ?>';
</script>

<?php endif; endif; ?>