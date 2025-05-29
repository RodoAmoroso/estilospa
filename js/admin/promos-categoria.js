$(function(){

	$('#form_category').submit(async form=>{
		form.preventDefault();

		const post = get_form(form.currentTarget);

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

		const response = await ajax('admin/promos-categories/save',post)
		window.location.href=`${ADMIN}promos-categorias`

	});


	var image = new UpFile({
		container:'[data-input="image"]',
		controller:'admin/promos-categories/image',
		folder:'img/categories',
		thumbnail:'#thumb',
		sufix:'-o'
	});

});