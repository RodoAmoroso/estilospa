$(function(){


	$('#load_more').submit(function(e){
		e.preventDefault();
		var post = get_form(this);
		post.gift = 1;
		ajax('site/search/load_more_promos',post)
			.then(function(data){
				$('#load_more').find('[name="page"]').val(data.page);
				if(data.results==false){
					$('#load_more button').remove();
					return false;
				}
				if(data.results.length<=post.limit){
					$('#load_more button').remove();
				}
				$('.promos-highlight').append(data.body);
			});
	});


	$('[data-toggle="collapse-zones"]').click(function(e){
		e.preventDefault();
		$('#list_zones').toggleClass('active');
	});

});