
var Glossary = {
	get:function(){
		$('#glossary').html('');
		AjaxConnection('jxGlossary.php',{Mode:'getgroups',IDG:$('#fd_groups_search').val()},function(DATA){
			$.each(DATA.Results,function(k,v){				
				var mod = $('#mod_panel').clone();
				mod.removeClass('dp-none').removeAttr('id');
				mod.find('.panel-heading').html(v.name+' | <i class="fa fa-caret-square-o-down clickable" data-target="#group_body_'+v.id+'" data-toggle="collapse" title="Plegar/Desplegar Todos"></i> | <i data-toogle="check" class="fa fa-square clickable" title="Marcar/Desmarcar Todos"></i>');
				mod.find('.panel-body').addClass('collapse in').attr('id','group_body_'+v.id);
				$.each(DATA.Glossary[k],function(kg,vg){
					var item = $('#mod_panel_group_item').clone();
					item.removeAttr('id').removeClass('dp-none').addClass('clickable').attr('data-id',vg.id);
					item.find('span').text(vg.name);
					item.find('.edit,.delete').remove();
					mod.find('.list-group').append(item);
				});
				$('#glossary').append(mod);				
			});
			$('#glossary .item').unbind('click').click(function(){
				$(this).find('i').toggleClass('fa-square fa-check-square');
			});
			$('#glossary [data-toogle="check"]').unbind('click').click(function(){
				$(this).toggleClass('fa-square fa-check-square');
				if($(this).hasClass('fa-check-square')){
					$(this).parent().parent().find('.panel-body .fa').removeClass('fa-square').addClass('fa-check-square');
				}else{
					$(this).parent().parent().find('.panel-body .fa').addClass('fa-square').removeClass('fa-check-square');
				}
			});
		});
	},
	init:function(){
		$('#btn_refresh_glossary').click(function(){
			Glossary.get();
		});
		$('#btn_collapse_glossary').click(function(){
			if($(this).attr('data-collapse')=='false'){
				$('#glossary .panel-body').removeClass('in');
				$(this).attr('data-collapse','true');
			}else{
				$('#glossary .panel-body').addClass('in');
				$(this).attr('data-collapse','false');
			}
		});	
		$('#btn_check_glossary').click(function(){
			if($(this).attr('data-check')=='false'){
				$('#glossary .item i').removeClass('fa-check-square').addClass('fa-square');
				$(this).attr('data-check','true');
			}else{
				$('#glossary .item i').addClass('fa-check-square').removeClass('fa-square');
				$(this).attr('data-check','false');
			}
		});
		Glossary.get();
	}
}
var Categories = {
	ID:0,
	get:function(){
		$('#fd_category,#list_categories_edit,#select_categories').html('');
		$('#select_categories').html('<option value="0">-- Todas --</option>');
		AjaxConnection('jxBlog.php',{Mode:'getcategories'},function(DATA){
			$.each(DATA.Results,function(k,v){
				var mod = $('#mod_list').clone();
				mod.removeAttr('id').removeClass('dp-none').attr('data-id',v.id);
				mod.find('h4').html(v.name);
				mod.find('.add').remove();
				mod.find('.edit,.delete').attr('data-id',v.id);
				$('#list_categories_edit').append(mod);
				$('#fd_category,#select_categories').append('<option value="'+v.id+'">'+v.name+'</option>');
			});			
			$('#list_categories_edit .edit').unbind('click').click(function(){
				$('#list_categories_edit .mod-list').removeClass('active');
				$(this).parent().parent().addClass('active');
				var id = $(this).attr('data-id');
				Categories.ID = id;
				Categories.find();
			});
			$('#list_categories_edit .delete').unbind('click').click(function(){
				var id = $(this).attr('data-id');
				Messages(true,'¿Seguro deseas borrar esta categoría?',function(){
					Categories.ID = id;
					Categories.delete();
				});
			});
		});
	},
	find:function(){
		AjaxConnection('jxBlog.php',{Mode:'findcategory',ID:Categories.ID},function(DATA){
			$('#list_categories_edit [data-id="'+Categories.ID+'"]').effect('transfer',{to:$('#fd_category_name')},function(){
				$('#fd_category_name').val(DATA.Result.name);
			});			
		});
	},
	save:function(){
		AjaxConnection('jxBlog.php',{Mode:'savecategory',Name:$('#fd_category_name').val(),ID:Categories.ID},function(DATA){
			Categories.get();
			Categories.reset();
		});
	},
	delete:function(){
		AjaxConnection('jxBlog.php',{Mode:'deletecategory',ID:Categories.ID},function(DATA){
			Categories.reset();
			Categories.get();
		});
	},
	reset:function(){
		$('#fd_category_name').val('');
		Categories.ID = 0;
	},
	init:function(){
		$('#btn_save_category').click(function(){
			CheckFields(['#fd_category_name'],Categories.save);
		});
		Categories.get();
		$('#list_categories_edit').sortable({update:function(e,ui){
			var arr = [];
			$.each($('#list_categories_edit .mod-list'),function(k,v){
				arr.push($(this).attr('data-id'));
			});
			AjaxConnection('jxBlog.php',{Mode:'reordercategory',ArrID:arr},function(DATA){Categories.reset()});
		}});
		$('#btn_category').click(function(){			
			$('#edit_panel').slideUp({easing:'easeInOutCubic',duration:900});
			$('#edit_categories').slideDown({easing:'easeInOutCubic',duration:900});
		});
		$('#btn_close_category').click(function(){
			$('#edit_panel').slideDown({easing:'easeInOutCubic',duration:900});
			$('#edit_categories').slideUp({easing:'easeInOutCubic',duration:900});
		});
	}
}
var Blog = {
	ID:0,
	get:function(){
		$('#blog').html('');		
		AjaxConnection('jxBlog.php',{Mode:'get',Keywords:$('#fd_search').val(),IDCategory:$('#select_categories').val()},function(DATA){
			$.each(DATA.Results,function(k,v){
				var mod = $('#mod_card').clone();
				mod.removeClass('dp-none').removeAttr('id');
				mod.attr('data-id',v.id);
				mod.find('h1').text(v.title);
				mod.find('.edit,.delete,.preview').attr('data-id',v.id);
				mod.find('p').html(v.fecha+' &bullet; Vistas: '+v.views);
				var img = $.parseJSON(v.gallery);
				if(img[0].video == undefined){
					mod.find('.thumb').css({backgroundImage:'url('+ROOTPATH+'img/blog/'+img[0].photoname+'-t.'+img[0].extension+')'});
				}else{
					GetYoutubeApi(img[0].video,function(data){
						mod.find('.thumb').css({backgroundImage:'url('+data.items[0].snippet.thumbnails.medium.url+')'});
					});
				}
				$('#blog').append(mod);
			});
			$('#blog .delete').unbind('click').click(function(){
				var id = $(this).attr('data-id');
				Messages(true,'¿Realmente deseas borrar esta noticia?',function(){
					Blog.ID = id;
					Blog.delete();
				});				
			});
			$('#blog .edit').unbind('click').click(function(){
				var id = $(this).attr('data-id');
				$('#btn_new').trigger('click');
				Blog.ID = id;
				Blog.find();
			});
			$('#blog .preview').unbind('click').click(function(){
				var id = $(this).attr('data-id');
				window.open(ROOTPATH+'blog-pagina/'+id+'-'+Permalink($('.mod-card[data-id="'+id+'"]').find('h1').text()));
			});
		});
	},
	find:function(){
		AjaxConnection('jxBlog.php',{Mode:'find',ID:Blog.ID},function(DATA){
			$('#fd_title').val(DATA.Result.title);
			$('#fd_subtitle').val(DATA.Result.subtitle);
			$('#fd_shortdescription').val(DATA.Result.shortdescription);
			$('#fd_date').val(DATA.Result.fecha);
			CKEDITOR.instances.fd_content.setData(DATA.Result.content);
			//////////// GLOSSARY /////////////////////////////////
			var glossary = DATA.Result.glossary.split(',');
			$.each($('#glossary .list-group-item'),function(kb,vb){
				var passglossary = false;
				$.each(glossary,function(kt,vt){
					if($(vb).attr('data-id')==vt){
						passglossary = true;
					}
				});
				if(passglossary){
					$(vb).find('i').removeClass('fa-square').addClass('fa-check-square');
				}else{
					$(vb).find('i').addClass('fa-square').removeClass('fa-check-square');
				}
			});
			///////////// GALLERY //////////////////////
			var gallery = $.parseJSON(DATA.Result.gallery);
			$.each(gallery,function(k,v){
				if(v.video != undefined){		
					Blog.buildvideos(v.video);					
				}else{
					Blog.buildthumbs(v.photoname,v.extension);
				}
			});
		});
	},
	save:function(){
		var glossary = [];
		$.each($('#glossary .list-group-item'),function(k,v){
			if($(this).find('i').hasClass('fa-check-square')){
				glossary.push($(this).attr('data-id'));
			}
		});
		var gallery = [];
		$.each($('#gallery .thumbnail'),function(k,v){
			if($(this).attr('data-video') != undefined){
				gallery.push({video:$(this).attr('data-video')});
			}else{
				gallery.push({photoname:$(this).attr('data-photoname'),extension:$(this).attr('data-extension')})
			}
		});
		if(gallery.length==0){
			Messages(true,'Debes subir al menos una imagen');
			$('.nav-tabs [href="#tab_gallery"]').trigger('click');
			return false;
		}		
		AjaxConnection('jxBlog.php',{
			Mode:'save',
			ID:Blog.ID,
			Title:$('#fd_title').val(),
			Subtitle:$('#fd_subtitle').val(),
			ShortDescription:$('#fd_shortdescription').val(),
			Content:CKEDITOR.instances.fd_content.getData(),
			Date:$('#fd_date').val(),
			Glossary:glossary,
			Gallery:gallery,
			IDCategory:$('#fd_category').val()
		},function(DATA){
			Blog.reset();
			Blog.get();
			$('#btn_cancel').trigger('click');
		});
	},
	delete:function(){
		AjaxConnection('jxBlog.php',{Mode:'delete',ID:Blog.ID},function(DATA){
			Blog.reset();
			Blog.get();
		});
	},
	reset:function(){
		$('#fd_title,#fd_subtitle,#fd_shortdescription,#fd_date').val('');
		CKEDITOR.instances.fd_content.setData('');
		$('#gallery').html('');
		Glossary.get();
		Blog.ID = 0;
	},
	buildthumbs:function(PHTNM,EXT){
		var mod = $('#mod_thumb').clone();
		mod.removeClass('dp-none').addClass('dp-ib').removeAttr('id');
		mod.css({backgroundImage:'url('+ROOTPATH+'img/blog/'+PHTNM+'-t.'+EXT+')'});
		mod.attr({'data-photoname':PHTNM,'data-extension':EXT});
		mod.find('.dp-table').remove();
		$('#gallery').append(mod);
		$('#gallery .delete').unbind('click').click(function(){
			$(this).parent().parent().remove();
		});
	},
	buildvideos:function(ID){
		GetYoutubeApi(ID,function(data){
			var mod = $('#mod_thumb').clone();
			mod.removeClass('dp-none').addClass('dp-ib').removeAttr('id');
			mod.css({backgroundImage:'url('+data.items[0].snippet.thumbnails.medium.url+')'});
			mod.attr({'data-video':ID});
			mod.find('.dp-table').remove();
			$('#gallery').append(mod);
			$('#gallery .delete').unbind('click').click(function(){
				$(this).parent().parent().remove();
			});
		});		
	},
	init:function(){
		$('#fd_date').datepicker();
		$('#fd_content').ckeditor({
			language:'es',
			height:540,
			contentsCss:['https://fonts.googleapis.com/css?family=Fira+Sans+Condensed:300,400,700|Raleway:300,400,700',ROOTPATH+'css/bootstrap.min.css',ROOTPATH+'css/styles.css'],
			allowedContent:true,
			toolbar:'MyToolBar',
			toolbar_MyToolBar:[['Bold','Italic','Underline','RemoveFormat'],['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],['FontSize','TextColor','BGColor'],['Link','Unlink'],['NumberedList','Bulletedist','Outdent','Indent','Blockquote'],['Cut','Copy','Paste','PasteText','PasteFromWord'],['Link','Unlink','Iframe','Image']]
		});
		$('#fd_shortdescription').keyup(function(){
			$('shortchar').text(500-$(this).val().length);
		});
		$('#btn_insertimage').click(function(){
		});
		UpFile.Init({MODE:'insertimage',PHP:'jxBlog.php',FOLDER:'img/blog/',FORM:'#form_insertimage',BTN:'#btn_insertimage',Callback:function(ArrFiles){
			var file = ROOTPATH+'img/blog/'+ArrFiles[0].photoname+'.'+ArrFiles[0].extension;
			CKEDITOR.instances.fd_content.insertHtml('<p><img src="'+file+'" data-cke-saved-src="'+file+'" style="max-width:100%"></p>');
		}});
		UpFile.Init({MODE:'upgallery',PHP:'jxBlog.php',FOLDER:'img/blog/',FORM:'#form_gallery',BTN:'#btn_gallery',Callback:function(ArrFiles){
			$.each(ArrFiles,function(k,v){
				Blog.buildthumbs(v.photoname,v.extension);
			});
		}});
		$('#gallery').sortable({});
		$('#btn_add_video').click(function(){
			CheckFields(['#fd_video'],function(){
				var idyoutube = GetIDVideo($('#fd_video').val(),'youtube');
				if(idyoutube){					
					Blog.buildvideos(idyoutube);
					$('#fd_video').val('');					
				}else{
					Messages(true,'La URL ingresada es errónea. Asegúrate de que esté bien escrita.');
				}			
			});
		});
		$('.nav-tabs a').click(function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			var li = $(this).parent();
			$('.nav-tabs li').not(li).removeClass('active');
			li.addClass('active');
			$('body,html').animate({scrollTop:$('.page-header').offset().top},{duration:900,easing:'easeInOutCubic'});
			$('#main_content .tab-panel').not(href).slideUp({easing:'easeInOutCubic',duration:900});		
			$(href).slideDown({easing:'easeInOutCubic',duration:900});
		});
		$('#btn_save').click(function(){
			if($('#fd_title').val() == '' || $('#fd_subtitle').val() == '' || $('#fd_shortdescription').val() == '' || $('#fd_date').val() == ''){
				$('.nav-tabs [href="#tab_general"]').trigger('click');
			}
			CheckFields(['#fd_title','#fd_date','#fd_subtitle','#fd_shortdescription'],function(){
				if(CKEDITOR.instances.fd_content.getData() == ''){
					Messages(true,'Debes ingresar el contenido de la noticia');
				}else{
					Blog.save();
				}
			});
		});
		$('#btn_cancel').click(function(){
			Blog.reset();
			$('#list_panel').slideDown({easing:'easeInOutCubic',duration:900});
			$('#edit_panel').slideUp({easing:'easeInOutCubic',duration:900});
		});		
		$('#btn_new').click(function(){
			$('#list_panel').slideUp({easing:'easeInOutCubic',duration:900});
			$('#edit_panel').slideDown({easing:'easeInOutCubic',duration:900});
		});
		$('#btn_delete').click(function(){
			Blog.delete();
		});
		SearchSuggestions('#form_search','jxBlog.php','get',Blog.get);
		$('#form_search').submit(function(e){
			e.preventDefault();
			Blog.get();
		});
		$('#select_categories').change(function(){
			Blog.get();
		});
		ModViews();
		///////////////////////////////////////
		Blog.get();		
	}
}
$(function(){	
	Glossary.init();
	Categories.init();
	Blog.init();
});