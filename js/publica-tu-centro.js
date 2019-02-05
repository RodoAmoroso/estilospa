var SendPublish = function(){
	$('#form_publish button').button('loading');
	AjaxConnection('jxForms.php',{
		Mode:'publish',
		Name:$('#fd_name').val(),
		Mail:$('#fd_mail').val(),
		Web:$('#fd_web').val(),
		Phone:$('#fd_phone').val(),
		Company:$('#fd_company').val(),
		How:$('#fd_how').val(),
		Message:$('#fd_message').val()
	},function(DATA){
		$('#form_publish button').button('reset');
		if(DATA.Status == 'fail'){
			Messages(true,'Hubo problemas al enviar la solicitud. Intenta más tarde');
			return false;
		}
		$('#form_publish').find('input,textarea').val('');
		$('#form_publish .status').html('<p class="alert alert-success">La solicitud ha sido enviada con éxito! En Breve nos comunicaremos con vos.</p>');
	});
}
$(function(){
	$('#form_publish').submit(function(e){
		e.preventDefault();		
		SendPublish();
	});
});