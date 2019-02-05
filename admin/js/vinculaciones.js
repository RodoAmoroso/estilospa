$(function(){
	$('#vinculaciones .unlink').click(function(){
		var id = $(this).attr('data-id');
		AjaxConnection('jxClients.php',{Mode:'unlinkadmin',idclient:id},function(){
			window.location.reload();
		});
	});
	$('#vinculaciones .renew').click(function(){
		var id = $(this).attr('data-id');
		AjaxConnection('jxClients.php',{Mode:'renewadmin',idclient:id},function(){
			window.location.reload();
		});
	});
});