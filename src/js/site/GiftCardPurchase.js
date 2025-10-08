class GiftCardPurchase {

	constructor(){
		this.init()
	}

	async create_preference(){

		const response = await ajax('site/giftcards/get-mp-preference',{
			giftcardid:giftcardid
		})
		this.preference = response.preference
		///return console.log(response)
		this.mp_render()

	}

	init(){
		console.log('giftcard.purchase')

		

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