<?php

class Clients {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public 	$keywords='',
					$sort='',
					$limit='',
					$searchmixed=0,
					$exclude=0,
					$visible=0,
					$arrtypes=array(),
					$arrglossary=array(),
					$arridclients=array(),
					$locations='',
					$isadmin=false,
					$searchpromos=false;

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function find($client=0){
		$field = is_numeric($client) ? 'c.id' : 'c.permalink';
		//$this->_db->get('clients',array($field,'=',$client));
		$this->_db->query("SELECT c.*, p.fee, p.promos 
			FROM {$this->_dbprefix}clients c 
			LEFT JOIN {$this->_dbprefix}clientplans p ON p.id=c.idplan 
			WHERE {$field}=?",
			array($client)
		);
		if(!$this->_db->count()) return false;
		$this->_data = $this->_db->first();
		$this->_data->glossary = $this->get_glossary($client);
		$this->_data->types = $this->get_types($client);
		return true;
				
	}
	public function get_glossary($clientid){		
		$this->_db->get('clients_glossary_assignments',array('clientid','=',$clientid));
		if(!$this->_db->count()) return false;
		$arr = array();
		foreach($this->_db->results() as $g){
			$arr[] = $g->glossaryid;
		}
		return $arr;
	}
	public function get_types($clientid){		
		$this->_db->get('clients_types_assignments',array('clientid','=',$clientid));
		if(!$this->_db->count()) return false;
		$arr = array();
		foreach($this->_db->results() as $g){
			$arr[] = $g->typeid;
		}
		return $arr;
	}

	//(glossary LIKE '%,104' OR glossary LIKE '104,%' OR glossary LIKE '%,104,%' OR glossary = 104)

