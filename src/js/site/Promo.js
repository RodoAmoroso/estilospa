class Promo {

	constructor(){

		this.init()
	}

	/*validate_voucher(post){
		return new Promise(resolve=>{
			ajax('site/vouchers/validate',post)
				.then(response=>resolve(response))
		})
	}*/

	/*apply_voucher(){

		$('#voucher_status').removeClass('alert-danger').addClass('alert-success').empty().append(this.voucher.ispercent==1 ? `<h4>${this.voucher.value}% de descuento</h4>` : `Descuento de $${this.voucher.value}, sobre el valor total de la promo`);

		//let promoprice = (this.voucher.price-(this.voucher.discount*this.voucher.price/100));
		const promo_price = this.voucher.price
		this.total=0;
		if(this.voucher.ispercent==1){
			this.total = promo_price-(this.voucher.price*this.voucher.value/100);
		}else{
			this.total = promo_price-this.voucher.value;
		}

		const title = dom('h3')
		title.html(`<b>Pagás: $ ${(this.total*$('#select_amount').val()).numberFormat(2,',','.')}</b>`)
		const button_next = dom('button','btn btn-primary')
		const i_next = dom('i','fa fa-angle-double-right fa-fw')
		button_next.attr({'data-toggle':'voucher-next'}).append(i_next,'<span>Continuar con el pago</span>')

		$('#voucher_status').append(title,button_next)

		$('#btn_voucher_cancel').hide()

	}*/


	/*set_mp_settings(){

		this.mp_settings = {
			initialization: {
				amount: this.total, // monto a ser pago
				payer: {
					//email:this.user.email,
					email: 'test_user_59143675@testuser.com',
					identification:{
						type:'DNI',
						number:'01111111'
					}
				}
			},
			customization: {
				maxInstallments:12,
				visual: {
					style: {
						theme: 'default', // | 'dark' | 'bootstrap' | 'flat'
					}
				}
			},
			callbacks: {
				onReady: () => {
					$('[data-toggle="form-loader"]').addClass('d-none')
				},
				onSubmit: (cardFormData) => {
					console.log('payment.processing...')
					//console.log(cardFormData)

					ajax('site/cart/checkout-mp',cardFormData)
						.then(response=>{
							console.log('payment.approved')
							window.location.href = `${ROOT}cart-thanks`
						})
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

	}*/




	init(){

		console.log('promo')

		this.promoid = IDPromo
		this.clientid = IDClient
		this.is_logged = islogged
		this.has_voucher = hasVoucher
		this.voucher = false
		this.total = parseFloat($('[data-toggle="price"]').attr('data-value'))

		const slider = new Slider({
			container:'.gallery'
		})
		const questions = new Questions({
			container:'#questions',
			form:'#form_question',
			mode:'getbyid'
		})
		questions.get();

		char_count('#form_question [name=message]')


		const reservations = new Reservations({
			idclient:IDClient,
			container:'.calendar-promo',
			callback:function(data){
				$('#selected_schedule').text(data.text)
				$('[data-form="reservation"] [name=date]').val(data.date)
			}
		})


		$('[data-toggle="buy"]').click(btn=>{

			if(!this.is_logged){
				$('#modal_not_logged').modal('show')
				return false
			}

			if(this.has_voucher){

				$('#modal_voucher').modal('show');
				$('#btn_voucher_cancel,#btn_voucher_next').removeAttr('data-gift');

				return false
			}

			console.log('buy')
		})


		/// Voucher
		/*$('#modal_voucher').on('hidden.bs.modal', (e)=>{
			$('#voucher_status').empty().removeClass('alert-danger alert-success')
			$('#btn_voucher_cancel').show()
		})
		$('#btn_voucher_cancel').click(btn=>{
			$('#modal_voucher').modal('hide')
		})
		$('[data-form="apply-voucher"]').submit(async form=>{
			form.preventDefault()
			var post = get_form(form.currentTarget)
			const response = await ajax('site/vouchers/validate',post)
			this.voucher = response.result
			console.log(this.voucher)
			this.apply_voucher()
		})

		$('#voucher_status').on('click','[data-toggle="voucher-next"]',btn=>{
			console.log('apply!')

			this.set_mp_settings()
			console.log(this.mp_settings)
		})*/


	}

}


new Promo