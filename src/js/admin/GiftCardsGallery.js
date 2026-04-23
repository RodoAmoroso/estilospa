class GiftCardsGallery {

	constructor(){

		this.class = 'GiftCardsGallery'

		this.init()
	}

	async get_gallery(){

		$('[data-toggle="gallery"]').html('')

		const promise = await Promise.all([
			get_template('site/gallery-thumbnail'),
			ajax('admin/core/get',{
				class:this.class
			})
		])

		if(promise[1].results==false) return false 

		promise[1].results.map(image=>{
			const thumb = $(promise[0])
			thumb.css({
				backgroundImage:`url(${image.image.small})`
			})
			thumb.attr({
				'data-id':image.id
			})

			$('[data-toggle="gallery"]').append(thumb)
		})

		$('[data-toggle="gallery"]').sortable({
			update:async (event,ui)=>{
				const items = []
				$('[data-toggle="gallery"]').find('.thumbnail').each((index,elem)=>{
					items.push($(elem).attr('data-id'))
				})
				await ajax('admin/core/reorder',{
					class:this.class,
					arrids:items
				})
			}
		})

	}


	init(){
		console.log('giftcards.gallery')

		const gallery = new UpFile({
			container:'[data-input="gallery"]',			
			folder:'giftcards',
			controller:'admin/upload/medium',
			callback:async files=>{
				let images = []
				files.map(file=>{
					images.push({
						filename:file.filename,
						extension:file.extension
					})
				})
				const response = await ajax('admin/giftcards/save-gallery',{
					images:images
				})
				this.get_gallery()

			}
		})

		$('[data-toggle="gallery"]').on('click','[data-toggle="delete"]',async btn=>{
			btn.preventDefault()
			const thumb = $(btn.currentTarget).closest('.thumbnail')
			const id = thumb.attr('data-id')

			const swal = await SwalWarn.fire({
				title:'¿Seguro deseas borrar esta imagen?'
			})
			if(!swal.value) return false

			await ajax(`admin/core/delete/${id}`,{
				class:this.class
			})
			thumb.remove()
			this.get_gallery()
		})

		this.get_gallery()


	}

}

new GiftCardsGallery