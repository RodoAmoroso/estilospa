$(function(){

	$('#form_category').submit(function(e){
		e.preventDefault();

		var post = get_form(this);
		post.visible = $('#form_category [name="visible"]').is(':checked') ? 1 : 0;
		if($('#thumb').attr('data-filename')=='' || $('#thumb').attr('data-filename') == undefined){
			Swal.fire({
				type:'warning',
				text:'Debes subir una imagen primero'
			});
			return false;
		}
		post.image = {
			f:$('#thumb').attr('data-filename'),
			e:$('#thumb').attr('data-extension')
		}
		ajax('admin/promos-categories/save',post)
			.then(function(){
				window.location.href=ADMIN+'promos-categorias';
			});
	});


	var image = new UpFile({
		container:'[data-input="image"]',
		controller:'admin/promos-categories/image',
		folder:'img/categories',
		thumbnail:'#thumb',
		sufix:'-t'
	});

});