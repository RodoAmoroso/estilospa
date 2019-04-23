var StatusLabel = function(start,finish){
	var label = Templates.span.clone().addClass('label');
	if(start== 0){
		label.addClass('label-warning').text('no inició');
	}
	if(start == 1 && finish == 0){
		label.addClass('label-danger').text('finalizada');
	}
	if(start == 1 && finish == 1){
		label.addClass('label-success').text('en curso');
	}
	return label;
}
var Promos = {
	get:function(CONT,IDC,Status){
		$('#promos').html('');
		$('#fd_filter_promo').html('<option value="0">-- Todas --</option>');
		AjaxConnection('jxPromos.php',{Mode:'get',IDClient:IDC,Status:Status},function(DATA){
			$.each(DATA.Results,function(k,v){
				if(CONT=='block'){
					var mod = Templates.list_group_btn();
					mod.find('span').html('<b>'+v.title+'</b> - '+v.name);
					mod.attr('data-id',v.id);
					mod.append('<br />',StatusLabel(v.statusstart,v.statusfinish));
					var price = v.price-(v.discount*v.price/100);
					var small = Templates.small.clone();
					var i = Templates.i.clone().addClass('fa fa-shopping-bag fa-fw');
					small.append(i,' &bullet; $ '+price.FormatMoney(0,'.',','));
					//mod.append(span);
					if(v.sale==1){
						mod.append('<hr />',small);
					}
					$('#promos').append(mod);
				}
				if(CONT=='select'){
					$('#fd_filter_promo').append('<option value="'+v.id+'">'+v.title+'</option>');
				}
			});
			if(CONT=='block'){
				$('#promos .list-group-item').unbind('click').click(function(){
					Promos.assocpromos($(this).attr('data-id'));
				});
			}
		});
	},
	assocpromos:function(ID){
		if(Promos.checkassoc(ID,'#promos_selected')){
			var mod = $('#promos .list-group-item[data-id="'+ID+'"]').clone();
			$('#promos_selected').prepend(mod);
			$('#promos .list-group-item[data-id="'+ID+'"]').remove();
			$('#promos_selected .list-group-item').unbind('click').click(function(){
				Promos.removepromos($(this).attr('data-id'));
			});
		}else{
			$('#promos .list-group-item[data-id="'+ID+'"]').remove();
		}
	},
	removepromos:function(ID){
		if(Promos.checkassoc(ID,'#promos')){
			var mod = $('#promos_selected .list-group-item[data-id="'+ID+'"]').clone();
			$('#promos').prepend(mod);
			$('#promos_selected .list-group-item[data-id="'+ID+'"]').remove();
			$('#promos .list-group-item').unbind('click').click(function(){
				Promos.assocpromos($(this).attr('data-id'));
			});
		}else{
			$('#promos_selected .list-group-item[data-id="'+ID+'"]').remove();
		}
	},
	checkassoc:function(ID,CONT){
		var pass = true;
		$.each($(CONT+' .list-group-item'),function(k,v){
			var id = $(this).attr('data-id');
			if(id == ID){
				pass = false;
			}
		});
		return pass;
	},
	init:function(){
		$('#fd_filter_client').change(function(){
			Promos.get('select',$(this).val());
		}).trigger('change');
		$('#fd_filter_client_assoc').change(function(){
			Promos.get('block',$(this).val(),$('#fd_filter_status_assoc').val());
		}).trigger('change');
		$('#fd_filter_status_assoc').change(function(){
			Promos.get('block',$('#fd_filter_client_assoc').val(),$(this).val());
		});
		$('#btn_check_all').click(function(){
			$.each($('#promos .list-group-item'),function(k,v){
				Promos.assocpromos($(this).attr('data-id'));
			});
		});
		$('#btn_remove_all').click(function(){
			$.each($('#promos_selected .list-group-item'),function(k,v){
				Promos.removepromos($(this).attr('data-id'));
			});
		});
		//Promos.get('block',0);
	}
}
var Vouchers = {
	ID:0,
	ArrCodes:[],
	get:function(){
		$('#vouchers').empty();
		console.log($('#fd_filter_status').val());
		AjaxConnection('jxVouchers.php',{Mode:'get',Status:$('#fd_filter_status').val(),IDP:$('#fd_filter_promo').val(),Keywords:$('#fd_search').val()},function(DATA){
			if(DATA.Results == null){return false;}
			$.each(DATA.Results,function(k,v){
				var mod = Templates.mod_list();
				mod.find('h4').addClass('title-med').text(v.name+' - '+(v.isunique==1 ? v.code : 'Múltiples códigos'));
				var discount = (v.ispercent==1 ? '' : '$')+v.value+(v.ispercent==0 ? '' : '%');
				mod.find('p').append('Disponible en '+v.totpromos+' promos &bullet; Creado: ',v.creado,' &bullet; Descuento: ',discount,' &bullet; ',StatusLabel(v.statusstart,v.statusfinish));
				mod.find('.edit,.delete').attr('data-id',v.id);
				var btn = Templates.a.clone().attr({href:ROOT+'vouchers/'+Permalink(v.name)+'/'+v.id,target:'_blank'}).addClass('btn btn-xs btn-primary link').append(Templates.i.clone().addClass('fa fa-link fa-fw'));
				mod.find('.buttons').prepend(btn,' ');
				$('#vouchers').append(mod);				
			});
			$('#vouchers .edit').click(function(){
				Vouchers.ID = $(this).attr('data-id');
				$('#btn_new').trigger('click');
				Vouchers.find();
			});
			$('#vouchers .delete').click(function(){
				var id = $(this).attr('data-id');
				Messages(true,'¿Seguro deseas borrar este voucher?',function(){
					Vouchers.ID = id;
					Vouchers.delete();
				});
			});
		});
	},
	find:function(){
		AjaxConnection('jxVouchers.php',{Mode:'find',ID:Vouchers.ID},function(DATA){
			if(DATA.Result==null){return false;}
			var v = DATA.Result;
			$('#fd_name').val(v.name);			
			$('#fd_start').val(v.start);			
			$('#fd_finish').val(v.finish);
			$('#fd_value').val(v.value);
			$('#fd_type option[value="'+(v.ispercent==1 ? 'percent' : 'amount')+'"]').prop('selected',true).trigger('change');
			$('#fd_code_quantity,#btn_generate,#fd_code').prop('disabled',true);
			$('input[type="radio"][name="group_code_type"]').parent().removeClass('active');
			if(v.isunique==1){
				$('#fd_code').val(DATA.Codes[0].code);
				$('input[type="radio"][value="unique"]').trigger('change').parent().addClass('active');
			}else{
				$('input[type="radio"][value="multiple"]').trigger('change').parent().addClass('active');
				$('#fd_code_quantity').val(DATA.Codes.length);
				Vouchers.ArrCodes = [];
				$.each(DATA.Codes,function(k,v){
					Vouchers.generatecodes(v.code);
				});
			}
			$.each(DATA.Promos,function(k,v){
				$('#promos .list-group-item[data-id="'+v.idpromo+'"]').trigger('click');
			});
		});
	},
	save:function(){
		if($('#fd_start').datepicker('getDate') > $('#fd_finish').datepicker('getDate')){
			Messages(true,'La fecha inicial debe ser menor a la fecha final');
			return false;
		}
		var codes = [];
		if($('input[value="unique"]').parent().hasClass('active')){
			if($('#fd_code').val().length<4){
				Messages(true,'Debes ingresar un código');
				return false;
			}
			codes.push($('#fd_code').val());
		}else{
			if($('#code_list .list-group-item').length==0){				
				Messages(true,'Debes ingresar un código');
				return false;
			}
			$.each($('#code_list .list-group-item'),function(k,v){
				codes.push($(this).find('span:first-of-type').text());
			});
		}
		var promos = [];
		if($('#promos_selected .list-group-item').length == 0 ){
			Messages(true,'Debes seleccionar al menos una promo');
			return false;
		}
		$.each($('#promos_selected .list-group-item'),function(k,v){
			promos.push($(this).attr('data-id'));
		});
		AjaxConnection('jxVouchers.php',{
			Mode:'save',
			Name:$('#fd_name').val(),
			IsUnique:$('input[value="unique"]').parent().hasClass('active') ? 1 : 0,
			IsPercent:$('#fd_type').val() == 'percent' ? 1 : 0,
			Value:$('#fd_value').val(),
			Codes:codes.join(','),
			Promos:promos,
			Start:FormatDate($('#fd_start').datepicker('getDate')),
			Finish:FormatDate($('#fd_finish').datepicker('getDate')),
			ID:Vouchers.ID
		},function(DATA){
			if(DATA.Status == 'code'){
				Messages(true,'El código ingresado ya está en uso. Elige otro.');
				return false;
			}
			if(DATA.Status == 'fail'){
				Messages(true,'Hubo problemas al procesar la solicitud. Intenta más tarde.');
				return false;
			}
			$('#btn_cancel').trigger('click');
			Vouchers.get();
		});
	},
	delete:function(){
		AjaxConnection('jxVouchers.php',{Mode:'delete',ID:Vouchers.ID},function(DATA){
			if(DATA.Status == 'fail'){
				Messages(true,'Hubo problemas al procesar la solicitud. Intenta más tarde');
				return false;
			}
			$('#btn_cancel').trigger('click');
			Vouchers.get();
		});
	},
	reset:function(){
		Vouchers.ID = 0;
		Vouchers.ArrCodes = [];
		$('#fd_name,#fd_code,#fd_start,#fd_finish').val('');
		$('#fd_value').val(0);
		$('#promos_selected').empty();
		Promos.get('block',$('#fd_filter_client_assoc').val(),$('#fd_filter_status_assoc').val());
		$('#fd_filter_client_assoc option[value="0"],#fd_filter_status_assoc option[value="0"]').prop('selected',true);
		$('#fd_code_quantity,#btn_generate,#fd_code').prop('disabled',false);
	},	
	generatecodes:function(COD){
		var code = COD == undefined ? random_letters(4).toUpperCase()+random(1111,9999) : COD;
		var mod = Templates.list_group_item();
		mod.find('span:first-of-type').addClass('fw-600').text(code);
		mod.find('.label').attr('data-edit','true').append('<i class="fa fa-pencil"></i>');
		$('#code_list').append(mod);
		Vouchers.ArrCodes.push(code);	
		//}
		$('[name="codes"]').attr('value',Vouchers.ArrCodes);
	},
	init:function(){
		$('#code_list').on('click','.label',function(){
			$(this).toggleClass('label-success label-primary');
			var indx = $(this).parent().index();
			if($(this).attr('data-edit')=='true'){
				var code = $(this).prev().text();
				$(this).prev().html('<input type="text" value="'+code+'" />');
				$(this).attr('data-edit','false').html('<i class="fa fa-check"></i>');
			}else{
				var code = $(this).prev().find('input').val();
				Vouchers.ArrCodes[indx] = code;
				$('[name="codes"]').attr('value',Vouchers.ArrCodes);
				$(this).prev().html(code);
				$(this).attr('data-edit','true').html('<i class="fa fa-pencil"></i>');
			}
		});
		$('[name="group_code_type"]').change(function(){
			var type = $(this).val();
			var typehide = type == 'unique' ? 'multiple' : 'unique';
			$('#block_'+typehide).slideUp({duration:900,easing:'easeInOutCubic'});
			$('#block_'+type).slideDown({duration:900,easing:'easeInOutCubic'});
		});
		$('#btn_generate').click(function(){
			$('#code_list').html('');
			if($('#fd_code_quantity').val()>0){
				Vouchers.ArrCodes = [];
				for(var i=1; i<=$('#fd_code_quantity').val(); i++){
					Vouchers.generatecodes();
				}
				//$('[name="codes"]').attr('value',arr);
			}else{
				Messages(true,'Debes seleccionar un número mayor a 0');
			}
		});
		$('#btn_new').click(function(){
			$('#block_list').slideUp({duration:900,easing:'easeInOutCubic'});
			$('#block_edit').slideDown({duration:900,easing:'easeInOutCubic'});
		});	
		$('#btn_cancel').click(function(){
			$('#block_list').slideDown({duration:900,easing:'easeInOutCubic'});
			$('#block_edit').slideUp({duration:900,easing:'easeInOutCubic'});
			Vouchers.reset();
		});
		$('#btn_save').click(function(){
			CheckFields(['#fd_name','#fd_start','#fd_finish'],Vouchers.save);
		});
		$('#fd_start,#fd_finish').datepicker();
		$('#fd_type').change(function(){
			var type = $(this).val();
			if(type == 'percent'){
				$('#fd_value_symbol').text('%');
			}else{
				$('#fd_value_symbol').text('$');
			}
		});
		$('#fd_filter_status,#fd_filter_promo').change(function(){
			Vouchers.get();
		});
		$('#fd_code').keyup(function(){
			var txt = $(this).val();
			$('#fd_code').val(txt.toUpperCase().replace(/\s/g,''));
		});
		$('#form_search').submit(function(e){
			e.preventDefault();
			Vouchers.get();
		});
		Vouchers.get();
	}
}
$(function(){
	Vouchers.init();
	Promos.init();
});