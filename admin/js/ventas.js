var DateFunctions = {
	checkrange:function(start,finish){
		if(start == '' && finish == ''){
			return true;
		}
		if((start == '' && finish != '') || (start != '' && finish == '')){
			return false;
		}
		var arrfrom = start.split('/');
		var arrto = finish.split('/');
		var from = new Date(arrfrom[2]+"-"+arrfrom[1]+"-"+arrfrom[0]+" 00:00:00");
		var to = new Date(arrto[2]+"-"+arrto[1]+"-"+arrto[0]+" 00:00:00");
		var today = new Date();
		var statusstart = from.getTime()<=today.getTime() ? 1 : 0;
		var statusfinish = to.getTime()>=today.getTime() ? 1 : 0;
		if(to.getTime()-from.getTime() < 0){
			return false;
		}
		return true;
	}
}
sales = {
	switchstatus:function(status){
		var btn;
		var label;
		switch(status){
			case '1':
				btn = 'warning';
				label = 'Pendiente';
				break;
			case '2':
				btn = 'success';
				label = 'Brindado';
				break;
			case '3':
				btn = 'danger';
				label = 'Cancelado';
				break;
		}
		return {btn:btn,label:label};
	},
	status_payment:function(status){
		var label;
		var text;
		switch(status){
			case 'in_process':
				label = 'warning';
				text = 'El pago está siendo revisado';
				break;
			case 'rejected':
				label = 'danger';
				text = 'El pago fué rechazado, el usuario puede intentar nuevamente el pago';
				break;
			case 'approved':
				label = 'success';
				text = 'El pago fue aprobado y acreditado';
				break;
			case 'pending':
				label = 'warning';
				text = 'El usuario no completó el pago';
				break;
			default:
				label = 'danger';
				text = 'El usuario no completó el proceso de pago y no se ha generado ningún pago';
				break;
		}
		return {label:label,text:text};
	},
	get:function(){
		$('#sales').html('');
		$('[data-tag="totalmods"]').text(0);
		
		ajax('admin/sales/get',{
			OrderNumber:$('#fd_ordernumber').val(),
			From:$('#fd_from').val(),
			To:$('#fd_to').val(),
			IDClient:$('#fd_clients').val()
		})
			.then(function(DATA){
				if(DATA.results == null){return false;}
				
				$('[data-tag="totalmods"]').text(DATA.results.length);
				
				$.each(DATA.results,function(k,v){
					if(v.title == null){console.log(v.id)}
				
					var mod = $('#mod_sale').clone();

					mod.removeAttr('id')
						.removeClass('dp-none')
						.attr('data-id',v.id);


					if(v.title == null){
						mod.find('[data-tag="title"]')
							.html('La promo fue borrada');
					}else{
						mod.find('[data-tag="title"]')
							.html('<a href="'+ROOT+'promo/'+v.permalink+'/'+v.idpromo+'-'+v.title.permalink()+'" target="_blank">'+v.title+'</a> - <a href="'+ROOT+'centros/'+v.permalink+'" target="_blank">'+v.clientname+'</a>');
					}
					var discountvoucher = 0;
					var vouchertext = '';
					if(v.idvoucher!= null){
						vouchertext = ' - Usó Código: '+v.code;
						if(v.ispercent==1){
							discountvoucher = v.value*v.price/100;
						}else{
							discountvoucher = v.value;
						}
					}

					var total = (v.price-discountvoucher)*v.quantity;


					mod.find('[data-tag="ordernumber"]')
						.text('Orden Nro.: '+v.collection_id+' - $ '+(total.numberFormat(2,',','.')));
					
					mod.find('[data-tag="price"]')
						.html('Precio Unit.: $ '+(v.price-discountvoucher).numberFormat(2,',','.')+' | Cant.: '+v.quantity);
					
					mod.find('[data-tag="date"]')
						.html('Comisión EstiloSPA.com: $ '+parseFloat(v.application_fee).numberFormat(2,',','.')+' | Comisión MercadoPago: $ '+parseFloat(v.mercadopago_fee).numberFormat(2,',','.') + ' | Fecha de compra: '+v.fecha+' hs.'+vouchertext);


					mod.find('.status')
						.addClass('alert-'+sales.status_payment(v.collection_status).label)
						.find('.payment-status')
						.text(sales.status_payment(v.collection_status).text);

					mod.find('[data-button="toggle"],[data-group="status"]')
						.attr('data-id',v.id);

					if(v.status=='rejected') mod.find('.sale-actions').remove();
					
					if(v.gallery != null){
						var img = $.parseJSON(v.gallery);
						mod.find('.thumb')
							.css({backgroundImage:'url('+ROOT+'img/promos/'+img[0].photoname+'-t.'+img[0].extension+')'});
					}

					mod.find('[data-group="status"] button')
						.removeClass()
						.addClass('btn btn-xs dropdown-toggle btn-'+sales.switchstatus(v.status).btn)
						.find('span[data-tag="status"]')
						.text(sales.switchstatus(v.status).label);
		
					if(v.image != '' && v.image != null){
						var imgu = $.parseJSON(v.image);
						var thumbimage = imgu.photoname+'-t.'+imgu.extension;
					}else{
						var thumbimage = 'user-default.png';
					}

					mod.find('.user-thumb')
						.css({backgroundImage:'url('+ROOT+'img/users/'+thumbimage+')'});
					mod.find('[data-tag="username"]')
						.text(v.username);
					mod.find('[data-tag="mail"]')
						.text(v.mail);

					if(v.text != null){
						mod.find('[data-tag="comment"]').html(v.text);
						for(var i=1; i<=v.rate; i++){
							mod.find('.stars i:eq('+(i-1)+')')
								.addClass('fa-star');
						}
						for(var i=5; i>v.rate; i--){
							mod.find('.stars i:eq('+(i-1)+')')
								.addClass('fa-star-o');
						}
					}else{
						mod.find('.stars').remove();
					}
					///////////////////////////////////////////////////
					$('#sales').append(mod);
				});
				
			});
	},

	init:function(){
		
		$('#fd_search').submit(function(e){
			e.preventDefault();
			if(!DateFunctions.checkrange($('#fd_from').val(),$('#fd_to').val())){
				Swal.fire({
					type:'warning',
					text:'La fecha inicial debe ser anterior a la final!'
				})
				return false;
			}
			sales.get();
		});

		$('#sales').on('click','[data-button="toggle"]',function(){
			var id = $(this).attr('data-id');
			$('#sales .mod-sales[data-id="'+id+'"] .sale-footer').slideToggle();
		});
		$('#sales').on('click','[data-group="status"] a',function(e){
			e.preventDefault();
			var st = $(this).attr('data-value');
			var id = $(this).parent().parent().parent().attr('data-id');
			$('#sales [data-id="'+id+'"] [data-group="status"]').find('button')
				.removeClass()
				.addClass('btn btn-xs dropdown-toggle btn-'+sales.switchstatus(st).btn)
				.find('span[data-tag="status"]')
				.text(sales.switchstatus(st).label);
			
			ajax('admin/sales/setstatus',{ID:id,Status:st})
				.then(function(){});

		});

		$('#fd_clients').change(function(){
			sales.get();
		});
		$('#fd_from,#fd_to').datepicker();
		sales.get();
	}
}

