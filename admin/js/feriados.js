$(function(){

	$('#form_holiday').submit(function(e){
		e.preventDefault();
		var post = get_form(this);

		ajax('admin/holidays/add',post)
			.then(function(data){
				window.location.reload();
			});

	});

	$('#table_holidays .delete').click(function(){
		var id = $(this).attr('data-id');

		Swal.fire({
			type:'warning',
			text:'¿Seguro deseas borrar este feriado?'
		})
			.then(function(response){
				if(response.value){					
				ajax('admin/holidays/delete',{id:id})
					.then(function(data){
						window.location.reload();
					});
				}
			});


	});



	var datatable_options = {
		language:{
			url:ROOT+'js/lib/dataTables/spanish.json',
		},
		dom:'<"table-spacer-top"lf>t<"table-spacer-bottom"ip>',
		responsive:true,
		pageLength:25,
		columns:[
			null,
			null,
			null
		]		
	}

	 $('#table_holidays').DataTable(datatable_options);



	$('#form_holiday [name="date"]').datepicker();

});