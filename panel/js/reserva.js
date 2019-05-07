var reservation = {
	show_message:function(message){
		Swal.fire({
			type:'success',
			text:message,
			onClose:function(){
				window.location.reload();
			}
		});
	},
	delete:function(){
		var post = get_form('#form_reservation');
		ajax('panel/reservations/delete',post)
			.then(function(data){
				reservation.show_message(data.message);
			});
	},
	confirm:function(){
		var post = get_form('#form_reservation');
		ajax('panel/reservations/confirm',post)
			.then(function(data){
				reservation.show_message(data.message);
			});
	},
	change_date:function(){
		var post = get_form('#form_reservation');
		ajax('panel/reservations/change_date',post)
			.then(function(data){
				reservation.show_message(data.message);
			});
	}
}

$(function(){

	var reservations = new Reservations({
		idclient:$('[name=clientid]').val(),
		container:'.calendar-promo',
		callback:function(data){
			$('.date span').text(data.text);
			$('input[name=date]').val(data.date);
			$('#buttons').html('');
			$('[data-btn="update"]').removeClass('d-none');
		}
	});

	$('#buttons').on('click','button[data-btn=cancel]',function(){
		Swal.fire({
			type:'warning',
			text:'¿Seguro que querés cancelar esta reserva? Se enviará un email de aviso al usuario.',
			showCancelButton:true,
			reverseButtons:true
		})
		.then(function(response){
			if(response.value){
				reservation.delete();
			}
		});
	});
	$('#buttons').on('click','button[data-btn=confirm]',function(){
		reservation.confirm();
	});

	$('button[data-btn=update]').on('click',function(){
		Swal.fire({
			type:'warning',
			text:'¿Seguro que querés sugerir/cambiar otro día/horario para esta reserva? Se enviará un email de aviso al usuario. Si el usuario confirma o cancela, te vamos a enviar otro email para avisarte.',
			showCancelButton:true,
			reverseButtons:true
		})
		.then(function(response){
			if(response.value){				
				reservation.change_date();
			}
		});
	});


});