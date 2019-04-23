<?php

class GlossaryGroups {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public 	$limit='',
					$keywords='',
					$searchmixed=0;

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function find($id=0){
		$this->_db->get('glossarygroups',array('id','=',$id));
		
		if($this->_db->count()) return false;
		$data = $this->_db->first();
		$data->image = $this->get_image($data->image);
		$this->_data = $data;
		return true;		
	}

	public function get(){
		
		$search_main = BuildSearch($this->keywords,$this->searchmixed,array('name'));
		$search = empty($search_main) ? "" : "WHERE".$search_main;
		$limitby = '';
		if(!empty($this->limit)) $limitby = "LIMIT {$this->limit}";
		
		$this->_db->query(
			"SELECT * 
			FROM {glossarygroups} 
			{$search} 
			ORDER BY position ASC 
			{$limitby}"
		);
		
		if(!$this->_db->count()) return false;

		$data = $this->_db->results();
		$Glossary = new Glossary();
		foreach($data as $k=>$gl){
			$Glossary->idgroup = $gl->id;
			$Glossary->get();
			$data[$k]->glossary = $Glossary->data();
		}

		$this->_data = $data;
		return true;
		
	}
	public function get_image($image=''){
		if(empty($image)) return false;
		
		$img = json_decode($image);
		$data = new stdCLass();
		$data->big = View::img('glossary',$img->f.'.'.$img->e);
		$data->f = $img->f;
		$data->e = $img->e;
		return $data;
	}

	public function save(){
		$sql = array('name'=>Input::get('Name'));
		if(!Input::get('ID')){
			$this->_db->query(
				"UPDATE {glossarygroups} 
				SET position=position+1"
			);
			$sql['position'] = 1;
			$this->_db->insert('glossarygroups',$sql);
			$this->_lastid = $this->_db->getLastId();
			return true;
		}else{
			$this->_db->update('glossarygroups',Input::get('ID'),$sql);
			return true;
		}
		return false;
	}

	public function delete(){
		if($this->find(Input::get('ID'))){
			$position = $this->_data->position;
			if( $this->_db->delete('glossarygroups',array('id','=',Input::get('ID'))) ){
				$this->_db->query(
					"UPDATE {glossarygroups} 
					SET position=position-1 
					WHERE position>?",
					array($position)
				);
				return true;
			}
		}
		return false;
	}

	public function reorder(){
		$arrid = Input::get('ArrID');
		if(count($arrid)){			
			foreach($arrid as $k=>$v){
				$this->_db->update('glossarygroups',$v,array('position'=>$k+1));
			}
		}
		return true;
	}

	public function getLastId(){
		return $this->_lastid;
	}

	public function data(){
		return $this->_data;
	}

}
