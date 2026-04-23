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
	$('[data-form="profile"]').submit(async form=>{
		form.preventDefault();
		const post = get_form(form.currentTarget);
		post.newsletter = $('#newsletter').is(':checked') ? 1 : 0;
		const response = await ajax('site/users/update',post)
		Swal.fire({type:'success',html:response.message});
		
	});

});