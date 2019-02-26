$(function(){
	SetSlider.init('.gallery');
	$('.gallery .play').click(function(){
		var video = $(this).attr('data-video');
		$('.gallery').append('<div class="video"><iframe src="https://www.youtube.com/embed/'+video+'?rel=0&amp;showinfo=0&autoplay=true" frameborder="0" width="100%" height="100%" allowfullscreen></iframe><i class="fa fa-times"></i></div>');
		$('.gallery i.fa-times').unbind('click').click(function(){
			$(this).parent().remove();
		});
	});

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
});