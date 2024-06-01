$(function(){

	$('[data-btn="delete-question"]').click(async btn=>{

		const id = $(btn.currentTarget).attr('data-id');

		const swal = await Swal.fire({
			type:'warning',
			text:'Seguro deseás borrar esta pregunta?',
			showCancelButton:true,
			reverseButtons:true
		})
		if(!swal.value) return false
		const response = await ajax('admin/questions/delete_question',{id:id})
		window.location.reload()

	});


	$('[data-btn="delete-response"]').click(async btn=>{

		const id = $(btn.currentTarget).attr('data-id')

		const swal = await Swal.fire({
			type:'warning',
			text:'Seguro deseás borrar esta respuesta?',
			showCancelButton:true,
			reverseButtons:true
		})
		if(!swal.value) return false
		const response = await ajax('admin/questions/delete_response',{id:id})
		window.location.reload()

	})


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
		const response = await ajax('admin/questions/reply-question',post)
		window.location.reload()
	})


	// Response
	$('[data-btn="edit-response"]').click(async btn=>{
		const responseid = $(btn.currentTarget).attr('data-id')
		console.log(responseid)
		const promise = await Promise.all([
			get_template('admin/pop-response'),
			ajax('admin/questions/find-response',{responseid:responseid})
		])
		const form_response = $(promise[0])
		form_response.find('[name="response"]').val(promise[1].response.message)
		form_response.find('[name="responseid"]').val(promise[1].response.id)
		$('#popups').find('.modal-body').html(form_response)
		$('#popups').modal('show')
	})
	$('#popups').on('submit','[data-form="edit-response"]',async form=>{
		form.preventDefault()
		const post = get_form(form.currentTarget)
		const response = await ajax('admin/questions/edit-response',post)
		window.location.reload()
	})

	$('[data-btn="approve-response"]').click(async btn=>{
		const responseid = $(btn.currentTarget).attr('data-id')
		const response = await ajax('admin/questions/approve-response',{
			responseid:responseid,
			approve:1
		})
		window.location.reload()
	})
	$('[data-btn="disapprove-response"]').click(async btn=>{
		const responseid = $(btn.currentTarget).attr('data-id')
		const response = await ajax('admin/questions/approve-response',{
			responseid:responseid,
			approve:0
		})
		window.location.reload()
	})


});