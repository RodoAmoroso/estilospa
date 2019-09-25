$(function(){

	var voucher = {
		generate:function(){

			var post = get_form('#form_voucher');
			post.image = '';

			if($('#image').attr('data-filename') != undefined){
				post.image = {f:$('#image').attr('data-filename'), e:$('#image').attr('data-extension')}
			}			

			ajax('site/sales/add',post)
				.then(function(data){
					window.location.reload();					
				});
			
		}
	}

	$('#form_voucher').submit(function(e){
		e.preventDefault();		
		voucher.generate();
	});

	$('[name="gift"]').change(function(){
		if(this.value==1){
			$('#fieldset_gift').slideDown();
			$('#form_voucher [name="to_user"], #form_voucher [name="message"]').prop('required',true);
		}else{
			$('#fieldset_gift').slideUp();
			$('#form_voucher [name="to_user"], #form_voucher [name="message"]').prop('required',false);
		}
	});


	var upimage = new UpFile({
		container:'[data-input="image"]',
		controller:'site/sales/upimage',
		folder:'img/gift',
		thumbnail:'#image',
		scope:'site',
		sufix:''
	});


});