
<!-- COPYRIGHT -->
<section class="bg-aqua-5 cl-white pad-16">
	<div class="container text-center">
		<p><img class="logo" src="<?= ROOTPATH ?>assets/logo-white.png" alt="" style="max-width:240px;padding:26px"></p>
		<p class="sz-9" >&copy; 2006 - <?= date('Y').' '.TITLE ?>. Todos los derechos reservados.</p>
	</div>
</section>



<!-- MESSAGES -->
<div class="modal fade" id="messages" tabindex="-1" role="dialog" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Info</h4>
			</div>
			<div class="modal-body ff-futuralight" ></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- LOADING -->
<div class="pop overprint-fixed dp-none" id="loading">
	<div class="overprint-absolute op-90 bg-white"></div>
	<div class="dp-table wd-100 hg-100" >
		<div class="dp-table-cell text-center">
			<i class="fa fa-cog fa-spin fa-lg"></i>
			<p class="loading-text"></p>
		</div>
	</div>
</div>


<?php require '../scripts.php'; ?>
<script type="text/javascript" src="<?= ROOTPATH.'js/lib/ckeditor/ckeditor.js' ?>"></script>
<script type="text/javascript" src="<?= ROOTPATH.'js/lib/ckeditor/adapters/jquery.js' ?>"></script>

<?php if(file_exists('js/'.$_SECTION.'.js')): ?>
<script type="text/javascript" src="<?= ADMINPATH.'js/'.$_SECTION.'.js?id='.rand(1111,9999) ?>"></script>
<?php endif; ?>

</body>
</html>