<?php

error_reporting(0);


class File {

	public 		$filename = 'tempname',
						$extension = 'jpg',
						$image = true,
						$main_folder,
						$folder;

	private 	$file,
						$arrName = array(),
						$node = 0;

	public function __construct($file,$folder){
		$this->file = $file;
		$this->main_folder = 'img/';
		$this->folder = $folder;
		if(!is_dir(PATH.$this->main_folder.$this->folder)) @mkdir(PATH.$this->main_folder.$this->folder, 0777);
		if(!is_dir(PATH.$this->main_folder.$this->folder)) die(Responses::response('folder_fail','',['folder'=>$this->main_folder.$this->folder]));
	}

	////////////////////// MOVE FILE ///////////////////////
	public function MoveFile($image=true,$hashed=false){
		$this->hash = hash('sha256',date('Y-m-d H:i:s').rand(1111,9999));
		$this->image = $image;
		$this->arrName = explode('.',$this->file['name']);
		$this->filename = $hashed ? $this->hash : permalink($this->arrName[0].'-'.rand(1111,9999));
		$this->extension = strtolower($this->arrName[count($this->arrName)-1]);
		if($this->file['size'] > intval(ini_get('upload_max_filesize'))*1048576){
			die(Responses::response('max_filesize','',array('size'=>substr(ini_get('upload_max_filesize'),0,-1))));
		}
		if(move_uploaded_file($this->file['tmp_name'], PATH.$this->main_folder.$this->folder.($this->image ? 'tempname.'.$this->extension : $this->hash) )){
			return array(
				'filename'=>$this->filename,
				'extension'=>$this->extension,
				'hash'=>$this->hash,
				'folder'=>$this->folder,
				'main_folder'=>$this->main_folder
			);
		}else{
			//return Responses::response('upload_fail','',array('filename'=>$this->filename,'extension'=>$this->extension));
			die( Responses::response('upload_fail') );
		}
	}

	////////////////////// IMAGE CREATE //////////////////////
	public function ImageCreate($type,$file=false){
		$file = $file ?: PATH.$this->main_folder.$this->folder.'tempname.'.$this->extension;
		
		switch($type){
			case 1:
				return imagecreatefromgif($file);
				//$this->extension = 'gif';
				break;
			case 2:
				return imagecreatefromjpeg($file);
				//$this->extension = 'jpg';
				break;
			case 3:
				return imagecreatefrompng($file);
				///$this->extension = 'png';
				break;
			case 18:
				return imagecreatefromwebp($file);
				///$this->extension = 'webp';
				break;
			default:

				$imagick = new Imagick();
				try {
					$imagick->readImage($file);
					$imagick->setImageFormat('jpg');
					$imagick->writeImage(PATH.$this->main_folder.$this->folder.'tempname.jpg');
				} catch (Exception $e) {
					die( Responses::response('uploadfail','<div>No se reconoce el formato de la imagen.</div><br><div class="small text-danger">Los formatos de imagen soportados son: JPG, JPEG, PNG, GIF, WEBP</div>') );
				}

				return imagecreatefromjpeg(PATH.$this->main_folder.$this->folder.'tempname.jpg');

				die( Responses::response('upload_fail','No se reconoce el formato de la imagen.') );
				break;
		}
	}

	////////////////////// RESIZE IMAGE ///////////////////////
	///public function Resize($resizeWidth, $resizeHeight, $sx, $forced, $trim, $die){
	public function Resize($arrImg, $forced, $trim){

		$resizeWidth = $arrImg[$this->node][0];
		$resizeHeight = $arrImg[$this->node][1];
		$sx = $arrImg[$this->node][2];

		$filename = PATH.$this->main_folder.$this->folder.$this->filename.$sx.'.'.$this->extension;
		list($imgWidth, $imgHeight, $imgType) = getimagesize(PATH.$this->main_folder.$this->folder.'tempname.'.$this->extension);

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
				if(file_exists(PATH.$this->main_folder.$this->folder.'tempname.'.$this->extension)){
					unlink(PATH.$this->main_folder.$this->folder.'tempname.'.$this->extension);
				}
			}
		}else{
			$this->node++;
			$this->Resize($arrImg, $forced, $trim);
		}
		/////////////////////////////////////////////

		return array(
			'status'=>'ok',
			'filename'=>$this->filename,
			'extension'=>$this->extension,
			'hash'=>$this->hash,
			'folder'=>$this->folder,
			'main_folder'=>$this->main_folder,
			'size'=>round($this->file['size']/1024,2).' KB',
			'url'=>ROOT.$this->main_folder.$this->folder.$this->filename.'.'.$this->extension
		);
	}


	public static function download_file($filename='',$file=''){

		//echo_json([$filename,$file],true);

		if(!is_file($file)) return false;

		$file_info = pathinfo($file);
		$mime = mime_content_type($file);
		$is_image = false;
		if(preg_match('/image\/jpeg/',$mime)) $is_image=true;

		ob_start();
		//echo_json($mime,true);

		header("Content-Type: ".$mime);
		header("Content-Length: ".filesize($file));
		if(!$is_image) header('Content-Disposition: attachment; filename="'.$filename.'"');

		ob_clean();
		flush();
		readfile($file);
		return true;
	}



	public static function download_excel($file='',$extension='',$content=''){
		$fpt = fopen("tempname.".$extension,"w");
		fwrite($fpt,$content);
		fclose($fpt);
		if(is_file("tempname.".$extension)){
			header("Content-Type: application/".$extension);
			header("Content-Length: tempname.".$extension);
			header('Content-Disposition: attachment; filename="'.$file.'"');
			readfile("tempname.".$extension);
		}
		return true;
	}


}