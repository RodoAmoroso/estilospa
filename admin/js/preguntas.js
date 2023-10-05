$(function(){

	$('[data-btn="delete-question"]').click(function(){
		var id = $(this).attr('data-id');

		Swal.fire({
			type:'warning',
			text:'Seguro deseás borrar esta pregunta?',
			showCancelButton:true,
			reverseButtons:true
		})
			.then(function(response){
				if(response.value){
					ajax('admin/questions/delete_question',{id:id})
						.then(function(data){
							window.location.reload();
						});
				}
			});

	});


	$('[data-btn="delete-response"]').click(function(){
		var id = $(this).attr('data-id');

		Swal.fire({
			type:'warning',
			text:'Seguro deseás borrar esta respuesta?',
			showCancelButton:true,
			reverseButtons:true
		})
			.then(function(response){
				if(response.value){
					ajax('admin/questions/delete_response',{id:id})
						.then(function(data){
							window.location.reload();
						});
				}
			});

	});


	$('[data-btn="reply-question"]').click(async btn=>{
		const messageid = $(btn.currentTarget).attr('data-id')
		const promise = await Promise.all([
			get_template('admin/pop-reply-question'),
			ajax('admin/questions/find-question',{messageid:messageid})
		])

		const form_response = $(promise[0])
		const question = promise[1].question

		form_response.find('[data-content="message"]').text(question.message)
		form_response.find('[name="messageid"]').val(messageid)

		$('#popups').find('.modal-body').html(form_response)
		$('#popups').modal('show')
	})

	$('#popups').on('submit','[data-form="reply-question"]',async form=>{
		form.preventDefault()
		const post = get_form(form.currentTarget)

		///return console.log(post)

		const response = await ajax('admin/questions/reply-question',post)
		window.location.reload()
	})


});