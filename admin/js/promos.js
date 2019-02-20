
var Promos = {
	ID:0,
	delete:function(){
		AjaxConnection('jxPromos.php',{Mode:'delete',ID:Promos.ID},function(DATA){
			Promos.reset();
			Promos.get();
		});
	},
	reset:function(){
		Promos.ID = 0;
	},
	get:function(){
		$('#promos').html('<p class="text-center"><i class="fa fa-cog fa-spin"></i> Cargando...</p>');
		AjaxConnection('jxPromos.php',{
			Mode:'get',
			Keywords:$('#fd_search').val(),
			Sort:$('#fd_select_order').val(),
			IDClient:$('#fd_select_client').val(),
			Status:$('#fd_select_status').val()
		},function(DATA){
			if(DATA.Results==null){$('#promos').html('<div class="col-xs-12">No se encontraron promos</div>');return false;}
			$('#promos').html('');
			$.each(DATA.Results,function(k,v){
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
					mod.find('.thumb').css({backgroundImage:'url('+ROOTPATH+'img/promos/'+img[0].photoname+'-t.'+img[0].extension+')'});
				}
				$('#promos').append(mod);
			});
			$('#promos .edit').unbind('click').click(function(){
				var id = $(this).attr('data-id');
				window.location.href = ROOTPATH+'admin/promo/'+id;
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
	init:function(){

		$('#form_search').submit(function(e){e.preventDefault();Promos.get()});
		$('#fd_select_order').change(function(){Promos.get()});
		$('#fd_select_status').change(function(){Promos.get()});
		$('#fd_select_client').change(function(){Promos.get()});
		SearchSuggestions('#form_search','jxPromos.php','get',Promos.get);
		$('#promos').sortable({update:function(){
			var arrids = [];
			/*if($('#fd_select_client').val() != 0 || $('#fd_select_status').val() != 0){
				Messages(true,'No debe haber ningún filtro seleccionado para reordenar las promos');
				return false;
			}*/
			$.each($('#promos .mod-card'),function(k,v){
				arrids.push($(this).attr('data-id'));
			});
			AjaxConnection('jxPromos.php',{Mode:'reorder',arrids:arrids},function(data){});
		}});
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
				Messages(true,'Debes seleccionar al menos una promo');
				return false;
			}
			AjaxConnection('jxPromos.php',{Mode:'reorder',arrids:arrids},function(data){
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