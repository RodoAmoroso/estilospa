var SendRequest = function(){
	$('#form_client_request button').button('loading');
	AjaxConnection('jxForms.php',{
		Mode:'requestclient',
		Name:$('#fd_name').val(),
		Mail:$('#fd_mail').val(),
		Phone:$('#fd_phone').val(),
		Message:$('#fd_message').val(),
		IDC:IDClient
	},function(DATA){
		$('#form_client_request button').button('reset');
		if(DATA.Status == 'fail'){
			Messages(true,'Hubo problemas al enviar la solicitud. Intenta más tarde');
			return false;
		}
		$('#form_client_request').find('input,textarea').val('');
		$('#form_client_request .status').html('<p class="alert alert-success">La solicitud ha sido enviada con éxito! En Breve nos comunicaremos con vos.</p>');
	});
}

var RenderMap = function(addresses) {
	var map;
	var elevator;
	var myOptions = {
		zoom:9,
		center: new google.maps.LatLng(0, 0),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map($('#map')[0], myOptions);
	/*var addresses = [
		['4355 Ashford Dunwoody Road NE, Atlanta, GA 30346, US'],
		['313 North Highland Ave NE, Atlanta, GA 30307'],
		['1989 Cheshire Bridge Road, Atlanta, GA 30324, US'],
		['1210 Howell Mill Rd NW, Atlanta, GA 30318, US']
	];*/
	var bounds = new google.maps.LatLngBounds();
	$.each(addresses,function(k,v){
	///for (var x=0; x<addresses.length; x++) {
		$.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address=' + addresses[k] + '&sensor=false', null, function(data){			
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
	SetSlider.init('.overview-header .gallery');
	var owlClients = $('#clients_carousel');
	owlClients.owlCarousel({autoplay:true,loop:true,dots:true,autoplaySpeed:1000,responsive:{0:{items:1},600:{items:2},960:{items:3},1200:{items:4}},margin:10});
	$('.gallery .play').click(function(){
		var video = $(this).attr('data-video');
		$('.gallery').append('<div class="video"><iframe src="https://www.youtube.com/embed/'+video+'?rel=0&amp;showinfo=0&autoplay=true" frameborder="0" width="100%" height="100%" allowfullscreen></iframe><i class="fa fa-times"></i></div>');
		$('.gallery i.fa-times').unbind('click').click(function(){
			$(this).parent().remove();
		});
	});

	$('#stores .list-group-item').click(function(e){
		e.preventDefault();
		$('#stores .list-group-item').removeClass('active');
		$(this).addClass('active');
		//console.log(address);
		AjaxConnection('jxClients.php',{Mode:'findstore',IDS:$(this).attr('data-id')},function(DATA){
			$('#map,.stores-highlight').addClass('active');
			$('.stores-highlight .city').text(DATA.Result.city);
			$('.stores-highlight .today span').html(DATA.Today);
			$('.stores-highlight .schedules-block').html('');
			if(DATA.Schedules != ''){
				$.each(DATA.Schedules,function(k,v){
					$('.stores-highlight .schedules-block').append('<p class="dropdown-item">'+v+'</p>');
				});
			}
			$('.stores-highlight .address').text(DATA.Result.address);
			var phones = DATA.Result.phones == '' ? '' : '<i class="fa fa-phone"></i> '+DATA.Result.phones;
			var whatsapp = DATA.Result.whatsapp == '' ? '' : '<i class="fa fa-whatsapp"></i> <a href="https://api.whatsapp.com/send?phone=549'+(DATA.Result.whatsapp.replace(/\s/g,''))+'">'+DATA.Result.whatsapp+'</a>';
			$('.stores-highlight .phones').html(phones);
			$('.stores-highlight .whatsapp').html(whatsapp);
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({ 'address': DATA.Result.address+', '+DATA.Result.city+', AR'}, geocodeResult);
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
	$('#form_client_request').submit(function(e){
		e.preventDefault();
		SendRequest();
	});
	$('#btn_fav').click(function(e){
		e.preventDefault();
		AjaxConnection('jxUsers.php',{Mode:'favs',IDC:IDClient},function(DATA){
			$('#btn_fav i').removeClass();
			if(DATA.IsFav==1){
				$('#btn_fav i').addClass('fa fa-heart');
			}else{
				$('#btn_fav i').addClass('fa fa-heart-o');
			}
		});
	});
});