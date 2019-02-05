$(function(){
	$('#fd_unlink').click(function(){
		Messages(true,'¿Seguro deseas desvincular la cuenta de MercadoPago? Ya no podrás usar la opción de venta online en las promos',function(){
			AjaxConnection('jxClients.php',{Mode:'unlink'},function(DATA){
				window.location.reload();
			});
		});
	});
});