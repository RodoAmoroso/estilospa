class Promo {

	constructor(){

		this.init()
	}


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

	}

}


new Promo