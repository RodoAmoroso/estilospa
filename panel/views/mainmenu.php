
<?php
$arrAdminMenu = array(
	//array('name'=>'Inicio','permalink'=>'inicio'),
	array('name'=>'Inicio','permalink'=>''),
	array('name'=>'Mi Centro','permalink'=>'mi-centro'),
	array('name'=>'Experiencias','permalink'=>'promos'),
	array('name'=>'Reservas','permalink'=>'reservas'),
	array('name'=>'Calendario','permalink'=>'calendario'),
	array('name'=>'Preguntas','permalink'=>'preguntas'),
	array('name'=>'Mis Ventas','permalink'=>'mi-cuenta'),
	array('name'=>'Vinculación con Mercado Pago','permalink'=>'mp')
	//array('name'=>'Estadísticas','permalink'=>'estadisticas')
);
?>


<!-- MAIN MENU -->
<nav>
	<div class="container">

		<i class="fa fa-bars fa-lg cl-white" ></i>

		<ul id="main_menu" class="main-menu" >
			<?php
			foreach($arrAdminMenu as $kmm=>$vmm):
				$active = '';
				if($_section == $vmm['permalink']):
					$active = 'active';
				endif;
			?>
			<li class="<?= $active ?>" ><a href="<?= ROOT.'panel/'.$vmm['permalink'] ?>" ><?= $vmm['name'] ?></a></li>
			<?php endforeach ?>
		</ul>

	</div>
</nav>

