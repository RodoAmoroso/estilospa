class GiftCardsPurchases {

	constructor(){
		this.init()
	}

	init(){
		console.log('giftcards.purchases')

		$('[data-toggle="copy-code"]').click(async btn=>{
			const code = $(btn.currentTarget).attr('data-code')
			if(!('clipboard' in navigator)) {
				toastr.error(`No pudimos copiar el código: ${code}`)
				return false
			}
			await navigator.clipboard.writeText(code)

			toastr.success(`Código copiado! ${code}`)
		})
	}

}

new GiftCardsPurchases