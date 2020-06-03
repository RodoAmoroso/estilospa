var Promos = {
	ID:0,
	delete:function(){
		ajax('admin/promos/delete',{ID:Promos.ID})
			.then(function(){
				Promos.reset();
				Promos.get();
			});
	},
	reset:function(){
		Promos.ID = 0;
	},
	get:function(){
		$('#promos').html('<p class="text-center"><i class="fa fa-cog fa-spin"></i> Cargando...</p>');
		ajax('admin/promos/get',{
			keywords:$('#fd_search').val(),
			sort:$('#fd_select_order').val(),
			idclient:$('#fd_select_client').val(),
			categoryid:$('#fd_select_category').val(),
			status:$('#fd_select_status').val()
		})
			.then(function(DATA){
				if(DATA.results==false){$('#promos').html('<div class="col-xs-12">No se encontraron promos</div>');return false;}
				$('#promos').html('');
				$.each(DATA.results,function(k,v){
					var mod = $('#mod_card').clone();
					mod.removeAttr('id').removeClass('dp-none');
					mod.attr('data-id',(v.id==undefined?0:v.id));
					mod.find('h1').text(v.title);
					mod.find('h1').after('<h2>'+v.name+'</h2>');
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
					mod.find('.description').html('Inicia: '+v.start+' &bullet; Finaliza: '+v.finish+(v.sale == 1 ? ' &bullet; <i class="fa fa-shopping-bag"></i>' : ''));
					mod.find('.foot').prepend(status);
					mod.find('.edit,.delete,.preview,.selector').attr({'data-id':v.id});
					mod.find('.preview').attr({'data-permalink':v.permalink,'data-title':v.title});
					if(v.gallery!=''){
						var img = $.parseJSON(v.gallery);
						if(img[0].photoname!=undefined) mod.find('.thumb').css({backgroundImage:'url('+ROOT+'img/promos/'+img[0].photoname+'-t.'+img[0].extension+')'});
					}
					$('#promos').append(mod);
				});

			});
	},
	init:function(){

		$('#form_search').submit(function(e){e.preventDefault();Promos.get()});
		$('#fd_select_order,#fd_select_status,#fd_select_client,#fd_select_category').change(function(){Promos.get()});
		SearchSuggestions('#form_search','admin/promos/get','',Promos.get);


		if($clientid!=''){
			$('#fd_select_client').val($clientid).trigger('change');
		}

		$('#promos').sortable({
			update:function(){
				var arrids = [];
				$.each($('#promos .mod-card'),function(k,v){
					arrids.push($(this).attr('data-id'));
				});
				ajax('admin/promos/reorder',{arrids:arrids})
					.then(function(){});
			}
		});


		$('#promos').on('click','.edit',function(){
			var id = $(this).attr('data-id');
			window.location.href = ROOT+'admin/promo/'+id;
		});
		$('#promos').on('click','.delete',function(){
			var id = $(this).attr('data-id');
			Swal.fire({
				type:'warning',
				text:'¿Seguro deseas borrar esta promo?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Promos.ID = id;
						Promos.delete();
					}
				});

		});
		$('#promos').on('click','.preview',function(){
			var id = $(this).attr('data-id');
			var permalink = $(this).attr('data-permalink');
			var title = $(this).attr('data-title');
			//console.log(id,permalink,title);
			window.open(ROOT+'promo/'+permalink+'/'+id+'-'+title.permalink());
		});

		$('#promos').on('click','.selector',function(){
			var id = $(this).attr('data-id');
			$(this).attr('data-value',$(this).attr('data-value') == 1 ? 0 : 1);
			$(this).find('i').toggleClass('fa-circle-o fa-dot-circle-o');
		});

		$('button[data-toggle="move"]').click(function(){
			var action = $(this).attr('data-value');
			var arrids = [];
			$.each($('#promos .selector'),function(){
				if($(this).attr('data-value')==1){
					arrids.push($(this).attr('data-id'));
				}
			});
			if(arrids.length==0){
				Swal.fire({
					type:'warning',
					text:'Debes seleccionar al menos una promo.'
				});
				return false;
			}
			ajax('admin/promos/reorder',{arrids:arrids})
				.then(function(){
					Promos.get();
				});
		});

		Promos.get();
	}
}
$(function(){
	ModViews();
	Promos.init();
});