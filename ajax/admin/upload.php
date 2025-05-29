<?php

if($_action!='file'){
	$folder = Input::get('folder');
	$upfile = new File($_FILES['file'],$folder);
	$upfile->MoveFile();
}
///////////////////////////////////////////////////////////////////
switch($_action):

	case 'ckeditor':
		$imgjson = $upfile->Resize([[1920,1080,'']], '', false);
		echo Responses::response('ok','',array('url'=>ROOT.'img/uploads/'.$imgjson['filename'].'.'.$imgjson['extension']));
		break;

	case 'slider':
		$file = $upfile->Resize([[1920,1080,'-n'],[720,480,'-t']], '', false);
		echo Responses::response('ok','',$file);
		break;

	case 'big':
		$file = $upfile->Resize([[1920,1080,'-n'],[720,720,'-t']], '', false);
		echo Responses::response('ok','',$file);
		break;
	case 'medium':
		$file = $upfile->Resize([[1080,720,'-n'],[640,480,'-t']], '', false);
		echo Responses::response('ok','',$file);
		break;
	case 'small':
		$file = $upfile->Resize([[640,640,'']], '', false);
		echo Responses::response('ok','',$file);
		break;

	case 'master':
		$file = $upfile->Resize([[1080,720,'-n'],[640,480,'-t']], '', false);
		echo Responses::response('ok','',$file);
		break;

	case 'file':
		$upfile = new File($_FILES['file'],Input::get('folder'));
		$file = $upfile->MoveFile(false);
		echo Responses::response('ok','',$file);
		break;

	default:
		echo Responses::response('fail');
		break;

endswitch;