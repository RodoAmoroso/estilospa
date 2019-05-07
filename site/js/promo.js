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
var calendar = {
	get_hours:function(){
		var activeday = $('.calendar-promo .week .day[data-dayname].active').attr('data-dayname');

		Promise.all([
			ajax('site/reservations/get_hours',{idclient:IDClient,activeday:activeday}),
			get_template('reservations/module-hour')
		])
			.then(function(promises){
				$('.calendar-promo .schedule .hours').html('');
				data = promises[0];

				if(data.hours.length == 0) return false;
		
				var min = '09:00';
				var max = '21:00';
				$.each(data.hours,function(kk,vv){
					if(kk==0){
						min = vv[0];
					}
					if(kk==data.hours.length-1){
						max = vv[1];
					}
				});

				var hourminmin = min.split(':');
				var hourminmax = max.split(':');

				for(var i=parseInt(hourminmin[0]); i<=parseInt(hourminmax[0]); i++){
					
					$template = $(promises[1]);
					$template.attr('data-hour',i+':00').find('.number').text(i+':00 hs.');
					$('.calendar-promo .schedule .hours').append($template);
					
					$template = $(promises[1]);
					$template.attr('data-hour',i+':30').find('.number').text(i+':30 hs.');
					$('.calendar-promo .schedule .hours').append($template);
					
					if(i==14){
						$template.addClass('disabled');
					}
				}				

			});
					
	},
	change_days:function(month,year,action,firstday,lastday){
		ajax('site/reservations/change_days',{month:month,year:year,action:action,firstday:firstday,lastday:lastday})
			.then(function(data){
				$('.calendar-promo [data-month]').attr('data-month',data.month).text(data.month_name);
				$('.calendar-promo [data-year]').attr('data-year',data.year).text(data.year);
				$.each(data.days,function(k,v){
					$('.calendar-promo .week .day[data-day]:eq('+k+')').attr({'data-day':v.day,'data-dayname':v.dayname}).text(v.name+' '+v.day);
				});
			});
	},
	change_month:function(month,year,action){
		ajax('site/reservations/change_month',{month:month,year:year,action:action})
			.then(function(data){
				calendar.change_days(data.month,data.year,'');
			});
	},
	init:function(){

		$('.calendar-promo .month').on('click','.next,.prev',function(){
			var month = $('.calendar-promo [data-month]').attr('data-month');
			var year = $('.calendar-promo [data-year]').attr('data-year');
			var action = $(this).attr('data-action');
			calendar.change_month(month,year,action);
		});

		$('.calendar-promo .week').on('click','.next,.prev',function(){
			var month = $('.calendar-promo [data-month]').attr('data-month');
			var year = $('.calendar-promo [data-year]').attr('data-year');
			var action = $(this).attr('data-action');
			var firstday = $('.calendar-promo .week .day[data-day]').first().attr('data-day');
			var lastday = $('.calendar-promo .week .day[data-day]').last().attr('data-day');
			calendar.change_days(month,year,action,firstday,lastday);
		});

		$('.calendar-promo .week').on('click','.day[data-day]',function(data){
			$('.calendar-promo .week .day[data-day]').removeClass('active');
			$(this).addClass('active');
			calendar.get_hours();
		});

		$('.calendar-promo .hours').on('click','.hour:not(.disabled) .btn',function(data){
			$('.calendar-promo .hours .btn').removeClass('active');
			$(this).addClass('active');

			$('#selected_schedule').text($('.calendar-promo .week .day[data-day].active').text() + ' de ' + $('.calendar-promo [data-month]').text() + ' ' + $('.calendar-promo [data-year]').text() + ' ' + $('.calendar-promo [data-action=select].active').parent().parent().attr('data-hour') + 'hs.');

			$('#form_promo_request [name=date]').val($('.calendar-promo [data-year]').text()+'-'+$('.calendar-promo [data-month]').attr('data-month')+'-'+$('.calendar-promo .week .day[data-day].active').attr('data-day')+' '+$('.calendar-promo [data-action=select].active').parent().parent().attr('data-hour')+':00' );

		});

		calendar.get_hours();

	}
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
	
	$('#form_promo_request').submit(function(e){
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

				$('#modal_promo_request').modal('hide');
				Swal.fire({
					type:'success',
					text:data.message
				});
			});
		
	});

	var reservations = new Reservations({
		idclient:IDClient,
		container:'.calendar-promo',
		callback:function(data){
			$('#selected_schedule').text(data.text);
			$('#form_promo_request [name=date]').val(data.date);
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