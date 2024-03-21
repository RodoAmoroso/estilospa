<?php

class Stores{

	private $_db,
					$_data,
					$_dbprefix,
					$_schedule_today = '',
					$_schedule_list = array(),
					$_lastid;

	public 	$keywords='',
					$searchmixed=0,
					$limit='',
					$group='',
					$idprovince=0,
					$ids;

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}


	public function search(){
		$search_main = BuildSearch($this->keywords,$this->searchmixed,array('s.city','s.address','p.name'));
		$search = empty($search_main) ? "" : "WHERE".$search_main;
		//echo $search;
		$limitby = '';
		if(!empty($this->limit)){
			$limitby = "LIMIT {$this->limit}";
		}
		$groupby = '';
		if(!empty($this->group)){
			switch ($this->group) {
				case 'province':
					$groupby = "GROUP BY p.name";
					break;
				case 'city':
					$groupby = "GROUP BY s.city";
					break;
			}
		}
		if($this->idprovince){
			$search .= empty($search) ? "WHERE " : " AND ";
			$search .= "s.idprovince={$this->idprovince}";
		}

		if($this->ids){
			$search .= empty($search) ? "WHERE " : " AND ";
			//$implode = implode(',', $this->ids)
			$search .= "s.id IN({$this->ids})";
		}

		$this->_db->query(
			"SELECT
				s.id, s.city, s.idclient, s.idprovince,
				p.name, (SELECT COUNT(*) FROM {stores} ss LEFT JOIN {clients} c ON c.id=ss.idclient WHERE ss.city=s.city AND c.visible=1) as countclients
			FROM {stores} s
			LEFT JOIN {provinces} p ON p.id=s.idprovince
			{$search}
			{$groupby}
			{$limitby}");

		//show_array($this->_db->getquery()->queryString);
		$this->_data = false;
		if(!$this->_db->count()) return false;
		$this->_data = $this->_db->results();
		return true;

	}

	public function get($idclient=0,$ids=''){
		$where = "";
		if(!empty($ids)){
			$ids = explode(',',$ids);
			$where = "AND (";
			foreach($ids as $ki=>$id):
				$where .= "s.id=".$id;
				if(count($ids)-1!=$ki) $where .= " OR ";
			endforeach;
			$where .= ")";
		}
		$this->_db->query(
			"SELECT s.*, p.name
			FROM {stores} s
			LEFT JOIN {provinces} p ON p.id=s.idprovince
			WHERE s.idclient=?
			{$where}
			ORDER BY s.position ASC",
			array($idclient)
		);
		///show_array( $this->_db->getquery() );
		$this->_data = false;
		if(!$this->_db->count()) return true;

		$this->_data = $this->_db->results();
		foreach($this->_data as $k=>$row){
			$this->_data[$k]->full_address = $row->address;
			if(!empty($row->additional)) $this->_data[$k]->full_address .= ', '.$row->additional;
			$this->_data[$k]->full_address .= ', '.$row->city;
			$this->_data[$k]->full_address .= ', '.$row->name;
		}

		return true;
	}

	public function find($id=0){
		if(!$id) return false;
		$this->_db->get('stores',array('id','=',$id));
		if(!$this->_db->count()) return false;
		$this->_data = $this->_db->first();
		return true;
	}

	public function updateclient($idclient=0,$ids=array()){
		if(count($ids)){
			foreach ($ids as $k=>$id) {
				$this->_db->query("UPDATE {$this->_dbprefix}stores SET idclient=? WHERE id=?",array($idclient,$id));
			}
			return true;
		}
		return false;
	}

	public function save($idclient=0){
		//$this->delete($idclient);
		$sql = array(
			'idclient'=>$idclient,
			'address'=>Input::get('Address'),
			'additional'=>Input::get('Additional'),
			'city'=>Input::get('City'),
			'idprovince'=>Input::get('IDProvince'),
			'phones'=>Input::get('Phones'),
			'whatsapp'=>Input::get('Whatsapp'),
			'map'=>Input::get('Map'),
			'schedules'=>Input::get('Schedules')
		);
		if(Input::get('ID')){
			$this->_db->update('stores',Input::get('ID'),$sql);
			return true;
		}else{
			$this->_db->query(
				"UPDATE {stores}
				SET position=position+1
				WHERE idclient=?",
				array($idclient)
			);
			$sql['position'] = 1;
			$this->_db->insert('stores',$sql);
			$this->_lastid = $this->_db->getLastId();
			return true;
		}
		return false;
	}

	public function deleteAll($idclient=0){
		if( $this->_db->delete('stores',array('idclient','=',$idclient)) ){
			return true;
		}
		return false;
	}

	public function delete($id=0){
		if( $this->_db->delete('stores',array('id','=',$id)) ){
			return true;
		}
		return false;
	}

	public function data(){
		return $this->_data;
	}

	public function reorder(){
		$arrid = Input::get('ArrID');
		if(count($arrid)):
			foreach($arrid as $k=>$v):
				$this->_db->query("UPDATE {$this->_dbprefix}stores SET position=? WHERE id=?",array(($k+1),$v));
			endforeach;
		endif;
		return true;
	}


	public function buildSchedule($schedules=''){
		$arr = array();
		$schedule = 'Horarios no disponible';
		if(!empty($schedules)):
			//foreach ($this->_data as $kst=>$vst):
			$objschedules = json_decode($schedules);

			foreach($objschedules as $ks=>$vs):
				$day = Dates::translateShortToFull($vs->day).' ';
				$open = false;
				foreach($vs->schedules as $kh=>$vh):
					/////////////////////////////////////////////////////////////
					$hour_1 = explode(':',$vh[0]);
					$hour_2 = explode(':',$vh[1]);
					if($vs->day == date('D') && $hour_1[0] <= date('H') && $hour_2[0] >= date('H') ) $open = true;
					/////////////////////////////////////////////////////////////
					if($kh!=0 && $kh==count($vs->schedules)-1) $day .= ' y ';
					if($kh!=count($vs->schedules)-1 && $kh!=0) $day .= ', ';
					$day .= 'de '.$vh[0].' a '.$vh[1].' hs.';
				endforeach;
				if(!count($vs->schedules)){
					$day = Dates::translateShortToFull($vs->day).' cerrado';
					//$schedule = 'Horarios no disponible';
				}
				$arr[] = $day;

				if($vs->day == date('D')):
					if(!$vs->schedules):
						$schedule = '<span class="cl-pink-3">Hoy cerrado</span>';
					else:
						if($open):
						$schedule = 'Abierto ahora';
						else:
						$schedule = 'Cerrado ahora';
						endif;
					endif;
				endif;

			endforeach;
			//$arrschedule = $schedule;
			//$arrlist = $arr;
			//endforeach;
		endif;
		$this->_schedule_list = $arr;
		$this->_schedule_today = $schedule;
	}


	public function scheduleShort($schedules=''){}

	public function scheduleToday($schedule=''){
		$this->buildSchedule($schedule);
		return $this->_schedule_today;
	}

	public function schedulesList($schedule=''){
		$this->buildSchedule($schedule);
		return $this->_schedule_list;
	}

	public function getLastId(){
		return $this->_lastid;
	}

}
