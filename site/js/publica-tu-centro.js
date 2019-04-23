$(function(){
	$('#form_publish').submit(function(e){
		e.preventDefault();
		var post = get_form(this);
		ajax('site/forms/publish',post)
			.then(function(data){
				$('#form_publish').find('input,textarea').val('');
				Swal.fire({type:'success',html:data.message});
			});
	});
});