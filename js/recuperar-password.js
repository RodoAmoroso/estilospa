var Recover = function(){
	AjaxConnection('jxUsers.php',{Mode:'recover',Mail:$('#fd_mail').val()},function(DATA){
		if(DATA.Status == 'none'){
			$('#status').html('<p class="alert alert-danger">El mail ingresado no corresponde a ningún usuario registrado en EstiloSPA. Verifica si el mail es correcto. Si aún no estás registrado, <a href="'+ROOTPATH+'registro">haz click aquí</a> para acceder a todos los beneficios dentro de EstiloSPA.</p>');
			return;
		}
		if(DATA.Status == 'inactive'){
			$('#status').html('<p class="alert alert-danger">Lo sentimos, pero por motivos de seguridad necesitamos validar el mail que usaste para registrarte.<br />Para activar tu cuenta y empezar a disfrutar de todos los beneficios que te brinda EstiloSPA, asegúrate de hacer click en el enlace que te fue enviado por mail.<br /><br />No olvides de revisar la bandeja de correo basura (SPAM). Si todavía no has recibido el mail con el link de confirmación, <a href="'+ROOTPATH+'reenviar-confirmacion">haz click aquí</a> para reenviarlo.</p>');
			return;
		}
		if(DATA.Status == 'wrongmail'){
			$('#status').html('<p class="alert alert-danger">Has ingresado un email no válido. Itenta nuevamente.</p>');
			return;
		}
		if(DATA.Status == 'fail'){
			$('#status').html('<p class="alert alert-danger">Hubo problemas con el servidor. Intenta nuevamente más tarde.</a></p>');
			return;
		}
		$('#status').html('<p class="alert alert-success">En minutos llegará un mensaje con instrucciones para poder generar una contraseña nueva.</p>');
	});
}
$(function(){
	$('#form_recover').submit(function(e){
		e.preventDefault();
		CheckFields(['#fd_mail:email'],Recover);
	});
});