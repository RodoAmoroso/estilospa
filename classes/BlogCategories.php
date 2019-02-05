<?php 

class BlogCategories {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;
	public 	$limit = '';

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function save(){
		$sql = array('name'=>Input::get('Name'));
		if(!Input::get('ID')){
			$this->_db->query("UPDATE {$this->_dbprefix}blogcategories SET position=position+1");
			$sql['position'] = 1;
			$this->_db->insert('blogcategories',$sql);
			$this->_lastid = $this->_db->getLastId();
			return true;
		}else{
			$this->_db->update('blogcategories',Input::get('ID'),$sql);
			$this->_lastid = Input::get('ID');
			return true;
		}
		return false;
	}

	public function getLastId(){
		return $this->_lastid;
	}

	public function delete(){
		if($this->find(Input::get('ID'))){
			$position = $this->_data->position;
			if( $this->_db->delete('blogcategories',array('id','=',Input::get('ID'))) ){
				$this->_db->query("UPDATE {$this->_dbprefix}blogcategories SET position=position-1 WHERE position>?",array($position));
				return true;
			}
		}		
		return false;
	}

	public function get(){
		$limitby = '';
		if(!empty($this->limit)) $limit = "LIMIT {$this->limit}";
		$this->_db->query("SELECT * FROM {$this->_dbprefix}blogcategories ORDER BY position ASC {$limitby}");
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function reorder(){
		$arrid = Input::get('ArrID');
		if(count($arrid)):
			foreach($arrid as $k=>$v):
				$this->_db->update('blogcategories',$v,array('position'=>$k+1));
			endforeach;
		endif;
		return true;
	}

	public function find($id=0){
		$this->_db->get('blogcategories',array('id','=',$id));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function data(){
		return $this->_data;
	}

}