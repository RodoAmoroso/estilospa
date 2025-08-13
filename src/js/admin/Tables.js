class Tables extends CRUD{

	constructor(_obj){

		super(_obj.controller,_obj.class)

		this.controller = _obj.controller
		this.class = _obj.class
		this.table = _obj.table

		this.datatable_options = {
			pageLength:50,
			language:{
				url:`${ROOT}js/lib/dataTables/spanish.json`
			},
			dom:'<"table-spacer-top"lf>t<"table-spacer-bottom"ip>',
			serverSide:true,
			responsive:true,
			processing:true,
			deferRender:true,
			ordering:true,
			order:[[0,'asc']],
			lengthMenu:[10,25,50,100,500]
		}


		this.datatable_options.ajax = {
			url:`${ROOT}ajax/index.php?uri=${this.controller}/get-datatable`,
			type:'POST',
			data:d=>{
				d.class = this.class
				d.token = TOKEN
				$.extend(d,this.set_filters())
				loading()
			}
		}
		this.datatable_options.fnDrawCallback = settings=>{
			loading({show:false})
		}


		/*** NEW ***/
		$('[data-toggle="new"]').on('click',btn=>{
			this.new()
		})



		/*** DELETE ***/
		this.table.on('click','[data-toggle="delete"]',async btn=>{
			let tr = $(btn.currentTarget).closest('tr')
			if(tr.hasClass('child')){
				let tr_prev = $(btn.currentTarget).closest('tr').prev()
				if(tr_prev.hasClass('parent')) tr = tr_prev
			}
			const id = tr.attr('data-id')
			const swal = await SwalWarn.fire({
				title:'¿Seguro deseas borrar este item?'
			})
			if(!swal.value) return false

			const response = await super.delete(id)
			///this.datatable_instance.fnUpdate()
			this.datatable_instance.ajax.reload()

		})


		/*** EDIT ***/
		this.table.on('click','[data-toggle="edit"]',btn=>{
			let tr = $(btn.currentTarget).closest('tr')
			if(tr.hasClass('child')){
				let tr_prev = $(btn.currentTarget).closest('tr').prev()
				if(tr_prev.hasClass('parent')) tr = tr_prev
			}
			const id = tr.attr('data-id')
			this.edition(id)
		})

		/*** SORT ***/
		this.table.find('tbody').sortable({
			update:(el,event)=>{
				this.listing = this.table.find('tbody')
				this.module = 'tr'
				return this.reorder(el)
			},
			handle:'[data-toggle="sort"]'
		})

	}



	reset(){
		$('[data-toggle="edition"]').addClass('d-none')
		$('[data-toggle="listing"]').removeClass('d-none')

		this.form.find('input[name], textarea[name]').val('')
		this.form.find('[name="id"]').val(0)
		if('extended_reset' in this) this.extended_reset()

		this.mode = 'listing'

		this.datatable_instance.ajax.reload()
	}

	async edition(id){
		const response = await super.find(id)
		const result = response.result
		if(result==false) return false

		this.mode = 'edition'

		$.each(result,(k,v)=>{
			this.form.find(`[name="${k}"]`).val(v)
		})

		if('extended_edition' in this) this.extended_edition(result)

		this.new()
	}


	new(){
		$('[data-toggle="edition"]').removeClass('d-none')
		$('[data-toggle="listing"]').addClass('d-none')
	}



	set_filters(){

		if(!('search_bar' in this)) return true

		let filters = {}
		$.each(this.search_bar.find('[name]'),(k,v)=>{
			filters[$(v).attr('name')] = $(v).val()
		})

		return {'filters':filters}
	}



}