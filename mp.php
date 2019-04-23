<?php
require_once 'config.php';

require 'site/controllers/main.php';
require 'site/views/head.php';
include 'site/views/header.php';
include 'site/views/mainmenu.php';

/*echo $_REQUEST['code'].'<br /><br />';
echo $_USER->logged() ? 'loggeado' : 'no loggeado';
echo '<br />'.($_USER->data()->idtype != 3 ? 'no es cliente' : 'es cliente').'<br />';
die('Logged: '.$_USER->logged().' - IDU: '.$_USER->data()->id.' - IDC: '.$_USER->data()->idclient.' - IDT: '.$_USER->data()->idtype);
*/
// Redirect::to('home');
if( !isset($_REQUEST['code']) ):
?>
<section class="gral-section">
	<div class="container">
		<h1>Problemas :(</h1>
		<hr>
		<p>El código de activación es incorrecto</p>
		<a href="<?= ROOTPATH.'cuenta/promos' ?>">Volver</a>
	</div>
</section>
<?php
require 'site/views/footer.php';
die();
endif;
if( !$_USER->logged() ):
?>
<section class="gral-section">
	<div class="container">
		<h1>Problemas :(</h1>
		<hr>
		<p>No estás loggueado en el sistema, para poder realizar la operación debes loggearte</p>
		<a href="<?= ROOTPATH.'login' ?>">Volver</a>
	</div>
</section>
<?php
require 'site/views/footer.php';
die();
endif;
if( intval($_USER->data()->idtype) != 3 ):
?>
<section class="gral-section">
	<div class="container">
		<h1>Problemas :(</h1>
		<hr>
		<p>Tu usuario no tiene permiso para realizar esta operación. </p>
		<a href="<?= ROOTPATH.'login' ?>">Volver</a>
	</div>
</section>
<?php
require 'site/views/footer.php';
die();
endif;
////if( !$_USER->logged() && $_USER->data()->idtype != 3 ) die('No estás logueado en EstiloSPA o tu usuario no es un cliente - ID:'.$_USER->data()->id);


$MPConfig = new MPConfig();
if($MPConfig->find($_USER->data()->idclient)) Redirect::to('home');


require 'mercadopago/mercadopago.php';
$mp = new MP("APP_USR-7300466898804487-070519-065286686bbe9e2c819c57c7094d11da__LD_LC__-263157583");
$code = $_REQUEST['code'];
$request = array(
	"uri" => "/oauth/token",
	"data" => array(
		"client_secret" => $mp->get_access_token(),
		"grant_type" => "authorization_code",
		"code" => $code,
		"redirect_uri" => "https://www.estilospa.com/mp.php"
	),
	"headers" => array(
	"content-type" => "application/x-www-form-urlencoded"
	),
	"authenticate" => false
);
$response = $mp->post($request);
if($response['status'] == 200):	
	$MPConfig->idclient = $_USER->data()->idclient;
	$MPConfig->arrfields = $response['response'];
	$MPConfig->save();
?>

<section class="gral-section">
	<div class="container">
		<h1>Vinculación Correcta!!!</h1>
		<hr>
		<p>Tu cuenta de MercadoPago ha sido vinculada con éxito!. Ahora podés habilitar la venta online en todas tus promociones.</p>
		<a href="<?= ROOTPATH.'cuenta/promos' ?>">Volver</a>
	</div>
</section>

<?php else: ?>

<section class="gral-section">
	<div class="container">
		<h1>Lo Sentimos :(</h1>
		<hr>
		<p>Hubo un problema al procesar la solicitud. Intenta nuevamente más tarde. Si el problema persiste comunicate con nosotros </p>
		<a href="<?= ROOTPATH.'cuenta' ?>">Volver</a>
	</div>
</section>

<?php
endif;
require 'footer.php';


///https://www.mercadopago.com/mla/account/credentials
//https://www.mercadopago.com/mla/herramientas/aplicaciones
//https://applications.mercadopago.com/

// rodo TG-595d7ac0e4b0e7023c808ec5-89899659

//https://auth.mercadopago.com.ar/authorization?client_id=7300466898804487&response_type=code&platform_id=mp&redirect_uri=https%3A%2F%2Festilospa.com.weblin.alsolnet.com/mp.php


//$mp = new MP("7300466898804487","4Y7yVlsccQUmJM3ExQT59JioiKPK113K");


/*
REFRESH TOKEN
$request = array(
	"uri" => "/oauth/token",
	"data" => array(
		"client_secret" => $mp->get_access_token(),
		"grant_type" => "refresh_token",
		"refresh_token" => "USER_RT"
	),
	"headers" => array(
		"content-type" => "application/x-www-form-urlencoded"
	),
	"authenticate" => false
);*/


/*echo '<pre>';
print_r($response['status']);
echo '</pre>';*/

/*if($response['status'] == 200):
	//echo $response['response']['access_token'];
	$MPConfig = new MPConfig();
	$MPConfig->idclient = $_USER->data()->idclient;
	//$MPConfig->arrfields = array('access_token' => 'TEST-7300466898804487-070520-3821ae392619efb29ec18b0df5546def__LD_LB__-89899659', 'public_key' => 'TEST-a49eac21-e592-4c89-9b5d-98603f5cc4b7', 'refresh_token' => 'TG-595d7dede4b0c1fed76c3cd2-89899659', 'user_id' => '89899659', 'expires_in' => '15552000');
	$MPConfig->arrfields = $response['response'];
	$MPConfig->save();
	echo 'Tu cuenta de Mercado Pago ha sido vinculada con éxito. <a href="'.ROOTPATH.'">volver</a>';
else:
	echo 'Hubo problemas al procesar su solicitud. Intenta nuevamente más tarde.';
endif;*/



// Rodo response;
/*Array(
  [status] => 200
  [response] => Array(
    [access_token] => TEST-7300466898804487-070520-3821ae392619efb29ec18b0df5546def__LD_LB__-89899659
    [public_key] => TEST-a49eac21-e592-4c89-9b5d-98603f5cc4b7
    [refresh_token] => TG-595d7dede4b0c1fed76c3cd2-89899659
    [live_mode] => 
    [user_id] => 89899659
    [token_type] => bearer
    [expires_in] => 15552000
    [scope] => offline_access
    )
)*/

//user_id 263157583 // estilospa
?>