class PromoPaymentMP {

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


	async mp_render(){

		//return
		$('#paymentBrick_container').html('')

		console.log(this.user)

		const mp_settings = {
			initialization: {
				amount: this.preference.sale.total, // monto a ser pago
				preferenceId: this.preference.id,
				payer: {
					email:this.user.email
				},
				//marketplace:true //????
			},
			customization: {
				visual: {
					style: {
						theme: 'flat', // | 'dark' | 'bootstrap' | 'flat'
					},
					texts:{
						paymentMethods:{
							creditCardValueProp:'Usá tu tarjeta de crédito de forma segura'//'Hasta 12 cuotas fijas'
						}
					}
				},
				paymentMethods: {
					creditCard: "all",
					debitCard: "all",
					mercadoPago: "all",
					maxInstallments: 1
				}
			},
			callbacks: {
				onReady: () => {
					///$('[data-toggle="form-mp-loader"]').addClass('d-none')
					console.log('ok')
				},
				onSubmit: async cardFormData=>{
					//console.log('payment.processing...')

					//return console.log(cardFormData)


					if(cardFormData.paymentType=='wallet_purchase' || cardFormData.paymentType=='onboarding_credits'){
						window.location.href = this.preference.promo.url
						return false
					}

					cardFormData.preference = this.preference
					cardFormData.promoid = this.promoid
					cardFormData.quantity = this.quantity

					cardFormData.user = {
						firstname:$('[data-form="user-info"] [name="firstname"]').val(),
						lastname:$('[data-form="user-info"] [name="lastname"]').val(),
						phone:$('[data-form="user-info"] [name="phone"]').val(),
						email:$('[data-form="user-info"] [name="email"]').val()
					}


					ajax('site/promos/checkout-mp',cardFormData,true,false)
						.then(response=>{
							window.location.href = response.response.url_thanks
						})
						.catch(async error=>{
							const swal = await Swal.fire({
								type:'error',
								html:error.message
							})
							window.location.reload()
						})
					///return console.log(response)
				},
				onError: async error=>{
					// callback llamado para todos los casos de error de Brick
					const swal = await Swal.fire({
						type:'error',
						html:error.message
					})

					console.log(error)

				}
			}
		}
		const mp = new MercadoPago(this.preference.public_key,{
			locale:'es-AR'
		})
		const bricksBuilder = mp.bricks()
		const cardPaymentBrickController = await bricksBuilder.create('payment', 'paymentBrick_container', mp_settings)
	}

	async update_sale(){
		const response = await ajax('site/promos/update-sale',{
			quantity:$('[name="quantity"]').val()
		})
		window.location.reload()
	}

	init(){
		console.log('comprar.experiencia')

		this.voucher = false
		this.subtotal = 0
		this.quantity = parseInt($('[name="quantity"]').val())
		this.promoid = _subsection
		this.unit_price = parseFloat($('[data-content="unit-price"]').attr('data-value'))

		$('[name="quantity"]').change(select=>{
			this.update_sale()
		})
		///$('[name="quantity"]').trigger('change')
		//this.create_preference()


		//voucher
		$('[data-form="voucher"]').submit(async form=>{
			form.preventDefault()
			const post = get_form(form.currentTarget)

			const response = await ajax('site/vouchers/validate',post)
			window.location.reload()
			return console.log(response)
			/*const template = $(promise[1])
			template.find('[data-content="name"]').html(`${this.voucher.name}`)
			if(this.voucher.ispercent=='1'){
				template.find('[data-content="discount"]').text(`${this.voucher.value}% off`)
			}else{
				template.find('[data-content="discount"]').text(`$ -${parseFloat(this.voucher.value).toLocaleString('es-AR',{
					minimumFractionDigits:2
				})}`)
			}
			$('[data-content="voucher"]').html(template).removeClass('alert-warning').addClass('alert-success')*/
			//this.calculate()
		})



		$('[data-form="user-info"]').submit(async form=>{
			form.preventDefault()
			const post = get_form(form.currentTarget)

			const response = await ajax('site/promos/update-user',post)
			this.user = post

			$('[data-form="user-info"] [name]').attr({readonly:true})
			$('[data-toggle="payment-box"]').removeClass('d-none')

			this.create_preference()
		})

	}

}
new PromoPaymentMP