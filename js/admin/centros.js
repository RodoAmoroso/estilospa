var Types = {
	ID:0,
	get:function(){
		$('#list_types_edit,#list_types').html('');
		ajax('admin/clients-types/get')
			.then(function(DATA){
				$.each(DATA.results,function(k,v){
					var mod = $('#mod_list').clone();
					mod.removeClass('dp-none').removeAttr('id').attr('data-id',v.id);
					mod.find('.add').remove();
					mod.find('h4').text(v.name);
					mod.find('.edit,.delete').attr('data-id',v.id);
					$('#list_types_edit').append(mod);
					////////////////////////////////////////
					$('#list_types').append('<button data-id="'+v.id+'" class="list-group-item"><i class="fa fa-square"></i> '+v.name+'</button>');
				});

				$('#list_types_edit').sortable({
					update:function(){
						var arrid = [];
						$.each($('#list_types_edit .mod-list'),function(k,v){
							arrid.push($(this).attr('data-id'));
						});
						ajax('admin/clients-types/reorder',{ArrID:arrid})
							.then(function(){
								Types.get();
							});
					}
				});

			});
	},
	save:function(){
		ajax('admin/clients-types/save',{
			Name:$('#fd_type_name').val(),
			ID:Types.ID
		})
			.then(function(data){
				Types.reset();
				Types.get();
			});
	},
	delete:function(){
		ajax('admin/clients-types/delete',{ID:Types.ID})
			.then(function(DATA){
				Types.reset();
				Types.get();
			});
	},
	reset:function(){
		$('#fd_type_name').val('');
		$('#list_types_edit .mod-list').removeClass('active');
		Types.ID = 0;
	},
	find:function(){
		ajax('admin/clients-types/find',{ID:Types.ID})
			.then(function(DATA){
				$('#fd_type_name').val(DATA.result.name);
			});
	},
	init:function(){
		$('#btn_type').click(function(){
			$('#edit_panel').slideUp({duration:900,easing:'easeInOutCubic'});
			$('#type_panel').slideDown({duration:900,easing:'easeInOutCubic'});
		});
		$('#btn_close_types').click(function(){
			Types.reset();
			$('#edit_panel').slideDown({duration:900,easing:'easeInOutCubic'});
			$('#type_panel').slideUp({duration:900,easing:'easeInOutCubic'});
		});
		$('#btn_save_type').click(function(){
			CheckFields(['#fd_type_name'],Types.save);
		});

		$('#list_types').on('click','button',function(){
			$(this).find('i').toggleClass('fa-square fa-check-square');
		});
		$('#list_types_edit').on('click','.edit',function(){
			$('#list_types_edit .mod-list').removeClass('active');
			$(this).parent().parent().addClass('active');
			var id = $(this).attr('data-id');
			$(this).parent().parent().effect('transfer',{to:$('#fd_type_name')});
			Types.ID = id;
			Types.find();
		});
		$('#list_types_edit').on('click','.delete',function(){
			var id = $(this).attr('data-id');
			Swal.fire({
				type:'warning',
				text:'¿Seguro deseas borrar este item?',
				showCancelButton:true,
				reverseButtons:true
			})
			.then(function(response){
				if(response.value){
					Types.ID = id;
					Types.delete();
				}
			});

		});


		Types.get();
	}
}
var Clients = {
	ID:0,
	buildthumbs:function(PHTNM,EXT){
		var mod = $('#mod_thumb').clone();
		mod.removeClass('dp-none').addClass('dp-ib').removeAttr('id');
		mod.css({backgroundImage:'url('+ROOT+'img/clients/'+PHTNM+'-t.'+EXT+')'});
		mod.attr({'data-filename':PHTNM,'data-extension':EXT});
		mod.find('.dp-table').remove();
		$('#gallery').append(mod);
		$('#gallery .delete').unbind('click').click(function(){
			$(this).parent().parent().remove();
		});
	},
	buildvideos:function(IMG,ID){
		var mod = $('#mod_thumb').clone();
		mod.removeClass('dp-none').addClass('dp-ib').removeAttr('id');
		mod.css({backgroundImage:'url('+IMG+')'});
		mod.attr({'data-video':ID});
		mod.find('.dp-table').remove();
		$('#gallery').append(mod);
		$('#gallery .delete').unbind('click').click(function(){
			$(this).parent().parent().remove();
		});
	},
	get:function(){
		$('#clients').html('');
		$('#search_suggest_clients').hide();
		ajax('admin/clients/get',{keywords:$('#fd_search').val(),sort:$('#select_order').val()})
			.then(function(DATA){
				$.each(DATA.results,function(k,v){
					var mod = $('#mod_card').clone();
					mod.removeClass('dp-none').removeAttr('id');
					mod.attr('data-id',v.id);
					mod.find('h1').text(v.name);
					mod.find('.edit,.delete,.preview').attr({'data-id':v.id});
					mod.find('.foot').remove();
					mod.find('.preview').attr('data-permalink',v.permalink);
					var visible = v.visible == 1 ? '<i class="fa fa-toggle-on"></i>' : '<i class="fa fa-toggle-off"></i>';
					mod.find('p').html(v.creado+' &bullet; Vistas: '+v.views+' &bullet; '+visible+' &bullet; Experiencias: '+v.promos);
					if(v.logo != ''){
						var logo = $.parseJSON(v.logo);
						mod.find('.thumb').css({backgroundImage:'url('+ROOT+'img/clients/'+logo.photoname+'.'+logo.extension+')'});
					}
					$('#clients').append(mod);
				});

			});
	},
	save:function(action){
		var logo = {};
		if($('#logo_client').attr('data-filename') == undefined){
			Swal.fire({
				type:'warning',
				text:'Debes subir un logo para este cliente.'
			});
			return false;
		}else{
			logo.photoname = $('#logo_client').attr('data-filename');
			logo.extension = $('#logo_client').attr('data-extension');
		}
		var users = [];
		$.each($('#users .mod-list'),function(k,v){
			users.push($(this).attr('data-id'));
		});

		var visible = $('#fd_visible').hasClass('fa-toggle-on') ? 1 : 0;
		var types = [];
		$.each($('#list_types button'),function(k,v){
			if($(this).find('i').hasClass('fa-check-square')){
				types.push($(this).attr('data-id'));
			}
		});
		if(types.length==0){
			Swal.fire({
				type:'warning',
				text:'Debes elegir al menos un tipo de centro.'
			});
			$('a[href="#tab_categories"]').trigger('click');
			return false;
		}
		var glossary = [];
		$.each($('#glossary .list-group-item'),function(k,v){
			if($(this).find('i').hasClass('fa-check-square')){
				glossary.push($(this).attr('data-id'));
			}
		});
		if(glossary.length==0){
			Swal.fire({
				type:'warning',
				text:'Debes elegir al menos una etiqueta.'
			});
			$('a[href="#tab_categories"]').trigger('click');
			return false;
		}
		var gallery = [];
		$.each($('#gallery .thumbnail'),function(k,v){
			if($(this).attr('data-video') != undefined){
				gallery.push({video:$(this).attr('data-video')});
			}else{
				gallery.push({photoname:$(this).attr('data-filename'),extension:$(this).attr('data-extension')})
			}
		});
		var idstores = [];
		$.each($('#stores .mod-list'),function(k,v){
			idstores.push($(this).attr('data-id'));
		});
		ajax('admin/clients/save',{
			ID:Clients.ID,
			Name:$('#fd_name').val(),
			Subtitle:$('#fd_subtitle').val(),
			Web:$('#fd_web').val(),
			Mail:$('#fd_mail').val(),
			Permalink:$('#fd_permalink').val(),
			Users:users,
			Plan:$('#fd_plans').val(),
			Visible:visible,
			IDStores:idstores,
			Types:types,
			Glossary:glossary,
			Gallery:gallery,
			Logo:logo,
			Features:Features.OBJ,
			Socials:Socials.OBJ
		})
			.then(function(DATA){

				if(action=='save'){
					Clients.reset();
					Clients.get();
				}else{
					window.open(ROOT+'centros/'+$('#fd_permalink').val());
				}
			});
	},
	delete:function(ID){

		ajax('admin/clients/delete',{ID:ID})
			.then(function(){
				Clients.reset();
				Clients.get();
			});

	},
	find:function(){
		ajax('admin/clients/find',{id:Clients.ID})
			.then(function(DATA){

				$('#fd_name').val(DATA.client.name);
				$('#fd_subtitle').val(DATA.client.subtitle);
				$('#fd_web').val(DATA.client.web);
				$('#fd_mail').val(DATA.client.mail);
				$('#fd_permalink').val(DATA.client.permalink);
				if(DATA.client.visible == 1){
					$('#fd_visible').removeClass('fa-toggle-off').addClass('fa-toggle-on');
				}else{
					$('#fd_visible').removeClass('fa-toggle-on').addClass('fa-toggle-off');
				}
				$('#fd_plans option[value="'+DATA.client.idplan+'"]').prop('selected',true);
				if(DATA.client.logo != ''){
					var logo = $.parseJSON(DATA.client.logo);
					$('#logo_client').attr({'data-filename':logo.photoname,'data-extension':logo.extension}).css({backgroundImage:'url('+ROOT+'img/clients/'+logo.photoname+'.'+logo.extension+')'});
				}
				//////////// USERS /////////////////////////////////
				$.each(DATA.users,function(k,v){
					Users.build(v.iduser,v.name+' '+v.lastname+' ('+v.mail+')');
				});
				//////////// STORES /////////////////////////////////
				Stores.get();
				//////////// SOCIALS /////////////////////////////////
				if(DATA.client.socials != ''){
					var socials = $.parseJSON(DATA.client.socials);
					$.each(socials,function(k,v){
						Socials.OBJ.push({social:v.social,link:v.link});
					});
					Socials.build();
				}
				//////////// TYPES /////////////////////////////////
				///var arrTypes = DATA.Client.types.split(',');
				var passtype = false;
				$.each($('#list_types button'),function(kb,vb){
					$.each(DATA.client.types,function(kt,vt){
						if($(vb).attr('data-id')==vt){
							passtype = true;
						}
					});
					if(passtype){
						$(vb).find('i').removeClass('fa-square').addClass('fa-check-square');
					}else{
						$(vb).find('i').addClass('fa-square').removeClass('fa-check-square');
					}
					passtype = false;
				});
				//////////// GLOSSARY /////////////////////////////////
				///var glossary = DATA.Client.glossary.split(',');
				var passglossary = false;
				$.each($('#glossary .list-group-item'),function(kb,vb){
					$.each(DATA.client.glossary,function(kt,vt){
						if($(vb).attr('data-id')==vt){
							passglossary = true;
						}
					});
					if(passglossary){
						$(vb).find('i').removeClass('fa-square').addClass('fa-check-square');
					}else{
						$(vb).find('i').addClass('fa-square').removeClass('fa-check-square');
					}
					passglossary = false;
				});
				///////////// GALLERY //////////////////////
				var gallery = $.parseJSON(DATA.client.images);
				$.each(gallery,function(k,v){
					if(v.video != undefined){
						var video = v.video;
						GetYoutubeApi(video,function(data){
							Clients.buildvideos(data.items[0].snippet.thumbnails.medium.url,video);
						});
					}else{
						Clients.buildthumbs(v.photoname,v.extension);
					}
				});
				///////////// FEATURES //////////////////////
				$.each(DATA.features,function(k,v){
					Features.OBJ.push({title:v.title,description:v.description});
				});
				Features.build();
			});
	},
	reset:function(){
		Clients.ID = 0;
		$('#fd_name,#fd_subtitle,#fd_web,#fd_permalink,#fd_mail').val('');
		$('#stores,#users,#promos,#features,#gallery,#socials').html('');
		Stores.OBJ = [];
		Features.OBJ = [];
		Socials.OBJ = [];
		$('#edit_panel').slideUp({duration:900,easing:'easeInOutCubic'});
		$('#list_panel').slideDown({duration:900,easing:'easeInOutCubic'});
		$.each($('#list_types button'),function(kb,vb){
			$(this).find('i').removeClass('fa-check-square').addClass('fa-square');
		});
		$.each($('#glossary .list-group-item'),function(kb,vb){
			$(this).find('i').removeClass('fa-check-square').addClass('fa-square');
		});
		$('#logo_client').removeAttr('data-filename').removeAttr('data-extension').css({backgroundImage:'none'});
	},
	init:function(){


		$('#btn_new').click(function(){
			$('#list_panel').slideUp({duration:900,easing:'easeInOutCubic'});
			$('#edit_panel').slideDown({duration:900,easing:'easeInOutCubic'});
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

		var logo = new UpFile({
			container:'[data-input="logo"]',
			controller:'admin/clients/logo',
			folder:'img/clients',
			thumbnail:'#logo_client'
		});
		var gallery = new UpFile({
			container:'[data-input="gallery"]',
			controller:'admin/clients/gallery',
			folder:'img/clients',
			gallery:'#gallery',
			sortable:true
		});

		$('#clients').on('click','.delete',function(){
			var id = $(this).attr('data-id');
			Swal.fire({
				type:'warning',
				text:'¿Realmente deseas borrar este centro?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Clients.delete(id);
					}
				});
		});
		$('#clients').on('click','.edit',function(){
			var id = $(this).attr('data-id');
			$('#btn_new').trigger('click');
			Clients.ID = id;
			Clients.find();
		});
		$('#clients').on('click','.preview',function(){
			window.open(ROOT+'centros/'+$(this).attr('data-permalink'));
		});


		///$('.nav-tabs a[href="#tab_stores"]').trigger('click');
		$('#type_plans .flex-item').click(function(){
			$('#type_plans i').removeClass('fa-check-circle').addClass('fa-circle');
			$(this).find('i').removeClass('fa-circle').addClass('fa-check-circle');
		});
		$('#fd_visible,[for="fd_visible"]').click(function(){
			$('#fd_visible').toggleClass('fa-toggle-off fa-toggle-on');
		});
		////////////////////////////////////////////////////////////////////
		SearchSuggestions('#form_search_users','admin/users/getbytype','',function(INPUT,ths,DATA){
			var id = $(ths).attr('data-id');
			var indx = $(ths).index();
			var text = $(ths).text();
			var pass = false;
			if(DATA.results[indx].idclient != null){
				Swal.fire({
					type:'warning',
					text:'El usuario ya ha sido asignado a un centro.'
				});
				return false;
			}
			$.each($('#users .mod-list'),function(kk,vv){
				if($(this).attr('data-id')==id){
					pass = true;
				}
			});
			if(!pass){
				Users.build(id,text);
			}
			$(INPUT).val('');
		});

		$('#form_search_users').submit(function(e){
			e.preventDefault();
		});
		/*$('section').click(function(){
			$('#search_suggest_users').hide();
		});*/
		$('#btn_cancel').click(function(){
			Clients.reset();
		});
		$('#btn_delete').click(function(){
			Clients.delete(Clients.ID);
		});

		$('#btn_save,#btn_preview').click(function(){
			CheckFields(['#fd_name','#fd_subtitle','#fd_permalink','#fd_mail']);
			if($('#fd_name').val() == '' || $('#fd_subtitle').val() == '' || $('#fd_permalink').val() == '' || $('#fd_mail').val() == ''){
				//$('#fd_name,#fd_subtitle,#fd_permalink').addClass('required');
				$('a[href="#tab_general"]').trigger('click');
				return;
			}
			$('#fd_name,#fd_subtitle,#fd_permalink,#fd_mail').removeClass('required');
			if($('#stores .mod-list').length==0){
				$('a[href="#tab_stores"]').trigger('click');
				Swal.fire({type:'warning',text:'Debes agregar al menos una dirección.'});
				return false;
			}
			if($('#gallery .thumbnail').length==0){
				$('a[href="#tab_gallery"]').trigger('click');
				Swal.fire({type:'warning',text:'Debes agregar al menos una imagen.'});
				return false;
			}
			if($('#features .mod-list').length==0){
				$('a[href="#tab_features"]').trigger('click');
				Swal.fire({type:'warning',text:'Debes agregar al menos una descripción o característica.'});
				return false;
			}
			var action = $(this).attr('id') == 'btn_save' ? 'save' : 'preview';
			Clients.save(action);
		});
		$('#select_order').change(function(){
			Clients.get();
		});
		$('#form_search').submit(function(e){
			e.preventDefault();
			Clients.get();
		});
		$('#fd_name').keyup(function(){
			$('#fd_permalink').val($(this).val().permalink());
		});
		$('#btn_add_video').click(function(){
			CheckFields(['#fd_video'],function(){
				var idyoutube = GetIDVideo($('#fd_video').val(),'youtube');
				if(idyoutube){
					GetYoutubeApi(idyoutube,function(data){
						Clients.buildvideos(data.items[0].snippet.thumbnails.medium.url,idyoutube);
						$('#fd_video').val('');
					});
				}else{
					Swal.fire({type:'warning',text:'La URL ingresada es errónea. Asegúrate de que esté bien escrita.'});
				}
			});
		});
		$('#btn_plans').click(function(){
			$('#plans_panel').slideDown({duration:900,easing:'easeInOutCubic'});
			$('#edit_panel').slideUp({duration:900,easing:'easeInOutCubic'});
		});
		SearchSuggestions('#form_search','admin/clients/get','',Clients.get);

		Clients.get();
	}
}
var Stores = {
	NODE:0,
	OBJ:[],
	ID:0,
	EditMode:false,
	get:function(){
		$('#stores').html('');
		ajax('admin/stores/get',{IDC:Clients.ID})
			.then(function(DATA){
				$.each(DATA.results,function(k,v){
					var mod = $('#mod_list').clone();
					mod.removeAttr('id').removeClass('dp-none').attr('data-id',v.id);
					mod.find('h4').text(v.address);
					mod.find('.edit,.delete').attr('data-id',v.id);
					mod.find('.add').remove();
					mod.append('<p>'+v.city+', '+v.name+'</p>')
					$('#stores').append(mod);
				});

			});
	},
	reset:function(){
		$('#tab_stores').find('input,textarea').val('');
		Stores.ID = 0;
		Schedules.reset();
		$('#stores .mod-list,.block-buttons .inactive-block').removeClass('active');
	},
	find:function(){
		ajax('admin/stores/find',{IDS:Stores.ID})
			.then(function(DATA){
				var obj = DATA.result;
				$('#fd_store_address').val(obj.address);
				$('#fd_store_additional').val(obj.additional);
				$('#fd_store_city').val(obj.city);
				$('#fd_store_phones').val(obj.phones);
				$('#fd_store_whatsapp').val(obj.whatsapp);
				$('#fd_store_map').val(obj.map);
				$('#fd_store_province option[value="'+obj.idprovince+'"]').prop('selected',true);
				if(obj.schedules != ''){
					Schedules.OBJ = $.parseJSON(obj.schedules);
					Schedules.buildlist();
				}
			});
	},
	save:function(){
		ajax('admin/stores/save',{
			ID:Stores.ID,
			Address:$('#fd_store_address').val(),
			IDClient:Clients.ID,
			Additional:$('#fd_store_additional').val(),
			City:$('#fd_store_city').val(),
			Province:$('#fd_store_province option:selected').text(),
			IDProvince:$('#fd_store_province').val(),
			Phones:$('#fd_store_phones').val(),
			Whatsapp:$('#fd_store_whatsapp').val(),
			Map:$('#fd_store_map').val(),
			Schedules:JSON.stringify(Schedules.OBJ)
		})
			.then(function(DATA){
				Stores.get();
				Stores.reset();
			});
	},
	delete:function(id){
		ajax('admin/stores/delete',{IDS:id})
			.then(function(DATA){
				Stores.reset();
				Stores.get();
			});
	},
	init:function(){
		$('#btn_save_store').click(function(){
			CheckFields(['#fd_store_address','#fd_store_city'],Stores.save);
		});
		$('#btn_cancel_store').click(function(){
			Stores.reset();
		});
		$('#stores').sortable({
			update:function(){
				var arr = [];
				$.each($(this).parent().find('.mod-list'),function(k,v){
					arr.push($(this).attr('data-id'));
				});
				ajax('admin/stores/reorder',{ArrID:arr}).then(function(){});
			}
		});

		$('#stores').on('click','.edit',function(){
			var id = $(this).attr('data-id');
			Stores.ID = id;
			$('#stores .mod-list').removeClass('active');
			$(this).parent().parent().addClass('active');
			$(this).parent().parent().effect('transfer',{to:$('#fd_store_address')});
			Stores.find();
			$('.block-buttons .inactive-block').addClass('active');
		});
		$('#stores').on('click','.delete',function(){
			var id = $(this).attr('data-id');
			Swal.fire({
				type:'warning',
				text:'¿Realmente desease borrar esta dirección?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Stores.delete(id);
					}
				});

		});

	}
}
var Features = {
	NODE:0,
	OBJ:[],
	EditMode:false,
	build:function(){
		$('#features').html('');

		$.each(Features.OBJ,function(k,v){
			var mod = $('#mod_list').clone();
			mod.removeAttr('id').removeClass('dp-none');
			mod.find('h4').text(v.title);
			mod.find('.edit').attr('data-node',k);
			mod.find('.delete').attr('data-node',k);
			mod.find('.add').remove();
			mod.append(v.description.substr(0,255)+'...');
			$('#features').append(mod);
		});

		Features.reset();
	},
	reset:function(){
		$('#fd_feature_name').val('');
		CKEDITOR.instances.fd_feature_description.setData('');
		Features.NODE = 0;
		Features.EditMode = false;
		$('#features .mod-list,.block-buttons .inactive-block').removeClass('active');
	},
	find:function(){
		var obj = Features.OBJ[Features.NODE];
		$('#fd_feature_name').val(obj.title);
		CKEDITOR.instances.fd_feature_description.setData(obj.description);
	},
	init:function(){
		$('#btn_save_feature').click(function(){
			CheckFields(['#fd_feature_name'],function(){
				if(CKEDITOR.instances.fd_feature_description.getData() == ''){
					Swal.fire({
						type:'warning',
						text:'Debes incluir una descripción'
					})
					return false;
				}
				var obj = {
					title:$('#fd_feature_name').val(),
					description:CKEDITOR.instances.fd_feature_description.getData()
				}
				if(!Features.EditMode){
					Features.OBJ.push(obj);
				}else{
					Features.OBJ[Features.NODE] = obj;
				}
				Features.build();
			});
		});

		$('#features').on('click','.edit',function(){
			var node = $(this).attr('data-node');
			Features.NODE = node;
			Features.EditMode = true;
			$('#features .mod-list').removeClass('active');
			$(this).parent().parent().addClass('active');
			$(this).parent().parent().effect('transfer',{to:$('#fd_feature_name')});
			Features.find();
			$('.block-buttons .inactive-block').addClass('active');
		});
		$('#features').on('click','.delete',function(){
			var node = $(this).attr('data-node');

			Swal.fire({
				type:'warning',
				text:'¿Realmente desease borrar este item?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Features.OBJ.splice(node,1);
						Features.build();
					}
				});

		});


		$('#btn_cancel_feature').click(function(){
			Features.reset();
		});
		$('#fd_feature_description').ckeditor({
			language:'es',
			height:340,
			contentsCss:['https://fonts.googleapis.com/css?family=Fira+Sans+Condensed:300,400,700|Raleway:300,400,700',ROOT+'css/bootstrap.min.css',ROOT+'css/styles.css'],
			allowedContent:true,
			toolbar:'MyToolBar',
			toolbar_MyToolBar:[['Bold','Italic','Underline','RemoveFormat'],['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],['FontSize','TextColor','BGColor'],['Link','Unlink'],['NumberedList','Bulletedist','Outdent','Indent','Blockquote'],['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'],['Link','Unlink','Anchor']]
		});
	}
}
var Socials = {
	NODE:0,
	OBJ:[],
	EditMode:false,
	build:function(){
		$('#socials').html('');
		$.each(Socials.OBJ,function(k,v){
			var mod = $('#mod_list').clone();
			mod.removeAttr('id').removeClass('dp-none');
			mod.find('h4').html('<b class="fa fa-'+v.social+'" ></b> &bullet; '+v.social.ucfirst());
			mod.find('.edit').attr('data-node',k);
			mod.find('.delete').attr('data-node',k);
			mod.find('.add').remove();
			$('#socials').append(mod);
		});

		Socials.reset();
	},
	reset:function(){
		$('#fd_social_link').val('');
		Socials.NODE = 0;
		Socials.EditMode = false;
		$('#socials .mod-list,.block-buttons .inactive-block').removeClass('active');
	},
	find:function(){
		var obj = Socials.OBJ[Socials.NODE];
		$('#fd_social_link').val(obj.link);
		$('#fd_social_type option[value="'+obj.social+'"]').prop('selected',true);
	},
	init:function(){
		$('#btn_social_save').click(function(){
			CheckFields(['#fd_social_link'],function(){
				var pass = true;
				var obj = {
					link:$('#fd_social_link').val(),
					social:$('#fd_social_type').val()
				};
				if(!Socials.EditMode){
					$.each(Socials.OBJ,function(k,v){
						if(v.social == obj.social){
							pass = false;
						}
					});
					if(pass){
						Socials.OBJ.push(obj);
					}else{
						Swal.fire({
							type:'warning',
							text:'Ya has agregado esa red social'
						});
						return false;
					}
				}else{
					Socials.OBJ[Socials.NODE] = obj;
				}
				Socials.build();
			});
		});

		$('#socials').on('click','.edit',function(){
			var node = $(this).attr('data-node');
			Socials.NODE = node;
			Socials.EditMode = true;
			$('#socials .mod-list').removeClass('active');
			$(this).parent().parent().addClass('active');
			$(this).parent().parent().effect('transfer',{to:$('#fd_social_link')});
			$('.block-buttons .inactive-block').addClass('active');
			Socials.find();
		});
		$('#socials').on('click','.delete',function(){
			var node = $(this).attr('data-node');
			Swal.fire({
				type:'warning',
				text:'¿Realmente desease borrar este item?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Socials.OBJ.splice(node,1);
						Socials.build();
					}
				});

		});

		$('#btn_social_cancel').click(function(){
			Socials.reset();
		});
	}
}
var Users = {
	build:function(id,text){
		var mod = $('#mod_list').clone();
		mod.removeAttr('id').removeClass('dp-none');
		mod.attr('data-id',id);
		mod.find('.edit').remove();
		mod.find('.add').text('Ingresar como este usuario');
		mod.find('h4').text(text);
		$('#users').append(mod);
	}
}
var Glossary = {
	get:function(){
		$('#glossary').html('');
		ajax('admin/glossary-groups/get')
			.then(function(DATA){
				$.each(DATA.results,function(k,v){
					var mod = $('#mod_panel').clone();
					mod.removeClass('dp-none').removeAttr('id');
					mod.find('.panel-heading').html(v.name+' <i class="fa fa-caret-down fa-fw clickable" data-target="#group_body_'+v.id+'" data-toggle="collapse" title="Plegar/Desplegar Todos"></i> | <i data-toogle="check" class="fa fa-square fa-fw clickable" title="Marcar/Desmarcar Todos"></i>');
					mod.find('.panel-body').addClass('collapse in').attr('id','group_body_'+v.id);

					$.each(DATA.glossary[k],function(kg,vg){
						var item = $('#mod_panel_group_item').clone();
						item.removeAttr('id').removeClass('dp-none').addClass('clickable').attr('data-id',vg.id);
						item.find('span').text(vg.name);
						item.find('.edit,.delete,.view').remove();
						mod.find('.list-group').append(item);
					});

					$('#glossary').append(mod);
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
		$('#glossary button').click(function(){
			$(this).find('i').toggleClass('fa-square fa-check-square');
		});

		$('#glossary').on('click','.item',function(){
			$(this).find('i').toggleClass('fa-square fa-check-square');
		});
		$('#glossary').on('click','[data-toogle="check"]',function(){
			$(this).toggleClass('fa-square fa-check-square');
			if($(this).hasClass('fa-check-square')){
				$(this).parent().parent().find('.panel-body .fa').removeClass('fa-square').addClass('fa-check-square');
			}else{
				$(this).parent().parent().find('.panel-body .fa').addClass('fa-square').removeClass('fa-check-square');
			}
		});


		Glossary.get();
	}
}
var Schedules = {
	Node:0,
	OBJ:[],
	ArrDays:['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'],
	EditMode:false,
	buildlist:function(){
		$('#schedules .list-group').html('');
		$.each(Schedules.OBJ,function(k,v){
			if(v.schedules != undefined){
				$('#schedules .list-group').append('<div class="list-group-item"><b>'+Schedules.ArrDays[k]+': </b><span>'+Schedules.buildtext(v.schedules)+'</span></div>');
			}
		});
	},
	buildtext:function(schedule){
		var txt = '';
		$.each(schedule,function(k,v){
			if(k!=0 && k==schedule.length-1){
				txt += ' y ';
			}
			if(k!=schedule.length-1 && k!=0){
				txt += ', ';
			}
			txt += 'de '+v[0]+' a '+v[1]+' hs.';
		});
		if(schedule.length==0){
			txt = 'cerrado';
		}
		return txt;
	},
	buildschedule:function(indx){
		$('#pop_schedules .hours button').removeClass('active');
		var match = false;
		$.each(Schedules.OBJ[indx].schedules,function(kk,vv){
			$.each($('#pop_schedules .hours button'),function(k,v){
				var hour = $(this).attr('data-hour');
				if(vv[0]==hour){match = true;}
				if(match){
					$('#pop_schedules .hours button:eq('+k+')').addClass('active');
				}
				if(vv[1]==hour){match = false;}
			});
		});
		$('#schedules_text').text(Schedules.buildtext(Schedules.OBJ[indx].schedules));
	},
	buildhours:function(mode){
		var arrhours = [];
		var arrhourpos = [];
		$.each($('#pop_schedules .hours button'),function(k,v){
			if($(this).hasClass('active')){
				arrhours.push($(this).attr('data-hour'));
				arrhourpos.push(k);
			}
		});
		var schedule = [];
		$.each(arrhourpos,function(k,v){
			if(k!=0){
				if(v-1!=arrhourpos[k-1]){
					/////////////////////////
					schedule[schedule.length-1].push(arrhours[k-1]);
					///// break ////
					schedule.push([arrhours[k]]);
				}
			}else{
				schedule.push([arrhours[k]]);  //first element
			}
		});
		if(arrhours.length>0){
			schedule[schedule.length-1].push(arrhours[arrhours.length-1]);
		}
		//////////////////////////////////
		$('#schedules_text').text(Schedules.buildtext(schedule));
		var day = $('#pop_schedules .row-days button.active').attr('data-day');
		$.each(Schedules.OBJ,function(ks,vs){
			if(mode=='alldays'){
				Schedules.OBJ[ks].schedules = schedule;
			}else if(mode=='lunavie' && vs.day!='Sat' && vs.day!='Sun'){
				Schedules.OBJ[ks].schedules = schedule;
			}else if(vs.day==day){
				Schedules.OBJ[ks].schedules = schedule;
			}
		});
		//console.log(Schedules.OBJ);
	},
	reset:function(){
		Schedules.OBJ = [
			{day:'Mon',schedules:[]},
			{day:'Tue',schedules:[]},
			{day:'Wed',schedules:[]},
			{day:'Thu',schedules:[]},
			{day:'Fri',schedules:[]},
			{day:'Sat',schedules:[]},
			{day:'Sun',schedules:[]}
		]
		$('#schedules .list-group').html('');
	},
	init:function(){
		$('#btn_schedules_lunvie').click(function(){
			$(this).find('i').effect('pulsate',{duration:1000},function(){$(this).css({opacity:0});});
			$('#pop_schedules .row-days button').not(':eq(5),:eq(6)').effect('highlight',{color:'#dffadb'},800);
			Schedules.buildhours('lunavie');
		});
		$('#btn_schedules_alldays').click(function(){
			$(this).find('i').effect('pulsate',{duration:1000},function(){$(this).css({opacity:0});});
			$('#pop_schedules .row-days button').effect('highlight',{color:'#dffadb'},800);
			Schedules.buildhours('alldays');
		});
		$('#pop_schedules .row-days button').click(function(){
			$('#pop_schedules .row-days button').removeClass('active');
			$(this).addClass('active');
			Schedules.buildschedule($(this).index());
		});
		$('#pop_schedules .hours button').click(function(){
			$(this).toggleClass('active');
			Schedules.buildhours();
			//////////////////////////////////
		});
		$('#btn_close_schedules').click(function(){
			$('#pop_schedules').slideUp();
			Schedules.buildlist();
		});
		$('#btn_schedules').click(function(){
			$('#pop_schedules').slideDown();
			$('#pop_schedules .row-days button:eq(0)').trigger('click');
		});
		Schedules.reset();
	}
}
var Plans = {
	ID:0,
	get:function(){
		$('#fd_plans,#list_plans_edit').html('');
		ajax('admin/plans/get')
			.then(function(data){

				$.each(data.results,function(k,v){
					var mod = $('#mod_list').clone();
					mod.removeClass('dp-none').removeAttr('id').attr('data-id',v.id);
					mod.find('.add').remove();
					mod.find('h4').text(v.name+' (Comisión '+v.fee+'%)');
					mod.find('.edit,.delete').attr('data-id',v.id);
					$('#list_plans_edit').append(mod);
					$('#fd_plans').append('<option value="'+v.id+'" >'+v.name+' (Comisión '+v.fee+'%)</option>');
				});


			});
	},
	find:function(){
		ajax('admin/plans/find',{ID:Plans.ID})
			.then(function(data){
				$('#fd_plan_name').val(data.result.name);
				$('#fd_plan_fee').val(data.result.fee);
				$('#fd_plan_promos').val(data.result.promos);
			});
	},
	reset:function(){
		$('#fd_plan_name').val('');
		$('#fd_plan_fee,#fd_plan_promos').val(0);
		Plans.ID = 0;
	},
	save:function(){
		ajax('admin/plans/save',{
			ID:Plans.ID,
			Name:$('#fd_plan_name').val(),
			Fee:$('#fd_plan_fee').val(),
			Promos:$('#fd_plan_promos').val()
		})
			.then(function(){
				Plans.reset();
				Plans.get();
			});
	},
	delete:function(){
		ajax('admin/plans/delete',{ID:Plans.ID})
			.then(function(){
				Plans.reset();
				Plans.get();
			});
	},
	init:function(){
		$('#btn_close_plans').click(function(){
			Plans.reset();
			$('#plans_panel').slideUp({duration:900,easing:'easeInOutCubic'});
			$('#edit_panel').slideDown({duration:900,easing:'easeInOutCubic'});
		});
		$('#btn_save_plan').click(function(){
			CheckFields(['#fd_plan_name','#fd_plan_fee','#fd_plan_promos'],Plans.save);
		});

		$('#list_plans_edit').on('click','.edit',function(){
			$('#list_plans_edit .mod-list').removeClass('active');
			$(this).parent().parent().addClass('active');
			var id = $(this).attr('data-id');
			$(this).parent().parent().effect('transfer',{to:$('#fd_plan_name')});
			Plans.ID = id;
			Plans.find();
		});
		$('#list_plans_edit').on('click','.delete',function(){
			var id = $(this).attr('data-id');

			Swal.fire({
				type:'warning',
				text:'¿Seguro deseas borrar este item?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Plans.ID = id;
						Plans.delete();
					}
				});
		});

		$('#users').on('click','.delete',function(){
			var el = $(this).parent().parent();
			Swal.fire({
				type:'warning',
				html:'¿Seguro querés quitar a este usuario para administrar este centro? Los cambios no se efecturarán hasta que se guarden todos los cambios desde el botón --Guardar--',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(data){
					if(data.value){
						el.remove();
					}
				});
		});

		$('#users').on('click','.add',function(){
			var id = $(this).parent().parent().attr('data-id');
			ajax('admin/users/login_as',{userid:id}).then(function(data){
				window.location.href=ROOT;
			});
		});

		Plans.get();
	}
}
$(function(){
	ModViews();

	Stores.init();
	Features.init();
	Socials.init();
	Schedules.init();

	Types.init();
	Plans.init();
	Glossary.init();
	Clients.init();


});
