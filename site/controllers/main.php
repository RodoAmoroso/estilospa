<?php

$User = new User;
$_userdata = null;
if($User->logged()){
	$User->update($User->data()->id,array('logged'=>date('Y-m-d H:i:s')));
	$_userdata = $User->data();
}

//dd(Env::get('SITE_URL'));

$dbprovinces = DB::getInstance()->get('provinces',array('id','!=',0));
foreach($dbprovinces->results() as $p){
	$Provinces[$p->id] = $p->name;
}


$Clients = new Clients();
$Clients->visible = 1;
$ClientTypes = new ClientTypes();
$Promos = new Promos();
$Stores = new Stores();
$Glossary = new Glossary();
$GlossaryGroups = new GlossaryGroups();
$Favs = new Favs();
$colorsequence = array('yellow-2','green-1','cyan-1','pink-1','aqua-2');

$MPConfig = new MPConfig();

$Stats = new Stats();
$Reservations = new Reservations();


$PromosCategories = new PromosCategories;
$PromosCategories->filters = ['visible'=>1];
$PromosCategories->limit = "0,8";
$menu_categories = $PromosCategories->get();



$MainCategories = new MainCategories;
$MainCategories->filters = ['visible'=>1];
$main_categories = $MainCategories->get();


$GiftCardsUsersAssignments = new GiftCardsUsersAssignments;
$giftcard_user_balance = $GiftCardsUsersAssignments->user_balance();



//dd($giftcard_user_balance);