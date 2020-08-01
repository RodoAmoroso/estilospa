
<!-- MAIN MENU -->
<nav class="main-nav">
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

