<?php

class Core {

	protected $_db,
						$_dbpx,
						$_data,
						$_lastid;

	private 	$_adminclass;

	public 		$visible=false,
						$listed=false,
						$search='',
						$exclude=0,
						$sort='',
						$filters=array(),
						$filters_multiple=array(),
						$range=array(),
						$group='',
						$limit='0,500',
						$response='',
						$userdata;


	public function __construct(){
		$this->_dbpx = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	protected function core_get($query='',$values=array()){
		$this->_db->query($query,$values);
		if(!$this->_db->count()) return false;

		$data = $this->_db->results();
		foreach($data as $k=>$db){
			/*if(isset($db->image)){
				$data[$k]->image = $this->core_get_image($db->image);
			}*/
			if(isset($db->added)){
				$date = new DateTime($db->added);
				$data[$k]->added = $date->format('d/m/Y H:i').' hs.';
				$data[$k]->added_obj = $date;
			}
			if(isset($db->modified) && !is_null($db->modified)){
				$date = new DateTime($db->modified);
				$data[$k]->modified = $date->format('d/m/Y H:i').' hs.';
				$data[$k]->modified_obj = $date;
			}

		}

		return $data;
	}

	protected function core_get_image($image='',$folder=''){
		if(empty($image)) return false;
		$img = json_decode($image);
		$images = new stdClass();
		$images->f = $img->f;
		$images->e = $img->e;
		foreach($this->sizes as $k=>$sz){
			$images->{$k} = ROOT.'img/'.($folder ? $folder : $this->folder).'/'.$img->f.$sz.'.'.$img->e;
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

		if($id) return $this->core_update($id,$values);

		if($this->_db->column_exists($this->table,'added')) $values['added'] = date('Y-m-d H:i:s');
		if($this->_db->column_exists($this->table,'position')){
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
	public function core_update($id=null,$values=array()){
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
				$img = json_decode($data->image);
				foreach($this->sizes as $size){
					if(file_exists(PATH.'img'.DS.$this->folder.DS.$img->f.$size.'.'.$img->e)){
						unlink(PATH.'img'.DS.$this->folder.DS.$img->f.$size.'.'.$img->e);
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

	public function core_lastid(){
		return $this->_lastid;
	}

	public function core_reorder($arrids=array()){
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


}