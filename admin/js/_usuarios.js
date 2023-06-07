var Users = {
	ID:0,
	get:function(){
		$('#mod_users').html('');
		ajax('admin/users/get',{
			keywords:$('#fd_search').val(),
			type:$('#search_type').val()
		})
			.then(function(DATA){
				$.each(DATA.results,function(k,v){
					var mod = $('#mod_list_caption').clone();
					mod.removeAttr('id').removeClass('dp-none');
					mod.attr('data-id',v.id);
					mod.find('.add').remove();
					mod.find('.delete,.edit').attr('data-id',v.id);
					mod.find('.title').text(v.name+' - '+v.mail);
					mod.find('.caption').html('Registrado: '+v.creado+' | '+'Último acceso: '+(v.last_access == '00/00/0000 00:00' ? 'nunca' : v.last_access+' hs.' ));
					$('#mod_users').append(mod);
				});

			});
	},
	find:function(){
		ajax('admin/users/find',{ID:Users.ID})
			.then(function(DATA){
				var d = DATA.result;
				$('#fd_pass').val('');
				$('#fd_mail').val(d.mail);
				$('#fd_name').val(d.name);
				$('#fd_lastname').val(d.lastname);
				$('#fd_dni').val(d.dni);
				$('#fd_phone').val(d.phone);
				$('#fd_address').val(d.address);
				$('#fd_adressobs').val(d.addressobs);
				$('#fd_zipcode').val(d.zipcode);
				$('#fd_city').val(d.city);
				$('#fd_email').val(d.email);
				if(d.active == 1){
					$('#fd_active i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
				}else{
					$('#fd_active i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
				}

				var date = d.birth.split('-');
				$('#fd_day').val(date[2]);
				$('#fd_month').val(date[1]);
				$('#fd_year').val(date[0]);
				$('#fd_types option[value="'+d.idtype+'"]').prop('selected',true);
				$('#fd_types').trigger('change');
				if(DATA.assoc.length!=0){
					$('#fd_clients option[value="'+DATA.assoc[0].idclient+'"]').prop('selected',true);
				}

				$('#fd_provinces option[value="'+d.idprovince+'"]').prop('selected',true);
				if(d.image != ''){
					var img = $.parseJSON(d.image);
					$('#avatar').css({backgroundImage:'url('+ROOT+'img/users/'+img.photoname+'-t.'+img.extension+')'}).attr({'data-filename':img.photoname,'data-extension':img.extension});
				}
			});
	},
	save:function(){
		var img = '';
		if($('#avatar').attr('data-filename') != undefined){
			img = {photoname:$('#avatar').attr('data-filename'),extension:$('#avatar').attr('data-extension')}
		}
		var active = $('#fd_active i').hasClass('fa-toggle-on') ? 1 : 0;
		var notify = $('#fd_notify i').hasClass('fa-toggle-on') ? 1 : 0;

		ajax('admin/users/save',{
			Name:$('#fd_name').val(),
			LastName:$('#fd_lastname').val(),
			DNI:$('#fd_dni').val(),
			Birth:$('#fd_year').val()+'-'+$('#fd_month').val()+'-'+$('#fd_day').val(),
			Mail:$('#fd_mail').val(),
			Phone:$('#fd_phone').val(),
			Address:$('#fd_address').val(),
			AddressObs:$('#fd_addressobs').val(),
			City:$('#fd_city').val(),
			Zip:$('#fd_zip').val(),
			IDProvince:$('#fd_provinces').val(),
			Pass:$('#fd_pass').val(),
			IDType:$('#fd_types').val(),
			IDClient:$('#fd_clients').val(),
			IMG:img,
			Active:active,
			Notify:notify,
			ID:Users.ID
		})
			.then(function(){
				Users.reset();
				Users.get();
			});
	},
	reset:function(){
		Users.ID=0,
		$('#edit_panel').slideUp({duration:900,easing:'easeInOutCubic'});
		$('#list_panel').slideDown({duration:900,easing:'easeInOutCubic'});
		$('#edit_panel input').val('');
		$('#avatar').css({backgroundImage:'none'}).removeAttr('data-filename data-extension');
	},
	delete:function(){
		ajax('admin/users/delete',{ID:Users.ID})
			.then(function(DATA){
				Users.reset();
				Users.get();
			});
	},
	init:function(){


		$('#btn_new').click(function(){
			$('#edit_panel').slideDown({duration:900,easing:'easeInOutCubic'});
			$('#list_panel').slideUp({duration:900,easing:'easeInOutCubic'});
		});
		$('#fd_active,#fd_notify').click(function(){
			$(this).find('i').toggleClass('fa-toggle-on fa-toggle-off');
		});
		$('#fd_types').change(function(){
			if($(this).val() == 3 || $(this).val()==4){
				$('#clients_block').slideDown();
			}else{
				$('#clients_block').slideUp();
			}
		});
		$('#btn_cancel').click(function(){
			Users.reset();
		});
		$('#btn_save').click(function(){
			CheckFields(['#fd_name','#fd_mail:email'],function(){
				if(Users.ID == 0){
					if($('#fd_pass').val().length < 3){
						Swal.fire({
							type:'warning',
							text:'Debes incluir una contraseña al crear un usuario por primera vez.'
						});
						return false;
					}
					Users.save();
				}else{
					Users.save();
				}
			});
		});
		$('#btn_delete').click(function(){
			Swal.fire({
				type:'warning',
				text:'¿Seguro deseas borrar este usuario?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Users.delete();
					}
				});

		});
		$('#search_type').change(function(){
			Users.get();
		});

		var image = new UpFile({
			container:'[data-input=image]',
			thumbnail:'#avatar',
			folder:'img/users',
			controller:'admin/users/upimage',
			sufix:'-o'
		});

		SearchSuggestions('#form_search','admin/users/get','',Users.get);

		$('#table_users').on('click','.edit',function(){
			var id = $(this).attr('data-id');
			Users.ID = id;
			Users.find();
			$('#btn_new').trigger('click');
		});
		/*$('#mod_users').on('click','.delete',function(){
			var id = $(this).attr('data-id');
			Swal.fire({
				type:'warning',
				text:'¿Seguro deseas borrar este usuario?',
				showCancelButton:true,
				reverseButtons:true
			})
				.then(function(response){
					if(response.value){
						Users.ID = id;
						Users.delete();
					}
				});

		});*/

		$('#login_as').click(function(){
			ajax('admin/users/login_as',{userid:Users.ID}).then(function(data){
				window.location.href=ROOT;
			});
		});

		datatable_options.buttons = [
			{
				extend:'excel',
				title:'usuarios_estilospa',
				text:'<i class="fa fa-file-excel-o fa-fw"></i> Exportar a Excel',
				exportOptions:{
					columns:[0,1,2]
				}
			}
		];
		datatable_options.ordering = false;

		$('#table_users').DataTable(datatable_options);


	}
}
$(function(){
	Users.init();
});