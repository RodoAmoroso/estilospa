$(function(){
	$('#sales .mod-sales [data-button="toggle"]').click(function(){
		var id = $(this).attr('data-id');
		console.log(id);
		$('#sales .mod-sales[data-id="'+id+'"] .sale-footer').slideToggle();
	});
});