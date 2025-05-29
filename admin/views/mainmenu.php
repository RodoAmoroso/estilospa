<div class="main-nav-mobile-button">
	<i class="fa fa-bars fa-lg cl-white" ></i>
</div>

<section id="page-wrapper">

	<!-- MAIN MENU -->
	<nav class="main-nav">

		<ul id="main_menu" class="main-menu" >
			<?php
			foreach($arrAdminMenu as $kmm=>$vmm):
				$active = '';
				if(($_section == $vmm['permalink']) || (isset($vmm['active-links']) && in_array($_section, $vmm['active-links'])) ):
					$active = 'active';
				endif;
			?>
			<li class="<?= $active ?>" >

				<a href="<?= isset($vmm['submenu']) ? '#' : ROOT.'admin/'.$vmm['permalink'] ?>" <?= isset($vmm['submenu']) ? 'data-bs-toggle="collapse" data-bs-target="#submenu_'.$kmm.'"' : '' ?> >
					<span><?= $vmm['name'] ?></span>
					<?php if(isset($vmm['submenu'])): ?>
					<i class="fa fa-caret-down fa-fw"></i>
					<?php endif; ?>
				</a>

				<?php if(isset($vmm['submenu'])): ?>
				<ul id="submenu_<?= $kmm ?>" class="submenu collapse <?= $active ? 'show' : '' ?>">
					<?php
					foreach($vmm['submenu'] as $submenu):
						$subactive = '';
						if($_section==$submenu['permalink']) $subactive = 'active';
					?>
					<li class="submenu-item <?= $subactive ?>">
						<a href="<?= ROOT.'admin/'.$submenu['permalink'] ?>"><?= $submenu['name'] ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>



			</li>
			<?php endforeach ?>
		</ul>

	</nav>

	<div id="page-content-wrapper">
