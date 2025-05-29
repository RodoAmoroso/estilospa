var switchstatus = function(status){
	var btn;
	var label;
	switch(status){
		case '1':
			btn = 'warning';
			label = 'Pendiente';
			break;
		case '2':
			btn = 'success';
			label = 'Brindado';
			break;
		case '3':
			btn = 'danger';
			label = 'Cancelado';
			break;
	}
	return {btn:btn,label:label};
}

$(function(){


	$('.sale-actions [data-group="status"] a').click(function(e){
		e.preventDefault();
		var st = $(this).attr('data-value');
		var id = $(this).parent().parent().parent().attr('data-id');

		ajax('admin/sales/setstatus',{ID:id,Status:st})
			.then(function(DATA){
				window.location.reload();
			});

	});


});