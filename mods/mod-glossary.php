<a href="<?= ROOT.'etiqueta/'.$glossary->id.'-'.Permalink($glossary->name) ?>" class="mod-mosaic col-xs-6 col-sm-4 col-md-3" >	
	<img src="<?= ROOT ?>assets/blank-rectangle.gif" class="wd-100" alt="">
	<div class="bg overprint-absolute grayscale op-60" style="background-image:url(<?= ROOT.$img ?>)"></div>
	<div class="caption">
		<h4><?= $glossary->name ?></h4>
	</div>	
</a>