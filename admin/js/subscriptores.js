var Subscribers = {
	ID:0,
	get:function(){
		$('#subscribers').html('');
		AjaxConnection('jxSubscribers.php',{mode:'get',keywords:$('#form_search input[type="text"]').val()},function(data){
			if(data.results == null){return false;}
			$.each(data.results,function(k,v){
				var mod = Templates.mod_list();
				mod.find('h4').html(v.email);
				mod.find('.edit').remove();
				mod.find('.delete').attr('data-id',v.id);
				$('#subscribers').append(mod);
			});
			$('#subscribers .delete').click(function(){
				var id = $(this).attr('data-id');
				Messages(true,'¿Seguro deseas borrar este usuario?',function(){
					AjaxConnection('jxSubscribers.php',{mode:'delete',id:id},function(data){
						Subscribers.get();
					});
				});
			});
		});
	},
	init:function(){
		$('#form_search').submit(function(e){
			e.preventDefault();
			Subscribers.get();
		});
		Subscribers.get();
	}
}
$(function(){
	Subscribers.init();
});