<?php

class File {

	public $filename = 'tempname';
	public $extension = 'jpg';
	public $image = true;
	private $folder;
	private $file;
	private $arrName = array();
	private $node = 0;

	public function __construct($file=array(),$folder=''){
		$this->file = $file;
		$this->folder = $folder;
	}

	////////////////////// MOVE FILE ///////////////////////
	public function MoveFile($image=true){

		if(!is_dir($this->folder)) mkdir($this->folder, 0777);
		if(!is_dir($this->folder)) die(Responses::response('folder_fail'));

		$this->image = $image;
		$this->arrName = explode('.',$this->file['name']);
		$this->filename = Permalink($this->arrName[0]).'-'.rand(1111,9999);
		$this->extension = strtolower($this->arrName[count($this->arrName)-1]);
		if($this->file['size'] > intval(ini_get('upload_max_filesize'))*1048576){
			die(Responses::response('maxsize','',array('size'=>substr(ini_get('upload_max_filesize'),0,-1))));
		}
		if(move_uploaded_file($this->file['tmp_name'], $this->folder.($this->image ? 'tempname' : $this->filename).'.'.$this->extension)){
			return Responses::response('ok','',array('filename'=>$this->filename,'extension'=>$this->extension));
		}else{
			return Responses::response('uploadfail','',array('filename'=>$this->filename,'extension'=>$this->extension));
		}
	}

	////////////////////// IMAGE CREATE //////////////////////
	public function ImageCreate($type){
		switch($type){
			case 1:
				return imagecreatefromgif($this->folder.'tempname.'.$this->extension);
				//$this->extension = 'gif';
				break;
			case 2:
				return imagecreatefromjpeg($this->folder.'tempname.'.$this->extension);
				//$this->extension = 'jpg';
				break;
			case 3:
				return imagecreatefrompng($this->folder.'tempname.'.$this->extension);
				///$this->extension = 'png';
				break;
			default:
				die( Responses::response('upload_fail') );
				break;
		}
	}

	////////////////////// RESIZE IMAGE ///////////////////////
	///public function Resize($resizeWidth, $resizeHeight, $sx, $forced, $trim, $die){
	public function Resize($arrImg, $forced, $trim){

		$resizeWidth = $arrImg[$this->node][0];
		$resizeHeight = $arrImg[$this->node][1];
		$sx = $arrImg[$this->node][2];

		$filename = $this->folder.$this->filename.$sx.'.'.$this->extension;
		list($imgWidth, $imgHeight, $imgType) = getimagesize($this->folder.'tempname.'.$this->extension);

		$srcimage = $this->ImageCreate($imgType);

		if(($resizeWidth == 0 && $resizeHeight == 0) || ($imgWidth < $resizeWidth && $imgHeight < $resizeHeight)) {
			$newHeight = $imgHeight;
			$newWidth = $imgWidth;
		}else{
			if($forced == 'height'){
				$newHeight = $resizeHeight;
				$newWidth = ($imgWidth/$imgHeight)*$resizeHeight;
			}else if($forced == 'width'){
				$newWidth = $resizeWidth;
				$newHeight = ($imgHeight/$imgWidth)*$resizeWidth;
			}else{
				if($imgWidth-$imgHeight >= 1){
					$newWidth = $resizeWidth;
					$newHeight = ($imgHeight/$imgWidth)*$resizeWidth;
				}else{
					$newHeight = $resizeHeight;
					$newWidth = ($imgWidth/$imgHeight)*$resizeHeight;
				}
			}
		}
		$newWidth = round($newWidth);
		$newHeight = round($newHeight);
		$tmpImg = imagecreatetruecolor($newWidth,$newHeight);
		if($imgType == 2){
			imagecopyresampled($tmpImg,$srcimage,0,0,0,0,$newWidth,$newHeight,$imgWidth,$imgHeight);
			//////////////////// SHARPNESS ///////////////////////////////////////////
			/*$spnMatrix = array( array(-1,-1,-1,), array(-1,16,-1,), array(-1,-1,-1));
			$divisor = 8;
			$offset = 0;
			imageconvolution($tmpImg, $spnMatrix, $divisor, $offset);*/
			//////////////////////////////////////////////////////////////////////////
			imagejpeg($tmpImg,$filename,65);
		}else{
			imagecolortransparent($tmpImg, imagecolorallocatealpha($tmpImg, 0, 0, 0, 127));
			imagealphablending($tmpImg, false);
			imagesavealpha($tmpImg, true);
			imagecopyresampled($tmpImg,$srcimage,0,0,0,0,$newWidth,$newHeight,$imgWidth,$imgHeight);
			imagepng($tmpImg,$filename,6);
		}
		imagedestroy($tmpImg);
		///////////////// TRIM /////////////////////
		if($trim){
			list($width_th,$height_th) = getimagesize($filename);
			$src_th = $this->ImageCreate($imgType);
			$tmb_th = imagecreatetruecolor($resizeWidth,$resizeHeight);
			if($imgType == 2){
				$bg_th = imagecolorallocate($tmb_th, 198, 198, 198);
			}else{
				$bg_th = imagecolorallocatealpha($tmb_th, 255, 255, 255, 0);
			}
			imagefill($tmb_th, 0, 0, $bg_th);
			imagecopy($tmb_th, $src_th, ($resizeWidth/2)-($width_th/2),0, 0, 0, $width_th, $height_th);
			if($imgType == 1){
				 imagegif($tmb_th, $filename);
			}else if($imgType == 3){
				imagepng($tmb_th,$filename,6);
			}else{
				imagejpeg($tmb_th,$filename,65);
			}
			imagedestroy($src_th);
			imagedestroy($tmb_th);
		}
		//////////////////// DIE /////////////////////
		if($this->node == count($arrImg)-1){
			if(imagedestroy($srcimage)){
				if(file_exists($this->folder.'tempname.'.$this->extension)){
					unlink($this->folder.'tempname.'.$this->extension);
				}
			}
		}else{
			$this->node++;
			$this->Resize($arrImg, $forced, $trim);
		}
		/////////////////////////////////////////////
		return array('status'=>'ok','filename'=>$this->filename,'extension'=>$this->extension);
	}

	public static function download($filepath='',$name=''){

		//$original = PATH.'descargas'.DS.$file->filename.'.'.$file->extension;

		if(!is_file($filepath)) return false;

		header("Content-Type: application/".$file->extension);
		header("Content-Length: ".filesize($filepath));
		header('Content-Disposition: attachment; filename="'.$name.'"');

		ob_clean();
		flush();
		readfile($filepath);
	}


}