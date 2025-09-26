
<script type="text/javascript" src="<?= JS.'lib/jquery-3.3.1.min.js' ?>" ></script>
<script type="text/javascript" src="<?= JS.'lib/jquery-ui.min.js' ?>" ></script>
<script type="text/javascript" src="<?= JS.'lib/bootstrap.min.js' ?>" ></script>
<script type="text/javascript" src="<?= JS.'lib/bluebird.min.js' ?>" ></script>
<script type="text/javascript" src="<?= JS.'lib/wow.min.js' ?>" ></script>
<script type="text/javascript" src="<?= JS.'lib/jquery.scrollUp.min.js' ?>"></script>
<script type="text/javascript" src="<?= JS.'lib/toastr.min.js' ?>"></script>
<script type="text/javascript" src="<?= JS.'lib/sweetalert2.min.js' ?>"></script>
<script type="text/javascript" src="<?= JS.'lib/lazyload.min.js' ?>"></script>
<script type="text/javascript" src="<?= JS.'functions.js?id='.RAND ?>" ></script>


<!--- Plugins --->
<?php if(isset($_arrjs)): foreach($_arrjs as $js): ?>
<script type="text/javascript" src="<?= !isset($js['folder']) ? $js['script'] : JS.$js['folder'].$js['script'].'.js?id='.RAND ?>"></script>
<?php endforeach; endif; ?>


<!-- Section Scripts -->
<script type="text/javascript" src="<?= JS.'site/main.js?id='.RAND ?>"></script>

<?php if($_sectionpath): if($_jspath = View::loader('js','site','js')): ?>
<script type="text/javascript" src="<?= JS.$_jspath.'?id='.RAND ?>"></script>
<?php endif; endif; ?>

</body>
</html>