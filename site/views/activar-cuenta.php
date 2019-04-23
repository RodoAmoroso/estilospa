<?php

if($User->isActive($userid)):
	$message = '<h1>¡Activada!</h1><hr><p>Tu cuenta ya ha sido activada con anteriodidad. Podés ingresar con tu usuario y contraseña <a href="'.View::url('login').'" ><u>desde aquí</u></a>.</p>';
else:

	if(!$User->activate($userid,$hash)):
		$message = '<h1>Algo ocurrió mal.</h1><hr><p>Hubo problemas al procesar la solicitud. Intentá nuevamente más tarde.<br /><br /> Si el problema persiste comunicate con nosotros <a href="'.View::url('contacto').'">contacto</a> </p>';
	else:		
		$message = '<h1>Gracias!</h1><hr><p>¡Tu cuenta ha sido activada con éxito! Ahora podés ingresar con tu usuario y contraseña <a href="'.View::url('login').'" ><u>desde aquí</u></a>.</p>';
		
	endif;
endif;

?>

<section class="gral-section">
	<div class="container">
		<?= $message ?>
	</div>
</section>