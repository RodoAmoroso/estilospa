<?php 

$GiftCards = new GiftCards();
list($giftcardid) = explode('-',$_subsection);
if(!$giftcard = $GiftCards->find($giftcardid)) Redirect::to('404');
if(!$giftcard->visible) Redirect::to('404');

$_arrjs[] = ['script'=>'https://sdk.mercadopago.com/js/v2'];