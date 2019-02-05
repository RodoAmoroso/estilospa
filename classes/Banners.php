<?php 

class Banners {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;
	public 	$visible=0,
					$type='',
					$sort='',
					$limit='';

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function save(){
		$sql = array(
			'name'=>Input::get('Name'),
			'title'=>Input::get('Title'),
			'caption'=>Input::get('Caption'),
			'image'=>json_encode(Input::get('IMG')),
			'link'=>json_encode(Input::get('Link')),
			'visible'=>Input::get('Visible'),
			'type'=>Input::get('Type')
		);
		if(!Input::get('ID')){
			$this->_db->query("UPDATE {$this->_dbprefix}banners SET position=position+1 WHERE type=?",array(Input::get('Type')));
			$sql['position'] = 1;
			$sql['added'] = date('Y-m-d H:s:i');
			$this->_db->insert('banners',$sql);
			$this->_lastid = $this->_db->getLastId();
			return true;
		}else{
			$this->_db->update('banners',Input::get('ID'),$sql);
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
			$img = json_decode($this->_data->image);
			$pos = $this->_data->position;
			$type = $this->_data->type;
			if( $this->_db->delete('banners',array('id','=',Input::get('ID'))) ){
				if(file_exists(PATH.'\img\home\\'.$img->photoname.'-o.'.$img->extension)) unlink(PATH.'\img\home\\'.$img->photoname.'-o.'.$img->extension);
				if(file_exists(PATH.'\img\home\\'.$img->photoname.'-t.'.$img->extension)) unlink(PATH.'\img\home\\'.$img->photoname.'-t.'.$img->extension);
				$this->_db->query("UPDATE {$this->_dbprefix}banners SET position=position-1 WHERE type=? AND position>?",array($type,$pos));
				return true;
			}
		}		
		return false;
	}

	public function get(){
		$search = '';
		if($this->visible) $search = "WHERE visible=1";
		if(!empty($this->type)){
			if(!empty($search)){
				$search .= " AND";
			}else{
				$search = "WHERE";
			}
			$search .= " type='{$this->type}'";
		}		
		switch($this->sort){
			case 'name':
				$sort = "ORDER BY name ASC";
				break;
			case 'position':
				$sort = "ORDER BY name ASC";
				break;
			case 'rand':
				$sort = "ORDER BY RAND()";
				break;
			default:
				$sort = "ORDER BY added DESC";
				break;
		}
		$limitby = '';
		if(!empty($this->limit)){
			$limitby = "LIMIT {$this->limit}";
		}
		$this->_db->query("SELECT id, name, title, caption, visible, DATE_FORMAT(added,'%d/%m/%Y') added, image, link, type FROM {$this->_dbprefix}banners {$search} {$sort} {$limitby}");
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function find($id=0){
		$this->_db->get('banners',array('id','=',$id));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function reorder(){
		$arrid = Input::get('ArrID');
		if(count($arrid)):
			foreach($arrid as $k=>$v):
				$this->_db->update('banners',$v,array('position'=>$k+1));
			endforeach;
		endif;
		return true;
	}

	public function data(){
		return $this->_data;
	}
}