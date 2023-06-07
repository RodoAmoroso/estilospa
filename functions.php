<?php
function Permalink($str) {
	$clean = preg_replace("/ã|à|á|ä|â|Ã|À|Á|Ä|Â/", "a", $str);
	$clean = preg_replace("/è|é|ë|ê|È|É|Ë|Ê/", "e", $clean);
	$clean = preg_replace("/ì|í|ï|î|Ì|Í|Ï|Î/", "i", $clean);
	$clean = preg_replace("/ò|ó|ö|ô|Ò|Ó|Ö|Ô/", "o", $clean);
	$clean = preg_replace("/ù|ú|ü|û|Ù|Ú|Ü|Û/", "u", $clean);
	$clean = preg_replace("/ñ|Ñ/", "ni", $clean);
	$clean = preg_replace("/Ç|ç/", "c", $clean);
	$clean = strtolower($clean);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = trim($clean, '-');
	$clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
	return $clean;
}
function CryptMe($key, $string, $action){
	$res = '';
	if($action == 'decrypt'){
		$string = base64_decode($string);
	}
	for( $i = 0; $i < strlen($string); $i++){
		$c = ord(substr($string, $i));
		if($action == 'encrypt'){
			$c += ord(substr($key, (($i + 1) % strlen($key))));
			$res .= chr($c & 0xFF);
		}else{
			$c -= ord(substr($key, (($i + 1) % strlen($key))));
			$res .= chr(abs($c) & 0xFF);
		}
	}
	if($action == 'encrypt'){
		$res = base64_encode($res);
	}
	return $res;
}
function MakeImage($MaxW, $MaxH, $sufijo, $forced, $trim, $Die){

	global $photoname;
	global $uploadedfile;
	global $ruta;
	global $extension;

	list($width, $height, $type)=getimagesize($uploadedfile);

	switch ($type){
		case 1:   //   gif -> jpg
			$src = imagecreatefromgif($uploadedfile);
			$extension = 'gif';
			break;
		case 2:   //   jpeg -> jpg
			$src = imagecreatefromjpeg($uploadedfile);
			$extension = 'jpg';
			break;
		case 3:  //   png -> jpg
			$src = imagecreatefrompng($uploadedfile);
			$extension = 'png';
			break;
	}

	////////////////////////////////
	if($MaxW == 0 && $MaxH == 0) {

		$newheight=$height;
		$newwidth=$width;

	}else	if($width < $MaxW && $height < $MaxH){

		$newheight=$height;
		$newwidth=$width;

	}else{
		if($forced == "height"){
			$newheight=$MaxH;
			$newwidth=($width/$height)*$MaxH;
		}else if($forced == "width"){
			$newwidth = $MaxW;
			$newheight = ($height/$width)*$MaxW;
		}else if($forced == "least"){
			if($width-$height >= 1){
				$newheight=$MaxH;
				$newwidth=($width/$height)*$MaxH;
			}else{
				$newwidth = $MaxW;
				$newheight = ($height/$width)*$MaxW;
			}
		}else{
			if ($width-$height >= 1){
				if ($width < $MaxW and $height <$MaxH){
					$newwidth = $width;
					$newheight = $height;
				}else if (($height*$MaxW)/$width > $MaxH){
					$newheight=$MaxH;
					$newwidth=($width/$height)*$MaxH;
				}else{
					$newwidth = $MaxW;
					$newheight = ($height/$width)*$MaxW;
				}
			}else{
				$newheight=$MaxH;
				$newwidth=($width/$height)*$MaxH;
			}
		}
	}
	$tmp=imagecreatetruecolor($newwidth,$newheight);
	if($extension == 'jpg'){
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		//////////////////////////////////////////////////////////////////////////
		//$spnMatrix = array( array(-1,-1,-1,), array(-1,16,-1,), array(-1,-1,-1));
		//$divisor = 8;
		//$offset = 0;
		//imageconvolution($tmp, $spnMatrix, $divisor, $offset);
		//////////////////////////////////////////////////////////////////////////
		$filename = $ruta.$photoname.$sufijo.'.'.$extension;
		imagejpeg($tmp,$filename,100);
	}else{
		imagecolortransparent($tmp, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
		imagealphablending($tmp, false);
		imagesavealpha($tmp, true);

		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		$filename = $ruta.$photoname.$sufijo.'.'.$extension;
		imagepng($tmp,$filename,0);
	}
	imagedestroy($tmp);
	///////////////////////////////////////////////////////////////////////////////
	if($trim){
		$size_th = getimagesize($filename);
		$width_th = $size_th[0];
		$height_th = $size_th[1];
		$tmb_th = imagecreatetruecolor($MaxW,$MaxH);

		if($extension == 'gif'){
			$src_th = imagecreatefromgif($filename);
			$bg_th = imagecolorallocatealpha($tmb_th, 255, 255, 255, 0);
		}else if($extension == 'png'){
			$src_th = imagecreatefrompng($filename);
			$bg_th = imagecolorallocatealpha($tmb_th, 255, 255, 255, 0);
		}else{
			$src_th = imagecreatefromjpeg($filename);
			$bg_th = imagecolorallocate($tmb_th, 198, 198, 198);
		}
		imagefill($tmb_th, 0, 0, $bg_th);

		imagecopy($tmb_th, $src_th, ($MaxW/2)-($width_th/2),0, 0, 0, $width_th, $height_th);

		$newfilename = $ruta.$photoname.$sufijo.'.'.$extension;

		if($extension == 'gif'){
			 imagegif($tmb_th, $newfilename);
		}else if($extension == 'png'){
			imagepng($tmb_th, $newfilename, 0);
		}else{
			imagejpeg($tmb_th, $newfilename, 100);
		}

		imagedestroy($src_th);
		imagedestroy($tmb_th);
	}
	if($Die){
		//imagedestroy($src);
		if (imagedestroy($src)){
			if (file_exists($uploadedfile)){
				unlink($uploadedfile);
			}
			//echo json_encode(array("estatus"=>"ok", "photoname"=>$photoname));
		}
	}
}
function PageMaker($elements,$total){
	$split = $total/$elements;
	if(is_float($split)) return floor($split)+1;
	return $split;
}
function GetPercent($TOT,$PART){
	if($TOT > 0){
		return round(($PART*100)/$TOT,1);
	}else{
		return 0;
	}
}
function ConvertDays($day){
	switch ($day) {
		case 'Monday':
			$dayconverted = 'Lunes';
			break;
		case 'Tuesday':
			$dayconverted = 'Martes';
			break;
		case 'Wednesday':
			$dayconverted = 'Miércoles';
			break;
		case 'Thursday':
			$dayconverted = 'Jueves';
			break;
		case 'Friday':
			$dayconverted = 'Viernes';
			break;
		case 'Saturday':
			$dayconverted = 'Sábado';
			break;
		case 'Sunday':
			$dayconverted = 'Domingo';
			break;
		default:
			$dayconverted = '';
	}
	return $dayconverted;
}
function ConvertMonth($month){
	switch ($month) {
		case 'January':
			$monthconverted = 'Enero';
			break;
		case 'February':
			$monthconverted = 'Febrero';
			break;
		case 'March':
			$monthconverted = 'Marzo';
			break;
		case 'April':
			$monthconverted = 'Abril';
			break;
		case 'May':
			$monthconverted = 'Mayo';
			break;
		case 'June':
			$monthconverted = 'Junio';
			break;
		case 'July':
			$monthconverted = 'Julio';
			break;
		case 'August':
			$monthconverted = 'Agosto';
			break;
		case 'September':
			$monthconverted = 'Septiembre';
			break;
		case 'October':
			$monthconverted = 'Octubre';
			break;
		case 'November':
			$monthconverted = 'Noviembre';
			break;
		case 'December':
			$monthconverted = 'Diciembre';
			break;
	}
	return $monthconverted;
}
function GetInterval($DAYS){
	switch($DAYS){
		case 0:
			return 'Hoy';
			break;
		case 1:
			return 'Ayer';
			break;
		case 2:
			return 'Hace 2 días';
			break;
		case 3:
			return 'Hace 3 días';
			break;
		case 4:
			return 'Hace 4 días';
			break;
		case 5:
			return 'Hace 5 días';
			break;
		case 6:
			return 'Hace 6 días';
			break;
		case ($DAYS >= 7 && $DAYS < 14):
			return 'Hace 1 semana';
			break;
		case ($DAYS >= 14 && $DAYS < 21):
			return 'Hace 2 semanas';
			break;
		case ($DAYS >= 21 && $DAYS < 30):
			return 'Hace 3 semanas';
			break;
		case ($DAYS >= 30 && $DAYS < 60):
			return 'Hace 1 mes';
			break;
		case ($DAYS >= 60 && $DAYS < 90):
			return 'Hace 2 meses';
			break;
		case ($DAYS >= 90 && $DAYS < 120):
			return 'Hace 3 meses';
			break;
		case ($DAYS >= 120 && $DAYS < 150):
			return 'Hace 4 meses';
			break;
		case ($DAYS >= 150 && $DAYS < 180):
			return 'Hace 5 meses';
			break;
		case ($DAYS >= 180 && $DAYS < 365):
			return 'Hace más de 6 meses';
			break;
		case ($DAYS >= 365 && $DAYS < 730):
			return 'Hace más de 1 año';
			break;
		case ($DAYS >= 730 && $DAYS < 1095):
			return 'Hace más de 2 años';
			break;
		default:
			return 'Más de 3 años';
	}
}
function TimeLapse($START,$END){
	$MIN = abs((strtotime($START)-strtotime($END))/(60*60));
	$MIN = floor($MIN);
	return $MIN; //expresado horas
}
function SecondsToMinutes($NM){
	if(is_double($NM)){
		$arr = explode(',',$NM);
		$secs = $arr[1]*6;
		return $arr[0].':'.$secs;
	}else{
		return $NM;
	}
}
function SetURL($text){
	$text = html_entity_decode($text);
	$text = " ".$text;
	$text = preg_replace("/(?<!\")(((f|ht){1}tps?:\/\/)[-a-zA-Z0-9@:%_\+.~#?&\/\/=]+)/",'<a href="\\1" target=_blank>\\1</a>', $text);
	$text = preg_replace("/([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&\/\/=]+)/",'\\1<a href="http://\\2" target=_blank>\\2</a>', $text);
	$text = preg_replace("/(?<!\")([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})/",'<a href="mailto:\\1" target=_blank>\\1</a>', $text);
	return $text;
}
function escape($string){
	return htmlentities($string,ENT_QUOTES,'UTF-8');
}
function YoutubeAPI($idvideo){
	$video = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$idvideo.'&key=AIzaSyAq3a2AC4jXd9AVmt646ZP_45Vd3oLJn7g&part=snippet');
	$jvideo = json_decode($video);
	$obj = $jvideo->items[0]->snippet->thumbnails;
	if(isset($obj->high->url)){
		return $obj->high->url;
	}else{
		return $obj->medium->url;
	}
}
function excludeWords($word=''){
	return strlen($word)>2;
}
function BuildSearch($keywords='',$mixed=0,$arrfields=array(),$operator='like'){

	$search = '';
	///show_array($keywords);
	if(!empty($keywords)){
		if(is_array($keywords)){
			//echo 'yyy<br>';
			$search = " (";
			foreach($keywords as $kw=>$vw){
				foreach($arrfields as $fk=>$field){
					if($operator=='like'){
						if(is_numeric($vw)){
							$search .= "{$field} LIKE '%,{$vw},%' OR {$field} LIKE '{$vw},%' OR {$field} LIKE '%,{$vw}' OR {$field}='{$vw}'";
						}else{
							$search .= "{$field} LIKE '%{$vw}%'";
						}
					}else{
						$search .= "{$field} = {$vw}";
					}
					if($fk+1!=count($arrfields)){
						$search .= " OR ";
					}
				}
				if($kw+1 != count($keywords)){
					$search .= " OR ";
				}
			}
			$search .= ") ";
		}else{
			if($mixed){
				$keyword = explode(' ',$keywords);
				$keyword = array_values((array_filter($keyword,'excludeWords')));

				foreach ($arrfields as $fk => $field){
					if($fk==0){
						$search .= "(";
					}

					foreach ($keyword as $kw => $vw){
						//if(strlen($vw)>2){
							if($kw==0){
								$search .= "(";
							}

							$search .= "{$field} LIKE '%{$vw}%'";

							if($kw==count($keyword)-1){
								$search .= ")";
							}else{
								$search .= " AND ";
							}
						//}
					}
					//echo $search;

					if($fk==count($arrfields)-1){
						$search .= ")";
					}else{
						$search .= " OR ";
					}
				}
				//echo $search;

			}else{
				$search = " (";
				foreach($arrfields as $fk=>$field){
					$search .= "{$field} LIKE '%{$keywords}%'";
					if($fk+1!=count($arrfields)){
						$search .= " OR ";
					}
				}
				$search .= ") ";
			}
		}
	}
	return $search;
}
function BuildSearchAssignment($glossary=array(),$row=''){
	$query = "";
	foreach($glossary as $k=>$gl){
		$query .= "{$row}={$gl}";
		$query .= count($glossary)-1 != $k ? " AND " : "";
	}
	return $query;
}
function Stars($rate=0,$size=''){
	$leftover = 5;
	$stars = '';
	for($i=1; $i<=floor($rate); $i++):
		$leftover--;
		$stars .= '<i class="fa fa-star '.$size.'"></i> ';
	endfor;
	if($rate-floor($rate)): $leftover--;
		$stars .= '<i class="fa fa-star-half-o '.$size.'"></i> ';
	endif;
	for($i=1; $i<=$leftover; $i++):
		$stars .= '<i class="fa fa-star-o '.$size.'"></i> ';
	endfor;
	return $stars;
}
function Fav($promoid=0,$clientid=0){
	global $User;
	//global $_PROMOS;
	//global $_CLIENTS;
	global $Favs;
	$favprop = 'data-btn-action="fav"';
	if($User->logged()){
		//echo $_CLIENTS->data()->id;
		$Favs->idpromo = $promoid;
		$Favs->idclient = $clientid;
		$Favs->iduser = $User->data()->id;

		if($Favs->find()):
			$fav = '<i class="fa fa-heart active"></i>';
		else:
			$fav = '<i class="fa fa-heart-o"></i>';
		endif;
	}else{
		$favprop = 'data-toggle="modal" data-target="#modal_not_logged"';
		$fav = '<i class="fa fa-heart-o" ></i>';
	}
	return '<a href="#" '.$favprop.' class="heart" data-clientid="'.$clientid.'" data-promoid="'.$promoid.'" title="Agregar/Quitar de mis favoritos" data-placement="bottom" >'.$fav.'</a>';
}
function show_array($obj){
	echo '<pre>';
	if(is_array($obj)):
		print_r($obj);
	elseif(is_object($obj)):
		print_r($obj);
	elseif(is_bool($obj)):
		var_dump($obj);
	else:
		echo $obj;
	endif;
	echo '</pre>';
}
function curl_post($url,$data){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Accept: application/json",));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = new stdClass();
	$output->response = curl_exec($ch);
	$output->status = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	curl_close($ch);
	return $output;
}
function reservation_labels($status){
	$output = new stdClass();
	switch ($status):
		case 0:
			$output->color = 'yellow-3';
			$output->text = 'A confirmar';
			break;

		case 1:
			$output->color = 'green-3';
			$output->text = 'Confirmado';
			break;

		case 2:
			$output->color = 'aqua-3';
			$output->text = 'A confirmar por el usuario';
			break;

		default:
			$output->color = 'pink-3';
			$output->text = 'No disponible';
			break;

	endswitch;

	return $output;
}

