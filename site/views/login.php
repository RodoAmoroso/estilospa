
<section class="gral-section registro">
	<div class="container">

		<h1>Ingresar</h1>
		<hr>	

		<div class="row">
			
			<div class="col-xs-12 col-sm-8 ">				

				<div class="row">
					<div class="col-xs-12 col-sm-6 bd-right-gray-5">

						<form id="form_login" >		
							<div class="form-group">
								<label for="fd_mail">E-Mail</label>
								<input name="email" id="fd_mail" type="email" class="form-control" required>
							</div>				
							<div class="form-group">
								<label for="fd_password">Password</label>
								<input name="password" id="fd_password" type="password" class="form-control" required>
							</div>

							<div id="status"></div>

							<p class="sz-9"><a href="<?= View::url('recuperar-password') ?>">Olvidé mi contraseña</a></p>
							<div class="form-group">
								<label for="" class="dp-block">&nbsp;</label>
								<button class="btn btn-fucsia">Ingresar <i class="fa fa-angle-double-right"></i></button>
							</div>					
						</form>



					</div>
					<div class="col-xs-12 col-sm-6 dp-none">
						<button class="btn btn-primary btn-sm btn-facebook"><i class="fa fa-facebook"></i> Ingresar con Facebook</button>
						<button class="btn btn-primary btn-sm btn-twitter"><i class="fa fa-twitter"></i> Ingresar con Twitter</button>
					</div>
					
				</div>		

			</div>
		</div>
		
		
	</div>
</section>
