<?php 

class ClientTypes {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public 	$limit='',
					$keywords='',
					$searchmixed=0;

	public function __construct(){
		$this->_dbprefix = Env::get('DB_PREFIX');
		$this->_db = DB::getInstance();
	}

	public function find($id=0){
		$this->_db->get('clienttypes',array('id','=',$id));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function get(){
		$search_main = BuildSearch($this->keywords,$this->searchmixed,array('name'));
		$search = empty($search_main) ? "" : "WHERE".$search_main;
		$limitby = '';
		if(!empty($this->limit)) $limitby = "LIMIT {$this->limit}";
		$this->_db->query("SELECT * FROM {$this->_dbprefix}clienttypes {$search} ORDER BY position ASC {$limitby}");
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function save(){
		$sql = array(
			'name'=>Input::get('Name'),
			'position'=>0
		);
		if(!Input::get('ID')){
			$this->_db->insert('clienttypes',$sql);
			$this->_lastid = $this->_db->getLastId();
			return true;
		}else{
			$this->_db->update('clienttypes',Input::get('ID'),$sql);
			$this->_lastid = Input::get('ID');
			return true;
		}
		return false;
	}

	public function delete(){
		if( $this->_db->delete('clienttypes',array('id','=',Input::get('ID'))) ){
			return true;
		}
		return false;
	}
	
	public function reorder(){
		$arrid = Input::get('ArrID');
		if(count($arrid)):
			foreach($arrid as $k=>$v):
				$this->_db->update('clienttypes',$v,array('position'=>$k+1));
			endforeach;
		endif;
		return true;
	}

	public function getLastId(){
		return $this->_lastid;
	}

	public function data(){
		return $this->_data;
	}

}