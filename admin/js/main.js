var datatable_options = {
	language:{
		url:ROOT+'js/lib/dataTables/spanish.json',
	},
	dom:'<"table-spacer-top"lf><"html5buttons"B>t<"table-spacer-bottom"ip>',
	responsive:true,
	pageLength:100

}

$(function(){


	$('[title]').tooltip();

	$('nav .fa-bars').click(function(){
		$('#main_menu').slideToggle();
	});
	$(window).resize(function(){
		if($(window).width()>767){
			$('#main_menu').removeAttr('style').slideDown();
		}
	});

	$('[data-toggle=slide]').click(function(e){
		e.preventDefault();
		var ref = $(this).attr('href');
		$(ref).toggleClass('active');
	});

	$('[data-toggle="switch"]').click(function(){
		$(this).find('i').toggleClass('fa-toggle-off fa-toggle-on');
		$(this).find('span').text($(this).find('i').hasClass('fa-toggle-on') ? 'Si' : 'No');
	});
	$('[data-toggle="btn-checkbox"]').click(function(){
		$(this).find('i').toggleClass('fa-square fa-check-square');
	});


	$.datepicker.regional['es'] = {closeText:'Cerrar',prevText:'<Ant',nextText:'Sig>',currentText:'Hoy',monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],monthNamesShort:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],dayNames:['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],dayNamesShort:['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],dayNamesMin:['Do','Lu','Ma','Mi','Ju','Vi','Sá'],weekHeader: 'Sm',dateFormat:'dd/mm/yy',firstDay:1,isRTL:false,showMonthAfterYear:false,yearSuffix:''};
	$.datepicker.setDefaults($.datepicker.regional['es']);




});