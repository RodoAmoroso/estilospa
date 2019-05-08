<?php 

$_arrjs[] = ['folder'=>'site/','script'=>'questions'];

if(!$User->logged()) Redirect::to('login');
$questionid = $_subsection;

$Questions = new Questions();
if(!$Questions->check_privilege($questionid,$_userdata)) $_section = 'restricted';

if(!$_question = $Questions->get($questionid)) Redirect::to('404');
$Questions->take_question($questionid);

$_promo = null;
$_glossary = null;
if($_question->type=='promos'){	
	$Promos->find($_question->rowid);
	$_promo = $Promos->data();
}
if($_question->type=='glossary'){	
	$Glossary->find($_question->rowid);
	$_glossary = $Glossary->data();
}

$User->find($_question->userid);
$_user = $User->data();

//$Assoc = new Assoc();
//$Assoc->iduser = $_userdata->id;
//$Assoc->client_user('get');
///$assoc = $_userdata->id;

///show_array($Assoc->data());