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
	/*$('#form_glossary_request').submit(function(e){
		e.preventDefault();
		SendRequest();
	});*/

	char_count('#form_question [name=message]');

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


	var questions = new Questions({
		container:'#questions',
		form:'#form_question',
		mode:'getbyid'
	});
	questions.get();

});