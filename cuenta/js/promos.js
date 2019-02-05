var Clients = {
	IDStores:[],
	get:function(){
		$('#stores').html('');
		AjaxConnection('jxClients.php',{Mode:'getstores',IDC:IDClient},function(DATA){
			$.each(DATA.Results,function(k,v){
				var mod = $('#mod_list').clone();
				mod.removeAttr('id').removeClass('dp-none').attr('data-id',v.id).addClass('clickable');
				mod.find('h4').text(v.address+' - '+v.city);
				mod.find('.delete,.edit,.add').remove();
				$('#stores').append(mod);
			});
			$('#stores .mod-list').unbind('click').click(function(){
				$(this).toggleClass('active');
			});
			if($('#stores .mod-list').length == 1){
				$('#stores .mod-list:eq(0)').trigger('click');
			}
			$.each(Clients.IDStores,function(k,v){
				$('#stores .mod-list[data-id="'+v+'"]').addClass('active');
			});
		});
	}
}
var Promos = {
	ID:0,
	save:function(){
		var arrfrom = $('#fd_start').val().split('/');
		var arrto = $('#fd_finish').val().split('/');
		var from = new Date(arrfrom[2]+"-"+arrfrom[1]+"-"+arrfrom[0]+" 00:00:00");
		var to = new Date(arrto[2]+"-"+arrto[1]+"-"+arrto[0]+" 00:00:00");
		var today = new Date();
		var statusstart = from.getTime()<=today.getTime() ? 1 : 0;
		var statusfinish = to.getTime()>=today.getTime() ? 1 : 0;
		if(to.getTime()-from.getTime() < 0){
			Messages(true,'La fecha inicial debe ser anterior a la final!');
			return false;
		}
		var gallery = [];
		$.each($('#gallery .thumbnail'),function(k,v){			
			gallery.push({photoname:$(this).attr('data-photoname'),extension:$(this).attr('data-extension')});
			
		});
		if(gallery.length==0){
			Messages(true,'Debes subir al menos una imagen');
			return false;
		}
		var stores = [];
		$.each($('#stores .mod-list.active'),function(k,v){
			stores.push($(this).attr('data-id'));
		});
		if(stores.length==0){
			Messages(true,'Debes elegir al menos una sucursal');
			return false;
		}
		if($('#fd_sale').hasClass('active')){
			if($('#fd_includes').val() == '' || $('#fd_recomendations').val() == '' || $('#fd_reservation').val() == '' || $('#fd_duration').val() == '' || $('#fd_cancellation').val() == ''){
				Messages(true,'Debes indicar ¿Que incluye la experiencia?, ¿Que recomendamos que lleve?, ¿Requiere reserva y/o algún requisito?, Duración de la actividad, ¿Cuál es la política de cancelación?');
				return false;
			}
			if($('#fd_price').val()==0){
				Messages(true,'Debes indicar el valor de la promo');
				return false;
			}
			if($('#fd_amount').val()==0){
				Messages(true,'La cantidad disponible no puede estar en 0');
				return false;
			}
		}
		AjaxConnection('jxPromos.php',{
			Mode:'saveclient',
			Title:$('#fd_title').val(),
			Subtitle:$('#fd_subtitle').val(),
			IDClient:IDClient,
			Stores:stores,
			Start:$('#fd_start').val(),
			Finish:$('#fd_finish').val(),
			Sale:$('#fd_sale').hasClass('active') ? 1 : 0,
			Price:$('#fd_price').val(),
			IDPromotype:$('#fd_promotypes').val(),
			Discount:$('#fd_discount').val(),
			Amount:$('#fd_amount').val(),
			Description:$('#fd_description').val(),
			Includes:$('#fd_includes').val(),
			Recomendations:$('#fd_recomendations').val(),
			Reservation:$('#fd_reservation').val(),
			Duration:$('#fd_duration').val(),
			Cancellation:$('#fd_cancellation').val(),
			Gallery:gallery,
			ID:Promos.ID
		},function(DATA){
			if(DATA.Status == 'cantpromos'){
				Messages(true,'Tu plan contratado no te permite agregar más promociones');
				return false;
			}
			Promos.get();
			Promos.reset();
		});
	},
	delete:function(){
		AjaxConnection('jxPromos.php',{Mode:'delete',ID:Promos.ID},function(DATA){
			Promos.reset();
			Promos.get();
		});
	},
	reset:function(){
		$('#fd_title,#fd_start,#fd_finish,#fd_subtitle,#fd_description,#fd_includes,#fd_recomendations,#fd_duration').val('');
		$('#fd_reservation').val('Si. Solicitar Previa Reserva de Turno');
		$('#fd_cancellation').val('24 hs antes del Turno Solicitado');
		$('#fd_price,#fd_discount').val(0);
		$('#fd_amount').val(20);
		$('#thumb_promo').css({backgroundImage:'none'}).removeAttr('data-photoname data-extension');
		$('#gallery,#stores').html('');
		$('#fd_clients option:selected').prop('selected',false);
		Clients.IDStores = [];
		Promos.ID = 0;
		$('#list_panel').slideDown({duration:900,easing:'easeInOutCubic'});
		$('#edit_panel').slideUp({duration:900,easing:'easeInOutCubic'});
	},
	get:function(){
		AjaxConnection('jxPromos.php',{
			Mode:'get',
			Keywords:$('#fd_search').val(),
			Sort:$('#fd_select_order').val(),
			IDClient:IDClient,
			Status:$('#fd_select_status').val()
		},function(DATA){
			if(DATA.Results==null){$('#promos').html('<div class="col-xs-12">No se encontraron promos</div>');return false;}
			$('#promos').html('');
			$.each(DATA.Results,function(k,v){
				var mod = $('#mod_card').clone();
				mod.removeAttr('id').removeClass('dp-none');
				mod.attr('data-id',(v.id==undefined?0:v.id));
				mod.find('h1').text(v.title);
				var status = '';
				if(v.statusstart== 0){
					status = '<span class="label label-warning">no inició</span>';
				}
				if(v.statusstart == 1 && v.statusfinish == 0){
					status = '<span class="label label-danger">finalizada</span>';
				}
				if(v.statusstart == 1 && v.statusfinish == 1){
					status = '<span class="label label-success">en curso</span>';
				}
				mod.find('p').html(status+'<br /><br />Inicia: '+v.start+' &bullet; Finaliza: '+v.finish+' &bullet; '+(v.sale == 1 ? '<i class="fa fa-shopping-bag"></i>' : ''));
				mod.find('.edit,.delete,.preview').attr({'data-id':v.id});
				mod.find('.preview').attr({'data-permalink':v.permalink,'data-title':v.title});
				if(v.gallery != ''){
					var img = $.parseJSON(v.gallery);
					mod.find('.thumb').css({backgroundImage:'url('+ROOTPATH+'img/promos/'+img[0].photoname+'-t.'+img[0].extension+')'});
				}
				$('#promos').append(mod);
			});
			$('#promos .edit').unbind('click').click(function(){
				Promos.ID = $(this).attr('data-id');
				Promos.find();
				$('#btn_new').trigger('click');
			});
			$('#promos .delete').unbind('click').click(function(){
				var id = $(this).attr('data-id');
				Messages(true,'¿Seguro deseas borrar esta promo?',function(){
					Promos.ID = id;
					Promos.delete();
				});
			});
			$('#promos .preview').unbind('click').click(function(){
				var id = $(this).attr('data-id');
				var permalink = $(this).attr('data-permalink');
				var title = $(this).attr('data-title');
				//console.log(id,permalink,title);
				window.open(ROOTPATH+'promo/'+permalink+'/'+id+'-'+Permalink(title));
			});
		});
	},
	find:function(){
		AjaxConnection('jxPromos.php',{Mode:'find',ID:Promos.ID},function(DATA){
			var obj = DATA.Result;
			$('#fd_title').val(obj.title);
			$('#fd_start').val(obj.inicio);
			$('#fd_finish').val(obj.fin);
			$('#fd_subtitle').val(obj.subtitle);
			$('#fd_clients option[value="'+obj.idclient+'"]').prop('selected',true);
			Clients.IDStores = obj.stores.split(',');
			Clients.get();
			$('#fd_description').val(obj.description);
			$('#fd_includes').val(obj.includes);
			$('#fd_recomendations').val(obj.recomendations);
			$('#fd_reservation').val(obj.reservation);
			$('#fd_duration').val(obj.duration);
			$('#fd_cancellation').val(obj.cancellation);
			if(obj.sale == 1){
				$('#fd_sale').removeClass('active').trigger('click');
			}else{
				$('#fd_sale').addClass('active').trigger('click');
			}
			$('#fd_price').val(obj.price);
			$('#fd_promotypes option[value="'+obj.idpromotype+'"]').prop('selected',true);
			$('#fd_discount').val(obj.discount);
			$('#fd_amount').val(obj.amount);
			if(obj.gallery != ''){
				var gallery = $.parseJSON(obj.gallery);
				$.each(gallery,function(k,v){
					Promos.buildgallery(v.photoname,v.extension);
				});
			}
		});
	},
	buildgallery:function(PHTNM,EXT){
		var mod = $('#mod_thumb').clone();
		mod.removeClass('dp-none').addClass('dp-ib').removeAttr('id');
		mod.css({backgroundImage:'url('+ROOTPATH+'img/promos/'+PHTNM+'-t.'+EXT+')'});
		mod.attr({'data-photoname':PHTNM,'data-extension':EXT});
		mod.find('.dp-table').remove();
		$('#gallery').append(mod);
		$('#gallery .delete').unbind('click').click(function(){
			$(this).parent().parent().remove();
		});
	},
	init:function(){
		UpFile.Init({MODE:'upimage',PHP:'jxPromos.php',FOLDER:'img/promos/',FORM:'#form_image',BTN:'#btn_image',TH:'#thumb_promo',Callback:function(ArrFiles){
				$.each(ArrFiles,function(k,v){
					Promos.buildgallery(v.photoname,v.extension);
				});
		}});
		$('#btn_new').click(function(){
			$('#edit_panel').slideDown({duration:900,easing:'easeInOutCubic'});
			$('#list_panel').slideUp({duration:900,easing:'easeInOutCubic'});
			if(Promos.ID == 0){
				Clients.get();
			}
		});
		$('#btn_cancel').click(function(){		
			Promos.reset();
		});
		$('#btn_delete').click(function(){
			Messages(true,'¿Realmente deseas borrar esta promo?',Promos.delete);			
		});
		$('#fd_start').datepicker();
		$('#fd_finish').datepicker();
		$('#btn_save').click(function(){
			CheckFields(['#fd_title','#fd_start','#fd_finish','#fd_description'],function(){
				Promos.save();
			});
		});
		$('#fd_sale').click(function(){			
			$(this).toggleClass('active');
			if($(this).hasClass('active')){
				$(this).find('i').addClass('fa-check-square').removeClass('fa-square-o');
				$('#sale_box').slideDown();
			}else{
				$(this).find('i').removeClass('fa-check-square').addClass('fa-square-o');
				$('#sale_box').slideUp();
			}
		});
		$('#btn_select_stores').click(function(){
			$('#stores .mod-list').addClass('active');
		});
		$('#form_search').submit(function(e){e.preventDefault();Promos.get()});
		$('#fd_select_order').change(function(){Promos.get()});
		$('#fd_select_status').change(function(){Promos.get()});
		$('#fd_select_client').change(function(){Promos.get()});
		SearchSuggestions('#form_search','jxPromos.php','get',Promos.get);
		$('#gallery').sortable();
		Promos.get();
	}	
}
var Promotypes = {
	ID:0,
	get:function(){
		$('#mod_promotype,#fd_promotypes').html('');
		AjaxConnection('jxPromos.php',{Mode:'getpromotypes'},function(DATA){
			$.each(DATA.Results,function(k,v){
				var mod = $('#mod_list').clone();
				mod.removeAttr('id').removeClass('dp-none');
				mod.find('h4').text(v.name);
				mod.find('.add,.delete,.edit').remove();
				$('#fd_promotypes').append('<option value="'+v.id+'">'+v.name+'</option>');
				$('#mod_promotype').append(mod);
			});
		});
	},
	init:function(){
		Promotypes.get();
	}
}
$(function(){
	ModViews();
	Promos.init();
	Promotypes.init();
});