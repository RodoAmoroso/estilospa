var Reset = function(){
	AjaxConnection('jxUsers.php',{Mode:'reset',Pass:$('#fd_password').val(),Hash:hash,ID:id},function(DATA){
		if(DATA.Status == 'fail'){
			$('#status').html('<p class="alert alert-danger">Hubo problemas con el servidor. Intenta nuevamente más tarde.</a></p>');
			return;
		}
		if(DATA.Status == 'wrongpass'){
			$('#status').html('<p class="alert alert-warning">La contraseña debe contener al menos 8 caracteres</a></p>');
			return;
		}
		$('#status').html('<p class="alert alert-success">La contraseña se ha actualizado con éxito. Ahora puedes ingresar con tu mail y contraseña <a href="'+ROOTPATH+'login" >haciendo click aquí</a></p>');
	});
}
$(function(){
	$('#form_reset').submit(function(e){
		e.preventDefault();
		CheckFields(['#fd_password'],Reset);
	});
});