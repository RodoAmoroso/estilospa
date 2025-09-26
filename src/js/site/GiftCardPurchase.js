class GiftCardPurchase {

	constructor(){
		this.init()
	}

	async create_preference(){

		const response = await ajax('site/promos/get-mp-preference',{
			promoid:this.promoid
		})
		this.preference = response.preference
		///return console.log(response)
		this.mp_render()

	}

	init(){
		console.log('giftcard.purchase')

		$('[data-form="user-info"]').submit(form=>{
			form.preventDefault()
			const post = get_form(form.currentTarget)

			console.log(post)

			$('[data-toggle="user-info"]').addClass('d-none')
			$('[data-toggle="payment"]').removeClass('d-none')
		})


		$('[data-toggle="giftcard-carousel"]').owlCarousel({
			margin:10,
			dots:true,
			responsive:{
				0:{
					items:1
				},
				597:{
					items:2
				},
				991:{
					items:3
				}
			}
		})

		$('[data-toggle="giftcard-carousel"]').on('click','.thumbnail',btn=>{
			$('[data-toggle="giftcard-carousel"] .thumbnail').removeClass('selected')
			$(btn.currentTarget).addClass('selected')
		})
	}
}

new GiftCardPurchase