<?php

class Promos {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid;

	public 	$keywords='',
					$keywords_sort='',
					$idclient=0,
					$status='',
					$issale=0,
					$sort='',
					$limit='',
					$exclude=0,
					$searchmixed=0,
					$arrtypes=array(),
					$arrglossary=array(),
					$arridclients=array(),
					$arrpromotypes=array(),
					$expiring=false,
					$expired=false,
					$visible=false,
					$search='';

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function find($id=0){
		$this->_db->query(
			"SELECT p.id, p.idclient, p.sale, p.stores, p.title, p.subtitle, p.description, p.includes, p.duration, p.recomendations, p.reservation, p.cancellation, p.gallery, p.price, p.idpromotype, p.discount, p.amount, DATE_FORMAT(p.start, '%d/%m/%Y') inicio, DATE_FORMAT(p.finish, '%d/%m/%Y') fin, p.start<=NOW() statusstart, p.finish>=NOW() statusfinish, p.views, c.permalink, c.name clientname
			FROM {$this->_dbprefix}promos p 
			LEFT JOIN {$this->_dbprefix}clients c ON c.id=p.idclient 
			WHERE p.id=?",
			array($id)
		);
		
		if(!$this->_db->count()) return false;
		$this->_data = $this->_db->first();
		$this->_data->glossary = $this->get_glossary($id);
		$this->_data->image = $this->get_image($this->_data->gallery);
		return true;
		
	}

	public function get_image($gallery=''){
		if(empty($gallery)) return false;
		$img = json_decode($gallery);
		return ROOT.'img/promos/'.$img[0]->photoname.'-t.'.$img[0]->extension;
	}

