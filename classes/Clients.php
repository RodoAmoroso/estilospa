<?php

class Clients {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid,
					$_error='';

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
			FROM {clients} c 
			LEFT JOIN {clientplans} p ON p.id=c.idplan 
			WHERE {$field}=?",
			array($client)
		);
		if(!$this->_db->count()) return false;
		$this->_data = $this->_db->first();
		$this->_data->glossary = $this->get_glossary($client);
		$this->_data->types = $this->get_types($client);
		$this->_data->imagery = $this->get_imagery($this->_data->logo,$this->_data->images);
		return true;
	}
	public function get_imagery($logo='',$images=''){
		if(empty($logo) || empty($images)) return false;
		$img = json_decode($logo);
		$data = new stdClass();
		$data->f = $img->photoname;
		$data->e = $img->extension;
		$data->logo = View::img('clients',$img->photoname.'.'.$img->extension);

		$gallery = json_decode($images);
		$data->gallery = array();
		foreach($gallery as $image){
			$img = new stdClass();
			if(isset($image->photoname)){
				$img->f = $image->photoname;
				$img->e = $image->extension;
				$img->big = View::img('clients',$image->photoname.'-o.'.$image->extension);
				$img->small = View::img('clients',$image->photoname.'-t.'.$image->extension);
			}
			if(isset($image->video)){
				$img->video = $image->video;
			}
			$data->gallery[] = $img;
		}

		return $data;
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

		

		$search .= empty($this->arrtypes) ? "" : (empty($search) ? "WHERE " : " OR ")." (SELECT COUNT(*) FROM {clients_types_assignments} ta WHERE ta.typeid IN (".implode(',',$this->arrtypes).") AND ta.clientid=c.id) > 0";

		$search .= empty($this->arrglossary) ? "" : (empty($search) ? "WHERE " : " OR ")." (SELECT COUNT(*) FROM {clients_glossary_assignments} ga WHERE ga.glossaryid IN (".implode(',',$this->arrglossary).") AND ga.clientid=c.id) > 0";


		$search = !empty($search_main) ? $search.') ' : $search;

		$search .= empty($this->arridclients) ? "" : (empty($search) ? "WHERE " : " AND ")." c.id IN (".implode(',',$this->arridclients).")";
		


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
			{$search}
			{$sortby} 
			{$limitby}";
		$this->search = $search;	
		$this->_db->query($query);
		//show_array( $this->_db->getquery()->queryString);


		if(!$this->_db->count()) {
			$this->_data = null; 
			return false;
		}
		$this->_data = $this->_db->results();
		
		return true;

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

	public function save($clientid=0){
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
		if(!$clientid){
			///////// CHECK PERMALINK //////////
			if($this->find(Input::get('Permalink'))) die(Responses::response('fail','El enlace permanente está en uso por otro centro. Deberás cambiarlo incluyendo alguna otra palabra.') );
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
			if(!$this->_db->update('clients',$clientid,$sql)) return false;
			$this->_lastid = $clientid;
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

	public function delete($idclient=0){

		if(!$this->find($idclient)){
			$this->_error = 'No se encontró el centro';
			return false;			
		}

		$Promos = new Promos();
		if(!$Promos->deleteAll($idclient)){			
			$this->_error = 'No se pudieron borrar las promos';
			return false;
		}

		$gallery = json_decode($this->_data->images);
		$logo = json_decode($this->_data->logo);			

		foreach($gallery as $kp=>$vp):
			if(isset($vg->photoname)){
				$th = IMG.'clients'.DS.$vg->photoname.'-t.'.$vg->extension;
				$bg = IMG.'clients'.DS.$vg->photoname.'-o.'.$vg->extension;
				if(file_exists($th)) unlink($th);
				if(file_exists($bg)) unlink($bg);
			}
			$lg = IMG.'clients'.DS.$logo->photoname.'.'.$logo->extension;
			if(file_exists($lg)) unlink($lg);
		endforeach;
		
		if(!$this->_db->delete('assoc_client_user',array('idclient','=',$idclient))){
			$this->_error = 'No se pudo borrar la asignación al usuario';
			return false;			
		}

		if(!$this->_db->delete('clients_glossary_assignments',array('clientid','=',$idclient))){			
			$this->_error = 'No se pudo la asignación a etiquetas';
			return false;
		}

		if(!$this->_db->delete('clients_types_assignments',array('clientid','=',$idclient))){
			$this->_error = 'No se pudo borrar la asignación del tipo de centro'; 
			return false;			
		}
		
		if(!$this->_db->delete('favs',array('idclient','=',$idclient))){
			$this->_error = 'No se pudieron borrar los favoritos';
			return false;			
		}

		if(!$this->_db->delete('mp',array('idclient','=',$idclient))){
			$this->_error = 'No se pudo borrar la integración con MercadoPago';
			return false;			
		}

		if(!$this->_db->query(
			"DELETE FROM {newsletters_queue} 
			WHERE type=? AND contextid=?",
			array('clients',$idclient)
		)){
			$this->_error = 'No se pudo borrar la cola de envío del Newsletter';
			return false;			
		}

		if(!$this->_db->delete('questions_queue',array('clientid','=',$idclient))){
			$this->_error = 'No se pudo borrar la cola de envío de las preguntas asociadas al centro';
			return false;			
		}

		if(!$this->_db->query(
			"DELETE FROM {questions}
			WHERE type=? AND rowid=?",
			array('promos',$promoid)
		)){
			$this->_error = 'No se pudieron borrar las preguntas asociadas al centro';
			return false;
		}

		if(!$this->_db->delete('reservations',array('clientid','=',$idclient))){
			$this->_error = 'No se pudieron borrar las reservas asociadas al centro';
			return false;
		}

		if(!$this->_db->delete('stats_events',array('clientid','=',$idclient))){
			$this->_error = 'No se pudieron borrar los eventos asociados al centro';
			return false;			
		}

		if(!$this->_db->delete('features',array('idclient','=',$idclient))){
			$this->_error = 'No se pudieron borrar las características del centro';
			return false;			
		}

		if(!$this->_db->delete('stores',array('idclient','=',$idclient))){
			$this->_error = 'No se pudieron borrar las sucursales';
			return false;			
		}

		if(!$this->_db->delete('clients',array('id','=',$idclient))){
			$this->_error = 'No se pudo borrar el centro';
			return false;			
		}

		return true;
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
	public function check_assoc($iduser=0,$idclient=0){
		$this->_db->query(
			"SELECT * 
			FROM {assoc_client_user} 
			WHERE iduser=? AND idclient=?",
			array($iduser,$idclient)
		);
		if(!$this->_db->count()) return false;
		return true;
	}

	public function data(){
		return $this->_data;
	}

	public function addvisit($clientid=0){
		$this->_db->query("UPDATE {clients} SET views=views+1 WHERE id=?",array($clientid));
	}

	public function getLastId(){
		return $this->_lastid;
	}

	public function error(){
		return $this->_error;
	}


}
