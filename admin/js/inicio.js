$(function(){

	$('[data-btn="delete-question"]').click(function(){
		var id = $(this).attr('data-id');
		
		Swal.fire({
			type:'warning',
			text:'Seguro deseás borrar esta pregunta?',
			showCancelButton:true,
			reverseButtons:true
		})
			.then(function(response){
				if(response.value){
					ajax('admin/questions/delete_question',{id:id})
						.then(function(data){
							window.location.reload();
						});
				}
			});

	});


	$('[data-btn="delete-response"]').click(function(){
		var id = $(this).attr('data-id');
		
		Swal.fire({
			type:'warning',
			text:'Seguro deseás borrar esta respuesta?',
			showCancelButton:true,
			reverseButtons:true
		})
			.then(function(response){
				if(response.value){
					ajax('admin/questions/delete_response',{id:id})
						.then(function(data){
							window.location.reload();
						});
				}
			});

	});

});