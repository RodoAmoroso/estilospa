$(function(){

	var upimage = new UpFile({
		container:'[data-input="image"]',
		controller:'site/users/upimage',
		folder:'img/users',
		thumbnail:'#avatar',
		scope:'site',
		sufix:'-o'
	});
	

	$('#fd_newsletter,[for="fd_newsletter"]').click(function(){
		$('#fd_newsletter').toggleClass('fa-check-square fa-square');
	});
	$('#form_user').submit(function(e){
		e.preventDefault();
		var post = get_form(this);
		post.newsletter = $('#newsletter').is(':checked') ? 1 : 0;
		ajax('site/users/update',post)
			.then(function(data){
				Swal.fire({type:'success',html:data.message});
			});
	});
});