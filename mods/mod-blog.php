<a href="<?= ROOT.'blog-pagina/'.$blog->id.'-'.Permalink($blog->title) ?>" class="mod-news">
	<div class="bg-gray-10 thumb-cover" style="background-image:url(<?= ROOT.'img/blog/'.$img[0]->photoname.'-t.'.$img[0]->extension ?>)"><img src="<?= ROOT.'assets/blank-rectangle.gif' ?>" class="wd-100" alt=""></div>
	<div class="caption">
		<h4><?= $blog->title ?></h4>
		<i><?= $blog->fecha ?></i>
		<p><?= $blog->shortdescription ?></p>
		<div class="readmore">
			<i class="sz-9">Leer más...</i>
		</div>
	</div>
</a>