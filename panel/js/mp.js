$(function(){
	$('#fd_unlink').click(function(){
		Swal.fire({
			type:'warning',
			text:'¿Seguro deseas desvincular la cuenta de MercadoPago? Ya no podrás usar la opción de venta online en las promos',
			showCancelButton:true,
			reverseButtons:true
		})
			.then(function(response){
				if(response.value){
					ajax('panel/clients/unlink')
						.then(function(DATA){
							window.location.reload();
						});
				}
			});
	});
});