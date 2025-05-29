$(function(){

	$('#categories').on('click','.delete',function(){
		var id = $(this).attr('data-id');
		Swal.fire({
			type:'warning',
			text:'¿Seguro deseas borrar esta categoría?',
			showCancelButton:true,
			reverseButtons:true,
		})
			.then(function(response){
				ajax('admin/promos-categories/delete',{id:id})
				.then(function(){
					window.location.reload();
				});
			});
	});


	$('#categories .row').sortable({
		update:function(e,ui){
			var arrids = [];
			$.each($('#categories .mod-card'),function(){
				arrids.push($(this).attr('data-id'));
			});
			ajax('admin/promos-categories/reorder',{arrids:arrids})
		}
	});
});