var stats = {
	evolution:function(){
		ajax('admin/sales/evolution')
			.then(function(data){
				return data;
			})
			.then(function(data){
				if(data.stats==false) return false;

				var chartdata = [];
				$.each(data.stats,function(k,v){
					var dd = new Date(v.year+'-'+v.month).getTime();
					chartdata.push([dd,parseInt(v.suma)]);					
				});

				var data, chartOptions;
				data = [{label:"$", data:chartdata}];

				chartOptions = {
					xaxis: {min:data.this_month, max:data.last_month, mode:"time", tickSize:[1, "month"], monthNames:["Ene ", "Feb ", "Mar ", "Abr ", "May ", "Jun ", "Jul ", "Ago ", "Sep ", "Oct ", "Nov ", "Dic "], tickLength:0}, 
					yaxis: {},
					series: {lines: {show:true, fill:true, lineWidth:3}, points: {show:true, radius:3, fill:true, fillColor:"#ffffff", lineWidth:2}},
					grid:{show:true, color:'#999', borderColor:'#dfdfdf', hoverable:true, clickable:false, borderWidth:1},
					legend: {show:false, backgroundColor:'#fff'},
					tooltip: true, tooltipOpts: {content: '%s: %y'},
					colors: ['#68bbce']
				}
				$.plot('#evolution', data, chartOptions);


			});
		
	},
	init:function(){
		this.evolution();		
	}
}

	
$(function(){
	sales.init();
	stats.init();
});