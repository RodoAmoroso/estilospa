var RebuildSearch = function(){
	var main = [];
	var location = [];
	$.each($('.left-column .button-menu li'),function(){
		if($(this).attr('data-word') == 'main'){
			main.push( $(this).find('span').text().permalink() );
		}
		if($(this).attr('data-word') == 'location'){
			location.push( $(this).find('span').text().permalink() );
		}
	});
	window.location.href=ROOT+'busqueda/'+(main.length == 0 ? '-' : main.join('-'))+'/'+location.join('-');
}
$(function(){
	$('.left-column .button-menu a').click(function(e){
		e.preventDefault();
		$(this).parent().remove();
		RebuildSearch();
	});
	$('.left-column .list > li > ul > li > a').click(function(e){
		e.preventDefault();
		var txt='';
		var wordtype = '';
		var pass = true;
		//if(!$(this).parent().has('ul')){
		txt = $(this).find('span:eq(0)').text().toLowerCase();
		wordtype = $(this).parent().attr('data-word');
		//}
		$.each($('.left-column .button-menu li'),function(k,v){
			if($(this).find('span:eq(0)').text().toLowerCase() == txt && $(this).attr('data-word') == wordtype){
				pass = false;
			}
		});
		if($(this).parent().has('ul').length == 0){
			if(pass){
				$('.left-column .button-menu').append('<li data-word="'+wordtype+'"><a href="#" ><span>'+txt+'</span> <i class="fa fa-times"></i> </a></li> ');
			}
			RebuildSearch();
		}
	});
});