var SendRequest = function(){
	$('#modal_glossary_request button').button('loading');
	AjaxConnection('jxForms.php',{
		Mode:'requestglossary',
		Name:$('#fd_name').val(),
		Mail:$('#fd_mail').val(),
		Phone:$('#fd_phone').val(),
		Message:$('#fd_message').val(),
		IDG:IDGlossary
	},function(DATA){
		$('#modal_glossary_request button').button('reset');
		if(DATA.Status != 'ok'){
			Messages(true,'Hubo problemas al enviar la solicitud. Intenta más tarde');
			return false;
		}
		$('#modal_glossary_request').find('input,textarea').val('');
		$('#modal_glossary_request .status').html('<p class="alert alert-success">La solicitud ha sido enviada con éxito! En Breve nos comunicaremos con vos.</p>');
	});
}
$(function(){
	var owlClients = $('#clients_carousel');	
	owlClients.owlCarousel({
		autoplay:true,
		loop:true,
		dots:true,
		autoplaySpeed:1000,
		responsive:{
			0:{items:1},
			520:{items:2},
			740:{items:4},
			991:{items:5}
		},
		margin:16
	});
	//////////////////////////////////////
	$('#form_glossary_request').submit(function(e){
		e.preventDefault();
		SendRequest();
	});
});