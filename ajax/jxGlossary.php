<?php 

require_once '../config.php';

if(!Input::exists()){
	die(json_encode(array('Status'=>'fail')));
}

$Glossary = new Glossary();
$GlossaryGroups = new GlossaryGroups();

switch (Input::get('Mode')) {
	
	case 'upimage':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();		
		$file = $upfile->Resize(array(array(1280,720,'')), '', false);
		echo json_encode($file);
		break;

	case 'upheader':
		if(!$User->logged() && $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();		
		$file = $upfile->Resize(array(array(1280,720,'')), '', false);
		echo json_encode($file);
		break;

	case 'save':
		if(!$User->logged() || $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Glossary->save();		
		echo json_encode(array('Status'=>'ok','ID'=>$Glossary->getLastId()));
		break;

	case 'get':
		$glossary = array();
		if(Input::get('IDG')){
			$Glossary->find(Input::get('IDG'));
			$glossary[] = $Glossary->data();
		}else{
			$Glossary->get();
			$glossary = $Glossary->data();
		}
		echo json_encode(array('Status'=>'ok','Results'=>$glossary));
		break;

	case 'delete':
		if(!$User->logged() || $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Glossary->delete();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'find':
		$Glossary->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok', 'Result'=>$Glossary->data()));
		break;
	
	case 'reorder':
		$Glossary->reorder();
		echo json_encode(array('Status'=>'ok'));
		break;
	///////////////////// GROUPS ///////////////////////////	
	case 'savegroup':
		if(!$User->logged() || $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$GlossaryGroups->save();
		$id = $GlossaryGroups->getLastId();
		echo json_encode(array('Status'=>'ok','ID'=>$id));
		break;

	case 'reordergroup':
		$GlossaryGroups->reorder();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'getgroups':
		$id = Input::get('IDG');
		$glossary = array();
		if(!$id){
			$GlossaryGroups->get();
		}else{
			$GlossaryGroups->find($id);
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
		echo json_encode(array('Status'=>'ok','Results'=>$groupdata,'Glossary'=>$glossary));
		break;

	case 'deletegroup':
		if(!$User->logged() || $User->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$GlossaryGroups->delete();		
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'findgroup':
		$GlossaryGroups->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok', 'Result'=>$GlossaryGroups->data()));
		break;
	
	default:
		echo json_encode(array('Status'=>'fail'));
		break;
}