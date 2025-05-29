var Subscribers = {
	ID:0,
	get:function(){
		$('#subscribers').html('');
		Promise.all([
			ajax('admin/subscribers/get',{keywords:$('#form_search input[type="text"]').val()}),
			get_template('modules/list')
		])
		.then(function(response){
			var results = response[0].results;
			if(results==false) return false;

			$.each(results,function(k,v){
				var $module = $(response[1]);
				$module.find('[data-content=title]').html(v.email);
				$module.find('.edit').remove();
				$module.find('.delete').attr('data-id',v.id);
				$('#subscribers').append($module);
			});

		});

	},
	delete:function(id){
		ajax('admin/subscribers/delete',{id:id})
			.then(function(data){
				Subscribers.get();
			});
	},
	init:function(){
		$('#form_search').submit(function(e){
			e.preventDefault();
			Subscribers.get();
		});
		$('#subscribers').on('click','.delete',function(){
			var id = $(this).attr('data-id');
			Swal.fire({
				type:'warning',
				text:'¿Seguro deseas borrar este usuario de la lista de subscriptores?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Subscribers.delete(id);
					}
				});
			
		});
		Subscribers.get();
	}
}
$(function(){
	Subscribers.init();
});