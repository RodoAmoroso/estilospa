$(function(){

	$('[name="date_from"],[name="date_to"]').datepicker();

	var datatable_options = {
		language:{
			url:ROOT+'js/lib/dataTables/spanish.json',
		},
		dom:'<"table-spacer-top"lf>t<"table-spacer-bottom"ip>',
		//responsive:true,		
		columns:[
			{orderable:false},
			{orderable:false},
			null,
			null,
			null,
			null
		]		
	}

	$('#reservations').DataTable(datatable_options);


	$('#reservations').on('click','a.label',function(e){
		e.preventDefault();
		var reservationid = $(this).attr('data-id');

		Promise.all([get_template('reservations/modal-calendar'),ajax('panel/reservations/find',{reservationid:reservationid})])
		.then(function(promise){

				var $template = $(promise[0]);

				var data = promise[1].result;
				if(data==false) return false;
				//return console.log(data);


				$template.find('.title').html('<a href="'+ROOT+'promo/'+data.client.permalink+'/'+data.promoid+'-'+(data.promo.title).permalink()+'" target="_blank" >'+data.promo.title+'</a>');

				$template.find('.subtitle').html(data.promo.subtitle);
				$template.find('.client').html('<a href="'+ROOT+'centros/'+data.client.permalink+'" target="_blank" >'+data.client.name+'</a>');

				$template.find('.user-name').html(data.user.fullname+' (<a href="mailto:'+data.user.mail+'">'+data.user.mail+'</a>)');
				$template.find('.user-phone span').html(data.user.phone);

				$template.find('.user-comments').html(data.comments==null ? 'No ha dejado comentarios' : data.comments);

				$template.find('.date span').html(data.fecha+' hs.');
				$template.find('.price').html('$ '+(parseInt(data.promo.price).numberFormat(2,',','.')));

				$template.find('.image').css({backgroundImage:'url('+data.promo.image+')'});
				$template.find('[data-btn=cancel],[data-btn=confirm]').attr({'data-id':data.id,'data-promoid':data.promoid});
				$template.find('[data-btn=change-date]').attr({'href':ROOT+'panel/reserva/'+data.id});

				if(data.status==1){
					$template.find('[data-status]').removeClass().addClass('label bg-green-3').text('Confirmada');
					$template.find('[data-btn="confirm"]').remove();
				}else if(data.status==0){
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




	});


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

});