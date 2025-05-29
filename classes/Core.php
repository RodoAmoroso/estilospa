<?php

class Core {

	protected $_db,
						$_dbpx,
						$_data,
						$_lastid,
						$_ids;

	private 	$_adminclass;

	public 		$visible=false,
						$listed=false,
						$search='',
						$exclude=0,
						$sort='',
						$filters=[],
						$filters_multiple=[],
						$range=[],
						$group='',
						$limit='0,10000',
						$response='',
						$userdata;


	public function __construct(){
		global $DB,$_userdata;
		//$this->_dbpx = Config::get('mysql/prefix');
		//$this->_db = DB::getInstance();
		$this->_db = $DB;
		$this->userdata = $_userdata;
	}

	protected function core_get($query='',$values=[],$has_many=false){

		$this->_db->query($query,$values);
		if(!$this->_db->count()) return false;

		$data = $this->_db->results();
		$this->_ids = $this->core_get_ids($data);

		foreach($data as $k=>$db){
			if(property_exists($db,'image')){
				$data[$k]->image = $this->core_get_image($db->image);
			}
			if(property_exists($db,'added')){
				$date = new DateTime($db->added);
				$data[$k]->added = $date->format('d/m/Y H:i').' hs.';
				$data[$k]->added_obj = $date;
			}
			if(property_exists($db,'modified') && !is_null($db->modified)){
				$date = new DateTime($db->modified);
				$data[$k]->modified = $date->format('d/m/Y H:i').' hs.';
				$data[$k]->modified_obj = $date;
			}

		}



		if($has_many!==false && is_array($has_many)){

			foreach($has_many as $hm){

				$hm = (object) $hm;

				if(property_exists($hm,'class')){
					$Class = new $hm->class;

					if(property_exists($hm,'foreign_filter')){
						$Class->filters = [
							$hm->foreign_filter=>implode(',',$this->_ids)
						];
					}
					if(property_exists($hm,'foreign_filters')){
						//$Class->filters = [];
						foreach($hm->foreign_filters as $k_filter=>$f_filter){
							$Class->filters[$k_filter] = $f_filter;
						}
					}
					if(property_exists($hm, 'sort')){
						$Class->sort = $hm->sort;
					}
					$has_many_results = $Class->get(property_exists($hm, 'get_params') ? $hm->get_params : null);
				}else{
					$has_many_results = $this->core_has_many(
						$hm->table,
						$hm->foreign_key,
						property_exists($hm,'selections') ? $hm->selections : '*'
					);
				}

				foreach($data as $k=>$db){

					$relations = $has_many_results!==false ? array_filter($has_many_results, function($row) use ($hm,$db,&$_ITERATIONS){
						if($row->{$hm->foreign_key}==$db->id) return $row;
					}) : false;

					$data[$k]->{$hm->name} = $relations ? array_values($relations) : false;

				}
			}

		}

		return $data;
	}


	public function core_get_ids($data){
		if($data==false) return false;
		return array_column($data, 'id');
	}
	public function core_has_many($table,$foreign_key,$selection='*'){
		if(!$this->_ids || !is_array($this->_ids)) return false;
		$ids = implode(',',$this->_ids);
		$this->_db->query("
			SELECT {$selection}
			FROM {{$table}}
			WHERE {$foreign_key} IN ({$ids})"
		);
		if(!$this->_db->count()) return false;
		return $this->_db->results();
	}
	public function ids(){
		return $this->_ids;
	}

	protected function core_get_image($image='',$folder=''){
		if(empty($image)) return false;
		$img = is_object($image) ? $image : json_decode($image);

		$images = new stdClass();

		if(property_exists($img, 'f')) $images->f = $img->f;
		if(property_exists($img, 'e')) $images->e = $img->e;

		if(property_exists($img, 'photoname')) $images->f = $img->photoname;
		if(property_exists($img, 'extension')) $images->e = $img->extension;

		if(!property_exists($images, 'f') || !property_exists($images, 'e')) return false;

		foreach($this->sizes as $k=>$sz){
			$images->{$k} = ROOT.'img/'.($folder ? $folder : $this->folder).'/'.$images->f.$sz.'.'.$images->e;
		}
		return $images;
	}


