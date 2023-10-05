<?php

$Questions = new Questions();
$Questions->limit = 250;

$_where = Input::get('where');
$_status = Input::get('status');
//$Questions->filters = [['type'=>$_where],['status'=>$_status]];
$Questions->filters = array();
if(!empty($_where)) $Questions->filters[] = ['type'=>$_where];
if(!empty($_status)) $Questions->filters[] = ['status'=>$_status];

$questions = $Questions->get();