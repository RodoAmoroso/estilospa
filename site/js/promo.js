var GetMPLink = function(GIFT,IDCode){
	$('#modal_mp iframe').attr('src',ROOT+'views/cargando.php');

	ajax('site/promos/getmplink',{
		idpromo:IDPromo,
		amount:$('#select_amount').val(),
		idcode:IDCode,
		reservationid:_vars
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
		mode:'getbyid'
	});
	questions.get();

	char_count('#form_question [name=message]');
	
	

	$('#form_reservation').submit(function(e){
		e.preventDefault();
		var post = get_form(this);
		if(post.date == ''){
			Swal.fire({
				type:'warning',
				text:'Te falta seleccionar un día y un horario'
			});
			return false;
		}
		ajax('site/reservations/book',post)
			.then(function(data){

				$('#modal_reservation').modal('hide');
				Swal.fire({
					type:'success',
					html:data.message
				});
			});
		
	});
	var reservations = new Reservations({
		idclient:IDClient,
		container:'.calendar-promo',
		callback:function(data){
			$('#selected_schedule').text(data.text);
			$('#form_reservation [name=date]').val(data.date);
		}
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
							window.location.href = ROOT+'pago-status/success';
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
				$('#modal_reservation').modal('show');
				break;
			case 'turno':
				$('#modal_reservation').modal('show');
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