	public function get(){
		
		$search_main = BuildSearch($this->keywords,$this->searchmixed,array('c.name','c.subtitle'));
		$search = empty($search_main) ? "" : "WHERE (".$search_main;

		

		///$search_type = BuildSearch($this->arrtypes,$this->searchmixed,array('c.types'));
		$search .= empty($this->arrtypes) ? "" : (empty($search) ? "WHERE " : " OR ")." ta.typeid IN (".implode(',',$this->arrtypes).")";

		$search .= empty($this->arrglossary) ? "" : (empty($search) ? "WHERE " : " OR ")." ga.glossaryid IN (".implode(',',$this->arrglossary).")";

		//$search .= empty($this->arrglossary) ? "" : (empty($search) ? "WHERE " : " OR ")." ga.glossaryid IN (".implode(',',$this->arrglossary).")";


			//$search_glossary = BuildSearch($this->arrglossary,$this->searchmixed,array('c.glossary'));
		//	$search .= empty($search_glossary) ? "" : (empty($search) ? "WHERE (".$search_glossary : "OR".$search_glossary);			
		//}
		//show_array( $search);

		//$search_idclient = BuildSearch($this->arridclients,$this->searchmixed,array('c.id'),'equal');
		//$search .= empty($search_idclient) ? "" : (empty($search) ? "WHERE (".$search_idclient : " AND".$search_idclient);
		///$search = !empty($search) ? $search : $search;
		$search = !empty($search_main) ? $search.') ' : $search;

		$search .= empty($this->arridclients) ? "" : (empty($search) ? "WHERE " : " AND ")." c.id IN (".implode(',',$this->arridclients).")";
		
		//echo $search;

		/*if($this->searchpromos){
			$search .= empty($search) ? "WHERE " : " OR ";
			$search .= "(SELECT COUNT(*) FROM {$this->_dbprefix}promos ps WHERE ps.idclient=c.id AND (ps.start<=NOW() AND ps.finish >= NOW()) AND (ps.title LIKE '%{$this->keywords}%' OR ps.subtitle LIKE '%{$this->keywords}%') ) > 0";
			//echo $search;
		}*/

		///echo $search;


		$sortby = '';
		if(!empty($this->sort)){
			switch($this->sort){
				case 'name':
					$sortby = "ORDER BY c.name ASC";
					break;
				case 'rand':
					$sortby = "ORDER BY RAND()";
					break;
				case 'promocount':
					$sortby = "ORDER BY (SELECT COUNT(*) FROM {$this->_dbprefix}promos p WHERE p.idclient=c.id AND (p.start<=NOW() AND p.finish >= NOW()) ) DESC, (SELECT SUM(pp.price) FROM {$this->_dbprefix}promos pp WHERE pp.idclient=c.id) DESC";
					break;
				case 'date':
					$sortby = "ORDER BY c.added DESC";
					break;
			}
		}else{
			$sortby = "ORDER BY c.added DESC";
		}

		$limitby = '';
		if(!empty($this->limit)){
			$limitby = "LIMIT {$this->limit}";
		}
		if($this->exclude){
			if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$search .= " c.id != {$this->exclude}";
		}
		if($this->visible){
			if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$search .= " c.visible=1";
		}
		$query = "SELECT c.*, DATE_FORMAT(c.added,'%d/%m/%Y') as creado, (SELECT COUNT(*) FROM {$this->_dbprefix}promos p WHERE p.idclient=c.id) promos
			FROM {clients} c 
			LEFT JOIN {clients_glossary_assignments} ga ON ga.clientid=c.id
			LEFT JOIN {clients_types_assignments} ta ON ta.clientid=c.id
			{$search} 
			GROUP BY c.id
			{$sortby} 
			{$limitby}";
		///echo $search;
		$this->search = $search;	
		$this->_db->query($query);
		//echo $query;


		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function rating($id=0){
		$this->_db->query("SELECT AVG(cm.rate) rating
		FROM spa_comments cm 
		LEFT JOIN spa_sales s ON s.id=cm.idsale
		LEFT JOIN spa_promos p ON p.id=s.idpromo
		LEFT JOIN spa_clients c ON c.id=p.idclient
		WHERE c.id={$id}"
		);
		if($this->_db->count()){
			return $this->_db->first()->rating;
		}
		return 0;
	}

	public function save(){
		$sql = array(
			'name'=>Input::get('Name'),
			'subtitle'=>Input::get('Subtitle'),
			'web'=>Input::get('Web'),
			'mail'=>Input::get('Mail'),
			'images'=>json_encode(Input::get('Gallery')),
			'logo'=>json_encode(Input::get('Logo')),
			'socials'=>empty(Input::get('Socials')) ? '' : json_encode(Input::get('Socials'))
		);
		if($this->isadmin){
			$sql['permalink'] = Input::get('Permalink');
			$sql['idplan'] = Input::get('Plan');
			//$sql['types'] = implode(',',Input::get('Types'));
			//$sql['glossary'] = implode(',',Input::get('Glossary'));
			$sql['visible'] = Input::get('Visible');			
		}
		$_FEATURES = new Features();
		if(!Input::get('ID')){
			///////// CHECK PERMALINK //////////
			if($this->find(Input::get('Permalink'))) die(json_encode(array('Status'=>'permalink')));
			////////////////////////////////////
			$sql['added'] = date('Y-m-d H:i:s');
			if(!$this->_db->insert('clients',$sql)) return false;
			$this->_lastid = $this->_db->getLastId();
			$_STORES = new Stores();
			if(!$_STORES->updateclient($this->_lastid,Input::get('IDStores'))) return false;
			if(!$_FEATURES->save($this->_lastid,Input::get('Features'))) return false;
			//return true;
		}else{
			$sql['modified'] = date('Y-m-d H:i:s');
			if(!$this->_db->update('clients',Input::get('ID'),$sql)) return false;
			$this->_lastid = Input::get('ID');
			if(!$_FEATURES->save($this->_lastid,Input::get('Features'))) return false;
		}
		if($this->isadmin){
			if(!$this->save_glossary()) return false;
			if(!$this->save_types()) return false;
		}
		return true;
	}

	public function save_glossary(){

		$clientid = $this->_lastid;
		if(Input::get('Glossary')){
			$this->_db->delete('clients_glossary_assignments',array('clientid','=',$clientid));
			foreach(Input::get('Glossary') as $glossary){
				$this->_db->insert('clients_glossary_assignments',array(
					'clientid'=>$clientid,
					'glossaryid'=>$glossary
				));
			}
		}
		return true;
	}
	public function save_types(){

		$clientid = $this->_lastid;
		if(Input::get('Types')){
			$this->_db->delete('clients_types_assignments',array('clientid','=',$clientid));
			foreach(Input::get('Types') as $type){
				$this->_db->insert('clients_types_assignments',array(
					'clientid'=>$clientid,
					'typeid'=>$type
				));
			}
		}
		return true;
	}

	public function delete(){
		if($this->find(Input::get('ID'))){
			$gallery = json_decode($this->_data->images);
			$logo = json_decode($this->_data->logo);			
			if($this->_db->delete('clients',array('id','=',Input::get('ID')))){
				foreach($gallery as $kp=>$vp):
					if(isset($vg->photoname)){
						$th = PATH.'\img\clients\\'.$vg->photoname.'-t.'.$vg->extension;
						$bg = PATH.'\img\clients\\'.$vg->photoname.'-o.'.$vg->extension;
						if(file_exists($th)) unlink($th);
						if(file_exists($bg)) unlink($bg);
					}
					$lg = PATH.'\img\clients\\'.$logo->photoname.'.'.$logo->extension;
					if(file_exists($lg)) unlink($lg);
				endforeach;
				return true;
			}
		}
		return false;
	}

	public function getfee($idclient){
		$this->_db->query("SELECT p.fee, p.promos FROM {$this->_dbprefix}clients c LEFT JOIN {$this->_dbprefix}clientplans p ON p.id=c.idplan WHERE c.id=?",array($idclient));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function getassoc($iduser=0){
		$this->_db->get('assoc_client_user',array('iduser','=',$iduser));
		if(!$this->_db->count()) return false;
		$this->find($this->_db->first()->idclient);
		return true;
	}

	public function data(){
		return $this->_data;
	}

	public function addvisit(){
		$this->_db->query("UPDATE {$this->_dbprefix}clients SET views=views+1 WHERE id=?",array($this->_data->id));
	}

	public function getLastId(){
		return $this->_lastid;
	}

}
