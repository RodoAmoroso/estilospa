var Actions = {
	ID:0,
	save:function(type){
		var type = type == undefined ? 'main' : type;
		if($('#th_image_'+type).attr('data-filename') == undefined){
			Swal.fire({
				type:'warning',
				text:'Debes subir una imagen.'
			});
			return false;
		}
		var img = {
			photoname:$('#th_image_'+type).attr('data-filename'),
			extension:$('#th_image_'+type).attr('data-extension'),
			position:$('#th_image_'+type).css('backgroundPosition')
		};
		var link = {
			url:$('#fd_url_'+type).val(),
			blank:($('#btn_url_'+type+'_blank i').hasClass('fa-check-square') ? 1 : 0)
		};
		var visdiv = type== 'main' ? 'fd_visible_main' : 'fd_visible_side';
		ajax('admin/home/save',{
			name:$('#fd_name_'+type).val(),
			title:type == 'main' ? $('#fd_title_'+type).val() : '',
			caption:type == 'main' ? $('#fd_caption_'+type).val() : '',
			type:type,
			image:img,
			visible:$('#'+visdiv+' i').hasClass('fa-toggle-on') ? 1 : 0,
			link:link,
			id:Actions.ID
		})
			.then(function(DATA){
				Actions.reset(type);
				Actions.slide('list',type);
				Actions.get();
			});
	},
	reset:function(type){
		$('#fd_name_'+type+',#fd_caption_'+type+',#fd_title_'+type+',#fd_url_'+type).val('');
		Actions.ID = 0;
		$('#th_image_'+type)
			.removeAttr('data-filename data-extension')
			.css({backgroundImage:''})
			.find('.th').attr({src:''});
	},
	slide:function(mode,type){
		if(mode == 'edit'){
			$('#list_main').slideUp({duration:900,easing:'easeInOutCubic'});
			$('#edit_'+type).slideDown({duration:900,easing:'easeInOutCubic'});
		}
		if(mode == 'list'){
			$('#list_main').slideDown({duration:900,easing:'easeInOutCubic'});
			$('#edit_'+type).slideUp({duration:900,easing:'easeInOutCubic'});
		}
	},
	upimage:function(type){
		UpFile.Init({MODE:'up'+type,PHP:'jxHome.php',FOLDER:'img/home/',FORM:'#form_image_'+type,BTN:'#btn_image_'+type,SX:'-o',Callback:function(ArrFiles,ID){
			var img = new Image();
			img.onload = function(){
				$('#th_image_'+type).attr({'data-filename':ArrFiles[0].photoname,'data-extension':ArrFiles[0].extension}).css({backgroundImage:'url('+this.src+')'});
				/*$('#th_image_'+type+' .th').attr({src:this.src});
				$('#th_image_'+type+' .th').css({width:'100%'});
				if(this.height < $('#th_image_'+type).height()){
					$('#th_image_'+type+' .th').css({width:'auto',height:'100%'});
				}*/
			}
			img.src = ROOT+'img/home/'+ArrFiles[0].photoname+'-o.'+ArrFiles[0].extension;
		}});
	},
	get:function(){
		$('#main,#side').html('');
		ajax('admin/home/get')
			.then(function(data){
				$.each(data.results,function(k,v){
					var mod = $('#mod_card').clone();
					mod.removeAttr('id').removeClass('dp-none').attr({'data-type':v.type,'data-id':v.id});
					mod.find('h1').text(v.name);
					mod.find('.preview,.foot').remove();
					mod.find('.edit,.delete').attr({'data-type':v.type,'data-id':v.id});
					mod.find('p').html('Creado: '+v.added+' &bullet; '+(v.visible==1 ? '<i class="fa fa-toggle-on"></i>' : '<i class="fa fa-toggle-off"></i>'));
					///var img = $.parseJSON(v.image);
					mod.find('.thumb').css({backgroundImage:'url('+ROOT+'img/home/'+v.image.photoname+'-t.'+v.image.extension+')'});
					if(v.type=='main'){
						$('#main').append(mod);
					}
					if(v.type=='side'){
						$('#side').append(mod);
					}
				});
				$('#main,#side').sortable({
					update:function(e,ui){
						var arr = [];
						var type = $(ui.item[0]).attr('data-type');
						$.each($('#'+type+' .mod-card'),function(k,v){
							arr.push($(this).attr('data-id'));
						});
						ajax('admin/home/reorder',{type:type,arrids:arr}).then(function(){});
					}
				});

			});
	},
	find:function(type){
		Actions.slide('edit',type);
		ajax('admin/home/find',{id:Actions.ID})
			.then(function(data){

				if(data.result == false) return false;
				$('#fd_name_'+type).val(data.result.name);
				$('#fd_title_'+type).val(data.result.title);
				$('#fd_caption_'+type).val(data.result.caption);
				///var img = $.parseJSON(DATA.result.image);

				var arrpos = data.result.image.position.split(' ');
				$('#slider_h_'+type).slider({value:arrpos[0].replace('%','').replace('px','')});
				$('#slider_v_'+type).slider({value:arrpos[1].replace('%','').replace('px','')});
				$('#th_image_'+type)
				.attr({
					'data-filename':data.result.image.photoname,
					'data-extension':data.result.image.extension
				})
				.css({
					backgroundImage:'url('+ROOT+'img/home/'+data.result.image.photoname+'-o.'+data.result.image.extension+')',
					backgroundPosition:data.result.image.position
				});
				////var link = $.parseJSON(data.result.link);
				$('#fd_url_'+type).val(data.result.link.url);
				if(data.result.link.blank==0){
					$('#btn_url_'+type+'_blank i').addClass('fa-check-square').removeClass('fa-square');
				}else{
					$('#btn_url_'+type+'_blank i').removeClass('fa-check-square').addClass('fa-square');
				}
				if(data.result.visible == 1){
					$('#fd_visible_main i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
				}else{
					$('#fd_visible_main i').addClass('fa-toggle-off').removeClass('fa-toggle-on');
				}
			});
	},
	delete:function(type){
		ajax('admin/home/delete',{id:Actions.ID,Type:type})
			.then(function(){
				Actions.reset(type);
				Actions.get();
			});
	},
	init:function(){

		var main = new UpFile({
			container:'[data-input="main"]',
			controller:'admin/home/upmain',
			folder:'img/home',
			thumbnail:'#th_image_main',
			sufix:'-o'
		});
		var side = new UpFile({
			container:'[data-input="side"]',
			controller:'admin/home/upside',
			folder:'img/home',
			thumbnail:'#th_image_side',
			sufix:'-o'
		});

		$('#main,#side').on('click','.edit',function(){
			Actions.ID = $(this).attr('data-id');
			Actions.find($(this).attr('data-type'));
		});
		$('#main,#side').on('click','.delete',function(){
			var id = $(this).attr('data-id');
			var type = $(this).attr('data-type');
			Swal.fire({
				type:'warning',
				text:'¿Seguro deseas borrar este banner?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Actions.ID = id;
						Actions.delete(type);
					}
				});

		});

		this.get();
	}
}
$(function(){
	//Actions.upimage('main');
	//Actions.upimage('side');
	DragImages('#th_image_main');
	DragImages('#th_image_side');
	$('#btn_save_main,#btn_save_side').click(function(){
		var type = $(this).attr('data-type');
		CheckFields(['#fd_name_'+type],function(){
			Actions.save(type);
		});
	});
	$('#btn_new_main,#btn_new_side').click(function(){
		var type = $(this).attr('data-type');
		Actions.slide('edit',type);
	});
	$('#btn_cancel_main,#btn_cancel_side').click(function(){
		var type = $(this).attr('data-type');
		Actions.slide('list',type);
		Actions.reset(type);
	});
	///////////////////////////////
	$('#slider_v_main,#slider_v_side').slider({
		slide:function( event, ui ){
			var type = $(ui.handle.offsetParent).attr('data-type');
			var arrpos = $("#th_image_"+type).css("backgroundPosition").split(" ");
			$("#th_image_"+type).css("backgroundPosition", arrpos[0]+" "+ui.value+"%");
		},
		value:50
	});
	$('#slider_h_main,#slider_h_side').slider({
		slide:function( event, ui ){
			var type = $(ui.handle.offsetParent).attr('data-type');
			var arrpos = $("#th_image_"+type).css("backgroundPosition").split(" ");
			$("#th_image_"+type).css("backgroundPosition", ui.value+"% "+arrpos[1]);
		},
		value:50
	});
	Actions.init();
});