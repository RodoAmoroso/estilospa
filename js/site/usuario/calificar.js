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

	$('[name="comment"]').keyup(function(){
		var dif = 500-$(this).val().length;		
		$('maxchar').text(dif);
	});

	$('#form_qualify').submit(async e=>{
		e.preventDefault();
		
		if($('.stars-wrapper input:checked').val() == undefined){
			Swal.fire({
				text:'Seleccioná la cantidad de estrellas primero',
				type:'warning'
			});
			return false;
		}
		const post = get_form(e.delegateTarget);
		const response = await ajax('site/users/qualify',post)
		
		const swal = await Swal.fire({
			type:'success',
			html:response.message,
			allowOutsideClick:false
		});		
		window.location.href = ROOT+'usuario/mis-experiencias';
	});
});