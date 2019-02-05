<?php 

require_once '../config.php';

if(!Input::exists()){
	die(json_encode(array('Status'=>'fail')));
}

$_GLOSSARY = new Glossary();
$_GLOSSARYGROUPS = new GlossaryGroups();

switch (Input::get('Mode')) {
	
	case 'upimage':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();		
		$file = $upfile->Resize(array(array(1280,720,'')), '', false);
		echo json_encode($file);
		break;

	case 'upheader':
		if(!$_USER->logged() && $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$Folder = '../'.Input::get('Folder');	
		$upfile = new File($_FILES[0],$Folder);
		$upfile->MoveFile();		
		$file = $upfile->Resize(array(array(1280,720,'')), '', false);
		echo json_encode($file);
		break;

	case 'save':
		if(!$_USER->logged() || $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$_GLOSSARY->save();		
		echo json_encode(array('Status'=>'ok','ID'=>$_GLOSSARY->getLastId()));
		break;

	case 'get':
		$glossary = array();
		if(Input::get('IDG')){
			$_GLOSSARY->find(Input::get('IDG'));
			$glossary[] = $_GLOSSARY->data();
		}else{
			$_GLOSSARY->get();
			$glossary = $_GLOSSARY->data();
		}
		echo json_encode(array('Status'=>'ok','Results'=>$glossary));
		break;

	case 'delete':
		if(!$_USER->logged() || $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$_GLOSSARY->delete();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'find':
		$_GLOSSARY->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok', 'Result'=>$_GLOSSARY->data()));
		break;
	
	case 'reorder':
		$_GLOSSARY->reorder();
		echo json_encode(array('Status'=>'ok'));
		break;
	///////////////////// GROUPS ///////////////////////////	
	case 'savegroup':
		if(!$_USER->logged() || $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$_GLOSSARYGROUPS->save();
		$id = $_GLOSSARYGROUPS->getLastId();
		echo json_encode(array('Status'=>'ok','ID'=>$id));
		break;

	case 'reordergroup':
		$_GLOSSARYGROUPS->reorder();
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'getgroups':
		$id = Input::get('IDG');
		$glossary = array();
		if(!$id){
			$_GLOSSARYGROUPS->get();
		}else{
			$_GLOSSARYGROUPS->find($id);
		}
		$groupdata = $_GLOSSARYGROUPS->data();
		if(is_array($groupdata)){
			foreach($groupdata as $group):
				$_GLOSSARY->idgroup = $group->id;
				$_GLOSSARY->get();
				$glossary[] = $_GLOSSARY->data();
			endforeach;
		}else{
			$_GLOSSARY->idgroup = $groupdata->id;
			$_GLOSSARY->get();
			$glossary[] = $_GLOSSARY->data();
			$groupdata = array($groupdata);
		}
		echo json_encode(array('Status'=>'ok','Results'=>$groupdata,'Glossary'=>$glossary));
		break;

	case 'deletegroup':
		if(!$_USER->logged() || $_USER->data()->idtype != 1) die(json_encode(array('Status'=>'fail')));
		$_GLOSSARYGROUPS->delete();		
		echo json_encode(array('Status'=>'ok'));
		break;

	case 'findgroup':
		$_GLOSSARYGROUPS->find(Input::get('ID'));
		echo json_encode(array('Status'=>'ok', 'Result'=>$_GLOSSARYGROUPS->data()));
		break;
	
	default:
		echo json_encode(array('Status'=>'fail'));
		break;
}