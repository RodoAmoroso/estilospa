var Actions = {
	ID:0,
	save:function(type){
		var type = type == undefined ? 'main' : type;
		if($('#th_image_'+type).attr('data-photoname') == undefined){
			Messages(true,'Debes subir una imagen');
			return false;
		}
		var img = {photoname:$('#th_image_'+type).attr('data-photoname'),extension:$('#th_image_'+type).attr('data-extension'),position:$('#th_image_'+type).css('backgroundPosition')};
		var link = {url:$('#fd_url_'+type).val(),blank:($('#btn_url_'+type+'_blank i').hasClass('fa-check-square') ? 1 : 0)};
		var visdiv = type== 'main' ? 'fd_visible_main' : 'fd_visible_side';
		AjaxConnection('jxHome.php',{
			Mode:'save',
			Name:$('#fd_name_'+type).val(),
			Title:type == 'main' ? $('#fd_title_'+type).val() : '',
			Caption:type == 'main' ? $('#fd_caption_'+type).val() : '',
			Type:type,
			IMG:img,
			Visible:$('#'+visdiv+' i').hasClass('fa-toggle-on') ? 1 : 0,
			Link:link,
			ID:Actions.ID
		},function(DATA){
			if(DATA.Status == 'url'){
				Messages(true,'Debes ingresar una URL válida');
				return false;
			}
			Actions.reset(type);
			Actions.slide('list',type);
			Actions.get();
		});
	},
	reset:function(type){
		$('#fd_name_'+type+',#fd_caption_'+type+',#fd_title_'+type+',#fd_url_'+type).val('');
		Actions.ID = 0;
		$('#th_image_'+type).removeAttr('data-photoname data-extension').find('.th').attr({src:''});
	},
	slide:function(mode,type){
		if(mode == 'edit'){
			$('#list_main,#list_side').slideUp({duration:900,easing:'easeInOutCubic'});
			$('#edit_'+type).slideDown({duration:900,easing:'easeInOutCubic'});
		}
		if(mode == 'list'){
			$('#list_main,#list_side').slideDown({duration:900,easing:'easeInOutCubic'});
			$('#edit_'+type).slideUp({duration:900,easing:'easeInOutCubic'});
		}
	},
	upimage:function(type){
		UpFile.Init({MODE:'up'+type,PHP:'jxHome.php',FOLDER:'img/home/',FORM:'#form_image_'+type,BTN:'#btn_image_'+type,SX:'-o',Callback:function(ArrFiles,ID){
			var img = new Image();
			img.onload = function(){
				$('#th_image_'+type).attr({'data-photoname':ArrFiles[0].photoname,'data-extension':ArrFiles[0].extension}).css({backgroundImage:'url('+this.src+')'});
				/*$('#th_image_'+type+' .th').attr({src:this.src});
				$('#th_image_'+type+' .th').css({width:'100%'});
				if(this.height < $('#th_image_'+type).height()){
					$('#th_image_'+type+' .th').css({width:'auto',height:'100%'});
				}*/
			}
			img.src = ROOTPATH+'img/home/'+ArrFiles[0].photoname+'-o.'+ArrFiles[0].extension;		
		}});	
	},
	get:function(){
		$('#main,#side').html('');
		AjaxConnection('jxHome.php',{Mode:'get'},function(DATA){
			$.each(DATA.Results,function(k,v){
				var mod = $('#mod_card').clone();
				mod.removeAttr('id').removeClass('dp-none').attr({'data-type':v.type,'data-id':v.id});
				mod.find('h1').text(v.name);
				mod.find('.preview').remove();
				mod.find('.edit,.delete').attr({'data-type':v.type,'data-id':v.id});
				mod.find('p').html('Creado: '+v.added+' &bullet; '+(v.visible==1 ? '<i class="fa fa-toggle-on"></i>' : '<i class="fa fa-toggle-off"></i>'));
				var img = $.parseJSON(v.image);
				mod.find('.thumb').css({backgroundImage:'url('+ROOTPATH+'img/home/'+img.photoname+'-t.'+img.extension+')'});
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
					console.log(arr);
					AjaxConnection('jxHome.php',{Mode:'reorder',Type:type,ArrID:arr},function(DATA){});
				}
			});
			$('#main .edit,#side .edit').unbind('click').click(function(){
				Actions.ID = $(this).attr('data-id');
				Actions.find($(this).attr('data-type'));
			});
			$('#main .delete,#side .delete').unbind('click').click(function(){
				var id = $(this).attr('data-id');
				var type = $(this).attr('data-type');
				Messages(true,'¿Seguro deseas borrar este banner?',function(){
					Actions.ID = id;
					Actions.delete(type);
				});				
			});
		});		
	},
	find:function(type){
		Actions.slide('edit',type);
		AjaxConnection('jxHome.php',{Mode:'find',ID:Actions.ID},function(DATA){
			$('#fd_name_'+type).val(DATA.Result.name);
			$('#fd_title_'+type).val(DATA.Result.name);
			$('#fd_caption_'+type).val(DATA.Result.name);
			var img = $.parseJSON(DATA.Result.image);
			var arrpos = img.position.split(' ');
			$('#slider_h_'+type).slider({value:arrpos[0].replace('%','').replace('px','')});
			$('#slider_v_'+type).slider({value:arrpos[1].replace('%','').replace('px','')});
			$('#th_image_'+type).attr({'data-photoname':img.photoname,'data-extension':img.extension}).css({backgroundImage:'url('+ROOTPATH+'img/home/'+img.photoname+'-o.'+img.extension+')',backgroundPosition:img.position});
			var link = $.parseJSON(DATA.Result.link);
			$('#fd_url_'+type).val(link.url);
			if(link.blank==0){
				$('#btn_url_'+type+'_blank i').addClass('fa-check').removeClass('fa-check-square');
			}else{
				$('#btn_url_'+type+'_blank i').removeClass('fa-check').addClass('fa-check-square');
			}
			if(DATA.Result.visible == 1){
				$('#fd_visible_main i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
			}else{
				$('#fd_visible_main i').addClass('fa-toggle-off').removeClass('fa-toggle-on');
			}
		});
	},
	delete:function(type){
		AjaxConnection('jxHome.php',{Mode:'delete',ID:Actions.ID,Type:type},function(){
			Actions.reset(type);
			Actions.get();
		});
	}
}
$(function(){	
	Actions.upimage('main');
	Actions.upimage('side');
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
	Actions.get();
});