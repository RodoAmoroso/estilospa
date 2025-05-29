$(function(){

	datatable_options.buttons = [
		{
			extend:'excel',
			title:'hotsale',
			text:'<i class="fa fa-file-excel-o fa-fw"></i> Exportar a Excel',
			exportOptions:{
				columns:[0,1]
			}
		}
	];

	$('#hotsale').DataTable(datatable_options);

	$('[data-action="delete"]').click(function(){
		var id = $(this).attr('data-id');
		Swal.fire({
			type:'warning',
			text:'¿Seguro deseas borrar a este usuario?',
			showCancelButton:true,
			reverseButtons:true
		})
			.then(function(response){
				if(response.value){
					ajax('admin/hotsale/delete',{id:id})
					.then(function(){
						window.location.reload();
					})
				}
			});
	});


});