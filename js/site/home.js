$(function(){

	var owlClients = $('#clients_carousel');
	var owlBlog = $('#blog_carousel');
	var owlSlider = $('.home-slider .sliders');


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

	owlBlog.owlCarousel({
		autoplay:false,
		loop:true,
		dots:true,
		autoplaySpeed:1000,
		margin:10,
		responsive:{
			0:{items:2},
			600:{items:3},
			960:{items:4}
		},
	});

	owlSlider.owlCarousel({
		autoplay:false,
		loop:false,
		dots:true,
		nav:true,
		navText:['<i class="fal fa-angle-left"></i>','<i class="fal fa-angle-right"></i>'],
		autoplaySpeed:1000,
		items:1
	});


	/*var slider = new Slider({
		container:'.sliders'
	});*/

});