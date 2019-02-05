var Register = function(){
	AjaxConnection('jxUsers.php',{
		Mode:'register',
		Name:$('#fd_name').val(),
		Mail:$('#fd_mail').val(),
		Pass:$('#fd_password').val()
	},function(DATA){
		$('#form_register').find('button').button('reset');
		if(DATA.Status == 'wrongmail'){
			$('#status').html('<p class="alert alert-danger">Has ingresado un email no válido. Itenta nuevamente.</p>');
			return;
		}
		if(DATA.Status == 'exists'){
			$('#status').html('<p class="alert alert-warning">El mail ingresado ya existe en nuestra base de datos. <a href="'+ROOTPATH+'login">Haz click aquí</a> para ingresar con tu usuario y contraseña.</p>');
			return;
		}
		if(DATA.Status == 'wrongpass'){
			$('#status').html('<p class="alert alert-warning">La contraseña debe contener al menos 8 caracteres</a></p>');
			return;
		}
		if(DATA.Status == 'fail'){
			$('#status').html('<p class="alert alert-danger">Hubo problemas con el servidor. Intenta nuevamente más tarde.</a></p>');
			return;
		}
		$('.form-content').removeClass('row').html('<div class="alert alert-success"><h2>¡Te has registrado con éxito!</h2> En unos minutos vamos a enviarte un mail a tu casilla de correo para que confirmes la cuenta.<br /><br />No olvides revisar la carpeta de correo basura (SPAM)</div>');
	});
}
$(function(){
	$('#form_register').submit(function(e){
		e.preventDefault();
		$(this).find('button').button('loading');
		CheckFields(['#fd_name','#fd_mail:email','#fd_password','#fd_passwordagain'],function(){
			if($('#fd_password').val() != $('#fd_passwordagain').val()){
				Messages(true,'Las contraseñas deben coincidir');
				$('#form_register').find('button').button('reset');
				return;
			}
			Register();
		});
	});
	$('#form_register input').keyup(function(){
		$('#status').html('');
	});
});