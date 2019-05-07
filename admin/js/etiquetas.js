var Groups = {
	ID:0,
	get:function(){
		$('#mod_groups,#fd_groups,#fd_groups_search').html('');
		$('#fd_groups_search').html('<option value="0" >-- Todas --</option>')
		ajax('admin/glossary-groups/get')
			.then(function(DATA){
				$.each(DATA.results,function(k,v){
					var mod = $('#mod_list').clone();
					mod.removeClass('dp-none').removeAttr('id').attr('data-id',v.id);
					mod.find('.add').remove();
					mod.find('h4').text(v.name);
					mod.find('.edit,.delete').attr('data-id',v.id);
					$('#mod_groups').append(mod);
					$('#fd_groups,#fd_groups_search').append('<option value="'+v.id+'">'+v.name+'</option>');
				});
				
				$('#mod_groups').sortable({
					update:function(){
						var arr = [];
						$.each($(this).find('.mod-list'),function(k,v){
							arr.push($(this).attr('data-id'));
						});
						ajax('admin/glossary-groups/reorder',{ArrID:arr})
							.then(function(DATA){Glossary.get()});
					}
				});
			});
	},
	save:function(){
		ajax('admin/glossary-groups/save',{
			Name:$('#fd_group_name').val(),			
			ID:Groups.ID
		})
			.then(function(){
				Groups.reset();
				Groups.get();
			});
	},
	delete:function(){
		ajax('admin/glossary-groups/delete',{ID:Groups.ID})
			.then(function(DATA){
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
		ajax('admin/glossary-groups/find',{ID:Groups.ID})
			.then(function(DATA){
				$('#fd_group_name').val(DATA.result.name);
			});
	},
	init:function(){

		$('#btn_close_groups').click(function(){
			$('#edit_panel').slideDown({duration:900,easing:'easeInOutCubic'});
			$('#groups_panel').slideUp({duration:900,easing:'easeInOutCubic'});
			Groups.reset();
		});
		$('#btn_edit_groups,#btn_edit_groups_search').click(function(){
			$('#edit_panel,#list_panel').slideUp({duration:900,easing:'easeInOutCubic'});
			$('#groups_panel').slideDown({duration:900,easing:'easeInOutCubic'});
		});
		$('#btn_save_group').click(function(){
			CheckFields(['#fd_group_name'],Groups.save);
		});

		$('#mod_groups').on('click','.edit',function(){
			Groups.reset();
			var id = $(this).attr('data-id');
			$('#mod_groups .mod-list').removeClass('active');
			$(this).parent().parent().addClass('active');
			$(this).parent().parent().effect('transfer',{to:$('#fd_group_name')});
			Groups.ID = id;
			Groups.find();
		});
		$('#mod_groups').on('click','.delete',function(){
			var id = $(this).attr('data-id');
			Swal.fire({
				text:'¿Seguro deseas borrar este item?',
				type:'warning',
				showCancelButton:true,
				reverseButtons:true
			}).then(function(response){
				if(response.value){
					Groups.ID = id;
					Groups.delete();
				}
			});
		});
		$('#fd_groups_search').change(function(){
			Glossary.get();
		});

		this.get();


	}
}
var Glossary = {
	ID:0,
	ArrID:[],
	get:function(){
		$('#mod_glossary').html('');
		ajax('admin/glossary-groups/get',{glossaryid:$('#fd_groups_search').val()})
			.then(function(DATA){
				$.each(DATA.results,function(k,v){
					var mod = $('#mod_panel').clone();
					mod.removeClass('dp-none').removeAttr('id');
					mod.find('.panel-heading').html(v.name+' <i class="fa fa-caret-down fa-fw clickable" data-target="#group_body_'+v.id+'" data-toggle="collapse"></i>');
					mod.find('.panel-body').addClass('collapse in').attr('id','group_body_'+v.id);
					$.each(DATA.glossary[k],function(kg,vg){
						var item = $('#mod_panel_group_item').clone();
						item.removeAttr('id').removeClass('dp-none').attr('data-id',vg.id);
						item.find('span').text(vg.name);
						item.find('.edit,.delete,.view').attr('data-id',vg.id);
						item.find('.view').attr('data-permalink',vg.name.permalink());
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
						ajax('admin/glossary/reorder',{ArrID:arr}).then(function(DATA){});
					}
				});
				
			});
	},
	save:function(){
		if(CKEDITOR.instances.fd_description.getData() == ''){
			Swal.fire({type:'warning',text:'Debes incluir alguna descripción'});
			return false;
		}
		if($('#header').attr('data-filename') == undefined){
			Swal.fire({type:'warning',text:'Debes subir una imagen de cabecera primero!'});
			return false;
		}
		ajax('admin/glossary/save',{
			Mode:'save',
			ID:Glossary.ID,
			Name:$('#fd_name').val(),
			IDGroup:$('#fd_groups').val(),
			IMG:{photoname:$('#header').attr('data-filename'),extension:$('#header').attr('data-extension')},
			Description:CKEDITOR.instances.fd_description.getData()
		})
			.then(function(DATA){
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
		$('#header').removeAttr('data-filename data-extension').css({backgroundImage:'none'});
	},
	delete:function(){
		ajax('admin/glossary/delete',{ArrID:Glossary.ArrID})
			.then(function(DATA){
				if(DATA.Status=='fail'){
					Swal.fire({type:'warning',text:'Hubo problemas al procesar la solicitud. Intenta nuevamente más tarde.'});
					return false;
				};
				Glossary.reset();
				Glossary.get();
			});
	},
	find:function(){
		ajax('admin/glossary/find',{ID:Glossary.ID})
			.then(function(DATA){
				$('#fd_name').val(DATA.result.name);
				CKEDITOR.instances.fd_description.setData(DATA.result.description);
				$('#fd_groups option[value="'+DATA.result.idgroup+'"]').prop('selected',true);
				if(DATA.result.image != ''){
					var img = $.parseJSON(DATA.result.image);
					$('#header').attr({'data-filename':img.photoname,'data-extension':img.extension}).css({backgroundImage:'url('+ROOT+'img/glossary/'+img.photoname+'.'+img.extension+')'});
				}
			});
	},
	init:function(){

		var upimage = new UpFile({
			container:'[data-input="image"]',
			controller:'admin/glossary/upimage',
			folder:'img/glossary',
			callback:function(data){
				$.each(data,function(ki,vi){
					var file = ROOT+'img/glossary/'+vi.filename+'.'+vi.extension;
					if(vi.extension == 'jpg' || vi.extension == 'jpeg' || vi.extension == 'gif' || vi.extension == 'png'){
						CKEDITOR.instances.fd_description.insertHtml('<p><img src="'+file+'" data-cke-saved-src="'+file+'" style="max-width:100%"></p>');
					}else{
						CKEDITOR.instances.fd_description.insertHtml('<p><a data-cke-saved-href="'+file+'" href="'+file+'" class="btn btn-default" >'+vi.filename+'.'+vi.extension+'</a></p>');
					}
				});	
			}
		});
		var upheader = new UpFile({
			container:'[data-input="header"]',
			controller:'admin/glossary/upheader',
			folder:'img/glossary',
			thumbnail:'#header'
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
			Swal.fire({
				type:'warning',
				text:'¿Seguro deseas borrar este item?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Glossary.delete();
					}
				});
		});


		$('#fd_description').ckeditor({
			language:'es',
			height:400,
			contentsCss:['https://fonts.googleapis.com/css?family=Fira+Sans+Condensed:300,400,700|Raleway:300,400,700',ROOT+'css/bootstrap.min.css',ROOT+'css/styles.css'],
			allowedContent:true,
			toolbar:'MyToolBar',
			toolbar_MyToolBar:[['Bold','Italic','Underline','RemoveFormat'],['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],['FontSize','TextColor','BGColor'],['Link','Unlink'],['NumberedList','Bulletedist','Outdent','Indent','Blockquote'],['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'],['Link','Unlink','Anchor']]
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
			Swal.fire({
				text:'¿Seguro deseas borrar estos items?',
				type:'warning',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Glossary.ArrID = arr;
						Glossary.delete();						
					}
				});
		});

		$('#form_search').submit(function(e){
			e.preventDefault();
			Glossary.get();
		});
		

		$('#mod_glossary').on('click','.item i',function(){
			$(this).toggleClass('fa-square fa-check-square');
		});
		$('#mod_glossary').on('click','.edit',function(){
			$('#btn_new').trigger('click');
			var id = $(this).attr('data-id');
			Glossary.ID = id;
			Glossary.find();
		});

		$('#mod_glossary').on('click','.view',function(){
			var id = $(this).attr('data-id');
			var permalink = $(this).attr('data-permalink');
			window.open(ROOT+'etiqueta/'+id+'-'+permalink);
		});

		$('#mod_glossary').on('click','.delete',function(){
			var id = $(this).attr('data-id');
			Swal.fire({
				text:'¿Seguro deseas borrar este item?',
				type:'warning',
				showCancelButton:true,
				reverseButtons:true
			})
			.then(function(response){
				if(response.value){
					Glossary.ArrID.push(id);
					Glossary.delete();					
				}
			});
		});

		this.get();

	}
}
$(function(){
	
	Groups.init();
	Glossary.init();
});