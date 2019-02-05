
<?php 
$arrAdminMenu = array(
	//array('name'=>'Inicio','permalink'=>'inicio'),
	array('name'=>'Inicio','permalink'=>''),
	array('name'=>'Mi Centro','permalink'=>'mi-centro'),
	array('name'=>'Promos','permalink'=>'promos'),
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
				if($_SECTION == $vmm['permalink']):
					$active = 'active';
				endif;
			?>
			<li class="<?= $active ?>" ><a href="<?= ROOTPATH.'cuenta/'.$vmm['permalink'] ?>" ><?= $vmm['name'] ?></a></li>
			<?php endforeach ?>			
		</ul>

	</div>
</nav>

