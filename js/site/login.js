$(()=>{	

	const pathname = window.location.pathname
	const url_obj = new URL(window.location.href)

	console.log( atob(url_obj.searchParams.get('back_url')) )
	
	$('#form_login').submit(async form=>{
		form.preventDefault()
		const  post = get_form(form.currentTarget)
		
		const response = await ajax('site/users/login',post)

		const pathname = window.location.pathname
		const url_obj = new URL(window.location.href)

		if(url_obj.searchParams.get('back_url')!=null){
			window.location.href = `${ROOT}${atob(url_obj.searchParams.get('back_url'))}`
			return false
		}
		window.location.reload()
		

	})

})