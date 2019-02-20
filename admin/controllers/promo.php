<?php 

$_promo = new Promos();
$_promo->find($_SUBSECTION);
$promodata = $_promo->data();
//show_array($promodata);