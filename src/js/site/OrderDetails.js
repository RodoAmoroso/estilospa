class OrderDetails{


	generate_voucher(post){
		return new Promise((resolve,reject)=>{
			ajax('site/sales/save',post)
				.then(response=>resolve(response))
				.catch(error=>reject(error))
		})
	}

	modal_voucher(data=false){

		get_template('sales/pop-voucher')
			.then(template=>{

				let $template = $(template)

				if(data){

					$template.find('[name="to_user"]').val(data.to_user)
					$template.find('[name="message"]').val(data.message)
					$template.find('[name="id"]').val(data.id)

					$template.find('[data-content="save-generate"]').text('Actualizar')

					if(data.image!=null) {
						$template
							.find('#image')
							.css({
								backgroundImage:`url(${data.image.url})`
							})
							.attr({
								'data-filename':data.image.f,
								'data-extension':data.image.e
							})
					}
				}

				this.modal.find('.modal-body').html($template)
				this.modal.modal('show')

				if(data.gift=='1') this.modal.find('[name="gift"][value="1"]').prop({checked:true}).trigger('change')


				let upimage = new UpFile({
					container:'[data-input="image"]',
					controller:'site/sales/upimage',
					folder:'img/gift',
					thumbnail:'#image',
					scope:'site',
					sufix:''
				})

			})
	}

	constructor(){

		this.saleid = _subsection
		this.modal = $('#popups')

		this.modal.on('submit','[data-form="voucher"]',form=>{
			form.preventDefault()

			let post = get_form(form.currentTarget)
			post.saleid = this.saleid

			post.image = ''
			if(this.modal.find('#image').attr('data-filename') != undefined){
				post.image = {
					f:this.modal.find('#image').attr('data-filename'),
					e:this.modal.find('#image').attr('data-extension')
				}
			}

			this.generate_voucher(post)
				.then(response=>window.location.reload())
		})

		this.modal.on('change','[name="gift"]',radio=>{
			if(radio.currentTarget.value==1){
				this.modal.find('#fieldset_gift').slideDown()
				this.modal.find('[name="to_user"],[name="message"]').prop('required',true);
			}else{
				this.modal.find('#fieldset_gift').slideUp()
				this.modal.find('[name="to_user"],[name="message"]').prop('required',false);
			}
		})


		$('[data-toggle="generate-voucher"]').click(btn=>{
			this.modal_voucher()
		})


		$('[data-toggle="edit-voucher"]').click(btn=>{
			let voucherid = $(btn.currentTarget).attr('data-voucherid')
			ajax('site/sales/find-voucher',{voucherid:voucherid})
				.then(response=>this.modal_voucher(response.voucher))
			//this.modal_voucher()
		})




	}

}

new OrderDetails