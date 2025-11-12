$(()=>{


	$('[data-form="giftcard-code"]').submit(async form=>{
		form.preventDefault()
		let code = ''
		$.each( $('[data-form="giftcard-code"] input'), (k,v)=>{
			code += $(v).val()
		})
		const response = await ajax('site/giftcards/redeem-code',{
			code:code
		})
		///$('[data-content="code-content"]').html(response.template_thanks)
		window.location.href = `${ROOT}abrir-regalo-exito`
		
	})
	$('[data-form="giftcard-code"] input').on('keyup',input=>{
		const wrapper = $(input.currentTarget).closest('.giftcard-code-input')
		const index = $(input.currentTarget).index()
		if(wrapper.find('input').length-1==index) return false 

		wrapper.find(`input:eq(${index+1})`).focus()

	})

	// Funcionalidad para pegar código completo desde el portapapeles
	$('[data-form="giftcard-code"] input').on('paste', async (e) => {
		e.preventDefault()
		const wrapper = $(e.currentTarget).closest('.giftcard-code-input')
		const inputs = wrapper.find('input')
		const startIndex = $(e.currentTarget).index()
		
		try {
			// Obtener texto del portapapeles
			const pastedText = (e.originalEvent.clipboardData || window.clipboardData).getData('text')
			
			// Limpiar el texto (eliminar espacios, guiones, etc.) y convertir a mayúsculas
			const cleanText = pastedText.replace(/[\s\-_]/g, '').toUpperCase()
			
			// Distribuir los caracteres en los inputs
			for (let i = 0; i < cleanText.length && (startIndex + i) < inputs.length; i++) {
				$(inputs[startIndex + i]).val(cleanText[i])
			}
			
			// Enfocar el siguiente input disponible o el último si ya se completó
			const nextIndex = Math.min(startIndex + cleanText.length, inputs.length - 1)
			$(inputs[nextIndex]).focus()
		} catch (error) {
			console.error('Error al pegar código:', error)
		}
	})


})