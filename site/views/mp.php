<?php

//if( !isset($_REQUEST['code']) ) Redirect::to('home');
//if( !$User->logged() && $User->data()->idtype != 3 ) Redirect::to('home');

//$code = $_REQUEST['code'];
echo '<pre>';
print_r($_REQUEST);
echo '</pre>';
die();

///https://www.mercadopago.com/mla/account/credentials
//https://www.mercadopago.com/mla/herramientas/aplicaciones
//https://applications.mercadopago.com/

// rodo TG-595d7ac0e4b0e7023c808ec5-89899659

//https://auth.mercadopago.com.ar/authorization?client_id=7300466898804487&response_type=code&platform_id=mp&redirect_uri=https%3A%2F%2Festilospa.com.weblin.alsolnet.com/mp.php

require 'mercadopago/mercadopago.php';
$mp = new MP("APP_USR-7300466898804487-070519-065286686bbe9e2c819c57c7094d11da__LD_LC__-263157583");
//$mp = new MP("7300466898804487","4Y7yVlsccQUmJM3ExQT59JioiKPK113K");

$request = array(
	"uri" => "/oauth/token",
	"data" => array(
		"client_secret" => $mp->get_access_token(),
		"grant_type" => "authorization_code",
		"code" => $code,
		"redirect_uri" => "https://stilospa.com.ar/mp.php"
	),
	"headers" => array(
	"content-type" => "application/x-www-form-urlencoded"
	),
	"authenticate" => false
);
/*
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

$response = $mp->post($request);
echo '<pre>';
print_r($response['status']);
echo '</pre>';

if($response['status'] == 200):
	echo $response['response']['access_token'];
	$MPConfig = new MPConfig();
	$MPConfig->idclient = $User->data()->idclient;
	//$MPConfig->arrfields = array('access_token' => 'TEST-7300466898804487-070520-3821ae392619efb29ec18b0df5546def__LD_LB__-89899659', 'public_key' => 'TEST-a49eac21-e592-4c89-9b5d-98603f5cc4b7', 'refresh_token' => 'TG-595d7dede4b0c1fed76c3cd2-89899659', 'user_id' => '89899659', 'expires_in' => '15552000');
	$MPConfig->arrfields = $response['response'];
	$MPConfig->save();
	echo 'Tu cuenta de Mercado Pago ha sido vinculada con éxito. <a href="'.ROOT.'">volver</a>';
else:
	echo 'Hubo problemas al procesar su solicitud. Intenta nuevamente más tarde.';
endif;



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