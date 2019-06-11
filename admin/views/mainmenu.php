
<?php 
$arrAdminMenu = array(
	array('name'=>'Inicio','permalink'=>'inicio'),
	array('name'=>'Actividad','permalink'=>'actividad'),
	array('name'=>'Home','permalink'=>'home'),
	array('name'=>'Centros','permalink'=>'centros'),
	array('name'=>'Promos','permalink'=>'promos'),
	array('name'=>'Usuarios','permalink'=>'usuarios'),
	array('name'=>'Ventas','permalink'=>'ventas'),
	array('name'=>'Etiquetas','permalink'=>'etiquetas'),
	array('name'=>'Preguntas','permalink'=>'preguntas'),
	array('name'=>'Reservas','permalink'=>'reservas'),
	array('name'=>'Blog','permalink'=>'blog'),
	///array('name'=>'Estadísticas','permalink'=>'estadisticas'),
	//array('name'=>'Opciones Generales','permalink'=>'opciones-generales'),
	array('name'=>'Vouchers','permalink'=>'vouchers'),
	array('name'=>'Subscriptores','permalink'=>'subscriptores'),
	array('name'=>'Vinculaciones','permalink'=>'vinculaciones')
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
			<li class="<?= $active ?>" ><a href="<?= ROOT.'admin/'.$vmm['permalink'] ?>" ><?= $vmm['name'] ?></a></li>
			<?php endforeach ?>			
		</ul>

	</div>
</nav>

