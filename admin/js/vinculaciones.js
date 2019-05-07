$(function(){
	$('#vinculaciones .unlink').click(function(){
		var id = $(this).attr('data-id');
		ajax('admin/clients/unlink_mp',{idclient:id})
			.then(function(){
				window.location.reload();
			});
	});
	$('#vinculaciones .renew').click(function(){
		var id = $(this).attr('data-id');
		ajax('admin/clients/renew_token',{idclient:id})
			.then(function(){
				window.location.reload();
			});
	});
});