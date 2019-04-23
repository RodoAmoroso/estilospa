var Users = {
	ID:0,
	get:function(){
		$('#mod_users').html('');
		AjaxConnection('jxUsers.php',{
			Mode:'get',
			Keywords:$('#fd_search').val(),
			Type:$('#search_type').val()
		},function(DATA){
			$.each(DATA.Results,function(k,v){
				var mod = $('#mod_list').clone();
				mod.removeAttr('id').removeClass('dp-none');
				mod.attr('data-id',v.id);
				mod.find('.add').remove();
				mod.find('.delete,.edit').attr('data-id',v.id);
				mod.find('h4').text(v.name+' - '+v.mail);
				$('#mod_users').append(mod);
			});
			$('#mod_users .edit').click(function(){
				var id = $(this).attr('data-id');
				Users.ID = id;
				Users.find();
				$('#btn_new').trigger('click');
			});
			$('#mod_users .delete').click(function(){
				var id = $(this).attr('data-id');
				Messages(true,'¿Seguro deseas borrar este usuario?',function(){
					Users.ID = id;
					Users.delete();
				});
			});
		});
	},
	find:function(){
		AjaxConnection('jxUsers.php',{Mode:'find',ID:Users.ID},function(DATA){			
			var d = DATA.Result;			
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
			if(DATA.Assoc.length!=0){
				$('#fd_clients option[value="'+DATA.Assoc[0].idclient+'"]').prop('selected',true);
			}
								
			$('#fd_provinces option[value="'+d.idprovince+'"]').prop('selected',true);
			if(d.image != ''){
				var img = $.parseJSON(d.image);
				$('#avatar').css({backgroundImage:'url('+ROOT+'img/users/'+img.photoname+'-t.'+img.extension+')'}).attr({'data-photoname':img.photoname,'data-extension':img.extension});
			}
		});
	},
	save:function(){
		var img = '';
		if($('#avatar').attr('data-photoname') != undefined){
			img = {photoname:$('#avatar').attr('data-photoname'),extension:$('#avatar').attr('data-extension')}
		}
		var active = $('#fd_active i').hasClass('fa-toggle-on') ? 1 : 0;
		var notify = $('#fd_notify i').hasClass('fa-toggle-on') ? 1 : 0;
		console.log(Users.ID,notify);
		AjaxConnection('jxUsers.php',{
			Mode:'save',
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
		},function(DATA){
			console.log(DATA);
			if(DATA.Status == 'wrongmail'){
				Messages(true,'Has ingresado un email no válido. Itenta nuevamente.');
				return;
			}
			if(DATA.Status == 'exists'){
				Messages(true,'El mail ingresado ya existe en la base de datos.');
				return;
			}
			if(DATA.Status == 'pass'){
				Messages(true,'Debes incluir una contraseña al crear un usuario por primera vez.');
				return;
			}
			Users.reset();
			Users.get();
		});
	},
	reset:function(){
		Users.ID=0,
		$('#edit_panel').slideUp({duration:900,easing:'easeInOutCubic'});
		$('#list_panel').slideDown({duration:900,easing:'easeInOutCubic'});
		$('#edit_panel input').val('');
		$('#avatar').css({backgroundImage:'none'}).removeAttr('data-photoname data-extension');
	},
	delete:function(){
		AjaxConnection('jxUsers.php',{Mode:'delete',ID:Users.ID},function(DATA){			
			if(DATA.Status == 'blocked'){
				Messages(true,'No puedes borrar este usuario');
				return false;
			}
			Users.reset();
			Users.get();			
		});
	},
	init:function(){
		UpFile.Init({MODE:'upimageadmin',PHP:'jxUsers.php',BTN:'#btn_image',FORM:'#form_image',TH:'#avatar',FOLDER:'img/users/',SX:'-o'});
		$('#btn_cancel').click(function(){
			Users.reset();
		});
		$('#btn_save').click(function(){			
			CheckFields(['#fd_name','#fd_mail:email'],function(){
				if(Users.ID == 0){
					if($('#fd_pass').val().length < 3){
						Messages(true,'Debes incluir una contraseña al crear un usuario por primera vez.');
						return false;
					}
					Users.save();
				}else{
					Users.save();
				}
			});
		});
		$('#btn_delete').click(function(){
			Messages(true,'¿Seguro deseas borrar este usuario?',function(){
				Users.delete();
			});
		});
		$('#btn_new').click(function(){
			$('#edit_panel').slideDown({duration:900,easing:'easeInOutCubic'});
			$('#list_panel').slideUp({duration:900,easing:'easeInOutCubic'});
		});
		$('#fd_active,#fd_notify').click(function(){
			$(this).find('i').toggleClass('fa-toggle-on fa-toggle-off');
		});
		$('#fd_types').change(function(){
			if($(this).val() == 3){
				$('#clients_block').slideDown();
			}else{
				$('#clients_block').slideUp();
			}
		});
		$('#search_type').change(function(){
			Users.get();
		});
		SearchSuggestions('#form_search','jxUsers.php','get',Users.get);
		Users.get();
	}
}
$(function(){
	Users.init();
});