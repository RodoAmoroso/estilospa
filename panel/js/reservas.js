var calendar = {
	from:'',
	to:'',
	get:function(){
		
		ajax('panel/reservations/get',{from:calendar.from,to:calendar.to})
			.then(function(data){
				//console.log(data);
				if(data.results==false){return false;}
				var events = [];
				$.each(data.results,function(k,v){
					var img = $.parseJSON(v.gallery);					
					
					var start = new Date(v.book_date);
					var end = new Date(start.valueOf()+(30*60000));
					var classcell = '';
					
					switch(v.status){
						case '0':
							classcell = 'bg-yellow-3 border-yellow-3';
							break;

						case '1':
							classcell = 'bg-green-3 border-green-3';
							break;

						case '2':
							classcell = 'bg-aqua-3 border-aqua-3';
							break;

						default:
							classcell = 'bg-pink-2 border-pink-2';
							break;
					}

					events.push({
						title:v.title==null?'No disponible':v.title,
						subtitle:v.subtitle,
						start:start,
						fecha:v.fecha,
						end:end,
						id:v.id,
						price:v.price,
						permalink:v.permalink,
						promoid:v.promoid,
						status:v.status,
						image:v.promoid==0 ? '' : img[0].photoname+'-t.'+img[0].extension,
						user_name:v.user_name,
						user_email:v.user_email,
						user_phone:v.user_phone,
						client_name:v.client_name,
						comments:v.comments,
						className:'pad-4 '+classcell
					});
				});
				$('#calendar').fullCalendar('removeEvents');
				$('#calendar').fullCalendar('addEventSource',events);
			});
	},
	delete:function(id,promoid){
		ajax('panel/reservations/delete',{id:id,promoid:promoid})
			.then(function(data){
				calendar.get();
				$('#modal_event').modal('hide');
				toastr['success'](data.message);
			});
	},
	confirm:function(id,promoid){
		ajax('panel/reservations/confirm',{id:id,promoid:promoid})
			.then(function(data){
				calendar.get();
				$('#modal_event').modal('hide');
				toastr['success'](data.message);
			});
	},
	change_date:function(id,promoid,date){
		ajax('panel/reservations/change_date',{id:id,promoid:promoid,date:date})
			.then(function(data){
				calendar.get();
				toastr['success'](data.message);
			});
	},
	init:function(){

		$('#calendar').fullCalendar({
			header:{
				left:'month,agendaWeek,agendaDay',
				center:'title',
				right:'prev,next today',
			},
			//eventColor: '#6dc0ae',
			editable:true,
			eventDurationEditable:false,
			droppable:true,
			defaultView: 'agendaWeek',
			themeSystem:'bootstrap4',
			eventClick:function(e, jsEvent, view){
				if(e.promoid==0) return false;

				get_template('reservations/modal-calendar')
					.then(function(template){
						$template = $(template);
						$template.find('.title').html('<a href="'+ROOT+'promo/'+e.permalink+'/'+e.promoid+'-'+(e.title).permalink()+'" target="_blank" >'+e.title+'</a>');

						$template.find('.subtitle').html(e.subtitle);
						$template.find('.client').html('<a href="'+ROOT+'centros/'+e.permalink+'" target="_blank" >'+e.client_name+'</a>');

						$template.find('.user-name').html(e.user_name+' (<a href="mailto:'+e.user_email+'">'+e.user_email+'</a>)');
						$template.find('.user-phone span').html(e.user_phone);

						$template.find('.user-comments').html(e.comments==null ? 'No ha dejado comentarios' : e.comments);

						$template.find('.date span').html(e.fecha+' hs.');
						$template.find('.price').html('$ '+(parseInt(e.price).numberFormat(2,',','.')));

						$template.find('.image').css({backgroundImage:'url('+ROOT+'img/promos/'+e.image +')'});
						$template.find('[data-btn=cancel],[data-btn=confirm]').attr({'data-id':e.id,'data-promoid':e.promoid});
						$template.find('[data-btn=change-date]').attr({'href':ROOT+'panel/reserva/'+e.id});

						if(e.status==1){
							$template.find('[data-status]').removeClass().addClass('label bg-green-3').text('Confirmada');
							$template.find('[data-btn="confirm"]').remove();
						}else if(e.status==0){
							$template.find('[data-status]').removeClass().addClass('label bg-yellow-3').text('A confirmar');
						}else{
							$template.find('[data-btn="confirm"]').remove();
							$template.find('[data-status]').removeClass().addClass('label bg-aqua-3').text('A confirmar por el usuario');
							$template.find('[data-btn=change-date]').remove();
							
						}

						$('#modal_event').find('.modal-body').html('');
						$('#modal_event').find('.modal-body').append($template);
						$('#modal_event').modal('show');

					});

			},
			viewRender:function (view, element) {
				var s = new Date(view.start.valueOf()+(3*60*60000));
				var e = new Date(view.end.valueOf()+(3*59*60000));
				
				calendar.from = s.getFullYear()+'-'+("0" + (s.getMonth() + 1)).slice(-2)+'-'+("0" + s.getDate()).slice(-2)+' 00:00:00';

				calendar.to = e.getFullYear()+'-'+("0" + (e.getMonth() + 1)).slice(-2)+'-'+("0" + e.getDate()).slice(-2)+' 00:00:00';
				
				calendar.get();

			},
			eventDrop:function(e){
				calendar.change_date(e.id,e.promoid,e.start.toISOString());
			}
		});

	}
}
$(function(){

	$('#modal_event').on('click','[data-btn=cancel]',function(){
		var id = $(this).attr('data-id');
		var promoid = $(this).attr('data-promoid');
		Swal.fire({
			type:'warning',
			text:'¿Seguro que querés cancelar esta reserva? Se enviará un email de aviso al usuario.',
			showCancelButton:true,
			reverseButtons:true
		})
		.then(function(response){
			if(response.value){
				calendar.delete(id,promoid);
			}
		});
	});
	$('#modal_event').on('click','[data-btn=confirm]',function(){
		var id = $(this).attr('data-id');
		var promoid = $(this).attr('data-promoid');
		calendar.confirm(id,promoid);
	});

	$('#modal_blocked').on('hidden.bs.modal',function(){
		calendar.get();
	});


	var reservations = new Reservations({
		idclient:$('[name=clientid]').val(),
		container:'#blocked_dates',
		callback:function(data){
			ajax('panel/reservations/exclude',{date:data.date})
				.then(function(response){
					reservations.get_hours();					
				});
		}
	});

	calendar.init();
	
});