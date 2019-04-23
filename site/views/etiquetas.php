
<section class="glossary-module-wrapper">
	<div class="container">
		<h2>Índice de Servicios y Tratamientos</h2>

		<?php if($GlossaryGroups->get()): foreach($GlossaryGroups->data() as $group): ?>

		<div id="grupo_<?=$group->id?>" class="glossary-module">
			<div class="glossary-header">
				<h2 class="title"><?= $group->name ?></h2>
				<small class="subtitle"><?=count($group->glossary)?> tratamientos</small>
			</div>
			<div class="glossary-body">
				<ul class="list">
					<?php if($group->glossary): foreach($group->glossary as $glossary): ?>
					<li class="item">
						<a href="<?=View::url('etiqueta',$glossary->id.'-'.Permalink($glossary->name))?>"><?= $glossary->name ?></a>
					</li>
					<?php endforeach; endif; ?>
				</ul>
			</div>			
		</div>
		<?php endforeach; endif; ?>

	</div>
</section>
