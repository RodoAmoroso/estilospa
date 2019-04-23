
<section class="blog">
	<div class="container">
		<!-- MENU -->
		<ul class="button-menu">		
			<li class="<?= $idcategory ? '' : 'active' ?>"><a href="<?= ROOT.'blog' ?>">Todas</a></li>
			<?php 
			if($BlogCategories->get()):
				foreach($BlogCategories->data() as $cat):
			?>
			<li class="<?= $idcategory==$cat->id ? 'active' : '' ?>" ><a href="<?= ROOT.'blog/'.$cat->id.'-'.Permalink($cat->name) ?>"><?= $cat->name ?></a></li>			
			<?php endforeach; endif; ?>
		</ul>

		<hr>


		<div class="row">
			<?php
			$Blog->idcategory = $idcategory;
			$Blog->limit = '0,24';
			if($Blog->get()):
				foreach($Blog->data() as $blog):
					echo '<div class="col-xs-12 col-sm-6 col-md-4">';
					$img = json_decode($blog->gallery);
					include 'mods/mod-blog.php';
					echo '</div>';
				endforeach;
			endif;
			?>			
		</div>

		<hr>


		<ul class="button-menu dp-none">
			<li class="active"><a href="<?= ROOT.'blog' ?>">1</a></li>
			<li><a href="<?= ROOT.'blog/1-eventos' ?>">2</a></li>
			<li><a href="<?= ROOT.'blog/1-celebrities' ?>">3</a></li>
			<li><a href="<?= ROOT.'blog/1-novedades' ?>">4</a></li>
			<li><a href="<?= ROOT.'blog/1-notas' ?>">5</a></li>
		</ul>


	</div>
</section>