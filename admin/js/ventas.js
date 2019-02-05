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
Actions = {
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
		AjaxConnection('jxClients.php',{Mode:'getsales',OrderNumber:$('#fd_ordernumber').val(),From:$('#fd_from').val(),To:$('#fd_to').val(),IDClient:$('#fd_clients').val()},function(DATA){
			if(DATA.Results == null){return false;}
			$('[data-tag="totalmods"]').text(DATA.Results.length);
			$.each(DATA.Results,function(k,v){
				if(v.title == null){console.log(v.id)}
				var mod = $('#mod_sale').clone();
				mod.removeAttr('id').removeClass('dp-none').attr('data-id',v.id);
				mod.find('[data-tag="ordernumber"]').text('Orden Nro.: '+v.merchant_order_id);
				if(v.title == null){
					mod.find('[data-tag="title"]').html('La promo fue borrada');
				}else{
					mod.find('[data-tag="title"]').html('<a href="'+ROOTPATH+'promo/'+v.permalink+'/'+v.idpromo+'-'+Permalink(v.title)+'" target="_blank">'+v.title+'</a> - <a href="'+ROOTPATH+'centros/'+v.permalink+'" target="_blank">'+v.clientname+'</a>');
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
				mod.find('[data-tag="collectionid"]').html('Nro. de comprobante: #'+v.collection_id);
				mod.find('[data-tag="price"]').html('Precio Unit.: $ '+(v.price-discountvoucher).FormatMoney(2,',','.')+' | Cant.: '+v.quantity+' | <span class="fw-400">Total: $ '+((v.price-discountvoucher)*v.quantity).FormatMoney(2,',','.')+'</span>');
				mod.find('[data-tag="date"]').text('Fecha de compra: '+v.fecha+' hs.'+vouchertext).after('<hr /><div class="sz-8 pad-4 alert-'+Actions.status_payment(v.collection_status).label+'">'+Actions.status_payment(v.collection_status).text+'</div>');
				mod.find('[data-button="toggle"],[data-group="status"]').attr('data-id',v.id);
				//Actions.status(v.id,v.status);
				if(v.gallery != null){
					var img = $.parseJSON(v.gallery);
					mod.find('.thumb').css({backgroundImage:'url('+ROOTPATH+'img/promos/'+img[0].photoname+'-t.'+img[0].extension+')'});
				}
				mod.find('[data-group="status"] button').removeClass().addClass('btn btn-xs dropdown-toggle btn-'+Actions.switchstatus(v.status).btn).find('span[data-tag="status"]').text(Actions.switchstatus(v.status).label);
				///////////// USER ////////////////////////////////
				if(v.image != ''){
					var imgu = $.parseJSON(v.image);
					var thumbimage = imgu.photoname+'-t.'+imgu.extension;
				}else{
					var thumbimage = 'user-default.png';
				}
				mod.find('.user-thumb').css({backgroundImage:'url('+ROOTPATH+'img/users/'+thumbimage+')'});
				mod.find('[data-tag="username"]').text(v.username);
				mod.find('[data-tag="mail"]').text(v.mail);
				if(v.text != null){
					mod.find('[data-tag="comment"]').html(v.text);
					for(var i=1; i<=v.rate; i++){
						mod.find('.stars i:eq('+(i-1)+')').addClass('fa-star');
					}
					for(var i=5; i>v.rate; i--){
						mod.find('.stars i:eq('+(i-1)+')').addClass('fa-star-o');
					}
				}
				///////////////////////////////////////////////////
				$('#sales').append(mod);
			});
			$('#sales [data-button="toggle"]').unbind('click').click(function(){
				var id = $(this).attr('data-id');
				$('#sales .mod-sales[data-id="'+id+'"] .sale-footer').slideToggle();
			});
			$('#sales [data-group="status"]').find('a').unbind('click').click(function(e){
				e.preventDefault();
				var st = $(this).attr('data-value');
				var id = $(this).parent().parent().parent().attr('data-id');
				$('#sales [data-id="'+id+'"] [data-group="status"]').find('button').removeClass().addClass('btn btn-xs dropdown-toggle btn-'+Actions.switchstatus(st).btn).find('span[data-tag="status"]').text(Actions.switchstatus(st).label);
				AjaxConnection('jxClients.php',{Mode:'setsalestatus',ID:id,Status:st},function(DATA){});
			});
		});
	},
	init:function(){
		$('#fd_search').submit(function(e){
			e.preventDefault();
			if(!DateFunctions.checkrange($('#fd_from').val(),$('#fd_to').val())){
				Messages(true,'La fecha inicial debe ser anterior a la final!');
				return false;
			}
			Actions.get();
		});		
		$('#fd_clients').change(function(){
			Actions.get();
		});
		$('#fd_from,#fd_to').datepicker();
		Actions.get();
	}
}
$(function(){
	Actions.init();
});