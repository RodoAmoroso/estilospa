<?php 

class Blog {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public 	$keywords='',
					$idcategory=0,
					$limit='',
					$exclude=0,
					$searchmixed=0,
					$arrglossary=array();

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function save(){
		$date = explode('/',Input::get('Date'));
		$sql = array(
			'idcategory'=>Input::get('IDCategory'),
			'title'=>Input::get('Title'),
			'subtitle'=>Input::get('Subtitle'),
			'content'=>Input::get('Content'),
			'gallery'=>json_encode(Input::get('Gallery')),
			'glossary'=>empty(Input::get('Glossary')) ? '' : implode(',',Input::get('Glossary')),
			'shortdescription'=>Input::get('ShortDescription'),
			'date'=>$date[2].'-'.$date[1].'-'.$date[0]
		);
		if(Input::get('ID')){
			$this->_db->update('blog',Input::get('ID'),$sql);
		}else{
			$sql['added'] = date('Y-m-d H:i:s');
			$this->_db->insert('blog',$sql);
		}

	}
	
	public function getLastId(){
		return $this->_lastid;
	}
	
	public function delete(){
		if($this->find(Input::get('ID'))){
			$gallery = json_decode($this->_db->first()->gallery);
			foreach($gallery as $kg=>$vg){
				if(isset($vg->photoname)){
					$th = IMG.'blog'.DS.$vg->photoname.'-t.'.$vg->extension;
					$bg = IMG.'blog'.DS.$vg->photoname.'-o.'.$vg->extension;
					if(file_exists($th)) unlink($th);
					if(file_exists($bg)) unlink($bg);
				}
			}
			if($this->_db->delete('blog',array('id','=',Input::get('ID')))){
				return true;
			}

		}		
		return false;
	}
	
	public function get(){
		$search_main = BuildSearch($this->keywords,$this->searchmixed,array('b.title','b.subtitle','shortdescription','content'));
		$search_glossary = BuildSearch($this->arrglossary,$this->searchmixed,array('b.glossary'));
		$search = empty($search_main) ? "" : "WHERE".$search_main;
		$search .= empty($search_glossary) ? "" : (empty($search) ? "WHERE".$search_glossary : "OR".$search_glossary);

		if($this->idcategory){
			if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$search .= " b.idcategory={$this->idcategory}";
		}
		$limitby = '';
		if(!empty($this->limit)){
			$limitby = "LIMIT {$this->limit}";
		}
		if($this->exclude){
			if(!empty($search)){$search .= " AND";}else{$search = "WHERE";}
			$search .= " b.id != {$this->exclude}";
		}
		$this->_db->query(
			"SELECT b.*, DATE_FORMAT(b.date,'%d/%m/%Y') as fecha 
			FROM {blog} b
			{$search} 
			ORDER BY b.date DESC 
			{$limitby}"
		);

		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;		
	}
	
	public function find($id=0){
		$this->_db->query(
			"SELECT b.*, DATE_FORMAT(b.date, '%d/%m/%Y') as fecha 
			FROM {blog} b
			WHERE b.id=?",
			array($id)
		);
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function addvisit(){
		$this->_db->query("UPDATE {blog} SET views=views+1 WHERE id=?",array($this->_data->id));
	}
	
	public function data(){
		return $this->_data;
	}

}