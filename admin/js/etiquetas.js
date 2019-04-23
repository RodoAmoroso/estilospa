var Groups = {
	ID:0,
	get:function(){
		$('#mod_groups,#fd_groups,#fd_groups_search').html('');
		$('#fd_groups_search').html('<option value="0" >-- Todas --</option>')
		AjaxConnection('jxGlossary.php',{Mode:'getgroups'},function(DATA){			
			$.each(DATA.Results,function(k,v){
				var mod = $('#mod_list').clone();
				mod.removeClass('dp-none').removeAttr('id').attr('data-id',v.id);
				mod.find('.add').remove();
				mod.find('h4').text(v.name);
				mod.find('.edit,.delete').attr('data-id',v.id);
				$('#mod_groups').append(mod);
				$('#fd_groups,#fd_groups_search').append('<option value="'+v.id+'">'+v.name+'</option>');
			});
			$('#mod_groups .edit').unbind('click').click(function(){
				Groups.reset();
				var id = $(this).attr('data-id');
				$('#mod_groups .mod-list').removeClass('active');
				$(this).parent().parent().addClass('active');
				$(this).parent().parent().effect('transfer',{to:$('#fd_group_name')});
				Groups.ID = id;				
				Groups.find();
			});
			$('#mod_groups .delete').unbind('click').click(function(){
				var id = $(this).attr('data-id');
				Messages(true,'¿Seguro deseas borrar este item?',function(){
					Groups.ID = id;
					Groups.delete();
				});
			});
			$('#fd_groups_search').unbind('change').change(function(){
				Glossary.get();
			});
			$('#mod_groups').sortable({
				update:function(){
					var arr = [];
					$.each($(this).find('.mod-list'),function(k,v){
						arr.push($(this).attr('data-id'));
					});
					AjaxConnection('jxGlossary.php',{Mode:'reordergroup',ArrID:arr},function(DATA){Glossary.get()});
				}
			});
		});
	},
	save:function(){
		AjaxConnection('jxGlossary.php',{
			Mode:'savegroup',
			Name:$('#fd_group_name').val(),			
			ID:Groups.ID
		},function(){
			Groups.reset();
			Groups.get();
		});
	},
	delete:function(){
		AjaxConnection('jxGlossary.php',{Mode:'deletegroup',ID:Groups.ID},function(DATA){
			if(DATA.Status=='fail'){Messages(true,'Hubo problemas al procesar la solicitud. Intenta nuevamente más tarde.');return;};
			Groups.reset();
			Groups.get();
		});
	},
	reset:function(){
		$('#fd_group_name').val('');
		$('#mod_groups h4').find('i').remove();
		Groups.ID = 0;
	},
	find:function(){
		AjaxConnection('jxGlossary.php',{Mode:'findgroup',ID:Groups.ID},function(DATA){
			$('#fd_group_name').val(DATA.Result.name);
		});
	}	
}
var Glossary = {
	ID:0,
	ArrID:[],
	get:function(){
		$('#mod_glossary').html('');
		AjaxConnection('jxGlossary.php',{Mode:'getgroups',IDG:$('#fd_groups_search').val()},function(DATA){
			$.each(DATA.Results,function(k,v){
				var mod = $('#mod_panel').clone();
				mod.removeClass('dp-none').removeAttr('id');
				mod.find('.panel-heading').html(v.name+' <i class="fa fa-caret-down fa-fw clickable" data-target="#group_body_'+v.id+'" data-toggle="collapse"></i>');
				mod.find('.panel-body').addClass('collapse in').attr('id','group_body_'+v.id);
				$.each(DATA.Glossary[k],function(kg,vg){
					var item = $('#mod_panel_group_item').clone();
					item.removeAttr('id').removeClass('dp-none').attr('data-id',vg.id);
					item.find('span').text(vg.name);
					item.find('.edit,.delete').attr('data-id',vg.id);
					mod.find('.list-group').append(item);
				});
				$('#mod_glossary').append(mod);				
			});
			$('#mod_glossary .list-group').sortable({
				update:function(){
					var arr = [];
					$.each($(this).parent().find('.list-group-item'),function(k,v){
						arr.push($(this).attr('data-id'));
					});
					AjaxConnection('jxGlossary.php',{Mode:'reorder',ArrID:arr},function(DATA){});
				}
			});
			$('#mod_glossary .item i').click(function(){
				$(this).toggleClass('fa-square fa-check-square');
			});
			$('#mod_glossary .edit').unbind('click').click(function(){
				$('#btn_new').trigger('click');
				var id = $(this).attr('data-id');
				Glossary.ID = id;
				Glossary.find();
			});
			$('#mod_glossary .delete').unbind('click').click(function(){
				var id = $(this).attr('data-id');
				Messages(true,'¿Seguro deseas borrar este item?',function(){
					Glossary.ArrID.push(id);
					Glossary.delete();
				});
			});
		});
	},
	save:function(){
		if(CKEDITOR.instances.fd_description.getData() == ''){
			Messages(true,'Debes incluir alguna descripción');
			return;
		}
		if($('#header').attr('data-photoname') == undefined){
			Messages(true,'Debes subir una imagen de cabecera primero!');
			return;
		}
		AjaxConnection('jxGlossary.php',{
			Mode:'save',
			ID:Glossary.ID,
			Name:$('#fd_name').val(),
			IDGroup:$('#fd_groups').val(),
			IMG:{photoname:$('#header').attr('data-photoname'),extension:$('#header').attr('data-extension')},
			Description:CKEDITOR.instances.fd_description.getData()
		},function(DATA){
			$('#btn_cancel').trigger('click');
			Glossary.reset();
			Glossary.get();
		});
	},
	reset:function(){
		$('#fd_name').val('');
		CKEDITOR.instances.fd_description.setData('');
		Glossary.ID = 0;
		Glossary.ArrID = [];
		$('#header').removeAttr('data-photoname').removeAttr('data-extension').css({backgroundImage:'none'});
	},
	delete:function(){
		AjaxConnection('jxGlossary.php',{Mode:'delete',ArrID:Glossary.ArrID},function(DATA){
			if(DATA.Status=='fail'){Messages(true,'Hubo problemas al procesar la solicitud. Intenta nuevamente más tarde.');return;};
			Glossary.reset();
			Glossary.get();
		});
	},
	find:function(){
		AjaxConnection('jxGlossary.php',{Mode:'find',ID:Glossary.ID},function(DATA){
			$('#fd_name').val(DATA.Result.name);
			CKEDITOR.instances.fd_description.setData(DATA.Result.description);
			$('#fd_groups option[value="'+DATA.Result.idgroup+'"]').prop('selected',true);
			if(DATA.Result.image != ''){
				var img = $.parseJSON(DATA.Result.image);
				$('#header').attr({'data-photoname':img.photoname,'data-extension':img.extension}).css({backgroundImage:'url('+ROOT+'img/glossary/'+img.photoname+'.'+img.extension+')'});
			}
		});
	}
}
$(function(){
	UpFile.Init({MODE:'upimage',FORM:'#form_image',BTN:'#btn_image',PHP:'jxGlossary.php',FOLDER:'img/glossary/',Callback:function(ArrFiles){
			$.each(ArrFiles,function(ki,vi){
				var file = ROOT+'img/glossary/'+vi.photoname+'.'+vi.extension;
				if(vi.extension == 'jpg' || vi.extension == 'jpeg' || vi.extension == 'gif' || vi.extension == 'png'){
					CKEDITOR.instances.fd_description.insertHtml('<p><img src="'+file+'" data-cke-saved-src="'+file+'" style="max-width:100%"></p>');
				}else{
					CKEDITOR.instances.fd_description.insertHtml('<p><a data-cke-saved-href="'+file+'" href="'+file+'" class="btn btn-default" >'+vi.photoname+'.'+vi.extension+'</a></p>');
				}
			});	
		}
	});
	UpFile.Init({MODE:'upheader',FORM:'#form_header',BTN:'#btn_header',PHP:'jxGlossary.php',FOLDER:'img/glossary/',TH:'#header'});
	$('#btn_edit_groups,#btn_edit_groups_search').click(function(){
		$('#edit_panel,#list_panel').slideUp({duration:900,easing:'easeInOutCubic'});
		$('#groups_panel').slideDown({duration:900,easing:'easeInOutCubic'});
	});
	$('#btn_cancel').click(function(){
		$('#edit_panel').slideUp({duration:900,easing:'easeInOutCubic'});
		$('#list_panel').slideDown({duration:900,easing:'easeInOutCubic'});
		Glossary.reset();
	});
	$('#btn_save').click(function(){
		CheckFields(['#fd_name'],Glossary.save);
	});
	$('#btn_new').click(function(){
		$('#edit_panel').slideDown({duration:900,easing:'easeInOutCubic'});
		$('#list_panel').slideUp({duration:900,easing:'easeInOutCubic'});
	});
	$('#btn_delete').click(function(){
		Messages(true,'¿Seguro deseas borrar este item?',Glossary.delete);
	});
	$('#btn_close_groups').click(function(){
		$('#edit_panel').slideDown({duration:900,easing:'easeInOutCubic'});
		$('#groups_panel').slideUp({duration:900,easing:'easeInOutCubic'});
		Groups.reset();
	});
	$('#fd_description').ckeditor({
		language:'es',
		height:400,
		contentsCss:['https://fonts.googleapis.com/css?family=Fira+Sans+Condensed:300,400,700|Raleway:300,400,700',ROOT+'css/bootstrap.min.css',ROOT+'css/styles.css'],
		allowedContent:true,
		toolbar:'MyToolBar',
		toolbar_MyToolBar:[['Bold','Italic','Underline','RemoveFormat'],['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],['FontSize','TextColor','BGColor'],['Link','Unlink'],['NumberedList','Bulletedist','Outdent','Indent','Blockquote'],['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'],['Link','Unlink','Anchor']]
	});
	////////////////////////////////////////////
	$('#btn_save_group').click(function(){
		CheckFields(['#fd_group_name'],Groups.save);
	});
	$('#form_search').submit(function(e){
		e.preventDefault();
		Glossary.get();
	});
	$('#select_all').click(function(){
		$(this).find('i').toggleClass('fa-square fa-check-square');
		if($(this).find('i').hasClass('fa-square')){
			$("#mod_glossary .item i").removeClass('fa-check-square').addClass('fa-square');
		}else{
			$("#mod_glossary .item i").addClass('fa-check-square').removeClass('fa-square');
		}
	});
	$('#btn_delete_selected').click(function(){
		var arr = [];
		$.each($('#mod_glossary .item'),function(k,v){
			if($(this).find('i').hasClass('fa-check-square')){
				arr.push($(this).parent().attr('data-id'));
			}
		});
		Messages(true,'¿Seguro deseas borrar estos items?',function(){
			Glossary.ArrID = arr;
			Glossary.delete();
		});
	});
	///SearchSuggestions('#form_search','jxGlossary.php','get',Glossary.get);
	///////////////////////////////////////////
	Groups.get();
	Glossary.get();
});