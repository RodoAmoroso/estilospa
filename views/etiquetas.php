<section class="gral-section">
	<div class="container">
		<h1>Índice de Servicios y Tratamientos</h1>
		<hr>

		<div class="glossary">
			<?php if($_GLOSSARYGROUPS->get()): foreach($_GLOSSARYGROUPS->data() as $group): ?>
			<div class="group">
				<h4 class="fw-400"><?= $group->name ?></h4>

				<?php 
				$_GLOSSARY->idgroup = $group->id;
				if($_GLOSSARY->get()):
					foreach($_GLOSSARY->data() as $glossary): 
				?>
				<a href="<?= ROOTPATH.'etiqueta/'.$glossary->id.'-'.Permalink($glossary->name) ?>" class="item"><?= $glossary->name ?></a>
				<?php endforeach; endif; ?>
			</div>
			<?php endforeach; endif; ?>
		</div>
	</div>
</section>