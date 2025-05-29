
var Promos = {
	delete:function($id){
		ajax('panel/promos/delete',{ID:id})
			.then(function(DATA){
				Promos.get();
			});
	},
	get:function(){
		ajax('panel/promos/get',{
			keywords:$('#fd_search').val(),
			sort:$('#fd_select_order').val(),
			status:$('#fd_select_status').val()
		})
			.then(function(DATA){
				if(DATA.results==null){$('#promos').html('<div class="col-xs-12">No se encontraron promos</div>');return false;}
				$('#promos').html('');
				$.each(DATA.results,function(k,v){
					var mod = $('#mod_card').clone();
					mod.removeAttr('id').removeClass('dp-none');
					mod.attr('data-id',(v.id==undefined?0:v.id));
					mod.find('.title').text(v.title);

					var client = $('<h2>').clone();
					var category = $('<h2 class="text-muted">').clone();
					client.text(v.name);
					category.text(v.category_name==null ? '' : v.category_name);

					mod.find('h1').after(client);
					client.after(category)


					mod.find('.foot').remove();
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
						mod.find('.thumb').css({backgroundImage:'url('+ROOT+'img/promos/'+img[0].photoname+'-t.'+img[0].extension+')'});
					}
					$('#promos').append(mod);
				});

			});
	},

	init:function(){


		$('#promos').on('click','.edit',function(){
			window.location.href = ROOT+'panel/promo/'+$(this).attr('data-id');
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
					Promos.delete(id);
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


		$('#form_search').submit(function(e){e.preventDefault();Promos.get()});
		$('#fd_select_order').change(function(){Promos.get()});
		$('#fd_select_status').change(function(){Promos.get()});

		$('#gallery').sortable();
		Promos.get();
	}
}

$(function(){
	ModViews();
	Promos.init();
});