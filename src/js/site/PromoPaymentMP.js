class PromoPaymentMP {

	constructor(){
		this.init()
	}


	async calculate(){

		this.amount = parseInt($('[name="amount"]').val())


		this.total = this.unit_price*this.amount


		if(this.voucher){
			if(this.voucher.ispercent=='1'){
				this.total = this.total-(this.total*this.voucher.value/100)
			}else{
				this.total = this.total-this.voucher.value
			}
		}

		const total_formatted = new Intl.NumberFormat('es-ES',{minimumFractionDigits:2}).format(this.total)

		$('[data-content="total"]').text(total_formatted)


		const response = await ajax('site/promos/get-mp-preference',{
			promoid:this.promoid,
			amount:this.amount,
			total:this.total
		})
		this.preference = response.preference
		//console.log(response.preference)
		this.user = response.user
		this.mp_render()
		//console.log(amount,unit_price,this.total)
	}


	async mp_render(){

		//return
		$('#paymentBrick_container').html('')

		const mp_settings = {
			initialization: {
				amount: this.total, // monto a ser pago
				preferenceId: this.preference.id,
				payer: {
					email:this.user.email
				},
				marketplace:true //????
			},
			customization: {
				visual: {
					style: {
						theme: 'flat', // | 'dark' | 'bootstrap' | 'flat'
					}
				},
				paymentMethods: {
					creditCard: "all",
					debitCard: "all",
					mercadoPago: "all"
				}
			},
			callbacks: {
				onReady: () => {
					///$('[data-toggle="form-mp-loader"]').addClass('d-none')
					console.log('ok')
				},
				onSubmit: async cardFormData=>{
					console.log('payment.processing...')

					cardFormData.preference = this.preference
					cardFormData.promoid = this.promoid
					cardFormData.voucher = this.voucher
					cardFormData.amount = this.amount

					const response = await ajax('site/promos/checkout-mp',cardFormData)
					///return console.log(response)
					window.location.href = response.response.url_thanks
				},
				onError: (error) => {
					// callback llamado para todos los casos de error de Brick
					console.log(error)
					Swal.fire({
						type:'error',
						html:error.message
					})
				}
			}
		}

		// TEST
		const mp = new MercadoPago('TEST-16b8dfa7-44d1-4aba-9b04-d9a7c5cf53ab',{
			locale:'es-AR'
		})
		// PROD
		/*const mp = new MercadoPago('APP_USR-1474aace-f3a2-4f25-aecb-45c16d766c92',{
			locale:'es-AR'
		})*/
		const bricksBuilder = mp.bricks()
		const cardPaymentBrickController = await bricksBuilder.create('payment', 'paymentBrick_container', mp_settings)
			///.then(response=>this.cardPaymentBrickController)

	}

	init(){
		console.log('comprar.experiencia')

		this.voucher = false
		this.total = 0
		this.promoid = _subsection
		this.unit_price = parseFloat($('[data-content="unit-price"]').attr('data-value'))

		$('[name="amount"]').change(select=>{
			this.calculate()
		})
		$('[name="amount"]').trigger('change')


		//voucher
		$('[data-form="voucher"]').submit(async form=>{
			form.preventDefault()
			const post = get_form(form.currentTarget)
			const promise = await Promise.all([
				ajax('site/vouchers/validate',post),
				get_template('site/voucher-applied')
			])
			this.voucher = promise[0].result
			const template = $(promise[1])
			template.find('[data-content="name"]').html(`${this.voucher.name}`)
			if(this.voucher.ispercent=='1'){
				template.find('[data-content="discount"]').text(`${this.voucher.value}% off`)
			}else{
				template.find('[data-content="discount"]').text(`$ -${parseFloat(this.voucher.value).toLocaleString('es-AR',{minimumFractionDigits:2})}`)
			}

			//console.log(this.voucher)

			$('[data-content="voucher"]').html(template).removeClass('alert-warning').addClass('alert-success')
			this.calculate()
		})

	}

}

new PromoPaymentMP