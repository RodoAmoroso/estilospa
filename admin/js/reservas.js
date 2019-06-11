$(function(){

	$('[name="date_from"],[name="date_to"]').datepicker();

	var datatable_options = {
		language:{
			url:ROOT+'js/lib/dataTables/spanish.json',
		},
		dom:'<"table-spacer-top"lf>t<"table-spacer-bottom"ip>',
		responsive:true,		
		columns:[
			{orderable:false},
			{orderable:false},
			null,
			null,
			null,
			null,
			null
		]		
	}

	 $('#reservations').DataTable(datatable_options);


});