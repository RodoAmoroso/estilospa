$(function(){

	$('#form_hotsale').submit(function(e){
		e.preventDefault();
		var post = get_form(this);
		ajax('site/forms/hotsale',post)
			.then(function(data){
				window.location.href = ROOT+'cybermonday-gracias';
			});

	});

});