	public function core_filters($array=array()){

		$output = new stdClass();
		$output->where = "";
		$output->values = array();
		$output->sort = '';
		$output->group = '';

		foreach($array as $k=>$options){
			switch ($k) {
				case 'filters':
					if(!empty($this->filters)){
						foreach($this->filters as $key=>$filter){
							if($filter!==''){
								$output->where .= empty($output->where) ? "WHERE " : " AND ";
								$output->where .= preg_replace('/\$/', $filter, $options[$key]);
								if(preg_match('/\?/', $options[$key])){
									$output->values[] = $filter;
								}
							}
						}
					}
					break;

				case 'filters_multiple':

					foreach($options as $key=>$option){
						if(isset($this->filters_multiple[$key]) && key($this->filters_multiple[$key])==$key && is_array($this->filters_multiple[$key])){
							$arr_search = [];
							foreach($this->filters_multiple[$key] as $kf=>$vf){
								$arr_search[] = $option."=?";
								$output->values[] = $vf;
							}
							if(!empty($arr_search)){
								$output->where .= empty($output->where) ? "WHERE " : " AND ";
								$output->where .= "(".implode(' OR ', $arr_search).")";
							}
						}
					}

					break;


				case 'search':
					if(!empty($this->search)){
						$arr_search = [];
						foreach($options as $option){
							$arr_search[] = $option." LIKE ?";
							$output->values[] = "%{$this->search}%";
						}
						if(!empty($arr_search)){
							$output->where .= empty($output->where) ? "WHERE " : " AND ";
							$output->where .= "(".implode(" OR ",$arr_search).")";
						}
					}
					break;
				case 'search_mixed':
					if(!empty($this->search_mixed)){
						$arr_search = [];
						$words = explode(' ', $this->search_mixed);
						foreach($words as $word){
							if(strlen($word)>3){
								$arr_search[] = "CONCAT_WS(' ',".implode(",",$options).") LIKE '%{$word}%'";
							}
						}
						if(!empty($arr_search)){
							$output->where .= empty($output->where) ? "WHERE " : " AND ";
							$output->where .= "(".implode(" AND ",$arr_search).")";
						}

					}
					break;

				case 'sort':
					foreach($options as $key=>$option){
						if($this->sort==$key){
							$output->sort = "ORDER BY ".$option;
						}
					}
					if(empty($output->sort)){
						$output->sort = "ORDER BY ".$options['default'];
					}
					break;

				case 'group':
					foreach($options as $key=>$option){
						if($this->group==$key){
							$output->group = "GROUP BY ".$option;
						}
					}
					break;

				case 'range':
					$arr_search = [];

					foreach($options as $key=>$option){
						if(isset($this->range[$key])){
							$arr_search[] = $option;
							$output->values[] = $this->range[$key];
						}
					}

					if(!empty($arr_search)){
						$output->where .= empty($output->where) ? "WHERE " : " AND ";
						$output->where .= "(".implode(" AND ",$arr_search).")";
					}
					break;

				case 'custom':
					if(!empty($this->custom_query) && is_array($this->custom_query)){

						$output->where .= empty($output->where) ? "WHERE " : " AND ";
						$arr_custom = [];

						foreach($this->custom_query as $key=>$qq){
							$arr_custom[] = $options[$key];

							if(is_array($qq)){
								foreach( $qq as $kv=>$qv){
									$output->values[] = $qv;
								}
							}else{
								$output->values[] = $qq;
							}
						}
						$output->where .= "(".implode(" AND ",$arr_custom).")";
					}
					break;

			}
		}


		return $output;
	}

