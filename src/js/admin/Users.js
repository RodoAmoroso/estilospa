class Users extends Tables{

	constructor(){

		super({
			controller:'admin/users',
			table:$('[data-table="users"]')
		})

		this.init()

	}


	delete(id){
		return new Promise(resolve=>{
			ajax(`${this.controller}/delete`,{ID:id})
				.then(response=>resolve(response))
		})
	}
	find(id){
		return new Promise(resolve=>{
			ajax(`${this.controller}/find/`,{ID:id})
				.then(response=>resolve(response))
		})
	}
	login_as(userid){
		return new Promise(resolve=>{
			ajax(`${this.controller}/login_as`,{userid:userid})
				.then(response=>resolve(response))
		})
	}
	save(post){
		return new Promise(resolve=>{
			ajax(`${this.controller}/save`,post)
				.then(response=>resolve(response))
		})
	}
	show(data){

		if(data.result==false) return false
		$.each(data.result,(k,v)=>{
			this.form.find(`[name="${k}"]`).val(v)
		})

		if(data.result.active == 1){
			$('#fd_active i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
		}else{
			$('#fd_active i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
		}

		if(data.result.image!=''){
			const img = $.parseJSON(data.result.image)
			$('#avatar').css({
				backgroundImage:`url(${ROOT}img/users/${img.photoname}-t.${img.extension})`
			}).attr({
				'data-filename':img.photoname,
				'data-extension':img.extension
			})
		}

		if(data.assoc.length!=0){
			this.form.find(`#fd_clients`).val(data.assoc[0].idclient);
		}

		const date = data.result.birth.split('-');
		$('#fd_day').val(date[2]);
		$('#fd_month').val(date[1]);
		$('#fd_year').val(date[0]);

		this.form.find('[name="idtype"]').trigger('change')

	}

	reset(){

		this.edition.slideUp({duration:900,easing:'easeInOutCubic'});
		this.listing.slideDown({duration:900,easing:'easeInOutCubic'});
		this.form.find('input[name], textarea[name]').val('')
		this.form.find('[name="id"]').val(0)
		$('#avatar').css({backgroundImage:'none'}).removeAttr('data-filename data-extension');

	}

	init(){

		this.form = $('[data-form="main"]')
		this.edition = $('#edit_panel')
		this.listing = $('#list_panel')
		this.controller = 'admin/users'
		this.table = $('[data-table="users"]')


		$('#btn_new').click(btn=>{
			this.edition.slideDown({duration:900,easing:'easeInOutCubic'});
			this.listing.slideUp({duration:900,easing:'easeInOutCubic'});
		})

		$('#fd_active,#fd_notify').click(btn=>{
			$(btn.currentTarget).find('i').toggleClass('fa-toggle-on fa-toggle-off');
		})

		$('#fd_types').change(btn=>{
			if($(btn.currentTarget).val() == 3 || $(btn.currentTarget).val()==4){
				$('#clients_block').slideDown();
			}else{
				$('#clients_block').slideUp();
			}
		})

		$('#btn_cancel').click(btn=>{
			this.reset();
		})


		// DELETE
		this.table.on('click', '[data-toggle="delete"]', async btn=>{
			const tr = $(btn.currentTarget).closest('tr')
			const id = tr.attr('data-id')
			const swal = await Swal.fire({
				type:'warning',
				text:'¿Seguro deseas borrar este usuario?',
				showCancelButton:true,
				reverseButtons:true
			})
			if(swal.value){
				const response = await this.delete(id)
				this.reset();
				this.datatable_instance.fnUpdate()
			}
		})
		// EDIT
		this.table.on('click','[data-toggle="edit"]',async btn=>{
			const tr = $(btn.currentTarget).closest('tr')
			const id = tr.attr('data-id')
			const response = await this.find(id)
			$('#btn_new').trigger('click')
			this.show(response)
		})
		// LOGIN
		this.table.on('click','[data-toggle="loginas"]',async btn=>{
			const tr = $(btn.currentTarget).closest('tr')
			const id = tr.attr('data-id')
			const response = await this.login_as(id)
			window.location.href=ROOT
		})
		// SAVE
		this.form.on('submit',async form=>{
			form.preventDefault()
			let post = get_form(form.currentTarget)

			post.active = $('#fd_active i').hasClass('fa-toggle-on') ? 1 : 0
			post.notify = $('#fd_notify i').hasClass('fa-toggle-on') ? 1 : 0

			if(post.id==0){
				if($('#fd_pass').val().length < 3){
					Swal.fire({
						type:'warning',
						text:'Debes incluir una contraseña al crear un usuario por primera vez.'
					})
					return false
				}
			}

			post.img = ''
			if($('#avatar').attr('data-filename') != undefined){
				post.img = {
					photoname:$('#avatar').attr('data-filename'),
					extension:$('#avatar').attr('data-extension')
				}
			}

			post.birth = `${post.birth_year==''?'0000':post.birth_year}-${post.birth_month==''?'00':post.birth_month}-${post.birth_day==''?'00':post.birth_day}`

			const response = await this.save(post)
			toastr['success'](response.message)
			this.datatable_instance.fnUpdate()
			this.reset()
		})

		this.image = new UpFile({
			container:'[data-input=image]',
			thumbnail:'#avatar',
			folder:'img/users',
			controller:'admin/users/upimage',
			sufix:'-o'
		})

		///SearchSuggestions('#form_search','admin/users/get','',Users.get);





		this.datatable_options.columns = [
			{
				data:'id',
				name:'id'
			},
			{
				name:'name',
				data:row=>row,
				render:v=>{
					return `${v.name} ${v.lastname}`
				}
			},
			{
				data:'mail',
				name:'mail'
			},
			{
				data:'phone',
				name:'phone'
			},
			{
				data:'created',
				name:'created'
			},
			{
				data:'logged',
				name:'logged'
			},
			{
				data:'type_name',
				name:'type_name'
			},
			{
				name:'actions',
				data:row=>row,
				className:'text-right',
				render:v=>{
					return `
						<button data-toggle="delete" class="btn btn-danger btn-xs mb-4"><i class="fa fa-trash"></i></button>
						<button data-toggle="edit" class="btn btn-success btn-xs mb-4"><i class="fa fa-pencil fa-fw"></i> editar</button>
						<button data-toggle="loginas" class="btn btn-primary btn-xs mb-4"><i class="fa fa-sign-in fa-fw"></i> login</button>
						<a href="${ADMIN}actividad-usuario/${v.id}" target="_blank" class="btn btn-info btn-xs mb-4"><i class="fa fa-line-chart fa-fw"></i> actividad</a>
					`
				},
				orderable:false
			}
		]
		this.datatable_options.createdRow = (row,data,dataIndex)=>{
			$(row).attr('data-id',data.id)
		}

		this.datatable_instance = this.table.dataTable(this.datatable_options)



		console.log('usuarios')

	}
}

new Users