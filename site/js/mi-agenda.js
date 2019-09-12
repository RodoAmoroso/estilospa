var calendar = {
	from:'',
	to:'',
	get:function(){
		
		ajax('site/reservations/get',{from:calendar.from,to:calendar.to})
			.then(function(data){

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
					}

					events.push({
						title:v.title == null ? v.client_name : v.title,
						start:start,
						end:end,
						id:v.id,
						
						className:'pad-4 '+classcell
					});
				});
				$('#calendar').fullCalendar('removeEvents');
				$('#calendar').fullCalendar('addEventSource',events);
			});
	},
	delete:function(id){
		ajax('site/reservations/delete',{id:id})
			.then(function(data){
				calendar.get();
				$('#modal_event').modal('hide');
				toastr['success'](data.message);
			});
	},
	confirm:function(id){
		ajax('site/reservations/confirm',{id:id})
			.then(function(data){
				calendar.get();
				$('#modal_event').modal('hide');
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
			editable:false,
			eventDurationEditable:false,
			droppable:false,
			defaultView: 'month',
			themeSystem:'bootstrap4',
			eventClick:function(e, jsEvent, view){

				Promise.all([
					get_template('reservations/modal-calendar'),
					ajax('site/reservations/find',{id:e.id})
				])
				.then(function(promise){

					var $template = $(promise[0]);
					var data = promise[1].result;

					if(data==false) return false;

					if(data.promo.title!=null){
						$template.find('.title').html('<a href="'+ROOT+'promo/'+data.client.permalink+'/'+data.promoid+'-'+(data.promo.title).permalink()+'" target="_blank" >'+data.promo.title+'</a>');
						$template.find('.subtitle').html(data.promo.subtitle);
						$template.find('.price').html('$ '+(parseInt(data.promo.price).numberFormat(2,',','.')));
						$template.find('.image').css({backgroundImage:'url('+data.promo.image+')'});						
					}else{
						$template.find('.image').remove();
					}

					$template.find('[data-btn=cancel],[data-btn=confirm]').attr({'data-id':data.id});

					$template.find('.client').html('<a href="'+ROOT+'centros/'+data.client.permalink+'" target="_blank" >'+data.client.name+'</a>');
					
					$template.find('[data-btn=change-date],.user').remove();

					$template.find('.date span').html(data.fecha+' hs.');


					if(data.status==1){
						$template.find('[data-status]').removeClass().addClass('label bg-green-3').text('Confirmada');
						$template.find('[data-btn="confirm"]').remove();
					}else if(data.status==0){
						$template.find('[data-status]').removeClass().addClass('label bg-yellow-3').text('Esperando confirmación del centro');
						$template.find('[data-btn="confirm"]').remove();
					}else{
						$template.find('[data-status]').removeClass().addClass('label bg-aqua-3').text('Esperando tu confirmación');	
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
		Swal.fire({
			type:'warning',
			text:'¿Seguro que querés cancelar esta reserva?',
			showCancelButton:true,
			reverseButtons:true
		})
		.then(function(response){
			if(response.value){
				calendar.delete(id);
			}
		});
	});
	$('#modal_event').on('click','[data-btn=confirm]',function(){
		var id = $(this).attr('data-id');
		calendar.confirm(id);
	});

	calendar.init();
	
});