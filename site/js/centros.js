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



/*var calendar = {
	get_hours:function(){
		var activeday = $('.calendar-promo .week .day[data-dayname].active').attr('data-dayname');

		Promise.all([
			ajax('site/reservations/get_hours',{idclient:IDClient,activeday:activeday}),
			get_template('reservations/module-hour')
		])
			.then(function(promises){
				$('.calendar-promo .schedule .hours').html('');
				data = promises[0];

				if(data.hours.length == 0) return false;
		
				var min = '09:00';
				var max = '21:00';
				$.each(data.hours,function(kk,vv){
					if(kk==0){
						min = vv[0];
					}
					if(kk==data.hours.length-1){
						max = vv[1];
					}
				});

				var hourminmin = min.split(':');
				var hourminmax = max.split(':');

				for(var i=parseInt(hourminmin[0]); i<=parseInt(hourminmax[0]); i++){
					
					$template = $(promises[1]);
					$template.attr('data-hour',i+':00').find('.number').text(i+':00 hs.');
					$('.calendar-promo .schedule .hours').append($template);
					
					$template = $(promises[1]);
					$template.attr('data-hour',i+':30').find('.number').text(i+':30 hs.');
					$('.calendar-promo .schedule .hours').append($template);
					
					if(i==14){
						$template.addClass('disabled');
					}
				}				

			});
					
	},
	change_days:function(month,year,action,firstday,lastday){
		ajax('site/reservations/change_days',{month:month,year:year,action:action,firstday:firstday,lastday:lastday})
			.then(function(data){
				$('.calendar-promo [data-month]').attr('data-month',data.month).text(data.month_name);
				$('.calendar-promo [data-year]').attr('data-year',data.year).text(data.year);
				$.each(data.days,function(k,v){
					$('.calendar-promo .week .day[data-day]:eq('+k+')').attr({'data-day':v.day,'data-dayname':v.dayname}).text(v.name+' '+v.day);
				});
			});
	},
	change_month:function(month,year,action){
		ajax('site/reservations/change_month',{month:month,year:year,action:action})
			.then(function(data){
				calendar.change_days(data.month,data.year,'');
			});
	},
	init:function(){

		$('.calendar-promo .month').on('click','.next,.prev',function(){
			var month = $('.calendar-promo [data-month]').attr('data-month');
			var year = $('.calendar-promo [data-year]').attr('data-year');
			var action = $(this).attr('data-action');
			calendar.change_month(month,year,action);
		});

		$('.calendar-promo .week').on('click','.next,.prev',function(){
			var month = $('.calendar-promo [data-month]').attr('data-month');
			var year = $('.calendar-promo [data-year]').attr('data-year');
			var action = $(this).attr('data-action');
			var firstday = $('.calendar-promo .week .day[data-day]').first().attr('data-day');
			var lastday = $('.calendar-promo .week .day[data-day]').last().attr('data-day');
			calendar.change_days(month,year,action,firstday,lastday);
		});

		$('.calendar-promo .week').on('click','.day[data-day]',function(data){
			$('.calendar-promo .week .day[data-day]').removeClass('active');
			$(this).addClass('active');
			calendar.get_hours();
		});

		$('.calendar-promo .hours').on('click','.hour:not(.disabled) .btn',function(data){
			$('.calendar-promo .hours .btn').removeClass('active');
			$(this).addClass('active');

			$('#selected_schedule').text($('.calendar-promo .week .day[data-day].active').text() + ' de ' + $('.calendar-promo [data-month]').text() + ' ' + $('.calendar-promo [data-year]').text() + ' ' + $('.calendar-promo [data-action=select].active').parent().parent().attr('data-hour') + 'hs.');

			$('#form_promo_request [name=date]').val($('.calendar-promo [data-year]').text()+'-'+$('.calendar-promo [data-month]').attr('data-month')+'-'+$('.calendar-promo .week .day[data-day].active').attr('data-day')+' '+$('.calendar-promo [data-action=select].active').parent().parent().attr('data-hour')+':00' );

		});

		calendar.get_hours();

	}
}*/


$(function(){
	var slider = new Slider({
		container:'.overview-header .gallery'
	});

	char_count('#form_question [name=message]');


	$('#form_reservation').submit(function(e){
		e.preventDefault();
		var post = get_form(this);
		if(post.date == ''){
			Swal.fire({
				type:'warning',
				text:'Te falta seleccionar un día y un horario'
			});
			return false;
		}
		ajax('site/reservations/book',post)
			.then(function(data){

				$('#modal_reservation').modal('hide');
				Swal.fire({
					type:'success',
					html:data.message
				});
			});
		
	});
	var reservations = new Reservations({
		idclient:$('#form_reservation [name="clientid"]').val(),
		container:'.calendar-promo',
		callback:function(data){
			$('#selected_schedule').text(data.text);
			$('#form_reservation [name="date"]').val(data.date);
		}
	});	


	
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



	var hash = window.location.hash.replace('#','');
	if(hash != ''){
		switch(hash){
			case 'consultar':
				$('#modal_reservation').modal('show');
				break;
			case 'turno':
				$('#modal_reservation').modal('show');
				break;
		}
	}
	
});