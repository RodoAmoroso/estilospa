<?php 

class View {

	private static $_friendly=true;
	public static $scope=ROOT;

	public static function url($section='',$subsection='',$vars=''){
		if(self::$_friendly){
			return self::$scope.$section.(!empty($subsection) ? '/'.$subsection : '').(!empty($vars) ? '/'.$vars : '');
		}else{
			return self::$scope.'index.php?sct='.$section.(!empty($subsection) ? '&subsct='.$subsection : '').(!empty($vars) ? '&vars='.$vars : '');
		}
	}

	public static function img($folder='',$image=''){
		if(!file_exists(PATH.DS.'img'.DS.$folder.DS.$image)) return '';
		return ROOT.'img/'.$folder.'/'.$image;
	}

	public static function assets($image=''){
		if(!file_exists(PATH.DS.'assets'.DS.$image)) return '';
		return ROOT.'assets/'.$image;
	}

}