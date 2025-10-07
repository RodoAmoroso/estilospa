class GiftCardsGallery {

	constructor(){

		this.init()
	}


	init(){
		console.log('giftcards.gallery')

		const gallery = new UpFile({
			container:'[data-input="gallery"]',			
			folder:'giftcards',
			controller:'admin/upload/medium',
			callback:files=>{
				console.log(files)
			}
		})


	}

}

new GiftCardsGallery