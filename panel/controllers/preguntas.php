<?php 

$Questions = new Questions();
$Questions->limit = 100;

$_where = Input::get('where');
$_status = Input::get('status');

$Questions->filters = [['clientid'=>$_userdata->idclient]];
if(!empty($_where)) $Questions->filters[] = ['type'=>$_where];
if(!empty($_status)) $Questions->filters[] = ['status'=>$_status];

$questions = $Questions->get();



/// QUESTIONS ///
///$Questions = new Questions();
///$Questions->limit = 10;
///$questions = $Questions->get_unanswered($_userdata->idclient);