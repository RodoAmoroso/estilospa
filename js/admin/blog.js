var Glossary = {
	get:function(){
		$('#glossary').html('');
		ajax('admin/glossary-groups/get',{glossaryid:$('#fd_groups_search').val()})
			.then(function(data){
				$.each(data.results,function(k,v){

					var mod = $('#mod_panel').clone();
					mod.removeClass('dp-none').removeAttr('id');
					mod.find('.panel-heading').html(v.name+' | <i class="fa fa-caret-square-o-down clickable" data-target="#group_body_'+v.id+'" data-toggle="collapse" title="Plegar/Desplegar Todos"></i> | <i data-toogle="check" class="fa fa-square clickable" title="Marcar/Desmarcar Todos"></i>');
					mod.find('.panel-body').addClass('collapse in').attr('id','group_body_'+v.id);
					
					$.each(data.glossary[k],function(kg,vg){
						var item = $('#mod_panel_group_item').clone();
						item.removeAttr('id').removeClass('dp-none').addClass('clickable').attr('data-id',vg.id);
						item.find('span').text(vg.name);
						item.find('.edit,.delete,.view').remove();
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
		ajax('admin/blog-categories/get',{})
			.then(function(DATA){
				$.each(DATA.results,function(k,v){
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
		ajax('admin/blog-categories/find',{ID:Categories.ID})
			.then(function(DATA){
				$('#list_categories_edit [data-id="'+Categories.ID+'"]').effect('transfer',{to:$('#fd_category_name')},function(){
					$('#fd_category_name').val(DATA.result.name);
				});
			});
	},
	save:function(){
		ajax('admin/blog-categories/save',{
			Name:$('#fd_category_name').val(),
			ID:Categories.ID
		})
			.then(function(DATA){
				Categories.get();
				Categories.reset();
			});
	},
	delete:function(){
		ajax('admin/blog-categories/delete',{ID:Categories.ID},function(DATA){
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
			ajax('admin/blog-categories/reorder',{ArrID:arr})
				.then(function(DATA){Categories.reset()});
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
		ajax('admin/blog/get',{keywords:$('#fd_search').val(),idcategory:$('#select_categories').val()})
			.then(function(DATA){
				$.each(DATA.results,function(k,v){
					var mod = $('#mod_card').clone();
					mod.removeClass('dp-none').removeAttr('id');
					mod.attr('data-id',v.id);
					mod.find('h1').text(v.title);
					mod.find('.edit,.delete,.preview').attr('data-id',v.id);
					mod.find('p').html(v.fecha+' &bullet; Vistas: '+v.views);
					var img = $.parseJSON(v.gallery);
					if(img[0].video == undefined){
						mod.find('.thumb').css({backgroundImage:'url('+ROOT+'img/blog/'+img[0].photoname+'-t.'+img[0].extension+')'});
					}else{
						GetYoutubeApi(img[0].video,function(data){
							mod.find('.thumb').css({backgroundImage:'url('+data.items[0].snippet.thumbnails.medium.url+')'});
						});
					}
					$('#blog').append(mod);
				});				
			});
	},
	find:function(){
		ajax('admin/blog/find',{blogid:Blog.ID})
			.then(function(DATA){
				$('#fd_title').val(DATA.result.title);
				$('#fd_subtitle').val(DATA.result.subtitle);
				$('#fd_shortdescription').val(DATA.result.shortdescription);
				$('#fd_date').val(DATA.result.fecha);
				CKEDITOR.instances.fd_content.setData(DATA.result.content);
				//////////// GLOSSARY /////////////////////////////////
				var glossary = DATA.result.glossary.split(',');
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
				var gallery = $.parseJSON(DATA.result.gallery);
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
				gallery.push({photoname:$(this).attr('data-filename'),extension:$(this).attr('data-extension')})
			}
		});
		if(gallery.length==0){
			Messages(true,'Debes subir al menos una imagen');
			$('.nav-tabs [href="#tab_gallery"]').trigger('click');
			return false;
		}		
		ajax('admin/blog/save',{
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
		})
			.then(function(DATA){
				Blog.reset();
				Blog.get();
				$('#btn_cancel').trigger('click');
			});
	},
	delete:function(){
		ajax('admin/blog/delete',{ID:Blog.ID})
			.then(function(DATA){
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
		mod.css({backgroundImage:'url('+ROOT+'img/blog/'+PHTNM+'-t.'+EXT+')'});
		mod.attr({'data-filename':PHTNM,'data-extension':EXT});
		mod.find('.dp-table').remove();
		$('#gallery').append(mod);
	},
	buildvideos:function(ID){
		GetYoutubeApi(ID,function(data){
			var mod = $('#mod_thumb').clone();
			mod.removeClass('dp-none').addClass('dp-ib').removeAttr('id');
			mod.css({backgroundImage:'url('+data.items[0].snippet.thumbnails.medium.url+')'});
			mod.attr({'data-video':ID});
			mod.find('.dp-table').remove();
			$('#gallery').append(mod);
		});		
	},
	init:function(){

		$('#btn_cancel').click(function(){
			Blog.reset();
			$('#list_panel').slideDown({easing:'easeInOutCubic',duration:900});
			$('#edit_panel').slideUp({easing:'easeInOutCubic',duration:900});
		});		
		$('#btn_new').click(function(){
			$('#list_panel').slideUp({easing:'easeInOutCubic',duration:900});
			$('#edit_panel').slideDown({easing:'easeInOutCubic',duration:900});
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

		$('#blog').on('click','.delete',function(){
			var id = $(this).attr('data-id');
			Swal.fire({
				type:'warning',
				text:'¿Realmente deseas borrar esta noticia?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Blog.ID = id;
						Blog.delete();
					}
				});
		});
		$('#blog').on('click','.edit',function(){
			var id = $(this).attr('data-id');
			$('#btn_new').trigger('click');
			Blog.ID = id;
			Blog.find();
		});
		$('#blog').on('click','.preview',function(){
			var id = $(this).attr('data-id');
			window.open(ROOT+'blog-pagina/'+id+'-'+$('.mod-card[data-id="'+id+'"]').find('h1').text().permalink());
		});


		$('#fd_date').datepicker({
			dateFormat:'dd/mm/yy'
		});


		$('#fd_content').ckeditor({
			language:'es',
			height:540,
			contentsCss:['https://fonts.googleapis.com/css?family=Fira+Sans+Condensed:300,400,700|Raleway:300,400,700',ROOT+'css/bootstrap.min.css',ROOT+'css/styles.css'],
			allowedContent:true,
			toolbar:'MyToolBar',
			toolbar_MyToolBar:[['Bold','Italic','Underline','RemoveFormat'],['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],['FontSize','TextColor','BGColor'],['Link','Unlink'],['NumberedList','Bulletedist','Outdent','Indent','Blockquote'],['Cut','Copy','Paste','PasteText','PasteFromWord'],['Link','Unlink','Iframe','Image']]
		});
		$('#fd_shortdescription').keyup(function(){
			$('shortchar').text(500-$(this).val().length);
		});


		var upimage = new UpFile({
			container:'[data-input="image"]',
			controller:'admin/blog/insertimage',
			folder:'img/blog',
			callback:function(data){
				var file = ROOT+'img/blog/'+data[0].filename+'.'+data[0].extension;
				CKEDITOR.instances.fd_content.insertHtml('<p><img src="'+file+'" data-cke-saved-src="'+file+'" style="max-width:100%"></p>');
			}
		});
		var upgallery = new UpFile({
			container:'[data-input="gallery"]',
			controller:'admin/blog/gallery',
			folder:'img/blog',
			gallery:'#gallery',
			sortable:true			
		});


		$('#btn_add_video').click(function(){
			CheckFields(['#fd_video'],function(){
				var idyoutube = GetIDVideo($('#fd_video').val(),'youtube');
				if(idyoutube){					
					Blog.buildvideos(idyoutube);
					$('#fd_video').val('');					
				}else{
					Swal.fire({type:'warning',text:'La URL ingresada es errónea. Asegúrate de que esté bien escrita.'});
				}			
			});
		});

		

		$('#btn_save').click(function(){
			if($('#fd_title').val() == '' || $('#fd_subtitle').val() == '' || $('#fd_shortdescription').val() == '' || $('#fd_date').val() == ''){
				$('.nav-tabs [href="#tab_general"]').trigger('click');
			}
			CheckFields(['#fd_title','#fd_date','#fd_subtitle','#fd_shortdescription'],function(){
				if(CKEDITOR.instances.fd_content.getData() == ''){
					Swal.fire({type:'warning',text:'Debes ingresar el contenido de la noticia'});
				}else{
					Blog.save();
				}
			});
		});

		
		$('#btn_delete').click(function(){
			Blog.delete();
		});

		SearchSuggestions('#form_search','admin/blog/get','get',Blog.get);

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