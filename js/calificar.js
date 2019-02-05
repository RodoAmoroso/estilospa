var Qualify = function(){
	AjaxConnection('jxUsers.php',{
		Mode:'qualify',
		Comment:$('#fd_comment').val(),
		Rate:$('.stars-wrapper input:checked').val(),
		IDSale:idsale
	},function(DATA){
		console.log(DATA);
		$('#qualify_wrapper').html('<div class="col-xs-12 sz-14"><p>Gracias por compartir tu experiencia con EstiloSPA.com!!!</p><p>Con tu aporte podemos mejorar y ofrecer un mejor servicio día a día.</p></div>');
		$('body,html').animate({scrollTop:0});
	});
}
$(function(){
	$('.qualify .stars-wrapper > label').hover(
		function(){
			var indx = $(this).index('label');
			$('.star-text-wrapper span').removeClass('active');
			$('.star-text-wrapper span:eq('+indx+')').addClass('visible');
		},
		function(){
			$('.star-text-wrapper span').removeClass('active visible');
			if($('.stars-wrapper input:checked').val() != undefined){
				$('.star-text-wrapper span:eq('+(5-$('.stars-wrapper input:checked').val() )+')').addClass('active');
			}
		}
	).click(function(){
		var indx = $(this).index('label');
		$('.star-text-wrapper span').removeClass('active');
		$('.star-text-wrapper span:eq('+indx+')').addClass('active');
	});
	$('#fd_comment').keyup(function(){
		var dif = 500-$(this).val().length;
		$('maxchar').text(dif);
	});
	$('#btn_qualify').click(function(){
		if($('#fd_comment').val().length < 3){
			Messages(true,'Debes completar con un breve texto, cómo fue tu experiencia.');
			return false;
		}
		if($('.stars-wrapper input:checked').val() == undefined){
			Messages(true,'Debes seleccionar la cantidad de estrellas primero');
			return false;
		}
		Qualify();
	});
});