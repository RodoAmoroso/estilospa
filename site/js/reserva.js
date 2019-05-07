var reservation = {
	delete:function(){
		var post = get_form('#form_reservation');
		ajax('site/reservations/delete',post)
			.then(function(data){				
				Swal.fire({
					type:'success',
					text:message,
					onClose:function(){
						window.location.reload();
					}
				});
			});
	}
}

$(function(){


	$('button[data-btn=cancel]').on('click',function(){
		Swal.fire({
			type:'warning',
			html:'¿Seguro que querés cancelar esta reserva? ',
			showCancelButton:true,
			reverseButtons:true
		})
		.then(function(response){
			if(response.value){
				reservation.delete();
			}
		});
	});

	if(_subsection == 'cancelar'){
		$('button[data-btn=cancel]').trigger('click');
	}

});