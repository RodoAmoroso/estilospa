
<script type="text/javascript" src="<?= JS.'lib/jquery.min.js' ?>" ></script>
<script type="text/javascript" src="<?= JS.'lib/jquery-ui.min.js' ?>" ></script>
<script type="text/javascript" src="<?= JS.'lib/bootstrap.min.js' ?>" ></script>
<script type="text/javascript" src="<?= JS.'lib/bluebird.min.js' ?>" ></script>
<script type="text/javascript" src="<?= JS.'lib/wow.min.js' ?>" ></script>
<script type="text/javascript" src="<?= JS.'lib/jquery.scrollUp.min.js' ?>"></script>
<script type="text/javascript" src="<?= JS.'lib/sweetalert2.min.js' ?>"></script>
<script type="text/javascript" src="<?= JS.'lib/toastr.min.js' ?>"></script>
<script type="text/javascript" src="<?= JS.'functions.js?id='.rand(1111,9999) ?>" ></script>


<!--- Plugins --->
<?php if(isset($_arrjs)): foreach($_arrjs as $js): ?>
<script type="text/javascript" src="<?= !isset($js['folder']) ? $js['script'] : JS.$js['folder'].$js['script'].'.js' ?>"></script>
<?php endforeach; endif; ?>


<!-- Section Scripts -->
<script type="text/javascript" src="<?= PANEL.'js/main.js' ?>"></script>

<?php if($_sectionpath): if($_jspath = View::loader('js','js')): ?>
<script type="text/javascript" src="<?= PANEL.$_jspath.'?id='.rand(1111,9999) ?>"></script>
<?php endif; endif; ?>

</body>
</html>