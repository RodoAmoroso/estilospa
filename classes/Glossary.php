<?php

class Glossary {
	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public 	$keywords='',
					$idgroup=0,
					$limit='',
					$searchmixed=0,
					$sort='';

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function get(){
		$search_main = BuildSearch($this->keywords,$this->searchmixed,array('g.name','gg.name'));		
		$search = empty($search_main) ? "" : "WHERE".$search_main;	

		///echo $search;	
		$sortby = '';
		if(!empty($this->sort)){
			switch($this->sort){
				case 'name':
					$sortby = "ORDER BY g.name ASC";
					break;
				case 'rand':
					$sortby = "ORDER BY RAND()";
					break;
				default:
					$sortby = "ORDER BY g.position DESC";
					break;
			}
		}		
		if($this->idgroup){
			if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$search .= " g.idgroup={$this->idgroup}";
		}
		$limitby = '';
		if(!empty($this->limit)){
			$limitby = "LIMIT {$this->limit}";
		}
		$query = "
			SELECT g.id, g.name name, g.description, g.image, g.idgroup, gg.name groupname, (SELECT COUNT(*) FROM {$this->_dbprefix}clients c WHERE (c.glossary LIKE CONCAT('%',g.id,'%') OR c.glossary LIKE CONCAT(g.id,'%') OR c.glossary LIKE CONCAT('%',g.id) OR c.glossary=g.id) AND c.visible=1) as countclients
			FROM {$this->_dbprefix}glossary g 
			LEFT JOIN {$this->_dbprefix}glossarygroups gg ON gg.id=g.idgroup 
			{$search} 
			{$sortby} 
			{$limitby}";

		//show_array($query);

		$this->_db->query($query);
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function find($id=0){
		$this->_db->query("SELECT g.id, g.name, g.description, g.position, g.image, g.idgroup, gg.name groupname FROM {$this->_dbprefix}glossary g LEFT JOIN {$this->_dbprefix}glossarygroups gg ON gg.id=g.idgroup WHERE g.id=?",array($id));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function save(){
		$sql = array('name'=>Input::get('Name'),'idgroup'=>Input::get('IDGroup'),'description'=>Input::get('Description'),'image'=>json_encode(Input::get('IMG')));
		if(Input::get('ID')):
			$this->_db->update('glossary',Input::get('ID'),$sql);
			return true;
		else:
			$this->_db->query("UPDATE {$this->_dbprefix}glossary SET position=position+1 WHERE idgroup=?",array(Input::get('IDGroup')));
			$sql['position'] = 1;
			$this->_db->insert('glossary',$sql);
			$this->_lastid = $this->_db->getLastId();
			return true;
		endif;
		return false;
	}

	public function getLastId(){
		return $this->_lastid;
	}

	public function delete(){
		$arrid = Input::get('ArrID');
		if(count($arrid)):
			foreach($arrid as $k=>$v):
				$this->find($v);
				$img = json_decode($this->_data->image);
				$pos = $this->_data->position;
				$idgroup = $this->_data->idgroup;
				if(file_exists(PATH.'\img\glossary\\'.$img->photoname.'.'.$img->extension)):
					unlink(PATH.'\img\glossary\\'.$img->photoname.'.'.$img->extension);
				endif;
				$this->_db->delete('glossary',array('id','=',$v));
				$this->_db->query("UPDATE {$this->_dbprefix}glossary SET position=position-1 WHERE idgroup=? AND position>?",array($idgroup,$pos));
			endforeach;
			return true;
		else:
			return false;
		endif;
	}

	public function reorder(){
		$arrid = Input::get('ArrID');
		if(count($arrid)):
			foreach($arrid as $k=>$v):
				$this->_db->update('glossary',$v,array('position'=>$k+1));
			endforeach;
		endif;
		return true;
	}

	public function data(){
		return $this->_data;
	}


	public function addvisit($id=0){
		$this->_db->query("UPDATE {glossary} SET views=views+1 WHERE id=?",array($id));
	}


}