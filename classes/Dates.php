<?php


class Dates {

	public static $shortdays = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
	public static $days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
	public static $dateformat;
	static $_hours;

	public static function translateDays($day){
		switch($day):
			case 'Monday':
				$dayconverted = 'Lunes';
				break;
			case 'Mon':
				$dayconverted = 'Lun';
				break;
			case 'Tuesday':
				$dayconverted = 'Martes';				
				break;
			case 'Tue':
				$dayconverted = 'Mar';
				break;
			case 'Wednesday':
				$dayconverted = 'Miércoles';
				break;
			case 'Wed':
				$dayconverted = 'Mie';
				break;
			case 'Thursday':
				$dayconverted = 'Jueves';
				break;
			case 'Thu':
				$dayconverted = 'Jue';
				break;
			case 'Friday':
				$dayconverted = 'Viernes';
				break;
			case 'Fri':
				$dayconverted = 'Vie';
				break;
			case 'Saturday':
				$dayconverted = 'Sábado';
				break;
			case 'Sat':
				$dayconverted = 'Sab';
				break;
			case 'Sunday':
				$dayconverted = 'Domingo';
				break;
			case 'Sun':
				$dayconverted = 'Dom';
				break;
			default:
				$dayconverted = $day;
		endswitch;
		return $dayconverted;
	}

	public static function translateShortToFull($day){
		switch($day) {
			case 'Mon':
				$dayconverted = 'Lunes';
				break;
			case 'Tue':
				$dayconverted = 'Martes';
				break;
			case 'Wed':
				$dayconverted = 'Miércoles';
				break;
			case 'Thu':
				$dayconverted = 'Jueves';
				break;
			case 'Fri':
				$dayconverted = 'Viernes';
				break;
			case 'Sat':
				$dayconverted = 'Sábado';
				break;
			case 'Sun':
				$dayconverted = 'Domingo';
				break;
			default:
				$dayconverted = $day;
				break;
		}
		return $dayconverted;
	}

	public static function translateMonths($month){
		switch($month):
			case 'January':
				$monthconverted = 'Enero';
				break;
			case 'Jan':
				$monthconverted = 'Ene';
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
			case 'Apr':
				$monthconverted = 'Abr';
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
			case 'Aug':
				$monthconverted = 'Ago';
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
			case 'Dec':
				$monthconverted = 'Dic';
				break;
			default:
				$monthconverted = $month;
		endswitch;
		return $monthconverted;
	}

	public static function convert_datetime($datetime,$format=''){
		switch ($format) {
			case 'Y-m-d H:i:s':
				$arr = explode(' ',$datetime);
				$date = explode('/',$arr[0]);
				return $date[2].'-'.$date[1].'-'.$date[0].' '.(isset($arr[1]) ? $arr[1] : '00:00:00');
				break;
			case 'Y-m-d':
				$date = explode('/',$datetime);
				return $date[2].'-'.$date[1].'-'.$date[0];
				break;

			case 'd/m/Y H:i':
				$arr = explode(' ',$datetime);
				$dt = explode('-',$arr[0]);
				$tm = explode(':',$arr[1]);
				return $dt[2].'/'.$dt[1].'-'.$dt[0].' '.$tm[0].':'.$tm[1].' hs';
				break;
			
			default:
				return false;
				break;
		}
	}

	public static function getHours(){
		for ($i=0; $i<=23; $i++):
			$hour = $i<10 ? '0'.$i : $i;
			self::$_hours[] = $hour.':00';
			self::$_hours[] = $hour.':30';
		endfor;
		return self::$_hours;
	}

}
