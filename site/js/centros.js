var RenderMap = function(addresses) {
	var map;
	var elevator;
	var myOptions = {
		zoom:9,
		center: new google.maps.LatLng(0, 0),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map($('#map')[0], myOptions);
	var bounds = new google.maps.LatLngBounds();
	
	$.each(addresses,function(k,v){
		///for (var x=0; x<addresses.length; x++) {
		$.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address=' + addresses[k] + '&sensor=false&key=AIzaSyC2m93XcFMuCAPZSjBUNsZO24UJOSPSF1M', null, function(data){			
			if(data.status!= 'ZERO_RESULTS'){
				var p = data.results[0].geometry.location;
				var latlng = new google.maps.LatLng(p.lat, p.lng);
				bounds.extend(latlng);
				new google.maps.Marker({
					position: latlng,
					map: map,
					zoom:9
				});
				map.fitBounds(bounds);
			}else{
				console.log(addresses[k]);
			}
		});
	});

}
var geocodeResult = function(results, status) {
	var map;
	if (status == 'OK') {
		// Si hay resultados encontrados, centramos y repintamos el mapa
		// esto para eliminar cualquier pin antes puesto
		var mapOptions = {
			center: results[0].geometry.location,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map($("#map").get(0), mapOptions);
		// fitBounds acercará el mapa con el zoom adecuado de acuerdo a lo buscado
		map.fitBounds(results[0].geometry.viewport);
		// Dibujamos un marcador con la ubicación del primer resultado obtenido
		var markerOptions = { position: results[0].geometry.location }
		var marker = new google.maps.Marker(markerOptions);
		marker.setMap(map);
	}else{
		console.log(status);
	}
}

$(function(){
	var slider = new Slider({
		container:'.overview-header .gallery'
	});

	char_count('#form_question [name=message]');
	
	var owlClients = $('#clients_carousel');
	owlClients.owlCarousel({autoplay:true,loop:true,dots:true,autoplaySpeed:1000,responsive:{0:{items:1},600:{items:2},960:{items:3},1200:{items:4}},margin:10});
	
	$('.gallery .play').click(function(){
		var video = $(this).attr('data-video');
		$('.gallery').append('<div class="video"><iframe src="https://www.youtube.com/embed/'+video+'?rel=0&amp;showinfo=0&autoplay=1" frameborder="0" width="100%" height="100%" allowfullscreen></iframe><i class="fa fa-times"></i></div>');
		$('.gallery i.fa-times').unbind('click').click(function(){
			$(this).parent().remove();
		});
	});

	$('#stores .list-group-item').click(function(e){
		e.preventDefault();
		$('#stores .list-group-item').removeClass('active');
		$(this).addClass('active');
		
		ajax('site/clients/findstore',{idstore:$(this).attr('data-id')})
			.then(function(data){
				$('#map,.stores-highlight').addClass('active');
				$('.stores-highlight .city').text(data.result.city);
				$('.stores-highlight .today span').html(data.today);
				$('.stores-highlight .schedules-block').html('');
				if(data.schedules != ''){
					$.each(data.schedules,function(k,v){
						$('.stores-highlight .schedules-block').append('<p class="dropdown-item">'+v+'</p>');
					});
				}
				$('.stores-highlight .address').text(data.result.address);
				var phones = data.result.phones == '' ? '' : '<i class="fa fa-phone"></i> '+data.result.phones;
				var whatsapp = data.result.whatsapp == '' ? '' : '<i class="fa fa-whatsapp"></i> <a href="https://api.whatsapp.com/send?phone=549'+(data.result.whatsapp.replace(/\s/g,''))+'">'+data.result.whatsapp+'</a>';
				$('.stores-highlight .phones').html(phones);
				$('.stores-highlight .whatsapp').html(whatsapp);
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode({ 'address': data.result.address+', '+data.result.city+', AR'}, geocodeResult);
			});

	})
	if($('#stores .list-group-item').length==1){
		$('#stores .list-group-item').trigger('click');
	}else{
		RenderMap(addresses);
	}
	$('.schedules').click(function(e){
		e.preventDefault();
		$(this).find('.schedules-block').toggleClass('active');
	});	


	var questions = new Questions({
		container:'#questions',
		form:'#form_question',
		mode:'getbyid'
	});
	questions.get();
	
});