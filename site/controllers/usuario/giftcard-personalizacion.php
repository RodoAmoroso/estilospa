<?php

if(!$User->logged()) Redirect::to('home');

$GiftCardsPurchases = new GiftCardsPurchases();
if(!$giftcard_purchase = $GiftCardsPurchases->find($_idsection)) Redirect::to('404');

if($giftcard_purchase->user_id != $_userdata->id) Redirect::to('404');

$GiftCardsGallery = new GiftCardsGallery;
$giftcards_galleries = $GiftCardsGallery->get();

$GiftCardsPersonalizations = new GiftCardsPersonalizations;
$giftcard_personalization = $GiftCardsPersonalizations->find_by_purchase_id($giftcard_purchase->id);

//dd($giftcard_purchase);

$_arrjs[] = ['folder'=>'lib/','script'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.theme.default.min'];

$_arrjs[] = ['folder'=>'classes/','script'=>'upfile'];