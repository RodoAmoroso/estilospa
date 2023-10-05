$(function(){
	var slider = new Slider({
		container:'.overview-header .gallery'
	});

	char_count('#form_question [name=message]');

	/*$('#form_reservation').submit(function(e){
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

	});*/
	var reservations = new Reservations({
		idclient:$('#form_reservation [name="clientid"]').val(),
		container:'.calendar-promo',
		callback:function(data){
			$('#selected_schedule').text(data.text);
			$('[data-form="reservation"] [name="date"]').val(data.date);
		}
	});



	var owlClients = $('#clients_carousel');
	owlClients.owlCarousel({
		autoplay:true,
		loop:true,
		dots:true,
		autoplaySpeed:1000,
		responsive:{
			0:{items:1},
			600:{items:2},
			960:{items:3},
			1200:{items:4}
		},
		margin:10
	});

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
				if(data.result.map!=''){
					$('#map').show().html(data.result.map);
				}else{
					$('#map').hide().html('');
				}
				//var geocoder = new google.maps.Geocoder();
				//RenderMap(data.result.address+', '+data.result.city+', Argentina');
				//geocoder.geocode({ 'address': data.result.address+', '+data.result.city+', AR'}, geocodeResult);
			})
			.catch(function(data){
			});

	});
	/*if($('#stores .list-group-item').length==1){
	}else{
		console.log(addresses)
		//RenderMap(addresses);
	}*/
	$('#stores .list-group-item:eq(0)').trigger('click');

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