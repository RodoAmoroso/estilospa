class GiftCards extends Tables{

	constructor(){

		super({
			controller:'admin/core',
			class:'GiftCards',
			table:$('[data-table="giftcards"]')
		})

		this.init()
	}

	async extended_edition(data){

		this.form.find('#visible').attr({
			checked:data.visible==1
		})

		if(data.image){
			$('#image').attr({
				'data-filename':data.image.f,
				'data-extension':data.image.e
			})
			.css({
				backgroundImage:`url(${data.image.small})`
			})
		}

		/*if(data.faqs){
			const template = await get_template('admin/tr-category-faq')

			data.faqs.map(faq=>{

				const tr = $(template)
				tr.find('[name="question"]').val(faq.question)
				tr.find('[name="answer"]').val(faq.answer)
				tr.attr('data-questionid',faq.id)
				this.form.find('[data-table="faqs"] tbody').append(tr)

			})
		}*/

	}
	extended_reset(){
		this.form.find('#image').removeAttr('data-filename data-extension').css({
			backgroundImage:'none'
		})
		//this.form.find('[data-table="faqs"] tbody').html('')
	}



	init(){
		console.log('giftcards')

		this.form = $('[data-form="main"]')
		this.search_bar = $('[data-toggle="filters"]')


		this.datatable_options.columns = [
			{
				data:'position',
				className:'align-middle',
				name:'position',
				render:v=>{
					return `
					<span data-toggle="sort" class="btn btn-xs btn-dark">
						<i class="fa fa-sort"></i>
					</span>`
				}
			},
			{
				data:'image',
				className:'align-middle',
				name:'image',
				orderable:false,
				render:v=>{
					return `<div class="thumbnail rounded thumb-80x80 thumb-cover" style="background-image:url(${v.small})"></div>`
				}
			},
			{
				data:'title',
				className:'align-middle',
				name:'title'
			},

			{
				data:'visible',
				className:'align-middle',
				name:'visible',
				orderable:false,
				render:v=>{
					return `<span class="badge bg-${v==1?'success':'danger'} text-light">${v==1?'visible':'oculto'}</span>`
				}
			},
			{
				data:'value_formatted',
				className:'align-middle',
				name:'value'
			},
			{
				data:'expiration',
				className:'align-middle',
				name:'expiration',
				render:v=>{
					return v!=null ? `${v} días` : '<span class="text-muted fst-italic">[sin definir]</span>'
				}
			},
			{
				data:null,
				className:'align-middle small',
				name:'modified',
				render:v=>{
					let output = `<div>Creado: ${v.added}</div>`
					//if(v.modified==null) output += ``
					output += `<div>Modificado: ${v.modified ?? '<span class="text-muted fst-italic">[nunca]</span>'}</div>`

					return output
				}
			},
			{
				name:'actions',
				data:row=>row,
				className:'text-end align-middle',
				render:v=>{
					return `
						<button data-toggle="delete" class="btn btn-danger btn-xs my-1">
							<i class="fa fa-trash"></i>
						</button>
						<button data-toggle="edit" class="btn btn-secondary btn-xs my-1">
							<i class="fa fa-pencil fa-fw"></i> Editar
						</button>
					`
				},
				orderable:false
			}
		]
		this.datatable_options.createdRow = (row,data,dataIndex)=>{
			$(row).attr('data-id',data.id)
		}
		this.datatable_options.ordering = false
		this.datatable_instance = this.table.DataTable(this.datatable_options)


		/*** SAVE ***/
		this.form.on('submit',async form=>{
			form.preventDefault()
			let post = get_form(form.currentTarget)
			post.class = this.class
			post.visible = this.form.find('#visible').is(':checked') ? 1 : 0

			//image
			if($('#image').attr('data-filename') == undefined){
				Swal.fire({
					type:'warning',
					title:'Debes subir una imagen para esta GiftCard!'
				})
				return false
			}
			post.image = {
				f:$('#image').attr('data-filename'),
				e:$('#image').attr('data-extension')
			}

			/*post.faqs = []
			$.each( this.form.find('[data-table="faqs"] tbody tr'), (k,v)=>{
				post.faqs.push({
					id:$(v).attr('data-questionid'),
					answer:$(v).find('[name="answer"]').val(),
					question:$(v).find('[name="question"]').val(),
					position:k+1
				})
			})*/
			//return console.log(post)

			const response = await super.save(post)
			this.reset()

		})
		/*** CANCEL ***/
		this.form.on('click','[data-btn-action="cancel"]',btn=>{
			this.reset()
		})


		const image = new UpFile({
			container:'[data-input="image"]',
			controller:'admin/upload/big',
			folder:'giftcards',
			thumbnail:'#image',
			sufix:'-t'
		})


		$(this.form).find('[name="description"]').ckeditor({
			language:'es',
			height:580,
			allowedContent:true
		})
		CKEDITOR.config.contentsCss = [
			`${ROOT}css/lib/bootstrap.min.css`,
			`${ROOT}css/styles.css`
		];


		// faqs
		/*$('[data-toggle="add-faq"]').click(async btn=>{
			const template = await get_template('admin/tr-category-faq')
			const tr = $(template)
			this.form.find('[data-table="faqs"] tbody').append(tr)
		})
		this.form.find('[data-table="faqs"] tbody').sortable({
			handle:'[data-toggle="sort"]'
		})
		this.form.find('[data-table="faqs"]').on('click','[data-toggle="delete-faq"]',async btn=>{
			const tr = $(btn.currentTarget).closest('tr')
			const swal_warn = await SwalWarn.fire({
				title:'¿Seguro deseas borrar este item?'
			})
			if(!swal_warn.value) return false

			tr.remove()
		})*/

	}

}


new GiftCards