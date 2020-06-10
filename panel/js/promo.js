var Promotypes = {
	ID:0,
	get:function(){
		$('#mod_promotype,#fd_promotypes').html('');
		ajax('panel/promos/gettypes')
			.then(function(DATA){
				$.each(DATA.results,function(k,v){
					var mod = $('#mod_list').clone();
					mod.removeAttr('id').removeClass('dp-none');
					mod.find('h4').text(v.name);
					mod.find('.add,.delete,.edit').remove();
					$('#fd_promotypes').append('<option value="'+v.id+'">'+v.name+'</option>');
					$('#mod_promotype').append(mod);
				});

				if($_id!=0){
					Promos.find();
				}else{
					//$('[name="glossary"]').bootstrapDualListbox();
					Clients.get();
				}


			});
	},
	init:function(){
		Promotypes.get();
	}
}
var Clients = {
	IDStores:[],
	get:function(){
		$('#stores').html('');
		ajax('panel/stores/get')
			.then(function(DATA){
				$.each(DATA.results,function(k,v){
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
	save:function(){
		var arrfrom = $('#fd_start').val().split('/');
		var arrto = $('#fd_finish').val().split('/');
		var from = new Date(arrfrom[2]+"-"+arrfrom[1]+"-"+arrfrom[0]+" 00:00:00");
		var to = new Date(arrto[2]+"-"+arrto[1]+"-"+arrto[0]+" 00:00:00");
		var today = new Date();
		var statusstart = from.getTime()<=today.getTime() ? 1 : 0;
		var statusfinish = to.getTime()>=today.getTime() ? 1 : 0;
		if(to.getTime()-from.getTime() < 0){
			Swal.fire({
				type:'warning',
				text:'La fecha inicial debe ser anterior a la final!'
			});
			return false;
		}
		var gallery = [];
		$.each($('#gallery .thumbnail'),function(k,v){
			gallery.push({photoname:$(this).attr('data-filename'),extension:$(this).attr('data-extension')});

		});
		if(gallery.length==0){
			Swal.fire({
				type:'warning',
				text:'Debes subir al menos una imagen.'
			});
			return false;
		}
		var stores = [];
		$.each($('#stores .mod-list.active'),function(k,v){
			stores.push($(this).attr('data-id'));
		});
		if(stores.length==0){
			Swal.fire({
				type:'warning',
				text:'Debes elegir al menos una sucursal.'
			});
			return false;
		}
		if($('#fd_sale').hasClass('active')){
			if($('#fd_includes').val() == '' || $('#fd_recomendations').val() == '' || $('#fd_reservation').val() == '' || $('#fd_duration').val() == '' || $('#fd_cancellation').val() == ''){
				Swal.fire({
					type:'warning',
					text:'Debes indicar ¿Que incluye la experiencia?, ¿Que recomendamos que lleve?, ¿Requiere reserva y/o algún requisito?, Duración de la actividad, ¿Cuál es la política de cancelación?'
				});
				return false;
			}
			if($('#fd_price').val()==0){
				Swal.fire({
					type:'warning',
					text:'Debes indicar el valor de la experiencia.'
				});
				return false;
			}
			if($('#fd_amount').val()==0){
				Swal.fire({
					type:'warning',
					text:'La cantidad disponible no puede estar en 0'
				});
				return false;
			}
			if($('#fd_category').val()==''){
				Swal.fire({
					type:'warning',
					text:'Debes Elegir una Categoría para esta experiencia'
				});
				return false;
			}
		}
		ajax('panel/promos/save',{
			Title:$('#fd_title').val(),
			Subtitle:$('#fd_subtitle').val(),
			Stores:stores,
			Start:$('#fd_start').val(),
			Finish:$('#fd_finish').val(),
			Sale:$('#fd_gift').hasClass('active') ? 1 : 0,
			Sale:$('#fd_sale').hasClass('active') ? 1 : 0,
			Price:$('#fd_price').val(),
			IDPromotype:$('#fd_promotypes').val(),
			CategoryID:$('#fd_category').val(),
			Discount:$('#fd_discount').val(),
			Amount:$('#fd_amount').val(),
			Description:$('#fd_description').val(),
			Includes:$('#fd_includes').val(),
			Recomendations:$('#fd_recomendations').val(),
			Reservation:$('#fd_reservation').val(),
			Duration:$('#fd_duration').val(),
			Cancellation:$('#fd_cancellation').val(),
			Gallery:gallery,
			ID:$_id
		})
			.then(function(DATA){
				toastr['success'](DATA.message);
				Promos.reset();
			});
	},
	reset:function(){
		window.location.href = ROOT+'panel/promos';
	},
	find:function($id){
		ajax('panel/promos/find',{ID:$_id})
			.then(function(DATA){
				var obj = DATA.result;
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
				if(obj.gift == 1){
					$('#fd_gift').removeClass('active').trigger('click');
				}else{
					$('#fd_gift').addClass('active').trigger('click');
				}
				if(obj.sale == 1){
					$('#fd_sale').removeClass('active').trigger('click');
				}else{
					$('#fd_sale').addClass('active').trigger('click');
				}
				$('#fd_price').val(obj.price);
				$('#fd_promotypes option[value="'+obj.idpromotype+'"]').prop('selected',true);
				$('#fd_category').val(obj.categoryid);
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
		mod.css({backgroundImage:'url('+ROOT+'img/promos/'+PHTNM+'-t.'+EXT+')'});
		mod.attr({'data-filename':PHTNM,'data-extension':EXT});
		mod.find('.dp-table').remove();
		$('#gallery').append(mod);
		$('#gallery .delete').unbind('click').click(function(){
			$(this).parent().parent().remove();
		});
	},
	init:function(){

		var gallery = new UpFile({
			container:'[data-input=gallery]',
			folder:'img/promos',
			gallery:'#gallery',
			controller:'panel/promos/gallery'
		});


		$('#btn_cancel').click(function(){
			Promos.reset();
		});
		$('#btn_delete').click(function(){
			Swal.fire({
				type:'warning',
				text:'¿Realmente deseas borrar esta experiencia?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Promos.reset();
					}
				});

		});
		$('#fd_start').datepicker();
		$('#fd_finish').datepicker();
		$('#btn_save').click(function(){
			CheckFields(['#fd_title','#fd_subtitle','#fd_start','#fd_finish','#fd_description'],function(){
				Promos.save();
			});
		});
		$('#fd_gift,#fd_sale').click(function(){
			$(this).toggleClass('active');
			if($(this).hasClass('active')){
				$(this).find('i').addClass('fa-check-square').removeClass('fa-square-o');
				//$('#sale_box').slideDown();
			}else{
				$(this).find('i').removeClass('fa-check-square').addClass('fa-square-o');
				//$('#sale_box').slideUp();
			}
		});
		$('#btn_select_stores').click(function(){
			$('#stores .mod-list').addClass('active');
		});
	}
}
$(function(){
	Promos.init();
	Promotypes.init();
});