function status_payment($status=''){
	$label;
	$text;
	switch($status){
		case 'in_process':
			$label = 'warning';
			$text = 'El pago está siendo revisado';
			break;
		case 'rejected':
			$label = 'danger';
			$text = 'El pago fué rechazado';
			break;
		case 'approved':
			$label = 'success';
			$text = 'El pago fue aprobado y acreditado';
			break;
		case 'pending':
			$label = 'warning';
			$text = 'No se completó el pago';
			break;
		default:
			$label = 'danger';
			$text = 'No se completó el proceso de pago y no se ha generado ningún pago';
			break;
	}
	$output = new stdClass();
	$output->label = $label;
	$output->text = $text;
	return $output;
}
function status_service($status=''){
	$btn='';
	$label='';
	switch($status){
		case 1:
			$btn = 'warning';
			$label = 'Pendiente';
			break;
		case 2:
			$btn = 'success';
			$label = 'Brindado';
			break;
		case 3:
			$btn = 'danger';
			$label = 'Cancelado';
			break;
	}
	return (object) [
		'btn'=>$btn,
		'label'=>$label
	];
}

function is_hashsed($hash=''){
	if(preg_match("/^([a-f0-9]{64})$/", $hash) == 1) return true;
	return false;
}
function echo_json($obj,$exit=false){
	header("Content-Type: application/json; charset=utf-8", true);
	echo json_encode($obj);
	if($exit) exit;
}
function obfuscate_email($email){
	$em   = explode("@",$email);
	$name = implode('@', array_slice($em, 0, count($em)-1));
	$len  = floor(strlen($name)/2);
	return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
}
function obfuscate_phone($phone){
	return substr($phone, 0, 4) . '******' . substr($phone, -2);
}