class Tables{

	constructor(_obj){

		this.controller = _obj.controller
		this.table = _obj.table

		this.datatable_options = {}

		this.datatable_options.pageLength = 50
		this.datatable_options.language = {
			url:`${ROOT}js/lib/dataTables/spanish.json`
		}
		this.datatable_options.dom = '<"table-spacer-top"lf>t<"table-spacer-bottom"ip>';
		this.datatable_options.serverSide = true
		this.datatable_options.responsive = true
		this.datatable_options.processing = true
		this.datatable_options.deferRender = true
		this.datatable_options.ordering = true
		this.datatable_options.order = [[0,'asc']]
		this.datatable_options.lengthMenu = [10,25,50,100,500]


		this.datatable_options.ajax = {
			url:`${ROOT}ajax/index.php?uri=${this.controller}/get-datatable`,
			type:'POST',
			data:d=>{
				$.extend(d,this.set_filters())
				loading()
			}
		}
		this.datatable_options.fnDrawCallback = settings=>{
			//console.log(settings.json)
			loading({show:false})
		}

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