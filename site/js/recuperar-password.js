
$(function(){
	$('#form_recover').submit(function(e){
		e.preventDefault();
		var post = get_form(this);
	
		ajax('site/users/recover',post)
			.then(function(data){
				Swal.fire({html:data.message,type:'success'})
					.then(function(response){
						window.location.href = ROOT+'login';
					});
		});

	});

});