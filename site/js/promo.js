/*var SendRequest = function(){
	$('#form_promo_request button').button('loading');
	AjaxConnection('jxForms.php',{
		Mode:'requestpromo',
		Name:$('#fd_name').val(),
		Mail:$('#fd_mail').val(),
		Phone:$('#fd_phone').val(),
		Message:$('#fd_message').val(),
		PDay:$('#fd_preference_day option:selected').text(),
		PSchedule:$('#fd_preference_schedule option:selected').text(),
		IDP:IDPromo
	},function(DATA){
		$('#form_promo_request button').button('reset');
		if(DATA.Status == 'fail'){
			Messages(true,'Hubo problemas al enviar la solicitud. Intenta más tarde');
			return false;
		}
		$('#form_promo_request').find('input,textarea').val('');
		$('#form_promo_request .status').html('<p class="alert alert-success">La solicitud ha sido enviada con éxito! En Breve nos comunicaremos con vos.</p>');
	});
}*/
var GetMPLink = function(GIFT,IDCode){
	$('#modal_mp iframe').attr('src',ROOT+'views/cargando.php');

	ajax('site/promos/getmplink',{
		idpromo:IDPromo,
		amount:$('#select_amount').val(),
		idcode:IDCode
	})
		.then(function(data){	
			
			if(GIFT){
				var post = get_form('#modal_gift form');
				post.hash = data.hash;
				ajax('site/promos/gift',post)
					.then(function(){
						window.location.href=data.link;
					});
			}else{
				window.location.href=data.link;
			}

		});
}

$(function(){
	
	var slider = new Slider({
		container:'.gallery'
	});
	var questions = new Questions({
		container:'#questions',
		form:'#form_question',
		mode:'getbypromo'
	});
	questions.get();
	
	$('#form_promo_request').submit(function(e){
		e.preventDefault();
		var post = get_form(this);
		///ajax('site/forms/')
		///TODO RESERVAS
	});
	


	$('#btn_sale').click(function(e){
		e.preventDefault();
		if($('#modal_voucher').length==0){
			GetMPLink(false,0);
		}else{
			$('#modal_voucher').modal('show');
			$('#btn_voucher_cancel,#btn_voucher_next').removeAttr('data-gift');
		}
	});
	
	$('#fd_gift_message').keyup(function(){
		$('#gift_left_characters').text(255-$(this).val().length);
	});

	$('#modal_gift form').submit(function(e){
		e.preventDefault();
		$('#modal_gift').modal('hide');
		if($('#modal_voucher').length==0){
			GetMPLink(true,0);
		}else{
			$('#modal_voucher').modal('show');
			$('#btn_voucher_cancel,#btn_voucher_next').attr('data-gift','true');
		}	
	});

	$('#form_voucher_apply').submit(function(e){
		e.preventDefault();
		//CheckFields(['#fd_voucher_code'],function(){
		$('#btn_voucher_apply').button('loading');
		var post = get_form(this);

		ajax('site/vouchers/validate',post)
			.then(function(data){
				$('#btn_voucher_apply').button('reset');
				
				var v = data.result;

				$('#voucher_status').removeClass('alert-danger').addClass('alert-success').empty().append(v.ispercent==1 ? v.value+'% de descuento' : 'Descuento de $'+v.value,' sobre el valor total de la promo');
				
				var promoprice = (v.price-(v.discount*v.price/100));
				var total=0;
				if(v.ispercent==1){
					total = promoprice-(promoprice*v.value/100);
				}else{
					total = promoprice-v.value;
				}

				$('#voucher_status').append($('<h3>').clone().append('<b>Pagás: $'+(total*$('#select_amount').val()).numberFormat(2,',','.')+'</b>'),$('<button>').clone().addClass('btn btn-primary').attr('id','btn_voucher_next').append('Continuar con el pago',' ',$('<i>').clone().addClass('fa fa-angle-double-right')));

				$('#btn_voucher_cancel').hide();
				////////////////////////////////////////
				$('#btn_voucher_next').unbind('click').click(function(){
					$('#modal_voucher').modal('hide');
					if(total!=0){
						GetMPLink(false,v.id);
					}else{
						ajax('site/vouchers/free',post).then(function(data){
							window.location.href = ROOT+'/pago-status/success';
						});
					}
				});
			})
			.catch(function(response){
				$('#btn_voucher_apply').button('reset');
			});
		//});		
	});
	$('#btn_voucher_cancel').click(function(){
		$('#modal_voucher').modal('hide');
		var isgift = $(this).attr('data-gift');		
		GetMPLink(isgift==undefined?false:true,0);
	});
	$('#modal_voucher').on('hidden.bs.modal', function (e) {
		$('#voucher_status').empty().removeClass('alert-danger alert-success');
		$('#btn_voucher_cancel').show();
	});
	$('.schedules').click(function(e){
		e.preventDefault();
		$(this).find('.schedules-block').toggleClass('active');
	});
	//$('#modal_mp').modal('show');

	var hash = window.location.hash.replace('#','');
	if(hash != ''){
		switch(hash){
			case 'consultar':
				$('#modal_promo_request').modal('show');
				break;
			case 'turno':
				$('#modal_promo_request').modal('show');
				break;
			case 'comprar':
				if(islogged==1){
					$('#btn_sale').trigger('click');
				}else{
					$('#modal_not_logged').modal('show');
				}
				break;
			case 'regalar':
				if(islogged==1){
					$('#modal_gift').modal('show');
				}else{
					$('#modal_not_logged').modal('show');
				}
				break;
		}
	}
});