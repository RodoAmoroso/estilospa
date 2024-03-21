
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge" >
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" >

	<meta name="description" content="<?= isset($_DESCRIPTION) ? $_DESCRIPTION : DESCRIPTION ?>" >
	<meta name="keywords" content="<?= isset($_KEYWORDS) ? $_KEYWORDS : KEYWORDS ?>" >


  <title><?= isset($_TITLE) ? $_TITLE : TITLE ?></title>

	<link rel="stylesheet" href="<?= CSS ?>lib/bootstrap.min.css" >
	<link rel="stylesheet" href="<?= CSS ?>lib/jquery-ui.min.css" >
	<link rel="stylesheet" href="<?= CSS ?>lib/font-awesome.min.css" >
	<link rel="stylesheet" href="<?= CSS ?>lib/animate.css" >
	<link rel="stylesheet" href="<?= CSS ?>lib/sweetalert2.min.css" >
	<link rel="stylesheet" href="<?= CSS ?>lib/toastr.min.css" >

	<?php if(isset($_arrcss)): foreach ($_arrcss as $css): ?>
	<link rel="<?= isset($css['rel']) ? $css['rel'] : 'stylesheet' ?>" href="<?= CSS.$css['folder'].$css['style'].'.css?id='.rand(1111,9999) ?>" <?= isset($css['media']) ? 'media="'.$css['media'].'"' : '' ?> >
	<?php endforeach; endif; ?>

	<link rel="stylesheet" href="<?= CSS.'styles.css?id='.rand(1111,9999) ?>" >

	<link rel="shortcut icon" href="<?= View::assets('favicon.png') ?>" type="image/png" >

	<?php
		$_IMGFACEBOOK = isset($_IMGFACEBOOK) ? $_IMGFACEBOOK : 'assets'.DS.'logo-400x400.jpg';
		$_IMGSIZE = getimagesize(PATH.$_IMGFACEBOOK);
		$_URLHEAD = isset($_URLHEAD) ? $_URLHEAD : ROOT;
		$_DESCRIPTION = isset($_DESCRIPTION) ? $_DESCRIPTION : DESCRIPTION;
		$_TITLE = isset($_TITLE) ? $_TITLE : TITLE;
	?>

	<link rel="canonical" href="<?= $_URLHEAD ?>">
	<link rel="amphtml" href="<?= $_URLHEAD ?>">

	<meta property="fb:app_id" content="225559017576907" />
	<meta property="og:url" content="<?= $_URLHEAD ?>" />
	<meta property="og:type" content="website"/>
	<meta property="og:title" content="<?= $_TITLE ?>" />
	<meta property="og:description" content="<?= $_DESCRIPTION ?>" />
	<meta property="og:image" content="<?= ROOT.$_IMGFACEBOOK ?>" />
	<meta property="og:image:width" content="<?= $_IMGSIZE[0] ?>" />
	<meta property="og:image:height" content="<?= $_IMGSIZE[1] ?>" />

	<meta property="og:locale" content="es_ES" />
	<meta property="og:site_name" content="<?= $_TITLE ?>" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="@estilospa" />
	<meta name="twitter:title" content="<?= $_TITLE ?>" />
	<meta name="twitter:description" content="<?= $_DESCRIPTION ?>" />
	<meta name="twitter:image:src" content="<?= ROOT.$_IMGFACEBOOK ?>" />

	<meta name="facebook-domain-verification" content="l2zx8db4sq2njoo49xdot3gj0wteep" />

	<script>
		var ROOT = '<?= ROOT ?>';
		var PAGENAME = '<?= PAGENAME ?>';
		var MAXFILES = '<?= MAXFILES ?>';
		var _section = '<?= $_section ?>';
		var _subsection = '<?= $_subsection ?>';
		var _vars = '<?= $_vars ?>';

		var TOKEN = '<?= TOKEN ?>';
	</script>

	<!-- Google Analytics -->
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-106389212-1', 'auto');
	ga('send', 'pageview');
	</script>

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-124Y0RV7PH"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'G-124Y0RV7PH');
	</script>


	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '594316039509446');
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=594316039509446&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Facebook Pixel Code -->


	<meta name="google-site-verification" content="hHQNIXKcIha1J4C82llTavEIvXLE2DC2mINd-LL0vzc" />


</head>


<body>