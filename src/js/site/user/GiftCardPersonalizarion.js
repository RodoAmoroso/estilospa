class GiftCardPersonalizarion{    

    constructor(){
			
			this.init()

    }

		init(){

			console.log('GiftCardPersonalizarion.init')

			$('[data-toggle="giftcard-carousel"]').owlCarousel({
				loop:false,
				margin:20,
				nav:false,
				autoplay:false,
				mouseDrag: true,
				touchDrag: true,				
				responsive: {
					0:{
						items:2,						
					},
					500:{
						items:3,
					},
					992:{
						items:4,
					},
					1200:{
						items:5,
					}
				}

			})

			$('[data-form="giftcard-personalization"]').on('submit',async form=>{
				form.preventDefault()

				const swal = await SwalWarn.fire({					
					title:`¿Estas seguro de guardar la personalización?`					
				})
				if(!swal.value) return false

				const post = get_form(form.currentTarget)				
				post.gallery_id = $('[data-toggle="giftcard-carousel"] .thumbnail.selected').data('id')
				if($('[data-toggle="user-image-container"] li').length>0){
					const file = $('[data-toggle="user-image-container"] li').data()
					post.image = {
						f:file.filename,
						e:file.extension,
						h:file.hash
					}
				}
				const response = await ajax('site/giftcards/save-personalization',post)
				console.log(response)

				window.location.href = `${ROOT}usuario/mis-giftcards`
			})
			$('[data-form="giftcard-personalization"]').on('keyup update','[name]',input=>{
				const text = $(input.target).val()
				$(`[data-content="${$(input.target).attr('name')}"]`).html(text)
			})

			$('[data-toggle="giftcard-carousel"]').on('click','.thumbnail',card=>{
				$('[data-toggle="giftcard-carousel"] .thumbnail.selected').removeClass('selected')
				$(card.currentTarget).addClass('selected')
				const img = $(card.currentTarget).data('img')
				if($('[data-toggle="user-image-container"] li').length==0){
					$('.giftcard-preview .right-column').css('background-image',`url(${img})`)
				}
				$('.giftcard-preview .left-column').css('background-image',`url(${img})`)
			})  
			$('[data-toggle="giftcard-carousel"] .thumbnail.selected').trigger('click')



			const upimage = new UpFile({
				container:'[data-input="image"]',
				controller:'site/giftcards/upimage',
				callback:files=>{
					const file = files[0]
					const file_url = `${ROOT}img/giftcards/${file.filename}.${file.extension}`
					$('.giftcard-preview .right-column').css('background-image',`url(${file_url})`)

					$('[data-toggle="user-image-container"]').html(`
						<li data-filename="${file.filename}" data-extension="${file.extension}" data-hash="${file.hash}" class="list-group-item d-flex align-items-center justify-content-between">
							<div class="d-flex align-items-center">
								<div class="filename-thumbnail img-thumbnail bg-gray-5 me-3 thumb-80x80 thumb-contain" style="background-image:url(${file_url})"></div>
								<div>
									<div class="filename">${file.filename}.${file.extension}</div>
									<div class="small-comment">${file.size}</div>
								</div>
							</div>
							<button type="button" data-toggle="delete-image" class="btn btn-danger btn-xs">
								<i class="fa fa-trash fa-fw"></i>
							</button>
						</li>
					`)
					
				}					
			})

			$('[data-toggle="user-image-container"]').on('click','[data-toggle="delete-image"]',async btn=>{
				const file = $(btn.currentTarget).closest('li').data()
				const swal = await SwalWarn.fire({
					text:`¿Estas seguro de eliminar el archivo ${file.filename}.${file.extension}?`
				})
				if(!swal.value) return false 				
				const response = await ajax('site/giftcards/delete-image',file)
				$(btn.currentTarget).closest('li').remove()
				$('[data-toggle="giftcard-carousel"] .thumbnail:first').trigger('click')
			})

		}

}

new GiftCardPersonalizarion()