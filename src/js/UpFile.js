class UpFile {

	constructor(obj){
		$.extend(this,obj);
		this.init();
	}

	upload(event){
		let dataForm = new FormData();
		dataForm.append('file',this.files[this.node]);
		dataForm.append('node',this.node);
		dataForm.append('folder',this.folder+'/');
		dataForm.append('mode',this.mode);
		dataForm.append('token',TOKEN);

		if(this.node == 0){
			loading({message:`<p>Subiendo archivo(s). <br />Esta operación puede durar varios minutos dependiendo del tamaño del/los archivo(s) y de la conexión.</p><p><span class="badge badge-success">${this.node+1}/${this.files.length}</span></p>`});
		}

		return new Promise((resolve,reject) => {

			$.ajax({
				type:'POST',
				url:`${ROOT}ajax/index.php?uri=${this.controller}`,
				data:dataForm,
				cache:false,
				processData:false,
				contentType:false,
				dataType:'json'
			})
				.done(data=>{

					if(data.status!='ok'){
						Swal.fire({text:data.message,type:'error'});
						loading({show:false});
						return reject(data);
					}

					$('#loading .badge').text(`${(this.node+1)} / ${this.files.length}`);
					this.arrfiles.push({
						'filename':data.filename,
						'extension':data.extension,
						'hash':data.hash,
						'event':event,
						'size':data.size
					});
					///////////////////////////////////////////
					if(this.node < this.files.length-1){
						this.node = this.node+1;
						this.upload(event);
					}else{

						if('thumbnail' in this){
							$(this.thumbnail)
								.attr({
									'data-filename':this.arrfiles[0].filename,
									'data-extension':this.arrfiles[0].extension,
									'data-hash':this.arrfiles[0].hash
								})
								.css({
									'backgroundImage':`url(${ROOT}${data.main_folder}${this.folder}/${this.arrfiles[0].filename}${this.sufix}.${this.arrfiles[0].extension})`
								});
						}
						if('callback' in this){
							var idi = 'idi' in data ? data.idi : 0;
							this.callback(this.arrfiles,this.sufix,idi);
						}
						if('gallery' in this){
							this.build_gallery();
						}
						$('#loading .loading-text').text('');
						$(event.delegateTarget).find('input[type="file"]').val('');

						loading({show:false});
						resolve(data);
					}

				})
				.fail(data=>{
					loading({show:false});
					console.log(data.responseText)
					reject(data);
					Swal.fire({text:'Hubo problemas al subir el archivo. Intenta nuevamente.',type:'error'});
				});
		});

	}


	async build_gallery(){

		const template = await get_template('admin/thumbnail')

		$.each(this.arrfiles,(k,v)=>{
			var $module = $(template);
			$module.attr({'data-filename':v.filename,'data-extension':v.extension});
			$module.css({backgroundImage:`url(${ROOT}img/${this.folder}/${v.filename}${this.sufix}.${v.extension})`})
			$(this.gallery).append($module);
		});

		if('sortable' in this){
			$(this.gallery).sortable();
		}

	}


	init(){
		this.scope = 'scope' in this ? this.scope : 'admin';
		this.controller = 'controller' in this ? this.controller : 'upload';
		this.mode = 'mode' in this ? this.mode : '';
		this.folder = 'folder' in this ? this.folder : '';
		this.sufix = 'sufix' in this ? this.sufix : ''

		$.each($(this.container),(k,v)=>{
			$(v).attr('id','file_'+Math.round(Math.random()*1000000));
		});


		$(this.container).off('change').on('change','input[type="file"]',event=>{
			event.preventDefault();
			this.node = 0;
			this.arrfiles = [];

			if(event.target.files.length > 0){
				if(event.target.files.length > MAXFILES){
					Swal.fire({
						text:`La cantidad de archivos no debe superar los ${MAXFILES}`,
						type:'warning'
					});
					return false;
				}
				this.files = event.target.files;
				if(this.files[0].type.indexOf('image') != -1){
					var img = new Image();
					img.onload = ()=>{
						this.upload(event);
					}
					img.src = window.URL.createObjectURL(this.files[0]);
				}else{
					this.upload(event);
				}
			}
		});

		$(this.container).off('click').on('click','button',btn=>{
			var el = $(btn.currentTarget).closest(this.container);
			el.find('input[type="file"]').trigger('click');
		});

	}

}