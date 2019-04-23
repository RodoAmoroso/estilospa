var Clients = {
	IDStores:[],
	get:function(){
		$('#fd_clients').html('');
		AjaxConnection('jxClients.php',{Mode:'get'},function(DATA){
			$.each(DATA.Results,function(k,v){
				$('#fd_clients').append('<option value="'+v.id+'">'+v.name+'</option>');
			});
			$('#fd_clients').unbind('change').change(function(){
				$('#stores').html('');
				AjaxConnection('jxClients.php',{Mode:'getstores',IDC:$('#fd_clients').val()},function(DATA){
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
			});

			if($_id!=0){
				Promos.find();
			}

		});
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
				mod.find('.edit').attr('data-id',v.id);
				mod.find('.delete').attr('data-id',v.id);
				mod.find('.add').remove();
				$('#fd_promotypes').append('<option value="'+v.id+'">'+v.name+'</option>');
				$('#mod_promotype').append(mod);
			});
			$('#mod_promotype .delete').click(function(){
				var id = $(this).attr('data-id');
				Messages(true,'¿Seguro deseas borrar este item?',function(){
					Promotypes.ID = id;
					Promotypes.delete();
				});				
			});
			$('#mod_promotype .edit').click(function(){
				$('#mod_promotype h4').find('i').remove();
				var id = $(this).attr('data-id');
				$(this).parent().parent().effect('transfer',{to:$('#fd_promotype_name')});
				$('#mod_promotype .mod-list').removeClass('active');
				$(this).parent().parent().addClass('active');
				Promotypes.ID = id;
				Promotypes.find();
			});
		});
	},
	reset:function(){
		Promotypes.ID = 0;
		$('#mod_promotype .mod-list').removeClass('active');
		$('#fd_promotype_name').val('');
	},
	save:function(){
		AjaxConnection('jxPromos.php',{
			Mode:'savepromotype',
			ID:Promotypes.ID,
			Name:$('#fd_promotype_name').val()
		},function(DATA){
			Promotypes.reset();
			Promotypes.get();
		});
	},
	find:function(){
		AjaxConnection('jxPromos.php',{Mode:'findpromotypes',ID:Promotypes.ID},function(DATA){
			$('#fd_promotype_name').val(DATA.Result.name);
		});
	},
	delete:function(){
		AjaxConnection('jxPromos.php',{Mode:'deletepromotype',ID:Promotypes.ID},function(DATA){
			if(DATA.Status=='fail'){Messages(true,'Hubo problemas al procesar la solicitud. Intenta nuevamente más tarde.');return;};
			Promotypes.reset();
			Promotypes.get();
		});
	},
	init:function(){
		$('#btn_edit_promotypes').click(function(){
			$('#edit_panel').slideUp({duration:900,easing:'easeInOutCubic'});
			$('#promotypes_panel').slideDown({duration:900,easing:'easeInOutCubic'});
		});
		$('#btn_close_promotype').click(function(){
			$('#edit_panel').slideDown({duration:900,easing:'easeInOutCubic'});
			$('#promotypes_panel').slideUp({duration:900,easing:'easeInOutCubic'});
			Promotypes.reset();
		});
		$('#btn_save_promotype').click(function(){
			CheckFields(['#fd_promotype_name'],Promotypes.save);
		});
		Promotypes.get();
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
		//return console.log($('[name="glossary"]').val());
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
			Mode:'save',
			Title:$('#fd_title').val(),
			Subtitle:$('#fd_subtitle').val(),
			IDClient:$('#fd_clients').val(),
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
			Glossary:$('[name="glossary"]').val(),
			Gallery:gallery,
			ID:$_id
		},function(DATA){
			Promos.reset();
		});
	},
	delete:function(){
		AjaxConnection('jxPromos.php',{Mode:'delete',ID:$_id},function(DATA){
			Promos.reset();
		});
	},
	reset:function(){
		window.location.href = ROOT+'admin/promos';
	},
	find:function(){
		AjaxConnection('jxPromos.php',{Mode:'find',ID:$_id},function(DATA){
			var obj = DATA.Result;

			$('#fd_title').val(obj.title);
			$('#fd_start').val(obj.inicio);
			$('#fd_finish').val(obj.fin);
			$('#fd_subtitle').val(obj.subtitle);
			$('#fd_clients option[value="'+obj.idclient+'"]').prop('selected',true);
			Clients.IDStores = obj.stores.split(',');
			$('#fd_clients').trigger('change');
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

			$('[name="glossary"]').val(obj.glossary);
			$('[name="glossary"]').bootstrapDualListbox();

		});
	},
	buildgallery:function(PHTNM,EXT){
		var mod = $('#mod_thumb').clone();
		mod.removeClass('dp-none').addClass('dp-ib').removeAttr('id');
		mod.css({backgroundImage:'url('+ROOT+'img/promos/'+PHTNM+'-t.'+EXT+')'});
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

		$('#gallery').sortable();		
	}	
}


var Glossary = {
	get:function(){
		AjaxConnection('jxGlossary.php',{Mode:'get'},function(data){
			$('[name="glossary"]').html('');
			if(!data.Results) return false;
			$.each(data.Results,function(k,v){
				$('[name="glossary"]').append('<option value="'+v.id+'">'+v.name+'</option>');
			});
			//$('[name="glossary"]').bootstrapDualListbox();
		})
	}
}

$(function(){
	Promos.init();
	Promotypes.init();
	Glossary.get();
	Clients.get();
	
});