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

	$('#btn_search_bar').click(function(){
		$('#search_bar').slideToggle();
	});

	$('[data-toggle="switch"]').click(function(){
		$(this).find('i').toggleClass('fa-toggle-off fa-toggle-on');
		$(this).find('span').text($(this).find('i').hasClass('fa-toggle-on') ? 'Si' : 'No');
	});
	$('[data-toggle="btn-checkbox"]').click(function(){
		$(this).find('i').toggleClass('fa-square fa-check-square');
	});

	$.scrollUp({scrollText:'<i class="fa fa-angle-up"></i>'});


	SearchSuggestions('#form_main_search','site/search','',function(INPUT,ths){});

	$('#form_main_search').submit(function(e){
		e.preventDefault();
		var main = $(this).find('[name="main"]').val();
		var location = $(this).find('[name="location"]').val();
		var type = $(this).find('[name="type"]').val();
		window.location.href=ROOT+type+'/'+(main==''?'-':main.permalink())+'/'+location.permalink();
	});
	$('#form_main_search [name="type"]').change(function(){
		$('#form_main_search').trigger('submit');
	});


	$('[data-btn-action="fav"]').click(function(e){
		var _this = this;
		e.preventDefault();
		var promoid = $(this).attr('data-promoid') == undefined ? 0 : $(this).attr('data-promoid');
		var clientid = $(this).attr('data-clientid') == undefined ? 0 : $(this).attr('data-clientid');
		ajax('site/users/favs',{promoid:promoid,clientid:clientid})
			.then(function(data){
				$(_this).find('i').removeClass();
				if(data.is_fav==1){
					$(_this).find('i').addClass('fa fa-heart active');
				}else{
					$(_this).find('i').addClass('fa fa-heart-o');
				}
			});
	});



	$('#form_newsletter').submit(function(e){
		e.preventDefault();
		var post = get_form(this);
		ajax('site/forms/newsletter',post)
			.then(function(data){
				$('#form_newsletter input').val('');
				Swal.fire({type:'success',html:data.message});				
			});
	});


	$('[data-toggle=scrollto]').click(function(e){
		e.preventDefault();
		var target = $(this).attr('data-target');
		$('body,html').animate({scrollTop:$(target).offset().top-100});
	})


});