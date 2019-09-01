<?php 

$arrsection = explode('-',$_subsection);
$questionid = $arrsection[0];

$Questions = new Questions();
$Questions->filters = [['type'=>'glossary']];
if(!$_question = $Questions->get($questionid)) Redirect::to('404');


$Glossary->find($_question->rowid);
$_glossary = $Glossary->data();


$imgjson = json_decode($Glossary->data()->image);
$imgheader = '';
if(!empty($imgjson)){
	$imgheader = 'img/glossary/'.$imgjson->photoname.'.'.$imgjson->extension;
	$_IMGFACEBOOK = $imgheader;
}


///if(!$Glossary->find($idglossary)) Redirect::javascript('404');