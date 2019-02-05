var Update = function(){
	AjaxConnection('jxUsers.php',{
		Mode:'update',
		Name:$('#fd_name').val(),
		Lastname:$('#fd_lastname').val(),
		DNI:$('#fd_dni').val(),
		Birth:$('#fd_year').val()+'-'+$('#fd_month').val()+'-'+$('#fd_day').val(),
		Phone:$('#fd_phone').val(),
		Address:$('#fd_address').val(),
		AddressObs:$('#fd_addressobs').val(),
		City:$('#fd_city').val(),
		Zip:$('#fd_zip').val(),
		IDProvence:$('#fd_provinces').val(),
		Pass:$('#fd_pass').val(),
		PassNew:$('#fd_passnew').val(),
		Newsletter:$('#fd_newsletter').hasClass('fa-check-square') ? 1 : 0
	},function(DATA){
		if(DATA.Status == 'wrongpass'){
			$('#status').html('<p class="alert alert-danger">La contraseña anterior es incorrecta. Intenta nuevamente.</p>');
			return;
		}		
		$('#status').html('<p class="alert alert-success">Los datos fueron guardados correctamente!</p>');
	});
}
$(function(){
	UpFile.Init({MODE:'upimage',PHP:'jxUsers.php',BTN:'#btn_image',FORM:'#form_image',TH:'#avatar',FOLDER:'img/users/',SX:'-o'});
	$('#fd_newsletter,[for="fd_newsletter"]').click(function(){
		$('#fd_newsletter').toggleClass('fa-check-square fa-square');
	});
	$('#form_user').submit(function(e){
		e.preventDefault();
		CheckFields(['#fd_name'],Update);
	});
});