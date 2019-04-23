<?php 

class View {

	private static $_friendly=true;
	public static $root=ADMIN;
	public static $scope='admin';


	public static function loader($ext='',$folder=''){
		global $_section,$_subsection;
		$path = false;
		if(is_dir(PATH.self::$scope.DS.$folder.DS.$_section)){
			if(file_exists(PATH.self::$scope.DS.$folder.DS.$_section.DS.$_subsection.'.'.$ext)){
				return $folder.'/'.$_section.'/'.$_subsection.'.'.$ext;
			}
			if(file_exists(PATH.self::$scope.DS.$folder.DS.$_section.DS.'index.'.$ext)){
				return $folder.'/'.$_section.'/index.'.$ext;
			}
		}
		if(file_exists(PATH.self::$scope.DS.$folder.DS.$_section.'.'.$ext)){
			return $folder.'/'.$_section.'.'.$ext;
		}

		return $path;
	}


	public static function views($_section='',$_subsection=''){		
		$path=self::$scope.'/views/404.php';
		if(file_exists(PATH.self::$scope.DS.'views'.DS.$_section.'.php')) $path=self::$scope.'/views/'.$_section.'.php';		
		if(is_dir(PATH.self::$scope.DS.'views'.DS.$_section)) if(file_exists(PATH.self::$scope.DS.'views'.DS.$_section.DS.$_subsection.'.php')) $path=self::$scope.'/views/'.$_section.'/'.$_subsection.'.php';
		return $path;
	}

	public static function controllers($_section='',$_subsection=''){
		$path='';
		if(file_exists(PATH.self::$scope.DS.'controllers'.DS.$_section.'.php')){
			$path=self::$scope.'/controllers/'.$_section.'.php';
		}
		if(is_dir(PATH.self::$scope.DS.'controllers'.DS.$_section)) if(file_exists(PATH.self::$scope.DS.'controllers'.DS.$_section.DS.$_subsection.'.php')) $path=self::$scope.'/controllers/'.$_section.'/'.$_subsection.'.php';
		return $path;
	}

	public static function url($section='',$subsection='',$vars=''){
		if(self::$_friendly){
			return ROOT.$section.(!empty($subsection) ? '/'.$subsection : '').(!empty($vars) ? '/'.$vars : '');
		}else{
			return ROOT.'/index.php?sct='.$section.(!empty($subsection) ? '&subsct='.$subsection : '').(!empty($vars) ? '&vars='.$vars : '');
		}
	}

	public static function img($folder='',$image=''){
		if(!file_exists(IMG.$folder.DS.$image)) return '';
		return ROOT.'img/'.$folder.'/'.$image;
	}

	public static function assets($image=''){
		if(!file_exists(PATH.DS.'assets'.DS.$image)) return '';
		return ROOT.'assets/'.$image;
	}

}