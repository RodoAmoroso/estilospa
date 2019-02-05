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

	public static function convertFromUnix($date,$format=''){

		$day = date('d',$date);
		$dayname = date('l',$date);
		$daynameshort = date('D',$date);
		$month = date('m',$date);
		$monthname = date('F',$date);
		$monthshort = date('M',$date);
		$yearfull = date('Y',$date);
		$year = date('y',$date);

		switch($format):
			case 'dd/mm/yyyy':
				self::$dateformat = $day.'/'.$month.'/'.$yearfull;
				break;
			case 'dd monthname yyyy':
				self::$dateformat = $day.' '.self::translateMonths($monthname).' '.$yearfull;
				break;
			default:
				self::$dateformat = $yearfull.'-'.$month.' '.$day.' 00:00:00';
				break;
		endswitch;

		return self::$dateformat;
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
