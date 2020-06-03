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
					$issale=false,
					$isgift=false,
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
					$search='',
					$filters=false;

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function find($id=null){
		if(is_null($id)) return false;
		$this->_db->query(
			"SELECT p.*, DATE_FORMAT(p.start, '%d/%m/%Y') inicio, DATE_FORMAT(p.finish, '%d/%m/%Y') fin, p.start<=NOW() statusstart, p.finish>=NOW() statusfinish, c.permalink, c.name clientname
			FROM {promos} p
			LEFT JOIN {clients} c ON c.id=p.idclient
			WHERE p.id=?",
			array($id)
		);

		if(!$this->_db->count()) return false;
		$this->_data = $this->_db->first();
		$this->_data->glossary = $this->get_glossary($id);
		$this->_data->image = $this->get_image($this->_data->gallery);
		$this->_data->url = ROOT.'promo/'.$this->_data->permalink.'/'.$this->_data->id.'-'.Permalink($this->_data->title);
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
		$where = empty($search_main) ? "" : "WHERE (".$search_main;


		//$search_type = BuildSearch($this->arrtypes,$this->searchmixed,array('c.types'));
		//$where .= empty($search_type) ? "" : (empty($where) ? "WHERE (".$search_type : " AND".$search_type);

		//$search_glossary = BuildSearchAssignment($this->arrglossary,'ga.glossaryid');
		///$where .= empty($this->arrglossary) ? "" : (empty($where) ? "WHERE " : " OR ")." ga.glossaryid IN (".implode(',',$this->arrglossary).")";

		$where .= empty($this->arrglossary) ? "" : (empty($where) ? "WHERE " : " OR ")." (SELECT COUNT(*) FROM {promos_glossary_assignments} ga WHERE ga.glossaryid IN (".implode(',',$this->arrglossary).") AND ga.promoid=p.id) > 0";


		$search_promotype = BuildSearch($this->arrpromotypes,$this->searchmixed,array('p.idpromotype'));
		$where .= empty($search_promotype) ? "" : (empty($where) ? "WHERE (".$search_promotype : " OR".$search_promotype);

		$where = !empty($search_main) ? $where.') ' : $where;


		//$search_idclient = BuildSearch($this->arridclients,$this->searchmixed,array('c.id'),'equal');
		//$where .= empty($search_idclient) ? "" : (empty($where) ? "WHERE".$search_idclient : " AND".$search_idclient);


		$where .= empty($this->arridclients) ? "" : (empty($where) ? "WHERE " : " AND ")." c.id IN (".implode(',',$this->arridclients).")";
		///show_array( $this->arridclients);

		//$where .= empty($search_glossary) ? "" : (empty($where) ? "WHERE (".$search_glossary : "OR".$search_glossary);

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
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= " p.idclient={$this->idclient}";
		}
		if(!empty($this->status)){
			$where .= empty($where) ? "WHERE " : " AND ";
			$arrstatus = explode(':',$this->status);
			if($arrstatus[0]){$start = "p.start<=NOW()";}else{$start = "p.start >= NOW()";}
			if($arrstatus[1]){$finish = "p.finish>=NOW()";}else{$finish = "p.finish <= NOW()";}
			$where .= " {$start} AND {$finish}";
		}
		if($this->visible){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= " c.visible=1";
		}
		$limitby = '';
		if(!empty($this->limit)){
			$limitby = "LIMIT {$this->limit}";
		}
		if($this->exclude){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= " p.id != {$this->exclude}";
		}
		if($this->issale){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= " p.sale = 1";
		}

		/*if($this->isgift){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= " p.gift = 1";
		}*/


		if($this->expiring){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "(DATEDIFF(p.finish, NOW()) < 25 AND DATEDIFF(p.finish, NOW()) > 0)";
		}
		if($this->expired){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "DATEDIFF(p.finish, NOW()) < 0";
		}

		if($this->filters){
			foreach($this->filters as $key=>$filter){
				switch ($key) {
					case 'gift':
						$where .= empty($where) ? "WHERE " : " AND ";
						$where .= "p.gift={$filter}";
						break;

				}
			}
		}

		$this->search = $where;

		//echo $search;

		$query = "
			SELECT
				p.*, p.start<=NOW() statusstart, p.finish>=NOW() statusfinish, DATE_FORMAT(p.start, '%d/%m/%Y') start, DATE_FORMAT(p.finish, '%d/%m/%Y') finish, DATE_FORMAT(p.added, '%d/%m/%Y') creado,
				c.permalink, c.name, c.subtitle clientsubtitle, c.glossary, c.types,
				t.name promotypename, DATEDIFF(p.finish, NOW()) dif
				FROM {promos} p
			LEFT JOIN {clients} c ON c.id=p.idclient
			LEFT JOIN {promotypes} t ON t.id=p.idpromotype
			{$where}
			{$sortby}
			{$limitby}";

		//GROUP BY p.id
		//LEFT JOIN {promos_glossary_assignments} ga ON ga.promoid=p.id
		//echo $query;

		$this->_db->query($query);

		if(!$this->_db->count()){
			$this->_data = null;
			return true;
		}

		$this->_data = $this->_db->results();
		return true;
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

	public function get_total($clientid=0){
		$this->_db->get('promos',['idclient','=',$clientid]);
		if($this->_db->count()) return 0;
		return $this->_db->count();

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
		'categoryid'=>empty(Input::get('CategoryID')) ? null : Input::get('CategoryID'),
		'gift'=>Input::get('Gift'),
		'sale'=>Input::get('Sale'),
		'stores'=>implode(',',Input::get('Stores')),
		'title'=>Input::get('Title'),
		'subtitle'=>Input::get('Subtitle'),
		'label'=>Input::get('Label'),
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

	public function delete($promoid=0){

		if(!$this->find($promoid)) return false;

		$img = json_decode($this->_data->gallery);

		foreach($img as $kp=>$vp){
			$bg = IMG.'promos'.DS.$vp->photoname.'-o.'.$vp->extension;
			$th = IMG.'promos'.DS.$vp->photoname.'-t.'.$vp->extension;

			if(file_exists($th)) unlink($th);
			if(file_exists($bg)) unlink($bg);
		}


		if(!$this->_db->delete('promos_glossary_assignments',array('promoid','=',$promoid))) return false;
		if(!$this->_db->delete('promo_views',array('promoid','=',$promoid))) return false;


		if(!$this->_db->query(
			"DELETE FROM {questions}
			WHERE type=? AND rowid=?",
			array('promos',$promoid)
		)) return false;

		if(!$this->_db->delete('promos',array('id','=',$promoid))) return false;
		return true;

	}

	public function deleteAll($idclient=0){
		/////if(!$this->_db->delete('promos',array('idclient','=',$idclient))) return false;
		$this->idclient = $idclient;
		$this->get();
		if($this->_data){
			foreach($this->_data as $promo){
				$this->delete($promo->id);
			}
		}
		return true;
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