	public function core_save($id=null,$values=array(),$inverse=false){

		if(isset($values['id'])) unset($values['id']);

		if($id) return $this->core_update($id,$values);

		if($this->_db->column_exists($this->table,'added')) $values['added'] = date('Y-m-d H:i:s');
		if(!isset($values['position']) && $this->_db->column_exists($this->table,'position')){
			if(!$inverse){
				$values['position'] = 1;
				if(!$this->_db->query("UPDATE {{$this->table}} SET position=position+1")) return false;
			}else{
				$total = $this->_db->get($this->table)->count();
				$values['position'] = $total+1;
			}
		}
		if(!$this->_db->insert($this->table,$values)) return false;
		$this->_lastid = $this->_db->getLastId();
		return true;

	}
	public function core_update($id=null,$values=[]){
		$this->_lastid = $id;
		if($this->_db->column_exists($this->table,'modified')) $values['modified'] = date('Y-m-d H:i:s');
		if(!$this->_db->update($this->table,$id,$values)) return false;
		return true;
	}


	public function core_delete($data){

		if($this->_db->column_exists($this->table,'position')){
			if(!$this->_db->query("UPDATE {{$this->table}} SET position=position-1 WHERE position>?",array($data->position))) return false;
		}
		if($this->_db->column_exists($this->table,'image')){
			if($data->image !=''){
				$img = is_object($data->image) ? $data->image : json_decode($data->image);
				foreach($this->sizes as $size){
					if(file_exists(IMG.$this->folder.DS.$img->f.$size.'.'.$img->e)){
						unlink(IMG.$this->folder.DS.$img->f.$size.'.'.$img->e);
					}
				}
			}
		}
		if(!$this->_db->delete($this->table,array('id','=',$data->id))) return false;
		return true;
	}

	public function core_data(){
		return $this->_data;
	}

	public function core_nextid(){
		$this->_db->query("SHOW TABLE STATUS LIKE '{{$this->table}}'");
		if(!$this->_db->count()) return false;
		return $this->_db->first()->Auto_increment;
	}

	public function core_lastid(){
		return $this->_lastid;
	}

	public function core_reorder($arrids=[]){
		if(!empty($arrids)){
			foreach($arrids as $k=>$id){
				$this->_db->update($this->table,$id,array('position'=>$k+1));
			}
			return true;
		}
		return false;
	}

	public function core_query(){
		return $this->_db->getquery()->queryString;
	}

	public function core_get_response(){
		return $this->response;
	}



	public function core_find_object($id,$objects=[]){
		if(!$objects || !$id) return false;

		$output = false;
		foreach($objects as $object){
			if($object->id!=$id) continue;
			$output = $object;
		}
		return $output;
	}


	public function core_extract_ids($obj){

		$ids = array_filter(array_unique(array_column($obj->data, $obj->foreign_key)));
		if(!$ids) return [];
		$Class = new $obj->class;
		$Class->filters = ['ids'=>implode(',',$ids)];
		if(!$results = $Class->get()) return [];
		return array_column($results, null, 'id');

	}


	public function core_save_has_many($obj){

		$Class = new $obj->class;
		$Class->filters = $obj->class_filters;
		$saved_items = $Class->get();
		$saved_items_ids = $Class->ids();

		if(is_array($obj->items)){
			foreach($obj->items as $k=>$item){

				$values = [
					'id'=>null,
					$obj->foreign_key=>$obj->foreign_key_id
				];

				foreach($obj->values as $kv=>$val){
					$values[$val] = is_array($item[$val]) ? json_encode($item[$val]) : $item[$val];
				}

				if($saved_items){
					foreach($saved_items as $kitem=>$s_item){
						if(isset($item['id']) && $s_item->id == $item['id']){
							$values['id'] = $s_item->id;
							$key = array_search($s_item->id, $saved_items_ids);
							if($key !== false) unset($saved_items[$kitem]);
						}
					}
				}
				$Class->save($values);
			}
		}

		if($saved_items){
			foreach($saved_items as $d_item){
				$Class->delete($d_item);
			}
		}

		return true;
	}


}