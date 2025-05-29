$(function(){
	$('#form_contact').submit(function(e){
		e.preventDefault();		
		var post = get_form(this);
		ajax('site/forms/contact',post)
			.then(function(data){
				$('#form_contact').find('input,textarea').val('');
				Swal.fire({type:'success',html:data.message});
			});
	});
});