<?php

$vars = explode('-',$_SUBSECTION);

if(count($vars)>1):

	if($_USER->isActive($vars[0])):
		$message = '<h1>¡Activada!</h1><hr><p>Tu cuenta ya ha sido activada con anteriodidad. Puedes ingresar con tu usuario y contraseña <a href="'.ROOTPATH.'login" ><u>desde aquí</u></a>.</p>';
	else:

		$db = DB::getInstance();
		$db->query("SELECT id FROM spa_users WHERE id={$vars[0]} AND hash='{$vars[1]}'");
		if(!$db->count()):
			$message = '<h1>Algo ocurrió mal.</h1><hr><p>Hubo problemas al procesar la solicitud. Intenta nuevamente más tarde.<br /><br /> Si el problema persiste comunícate con nosotros <a href="'.ROOTPATH.'contacto">'.ROOTPATH.'contacto</a> </p>';
		else:
			$db->update('users',$vars[0],array('active'=>1));
			$message = '<h1>Gracias!</h1><hr><p>¡Tu cuenta ha sido activada con éxito! Ahora puedes ingresar con tu usuario y contraseña <a href="'.ROOTPATH.'login" ><u>desde aquí</u></a>.</p>';
			
		endif;
	endif;

else:
	$message = '<h1>Lo sentimos :(</h1><hr><p>Hubo problemas al procesar la solicitud. Intenta nuevamente más tarde.<br /><br /> Si el problema persiste comunícate con nosotros <a href="'.ROOTPATH.'contacto">'.ROOTPATH.'contacto</a> </p>';
endif;
?>

<section class="gral-section">
	<div class="container">
		<?= $message ?>
	</div>
</section>