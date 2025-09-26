<?php

if(!$User->logged()) Redirect::to('login?back_url='.base64_encode($_section.'/'.$_subsection));

$GiftCards = new GiftCards();
list($giftcardid) = explode('-',$_subsection);
if(!$giftcard = $GiftCards->find($giftcardid)) Redirect::to('404');
if(!$giftcard->visible) Redirect::to('404');



$_arrjs[] = ['folder'=>'lib/','script'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.theme.default.min'];


$_arrjs[] = ['script'=>'https://sdk.mercadopago.com/js/v2'];