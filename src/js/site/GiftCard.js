class GiftCard {

  constructor(){

    this.id = $('[name="giftcard_id"]').val()

    this.init()
  }

  async get_mp_preference(){
    const response = await ajax('site/giftcards/get-mp-preference',{
      id:this.id
    })

    this.preference = response.preference
    this.public_key = response.public_key

    this.mp_render()
  }

  async mp_render(){

		//return
		$('#paymentBrick_container').html('')

		const mp_settings = {
			initialization: {
				amount: this.preference.items[0].unit_price, // monto a ser pago
				preferenceId: this.preference.id,
				payer: {
					email:$('[data-form="user-info"] [name="email"]').val()
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
						//window.location.href = this.preference.promo.url
						return false
					}

					cardFormData.preference = this.preference
					cardFormData.giftcard_id = this.id

					cardFormData.user = {
						firstname:$('[data-form="user-info"] [name="firstname"]').val(),
						lastname:$('[data-form="user-info"] [name="lastname"]').val(),
						phone:$('[data-form="user-info"] [name="phone"]').val(),
						email:$('[data-form="user-info"] [name="email"]').val()
					}


					ajax('site/giftcards/checkout-mp',cardFormData,true,false)
						.then(response=>{
							window.location.href = response.url_thanks
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
		const mp = new MercadoPago(this.public_key,{
			locale:'es-AR'
		})
		const bricksBuilder = mp.bricks()
		const cardPaymentBrickController = await bricksBuilder.create('payment', 'paymentBrick_container', mp_settings)
	}

  init(){

    $('[data-form="user-info"]').submit(async form=>{
			form.preventDefault()
			const post = get_form(form.currentTarget)

			const response = await ajax('site/promos/update-user',post)

      this.user = post
      $('[data-form="user-info"] [name]').attr({readonly:true})
      $('[data-form="user-info"] button').attr({disabled:true})

      toastr.success(response.message)

      this.get_mp_preference()

			//$('[data-toggle="user-info"]').slideUp()
			$('[data-toggle="payment"]').removeClass('d-none')
		})


    console.log('giftcard')
  }

}

new GiftCard