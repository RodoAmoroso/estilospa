<?php 

$GiftCards = new GiftCards();
$GiftCards->filters = [
  'visible' => 1
];
$giftcards = $GiftCards->get();