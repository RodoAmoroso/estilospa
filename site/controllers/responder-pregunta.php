<?php 

$_arrjs[] = ['folder'=>'site/','script'=>'questions'];

if(!$User->logged()) Redirect::to('login');
$questionid = $_subsection;

$Questions = new Questions();
if(!$Questions->check_privilege($questionid,$User->data()->id)) $_section = 'restricted';

if(!$_question = $Questions->get($questionid)) Redirect::to('404');
$Questions->take_question($questionid);

if(!$Promos->find($_question->rowid)) return false;
$_promo = $Promos->data();

if(!$User->find($_question->userid)) return false;
$_user = $User->data();
