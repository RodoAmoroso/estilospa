$(function(){
	$('#promos .delete,#clients .delete').click(function(){
		var id = $(this).attr('data-id');
		AjaxConnection('jxUsers.php',{Mode:'deletefav',ID:id},function(DATA){
			window.location.reload();
		});
	});
});