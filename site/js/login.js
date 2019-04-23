$(function(){
	$('#form_login').submit(function(e){
		e.preventDefault();
		var post = get_form(this);	
		ajax('site/users/login',post)
			.then(function(data){
				if(window.location.hash != ''){
					window.location.href = window.location.hash.replace('#','');
					return false;
				}
				window.location.reload();
		});

	});

});