<?php

$Users = new Users;
if(!$user = $Users->find($_subsection)) Redirect::to('404');


$Sales = new Sales;
$Sales->iduser = $user->id;
$Sales->limit = '0,50';
$Sales->get();
$sales = $Sales->data();


$Questions = new Questions();
$Questions->limit = 100;

$_where = Input::get('question_where');
$_status = Input::get('question_status');
$Questions->filters = [
	['userid'=>$user->id]
];
if(!empty($_where))
	$Questions->filters[] = ['type'=>$_where];
if(!empty($_status))
	$Questions->filters[] = ['status'=>$_status];

$questions = $Questions->get();


$Stats = new Stats;
$Stats->limit = '0,25';
$Stats->filters = ['user'=>$user->id];
$promo_views = $Stats->promo_user_views();
$client_views = $Stats->client_user_views();
$glossary_views = $Stats->glossary_user_views();

$all_views = array_merge($promo_views,$client_views,$glossary_views);
$views = [];
foreach($all_views as $vw){
	$views[$vw->added_obj->format('YmdHis')] = (object) [
		'type'=>$vw->type,
		'type_name'=>$vw->type_name,
		'name'=>$vw->name,
		'link'=>$vw->link,
		'added_date'=>$vw->added_obj->format('d/m/Y'),
		'added_time'=>$vw->added_obj->format('H:i')
	];
}
krsort($views);
//show_array($views);

$Newsletters = new Newsletters;
$Newsletters->limit = '0,50';
$Newsletters->filters = [
	'user'=>$user->id,
	'body'=>"!=''"
];
$newsletters = $Newsletters->get_log($user->id);


$avatar = json_decode($user->image);


$Favs = new Favs;
$Favs->getpromos($user->id);
$favs = $Favs->data();