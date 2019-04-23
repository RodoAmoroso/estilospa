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

	var glossary = $('.glossary-page .page-content');
	var glossaryHeight = $('.glossary-page .page-content').height();
	var glossaryContentHeight = $('.glossary-page .page-content .content').height();
	var glossaryBar = $('.glossary-page .page-content .view-more').height();

	$('#view_more').on('click',function(){
	
		if(glossary.attr('data-collapse')=='false'){
			glossary.css({height:(glossaryContentHeight+glossaryBar+20)+'px'}).attr({'data-collapse':'true'});
			$(this).find('span').text('leer menos');
		}else{
			glossary.css({height:'260px'}).attr({'data-collapse':'false'});
			$(this).find('span').text('leer más');
			$('body,html').animate({scrollTop:glossary.offset().top});
		}
		$(this).find('i').toggleClass('fa-caret-down fa-caret-up');
	});

	if(glossaryContentHeight<=glossaryHeight){
		$('.glossary-page .page-content').css({'height':'auto'});
		$('.glossary-page .page-content .view-more #view_more').remove();
	}

});