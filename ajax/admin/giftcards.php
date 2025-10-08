<?php

$GiftCards = new GiftCards;
$GiftCardsGallery = new GiftCardsGallery;

if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){


	case 'save-gallery':
		if(!$GiftCardsGallery->save()) die(Responses::response('fail'));
		echo Responses::response('ok');
		break;


	default:
		echo Responses::response('fail');
		break;

}