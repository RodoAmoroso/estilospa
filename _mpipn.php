<?php
require_once "mercadopago/mercadopago.php";

//$mp = new MP("389403748152273", "YVDiCxOKBqhOZ4Y6bdbWYd2PLTFan8rj");

$mp = new MP("APP_USR-7300466898804487-070519-065286686bbe9e2c819c57c7094d11da__LD_LC__-263157583"); #EstiloSPA
//$mp = new MP("APP_USR-7300466898804487-091315-14e5243d7e3a01a16c7dfb77eab4afac__LC_LB__-105427104"); #Réveils
//$mp = new MP("APP_USR-7300466898804487-080713-2b43115ffb78a4fe333d34ed1fe6968e__LC_LA__-151097201"); #Meliá

/*$json_event = file_get_contents('php://input', true);
$event = json_decode($json_event);

if (!isset($event->type, $event->data) || !ctype_digit($event->data->id)) {
	http_response_code(400);
	return;
}
*/
//if ($event->type == 'payment'){
if(!isset($_REQUEST['id'])){
    die('faltan parámetros');
}
$collection_id = $_REQUEST['id'];
$payment_info = $mp->get('/v1/payments/'.$collection_id);

echo '<pre>';
if ($payment_info["status"] == 200) {
    print_r($payment_info["response"]);
}
echo '</pre>';
//}


//http://localhost/estilospa/_mpipn.php?id=3058404285
//http://localhost/estilospa/_mpipn.php?id=3058403399
//http://localhost/estilospa/_mpipn.php?id=3072497600

/*Array
(
    [id] => 3015521280
    [date_created] => 2017-09-26T22:15:17.000-04:00
    [date_approved] => 2017-09-26T22:15:17.000-04:00
    [date_last_updated] => 2017-09-26T22:15:17.000-04:00
    [date_of_expiration] => 
    [money_release_date] => 2017-10-10T22:15:17.000-04:00
    [operation_type] => regular_payment
    [issuer_id] => 326
    [payment_method_id] => visa
    [payment_type_id] => credit_card
    [status] => approved
    [status_detail] => accredited
    [currency_id] => ARS
    [description] => PACK ELECTRODOS prueba
    [live_mode] => 1
    [sponsor_id] => 
    [authorization_code] => 009496
    [money_release_schema] => 
    [counter_currency] => 
    [metadata] => Array
        (
        )

    [additional_info] => Array
        (
        )

    [order] => Array
        (
            [type] => mercadopago
            [id] => 569541371
        )

    [external_reference] => 
    [transaction_amount] => 50
    [transaction_amount_refunded] => 0
    [coupon_amount] => 0
    [differential_pricing_id] => 
    [deduction_schema] => 
    [transaction_details] => Array
        (
            [net_received_amount] => 37.23
            [total_paid_amount] => 50
            [overpaid_amount] => 0
            [external_resource_url] => 
            [installment_amount] => 50
            [financial_institution] => 
            [payment_method_reference_id] => 
            [payable_deferral_period] => 
            [acquirer_reference] => 751451221516
        )

    [fee_details] => Array
        (
            [0] => Array
                (
                    [type] => mercadopago_fee
                    [amount] => 2.76
                    [fee_payer] => collector
                )

            [1] => Array
                (
                    [type] => application_fee
                    [amount] => 10.01
                    [fee_payer] => collector
                )

        )

    [captured] => 1
    [binary_mode] => 
    [call_for_authorize_id] => 00
    [statement_descriptor] => WWW.MERCADOPAGO.COM
    [installments] => 1
    [card] => Array
        (
            [id] => 
            [first_six_digits] => 425821
            [last_four_digits] => 9086
            [expiration_month] => 
            [expiration_year] => 
            [date_created] => 
            [date_last_updated] => 
            [cardholder] => Array
                (
                    [name] => Federico dominguez
                    [identification] => Array
                        (
                            [number] => 26338705
                            [type] => DNI
                        )

                )

        )

    [notification_url] => 
    [refunds] => Array
        (
        )

    [processing_mode] => 
    [merchant_account_id] => 
    [acquirer] => 
    [merchant_number] => 
)
*/