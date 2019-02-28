var SendRequest = function(){
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
}
var GetMPLink = function(GIFT,IDCode){
	$('#modal_mp iframe').attr('src',ROOTPATH+'views/cargando.php');
	AjaxConnection('jxPromos.php',{Mode:'getmplink',IDP:IDPromo,Amount:$('#select_amount').val(),IDC:IDCode},function(DATA){
		if(DATA.Status == 'amount'){
			Messages(true,'La cantidad indicada es mayor con la cantidad de promos disponibles');
			$('#select_amount option[value="'+DATA.Amount+'"]').prop('selected',true);
			return false;
		}
		if(DATA.Status == 'fail'){
			Messages(true,'Hubo problemas al procesar la solicitud. Intenta más tarde.');
			return false;
		}
		if(DATA.Status == 'logged'){
			Messages(true,'Tienes que estar logueado para efectuar esta operación');
			return false;
		}
		if(DATA.Status == 'error'){
			Messages(true,'Ocurrió un error al procesar la solicitud. Intenta de nuevo en unos instantes. Si el problema persiste comunícate con nosotros.');
			console.log(DATA);
			return false;
		}
		//console.log(DATA.Hash);
		$('#modal_mp iframe').attr('src',DATA.Link);
		window.location.href=DATA.Link;
		///$('#modal_mp').modal('show');
		if(GIFT){
			AjaxConnection('jxPromos.php',{
			Mode:'gift',
			From:$('#fd_gift_from').val(),
			To:$('#fd_gift_to').val(),
			Mail:$('#fd_gift_mail').val(),
			Message:$('#fd_gift_message').val(),
			IDP:IDPromo,
			Hash:DATA.Hash
			},function(DATA){
				console.log(DATA);
			});
		}
		//$('a[name="MP-Checkout"]').attr('href',DATA.Link);			
	});
}
$(function(){
	SetSlider.init('.gallery');
	var owlPromos = $('#promos_carousel');	
	owlPromos.owlCarousel({autoplay:true,loop:true,dots:true,autoplaySpeed:1000,responsive:{0:{items:1},600:{items:2},960:{items:3},1200:{items:4}},margin:10});
	$('#form_promo_request').submit(function(e){
		e.preventDefault();
		SendRequest();
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
		CheckFields(['#fd_voucher_code'],function(){
			$('#btn_voucher_apply').button('loading');
			AjaxConnection('jxVouchers.php',{Mode:'validate',IDP:IDPromo,Code:$('#fd_voucher_code').val()},function(DATA){
				$('#btn_voucher_apply').button('reset');
				if(DATA.Status == 'fail'){
					$('#voucher_status').addClass('alert-danger').html(DATA.Message);
					return false;
				}
				var v = DATA.Result;
				$('#voucher_status').removeClass('alert-danger').addClass('alert-success').empty().append(v.ispercent==1 ? v.value+'% de descuento' : 'Descuento de $'+v.value,' sobre el valor total de la promo');
				var t = Templates;
				var promoprice = (v.price-(v.discount*v.price/100));
				var total=0;
				if(v.ispercent==1){
					total = promoprice-(promoprice*v.value/100);
				}else{
					total = promoprice-v.value;
				}
				$('#voucher_status').append(t.h3.clone().append('<b>Pagás: $'+(total*$('#select_amount').val()).FormatMoney(2,',','.')+'</b>'),t.button.clone().addClass('btn btn-fucsia').attr('id','btn_voucher_next').append('Continuar con el pago',' ',t.i.clone().addClass('fa fa-angle-double-right')));
				$('#btn_voucher_cancel').hide();
				////////////////////////////////////////
				$('#btn_voucher_next').unbind('click').click(function(){
					$('#modal_voucher').modal('hide');
					if(total!=0){
						GetMPLink(false,v.id);						
					}else{
						AjaxConnection('jxVouchers.php',{Mode:'free',IDP:IDPromo,Code:$('#fd_voucher_code').val()},function(data){
							///return console.log(data);
							window.location.href = ROOTPATH+'pago-status.php?status=success';
						});
					}
				});
			});
		});		
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