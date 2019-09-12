<?php

header("Content-Type: application/json; charset=utf-8", true);

$User = new User();
$Glossary = new Glossary();
$GlossaryGroups = new GlossaryGroups();

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));
if(!$User->logged() || $User->data()->idtype != 1) die(Responses::response('restricted'));

switch($_action){


	case 'save':
		$GlossaryGroups->save();
		echo Responses::response('ok','',array('id'=>$GlossaryGroups->getLastId()));
		break;

	case 'reorder':
		$GlossaryGroups->reorder();
		echo Responses::response('ok');
		break;

	case 'get':
		$id = Input::get('glossaryid');
		$glossary = array();
		if(!$id){
			$GlossaryGroups->get();
		}else{
			$GlossaryGroups->find($id);
			$groupdata = $GlossaryGroups->data();
		}
		$groupdata = $GlossaryGroups->data();

		if(is_array($groupdata)){
			foreach($groupdata as $group):
				$Glossary->idgroup = $group->id;
				$Glossary->get();
				$glossary[] = $Glossary->data();
			endforeach;
		}else{
			$Glossary->idgroup = $groupdata->id;
			$Glossary->get();
			$glossary[] = $Glossary->data();
			$groupdata = array($groupdata);
		}
		echo Responses::response('ok','',array('results'=>$groupdata,'glossary'=>$glossary));
		break;

	case 'delete':
		$GlossaryGroups->delete();
		echo Responses::response('ok');
		break;

	case 'find':
		$GlossaryGroups->find(Input::get('ID'));
		echo Responses::response('ok','',array('result'=>$GlossaryGroups->data()));
		break;


	default:
		echo Responses::response('fail');
		break;


}