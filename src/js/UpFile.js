class UpFile {

	constructor(obj){
		$.extend(this,obj);
		this.init();
	}

	upload(){
		let dataForm = new FormData();
		dataForm.append('file',this.files[this.node]);
		dataForm.append('node',this.node);
		dataForm.append('folder',this.folder+'/');
		dataForm.append('mode',this.mode);

		if(this.node == 0){
			loading({message:'<p>Subiendo archivo(s). <br />Esta operación puede durar varios minutos dependiendo del tamaño del/los archivo(s) y de la conexión.</p><p><span class="label label-success">...</span></p>'});
		}

		return new Promise((resolve,reject) => {

		 $.ajax({
				type:'POST',
				url:ROOT+'ajax/'+this.controller,
				data:dataForm,
				cache:false,
				processData:false,
				contentType:false,
				dataType:'json'
			})
			.done(data=>{
				if(data.status!='ok'){
					Swal.fire({text:data.message,type:'error'});
					reject(data);
				}

				$('#loading .label').text((this.node+1)+' / '+this.files.length);
				this.arrfiles.push({'filename':data.filename,'extension':data.extension});
				///////////////////////////////////////////
				if(this.node < this.files.length-1){
					this.node = this.node+1;
					this.upload();
				}else{
					var sufix = 'sufix' in this ? this.sufix : '';
					if('thumbnail' in this){
						$(this.thumbnail)
						.attr({
							'data-filename':this.arrfiles[0].filename,
							'data-extension':this.arrfiles[0].extension
						})
						.css({
							'backgroundImage':'url('+ROOT+this.folder+'/'+this.arrfiles[0].filename+sufix+'.'+this.arrfiles[0].extension+')'
						});
					}
					if('callback' in this){
						var idi = 'idi' in data ? data.idi : 0;
						this.callback(this.arrfiles,sufix,idi);
					}
					$('#loading .loading-text').text('');
					$(this.container).find('input').val('');
					if('gallery' in this){
						this.build_gallery();
					}
					resolve(this.arrfiles);
				}
			})
			.always(data=>{
				loading({show:false});
			})
			.fail(data=>{
				Swal.fire({text:'Hubo problemas al subir el archivo. Intenta nuevamente.',type:'error'});
				reject(data);
			});
		}).
		catch(data=>{
			console.log(data);
		});		
	}


	build_gallery(){
		get_template('site/gallery-thumbnail')
			.then(template=>{
				$.each(this.arrfiles,(k,v)=>{
					var $module = $($(template));
					$module.attr({'data-filename':v.filename,'data-extension':v.extension});
					$module.css({backgroundImage:'url('+ROOT+this.folder+'/'+v.filename+'-t.'+v.extension+')'})
					$(this.gallery).append($module);
				});
			});
	}


	init(){
		this.scope = 'scope' in this ? this.scope : 'admin';
		this.controller = 'controller' in this ? this.controller : 'upload';

		$(this.container).on('click','button',()=>{
			$(this.container).find('input').trigger('click');
		});

		$(this.container).on('change','input',event=>{
			event.preventDefault();
			this.node = 0;
			this.arrfiles = [];
			if(event.target.files.length > 0){
				if(event.target.files.length > MAXFILES){
					Swal.fire({
						text:'La cantidad de archivos no debe superar los '+MAXFILES,
						type:'warning'
					});
					return false;
				}
				this.files = event.target.files;
				if(this.files[0].type.indexOf('image') != -1){
					var img = new Image();
					img.onload = ()=>{
						this.upload();
					}
					img.src = window.URL.createObjectURL(this.files[0]);
				}else{
					this.upload();
				}
			}
		});

		if('gallery' in this){
			$(this.gallery).on('click','.delete',btn=>{
				$(btn.currentTarget).parent().parent().remove();
			});
			$(this.gallery).sortable();
		}
	}


}