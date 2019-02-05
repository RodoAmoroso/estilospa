var Resend = function(){	
	AjaxConnection('jxUsers.php',{Mode:'resend',Mail:$('#fd_mail').val()},function(DATA){
		console.log(DATA.Error);
		if(DATA.Status == 'wrongmail'){
			$('#status').html('<p class="alert alert-danger">Has ingresado un email no válido. Itenta nuevamente.</p>');
			return;
		}
		if(DATA.Status == 'none'){
			$('#status').html('<p class="alert alert-danger">El mail ingresado no corresponde a ningún usuario registrado en EstiloSPA. Verifica si el mail es correcto. Si aún no estás registrado, <a href="'+ROOTPATH+'registro">haz click aquí</a> para acceder a todos los beneficios dentro de EstiloSPA.</p>');
			return;
		}
		if(DATA.Status == 'active'){
			$('#status').html('<p class="alert alert-danger">La cuenta ya ha sido activada previamente. Ingresa con tu usuario y contraseña <a href="'+ROOTPATH+'login">haciendo click aquí</a>.</p>');
			return;
		}
		$('#status').html('<p class="alert alert-success">Se ha enviado un mensaje a tu casilla de correo con instrucciones para poder activar la cuenta. <br /><br />No olvides de revisar la bandeja de correo basura (SPAM).</p>');
	});
}
$(function(){
	$('#form_resend').submit(function(e){
		e.preventDefault();
		CheckFields(['#fd_mail:email'],Resend);
	});
});