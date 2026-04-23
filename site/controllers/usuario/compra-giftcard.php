<?php

if(!$User->logged()) Redirect::to('login#usuario/compra-giftcard');

$GiftCardsPurchases = new GiftCardsPurchases();
if(!$giftcard_purchase = $GiftCardsPurchases->find($_idsection)) Redirect::to('404');

if($giftcard_purchase->user_id != $_userdata->id) Redirect::to('404');