	public function get(){
		$this->_data = null;
		
		$search_main = BuildSearch($this->keywords,$this->searchmixed,array('p.title','p.subtitle','p.description'));
		$search = empty($search_main) ? "" : "WHERE (".$search_main;
		
		
		//$search_type = BuildSearch($this->arrtypes,$this->searchmixed,array('c.types'));
		//$search .= empty($search_type) ? "" : (empty($search) ? "WHERE (".$search_type : " AND".$search_type);
		
		//$search_glossary = BuildSearchAssignment($this->arrglossary,'ga.glossaryid');
		///$search .= empty($this->arrglossary) ? "" : (empty($search) ? "WHERE " : " OR ")." ga.glossaryid IN (".implode(',',$this->arrglossary).")";

		$search .= empty($this->arrglossary) ? "" : (empty($search) ? "WHERE " : " OR ")." (SELECT COUNT(*) FROM {promos_glossary_assignments} ga WHERE ga.glossaryid IN (".implode(',',$this->arrglossary).") AND ga.promoid=p.id) > 0";

		
		$search_promotype = BuildSearch($this->arrpromotypes,$this->searchmixed,array('p.idpromotype'));
		$search .= empty($search_promotype) ? "" : (empty($search) ? "WHERE (".$search_promotype : " OR".$search_promotype);
		
		$search = !empty($search_main) ? $search.') ' : $search;

		
		//$search_idclient = BuildSearch($this->arridclients,$this->searchmixed,array('c.id'),'equal');
		//$search .= empty($search_idclient) ? "" : (empty($search) ? "WHERE".$search_idclient : " AND".$search_idclient);


		$search .= empty($this->arridclients) ? "" : (empty($search) ? "WHERE " : " AND ")." c.id IN (".implode(',',$this->arridclients).")";
		///show_array( $this->arridclients);

		//$search .= empty($search_glossary) ? "" : (empty($search) ? "WHERE (".$search_glossary : "OR".$search_glossary);

		$sortby = "ORDER BY p.sale DESC, p.added DESC";
		if(!empty($this->sort)){
			switch($this->sort){
				case 'name':
					$sortby = "ORDER BY p.sale DESC, p.name ASC";
					break;
				case 'rand':
					$sortby = "ORDER BY RAND()";
					break;
				case 'search':
					$sortby = "ORDER BY (SELECT COUNT(*) FROM {$this->_dbprefix}promos ps WHERE ps.id=p.id AND (ps.start<=NOW() AND ps.finish >= NOW()) AND (ps.title LIKE '%{$this->keywords_sort}%' OR ps.subtitle LIKE '%{$this->keywords_sort}%')) DESC";
					break;
				case 'added':
					$sortby = "ORDER BY p.added DESC";
					break;
				case 'finish':
					$sortby = "ORDER BY p.finish ASC";
					break;
				case 'position':
					$sortby = "ORDER BY p.position ASC";
					break;
				case 'position_client':
					$sortby = "ORDER BY p.position_client ASC";
					break;
			}
		}
		///echo $sortby;
		if($this->idclient){
			if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$search .= " p.idclient={$this->idclient}";
		}
		if(!empty($this->status)){
			if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$arrstatus = explode(':',$this->status);
			if($arrstatus[0]){$start = "p.start<=NOW()";}else{$start = "p.start >= NOW()";}
			if($arrstatus[1]){$finish = "p.finish>=NOW()";}else{$finish = "p.finish <= NOW()";}
			$search .= " {$start} AND {$finish}";
		}
		if($this->visible){
			if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$search .= " c.visible=1";
		}
		$limitby = '';
		if(!empty($this->limit)){
			$limitby = "LIMIT {$this->limit}";
		}
		/*if($amount){
			if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$search .= " p.amount > 0";
		}*/
		if($this->exclude){
			if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$search .= " p.id != {$this->exclude}";
		}
		if($this->issale){
			if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$search .= " p.sale = 1";
		}
		if($this->expiring){
			//if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$search .= empty($search) ? "WHERE " : " AND ";
			$search .= "(DATEDIFF(p.finish, NOW()) < 25 AND DATEDIFF(p.finish, NOW()) > 0)";
		}
		if($this->expired){
			//if(empty($search)){$search = "WHERE";}else{$search .= " AND";}
			$search .= empty($search) ? "WHERE " : " AND ";
			$search .= "DATEDIFF(p.finish, NOW()) < 0";
		}

		$this->search = $search;

		//echo $search;

		$query = "SELECT p.*, p.start<=NOW() statusstart, p.finish>=NOW() statusfinish, DATE_FORMAT(p.start, '%d/%m/%Y') start, DATE_FORMAT(p.finish, '%d/%m/%Y') finish, DATE_FORMAT(p.added, '%d/%m/%Y') creado, c.permalink, c.name, c.subtitle clientsubtitle, c.glossary, c.types, t.name promotypename, DATEDIFF(p.finish, NOW()) dif
			FROM {promos} p 
			LEFT JOIN {clients} c ON c.id=p.idclient
			LEFT JOIN {promotypes} t ON t.id=p.idpromotype
			{$search} 
			{$sortby} 
			{$limitby}";

		//GROUP BY p.id
		//LEFT JOIN {promos_glossary_assignments} ga ON ga.promoid=p.id
		//echo $query;

		$this->_db->query($query);
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function get_glossary($promoid){		
		$this->_db->get('promos_glossary_assignments',array('promoid','=',$promoid));
		if(!$this->_db->count()) return false;
		$arr = array();
		foreach($this->_db->results() as $g){
			$arr[] = $g->glossaryid;
		}
		return $arr;
	}

	public function rating($id=0){		
		$this->_db->query(
			"SELECT AVG(cm.rate) rating 
			FROM {comments} cm 
			LEFT JOIN {sales} s ON s.id=cm.idsale 
			WHERE s.idpromo=?",
			array($id)
		);
		if($this->_db->count()){
			return $this->_db->first()->rating;
		}
		return 0;
	}

	public function save($idclient=0){
		$start = explode('/',Input::get('Start'));
		$finish = explode('/',Input::get('Finish'));
		$sql = array(
		'idclient'=>$idclient,
		'idpromotype'=>Input::get('IDPromotype'),
		'sale'=>Input::get('Sale'),
		'stores'=>implode(',',Input::get('Stores')),
		'title'=>Input::get('Title'),
		'subtitle'=>Input::get('Subtitle'),
		'description'=>Input::get('Description'),
		'includes'=>Input::get('Includes'),
		'duration'=>Input::get('Duration'),
		'recomendations'=>Input::get('Recomendations'),
		'reservation'=>Input::get('Reservation'),
		'cancellation'=>Input::get('Cancellation'),
		'gallery'=>json_encode(Input::get('Gallery')),
		'price'=>Input::get('Price'),
		'discount'=>Input::get('Discount'),
		'amount'=>Input::get('Amount'),
		'start'=>$start[2].'-'.$start[1].'-'.$start[0],
		'finish'=>$finish[2].'-'.$finish[1].'-'.$finish[0]
		);
		if(!Input::get('ID')){
			$sql['added'] = date('Y-m-d H:i:s');
			if(!$this->_db->insert('promos',$sql)) return false;
			$this->_lastid = $this->_db->getLastId();
		}else{
			if(!$this->_db->update('promos',Input::get('ID'),$sql)) return false;
			$this->_lastid = Input::get('ID');
		}
		$this->save_glossary();

		return true;
	}

	public function save_glossary(){
		$promoid = $this->_lastid;
		if(Input::get('Glossary')){
			$this->_db->delete('promos_glossary_assignments',array('promoid','=',$promoid));
			foreach(Input::get('Glossary') as $glossary){
				$this->_db->insert('promos_glossary_assignments',array(
					'promoid'=>$promoid,
					'glossaryid'=>$glossary
				));
			}
		}
		return true;
	}

	public function delete(){
		if($this->find(Input::get('ID'))){
			if($this->_db->count()):
				$img = json_decode($this->_db->first()->gallery);
				foreach($img as $kp=>$vp):
					$bg = IMG.'promos'.DS.$vp->photoname.'-o.'.$vp->extension;
					$th = IMG.'promos'.DS.$vp->photoname.'-t.'.$vp->extension;
					if(file_exists($th)) unlink($th);
					if(file_exists($bg)) unlink($bg);
				endforeach;
			endif;
			if($this->_db->delete('promos',array('id','=',Input::get('ID')))){
				return true;
			}
		}		
		return false;
	}

	public function deleteAll($idclient=0){
		if($this->_db->delete('promos',array('idclient','=',$idclient))){
			return true;
		}
		return false;
	}

	public function getLastId(){
		return $this->_lastid;
	}

	public function addvisit(){
		$this->_db->query("UPDATE {promos} SET views=views+1 WHERE id=?",array($this->_data->id));
	}

	public function take_amount($id,$q){
		if($this->_db->query("UPDATE {promos} SET amount=amount-{$q} WHERE id=?",array($id))){
			return true;
		}
		return false;
	}

	public function data(){
		return $this->_data;
	}

	public function reorder($arrids=array()){
		/*$this->_db->get('promos');
		$gettotal = $this->_db->count();*/
		$reordertotal = count($arrids);
		if(!empty($arrids)){
			$where_exclude = "WHERE (";
			///$where_include = "WHERE (";
			foreach($arrids as $k=>$v){
				$this->_db->update('promos',$v,array('position'=>$k+1));
				$where_exclude .= "id!={$v}".($k<count($arrids)-1 ? ' AND ' : ')');
				//$where_include .= "id={$v}".($k<count($arrids)-1 ? ' OR ' : ')');
			}
			//if(!empty($exclude))

			/*$this->_db->query(
				"UPDATE {$this->_dbprefix}promos 
				SET position = position+1
				{$where_include}"
			);*/

			/*$this->_db->query(
				"SET @rownumber = 0;
				UPDATE {$this->_dbprefix}promos SET position = (@rownumber:=@rownumber+1)
				{$where_include}
				ORDER BY position ASC;"
			);*/

			$this->_db->query(
				"SET @rownumber = {$reordertotal};
				UPDATE {promos} SET position = (@rownumber:=@rownumber+1)
				{$where_exclude}
				ORDER BY position ASC;"
			);


			//$this->_db->query("");
		}
		return true;
	}

}
