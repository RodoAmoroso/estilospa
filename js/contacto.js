var SendContact = function(){
	$('#form_contact button').button('loading');
	AjaxConnection('jxForms.php',{
		Mode:'contact',
		Name:$('#fd_name').val(),
		Mail:$('#fd_mail').val(),
		Phone:$('#fd_phone').val(),
		Subject:$('#fd_subject').val(),
		Message:$('#fd_message').val()
	},function(DATA){
		$('#form_contact button').button('reset');
		if(DATA.Status == 'fail'){
			Messages(true,'Hubo problemas al enviar la solicitud. Intenta más tarde');
			return false;
		}
		$('#form_contact').find('input,textarea').val('');
		$('#form_contact .status').html('<p class="alert alert-success">La solicitud ha sido enviada con éxito! En Breve nos comunicaremos con vos.</p>');
	});
}
$(function(){
	$('#form_contact').submit(function(e){
		e.preventDefault();		
		SendContact();
	});
});