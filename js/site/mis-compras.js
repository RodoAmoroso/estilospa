$(function(){
	$('#sales .mod-sales [data-button="toggle"]').click(function(){
		var id = $(this).attr('data-id');
		console.log(id);
		$('#sales .mod-sales[data-id="'+id+'"] .sale-footer').slideToggle();
	});


	$('[data-toggle="download-ritual"]').click(async btn=>{
		btn.preventDefault()
		const href = $(btn.currentTarget).attr('data-href')

		const response = await ajax('site/users/file-download',{
			type:'Ritual Mamá'
		})
		window.open(`${href}`)
	})
});