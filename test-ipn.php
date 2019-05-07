<?php 

require_once 'config.php';
require_once 'vendor/autoload.php';


if (!isset($_GET["id"], $_GET["topic"]) || !ctype_digit($_GET["id"])) {
	http_response_code(400);
	return;
}

$id = $_GET["id"];
$topic = $_GET["topic"];
$idclient = $_GET["idclient"];

$MPConfig = new MPConfig();

MercadoPago\SDK::setClientId($MPConfig->app_id);
MercadoPago\SDK::setClientSecret($MPConfig->secret_key);


if($topic == 'payment'){
	try {
		$payment_info = MercadoPago\Payment::find_by_id($id);
		show_array($payment_info);		
	} catch (Exception $e) {
		show_array($e->getMessage());
		http_response_code(400);
	}
}

die();



/////////////////


/*$DB->insert('testmp',array(
	'collection_id'=>$id,
	'topic'=>$topic,
	'input'=>json_encode($_GET)
));
http_response_code(200);
die();*/

$clientmp = $DB->get('mp',array('idclient','=',$idclient))->first();
$mp = new MP($clientmp->access_token); ///token del seller
//$MPConfig = new MPConfig();
//$mp = new MP($MPConfig->access_token);

if($topic == 'payment'){
	try {
		//$payment_info = $mp->get('/v1/payments/'.$id);
		$payment_info = $mp->get('/v1/payments/'.$id);
		$merchant_order = $mp->get('/merchant_orders/'.$payment_info['response']['order']['id']);		
		//$orderid = $payment_info['response']['order']['id'];
		show_array($payment_info);		
	} catch (Exception $e) {
		show_array($e->getMessage());
		http_response_code(400);
	}
}

if($topic == 'merchant_order'){
	try{
		$merchant_order = $mp->get('/merchant_orders/'.$id);
		show_array($merchant_order);
	}catch(Exception $e){
		show_array($e->getMessage());
		http_response_code(400);
	}
}


$paid_amount = 0;
foreach($merchant_order['response']['payments'] as $payment){
	if($payment['status'] == 'approved'){
		$paid_amount += $payment['transaction_amount'];
		echo $paid_amount;
	}
}

if($paid_amount >= $merchant_order['response']['total_amount']){
	echo 'pagado! '.$paid_amount;
}
http_response_code(200);