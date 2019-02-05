<?php 

//https://www.mercadopago.com.ar/developers/es/solutions/payments/custom-checkout/plans-and-subscriptions/
require 'config.php';
require 'mercadopago/mercadopago.php';

$mp = new MP('APP_USR-7300466898804487-073114-cc9a24b886833a13ebc411e12dcd2887__LD_LB__-89899659'); // seller access_token

$preference_data = array(
	"items" => array(
		array(
			"title" => "Item title",
			"description" => "Description",
			"quantity" => 1,
			"unit_price" => 10,
			"currency_id" => "ARS",
			"picture_url" => "https://www.mercadopago.com/org-img/MP3/home/logomp3.gif"
		)
	),
	"marketplace_fee" => 2.29,
	"payer"=>array(
		"email"=>$_USER->data()->mail,
		"name"=>$_USER->data()->name,
		"surname"=>$_USER->data()->lastname
	),
	"back_urls"=>array(
		"success"=>ROOTPATH.'pago-status.php?status=success',
		"failure"=>ROOTPATH.'pago-status.php?status=failure',
		"pending"=>ROOTPATH.'pago-status.php?status=pending'
	),
	"payment_methods"=>array(
		"excluded_payment_methods"=>array(),
		"excluded_payment_types"=>array(array("id"=>"ticket"),array("id"=>"atm")),
		"installments"=>null
	)
);

$preference = $mp->create_preference($preference_data);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Pay</title>
	</head>
	<body>
		<a href="<?php echo $preference['response']['init_point']; ?>" name="MP-Checkout" class="blue-rn-m">Pay</a>
		<script type="text/javascript" src="https://www.mercadopago.com/org-img/jsapi/mptools/buttons/render.js"></script>
	</body>
</html>