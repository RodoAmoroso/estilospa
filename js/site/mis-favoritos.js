$(function(){
	$('#promos .delete,#clients .delete').click(function(){
		var id = $(this).attr('data-id');
		ajax('site/users/deletefav',{id:id})
			.then(function(data){
				window.location.reload();
			});
	});
});