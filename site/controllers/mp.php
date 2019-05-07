<?php 

if(!$User->logged()) Redirect::to('restricted');
if($User->data()->idtype!=3) Redirect::to('restricted');

$MPConfig = new MPConfig();
if($MPConfig->find($User->data()->idclient)) Redirect::to('panel/mp');

if(!isset($_REQUEST['code'])) Redirect::to('panel/mp');

$code = $_REQUEST['code'];

$request = array(
	"client_secret" => $MPConfig->secret_key,
	"client_id" => $MPConfig->app_id,
	"grant_type" => "authorization_code",
	"code" => $code,
	"redirect_uri" => $MPConfig->redirect_uri
);


$response = curl_post('https://api.mercadopago.com/oauth/token',$request);
$json = json_decode($response->response);

if($response->status==200){	
	$MPConfig->save(array(
		'idclient'=>$User->data()->idclient,
		'userid'=>$json->user_id,
		'access_token'=>$json->access_token,
		'public_key'=>$json->public_key,
		'refresh_token'=>$json->refresh_token,
		'expires_in'=>$json->expires_in,
		'added'=>date('Y-m-d H:i:s')
	));
}

/*if($response->status==200){
	
	die();


	$MPConfig->idclient = $User->data()->idclient;
	$MPConfig->arrfields = json_decode($response->response);
	//$MPConfig->save();

}else{
	$response = null;
}
*/


/*require 'mercadopago/mercadopago.php';
$mp = new MP($MPConfig->access_token);
$code = $_REQUEST['code'];

$request = array(
	"uri" => "/oauth/token",
	"data" => array(
		"client_secret" => $MPConfig->secret_key,
		"client_id" => $MPConfig->app_id,
		"grant_type" => "authorization_code",
		"code" => $code,
		"redirect_uri" => $MPConfig->redirect_uri
	),
	"headers" => array(
	"content-type" => "application/x-www-form-urlencoded"
	),
	"authenticate" => false
);

$response = null;
try {
	$response = $mp->post($request);

	$MPConfig->idclient = $User->data()->idclient;
	$MPConfig->arrfields = $response['response'];
	$MPConfig->save();

} catch (MercadoPagoException $e) {
	show_array($e->getMessage());
}*/
