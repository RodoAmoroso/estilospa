var Login = function(){
	AjaxConnection('jxUsers.php',{
		Mode:'login',		
		Mail:$('#fd_mail').val(),
		Pass:$('#fd_password').val()
	},function(DATA){
		if(DATA.Status == 'none'){
			$('#status').html('<p class="alert alert-danger">El mail ingresado no corresponde a ningún usuario registrado en EstiloSPA. Verifica si el mail es correcto. Si aún no estás registrado, <a href="'+ROOTPATH+'registro">haz click aquí</a> para acceder a todos los beneficios dentro de EstiloSPA.</p>');
			return;
		}
		if(DATA.Status == 'wrongpass'){
			$('#status').html('<p class="alert alert-danger">La contraseña ingresada es incorrecta. Intenta nuevamente. Si no recuerdas las contraseña <a href="'+ROOTPATH+'recuperar-password" >haz click aquí</a> para recuperarla.</p>');
			return;
		}
		if(DATA.Status == 'wrongmail'){
			$('#status').html('<p class="alert alert-danger">Has ingresado un email no válido. Itenta nuevamente.</p>');
			return;
		}
		if(DATA.Status == 'inactive'){
			$('#status').html('<p class="alert alert-danger">Lo sentimos, pero por motivos de seguridad necesitamos validar el mail que usaste para registrarte.<br />Para activar tu cuenta y empezar a disfrutar de todos los beneficios que te brinda EstiloSPA, asegúrate de hacer click en el enlace que te fue enviado por mail.<br /><br />No olvides de revisar la bandeja de correo basura (SPAM). Si todavía no has recibido el mail con el link de confirmación, <a href="'+ROOTPATH+'reenviar-confirmacion">haz click aquí</a> para reenviarlo.</p>');
			return;
		}
		if(window.location.hash != ''){
			window.location.href = window.location.hash.replace('#','');
			return false;
		}
		window.location.reload();
	});
}
$(function(){
	$('#form_register').submit(function(e){
		e.preventDefault();
		CheckFields(['#fd_mail:email','#fd_password'],Login);
	});
});