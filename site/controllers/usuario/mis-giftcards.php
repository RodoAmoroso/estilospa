<?php

if(!$User->logged()) Redirect::to('home');

$GiftCardsPurchases = new GiftCardsPurchases;
$GiftCardsPurchases->filters = [
  'user'=>$_userdata->id,
  'payment_status'=>'approved'
];
$giftcards_purchases = $GiftCardsPurchases->get();