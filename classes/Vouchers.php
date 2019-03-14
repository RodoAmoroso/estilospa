<?php 

class Vouchers {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid,
					$_errors;

	public 	$keywords='',
					$limit='',
					$idpromo=0,
					$status='';

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function get(){
		$where = "";
		if(!empty($this->status)){			
			$arrstatus = explode(':',$this->status);
			if($arrstatus[0]){$start = "v.start<=NOW()";}else{$start = "v.start >= NOW()";}
			if($arrstatus[1]){$finish = "v.finish>=NOW()";}else{$finish = "v.finish <= NOW()";}
			$where = "WHERE {$start} AND {$finish}";
		}
		if($this->idpromo){
			if(empty($where)){$where = "WHERE";}else{$where .= " AND";}
			$where .= " a.idpromo={$this->idpromo}";
		}
		if(!empty($this->keywords)){
			if(empty($where)){$where = "WHERE";}else{$where .= " AND";}
			$where .= " (c.code LIKE '%{$this->keywords}%' OR v.name LIKE '%{$this->keywords}%')";
		}
		#(SELECT COUNT(*) FROM {$this->_dbprefix}vouchers_codes vc WHERE vc.idvoucher=a.idvoucher) as totcodes
		$this->_db->query(
			"SELECT DISTINCT v.*, v.start<=NOW() statusstart, v.finish>=NOW() statusfinish, DATE_FORMAT(v.start, '%d/%m/%Y') start, DATE_FORMAT(v.finish, '%d/%m/%Y') finish, DATE_FORMAT(v.added, '%d/%m/%Y') creado, (SELECT COUNT(*) FROM {vouchers_assoc} va WHERE va.idvoucher=a.idvoucher) as totpromos, c.code
			FROM {vouchers_assoc} a
			LEFT JOIN {vouchers} v ON v.id=a.idvoucher
			LEFT JOIN {vouchers_codes} c ON v.id=c.idvoucher
			{$where} 
			GROUP BY a.idvoucher, c.idvoucher
			ORDER BY v.added DESC");
		//GROUP BY a.idvoucher, c.idvoucher
		//show_array($this->_db->getquery()->queryString);
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function find($id=0){
		$this->_db->query("SELECT v.id, v.name, v.isunique, v.ispercent, v.value, DATE_FORMAT(v.start, '%d/%m/%Y') start, DATE_FORMAT(v.finish, '%d/%m/%Y') finish FROM {$this->_dbprefix}vouchers v WHERE v.id=?",array($id));
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function getcodes($idvoucher=0){
		$this->_db->get('vouchers_codes',array('idvoucher','=',$idvoucher));
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function getpromos($idvoucher=0){
		$this->_db->get('vouchers_assoc',array('idvoucher','=',$idvoucher));
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function findcode($code=''){
		$field = is_numeric($code) ? 'c.id' : 'c.code';
		$where = "WHERE {$field} = '{$code}'";
		//$this->_db->get('vouchers_codes',array($field,'=',$code));
		$this->_db->query(
			"SELECT c.id, c.code, c.idvoucher, v.isunique, v.name, v.ispercent, v.value
			FROM {$this->_dbprefix}vouchers_codes c
			LEFT JOIN {$this->_dbprefix}vouchers v ON v.id=c.idvoucher
			{$where}"
		);
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function getpromo($idpromo=0){
		$where = "WHERE idpromo=?";
		if(!empty($this->status)){			
			$arrstatus = explode(':',$this->status);
			if($arrstatus[0]){$start = "v.start<=NOW()";}else{$start = "v.start >= NOW()";}
			if($arrstatus[1]){$finish = "v.finish>=NOW()";}else{$finish = "v.finish <= NOW()";}
			$where .= " AND ({$start} AND {$finish})";
		}
		$this->_db->query("SELECT v.id, v.start<=NOW() statusstart, v.finish>=NOW() statusfinish, v.name, v.ispercent, v.isunique, v.value, v.start, v.finish
			FROM {$this->_dbprefix}vouchers_assoc a
			LEFT JOIN {$this->_dbprefix}vouchers v ON v.id=a.idvoucher
			{$where}
			ORDER BY finish ASC 
			LIMIT 0,1",
			array($idpromo)
		);
		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return true;
		}
		return false;
	}

	public function errors(){
		return $this->_errors;
	}

	public function validate($idpromo=0,$code='',$iduser=0){
		
		/////// Check Promo ////////////////////////
		$promo = new Promos();
		if($promo->find($idpromo)){
			if(!$promo->data()->statusstart || !$promo->data()->statusfinish){
				$this->_errors = 'La promo seleccionada no está disponible.';
				return false;
			}
		}else{
			$this->_errors = 'No se encontró la promo seleccionada.';
			return false;			
		}
		///////// End Check Promo ///////////////////

		////////// Check Voucher ////////////////////
		$this->status = '1:1';
		if($this->getpromo($idpromo)){ #check if active
			#Check if code exists and is not usage by the same user
			$where = "WHERE c.code=?";
			$arrvalues = [$code];
			if($this->_data->isunique){
				$where .= " AND u.iduser=?";
				$arrvalues[] = $iduser;
			}
			$this->_db->query("SELECT u.id
				FROM {$this->_dbprefix}vouchers_usage u 
				LEFT JOIN {$this->_dbprefix}vouchers_codes c ON c.id=u.idcode
				LEFT JOIN {$this->_dbprefix}vouchers v ON v.id=u.idvoucher
				{$where}",
				$arrvalues
			);
			if($this->_db->count()){
				$this->_errors = 'Este código promocional ya fue usado.';
				return false;
			}
			#Finally get voucher info
			$this->_db->query(
				"SELECT c.id, v.ispercent, v.value, v.isunique, p.price, p.discount
				FROM {$this->_dbprefix}vouchers_codes c
				LEFT JOIN {$this->_dbprefix}vouchers v ON v.id=c.idvoucher
				LEFT JOIN {$this->_dbprefix}vouchers_assoc a ON a.idvoucher=v.id
				LEFT JOIN {$this->_dbprefix}promos p ON p.id=a.idpromo
				WHERE c.code=? AND a.idpromo=?",
				array($code,$idpromo)
			);
			if($this->_db->count()){
				$this->_data = $this->_db->first();
				return true;
			}else{
				$this->_errors = 'El código ingresado no es válido para esta promo.';
				return false;
			}
		}else{
			$this->_errors = 'El código ingresado no es válido para esta promo.';
			return false;
		}
		////////// End Check Voucher ////////////////

		return false;
	}

	public function usage($sql=array()){
		if($this->_db->insert('vouchers_usage',$sql)){
			return true;
		}
		return false;
	}

	public function save(){
		$sql = array(
			'isunique'=>Input::get('IsUnique'),
			'name'=>Input::get('Name'),
			'ispercent'=>Input::get('IsPercent'),
			'value'=>Input::get('Value'),
			'start'=>Input::get('Start'),
			'finish'=>Input::get('Finish')
		);
		
		if(Input::get('ID')){ #update
			if($this->_db->update('vouchers',Input::get('ID'),$sql)){
				$this->_lastid = Input::get('ID');
				///////////////////////////////////////////////////////////////////////
				$this->_db->delete('vouchers_assoc',array('idvoucher','=',$this->_lastid));
				if(count(Input::get('Promos'))){
					foreach(Input::get('Promos') as $promo){
						$this->_db->insert('vouchers_assoc',array('idpromo'=>$promo,'idvoucher'=>$this->_lastid));
					}
				}
				///////////////////////////////////////////////////////////////////////
				return true;
			}
		}else{ #insert
			$sql['added'] = date('Y-m-d H:i:s');
			if($this->_db->insert('vouchers',$sql)){
				$this->_lastid = $this->_db->getLastId();
				$arrcodes = explode(',',Input::get('Codes'));
				if(count($arrcodes)){
					foreach($arrcodes as $code){
						$this->_db->insert('vouchers_codes',array('code'=>$code,'idvoucher'=>$this->_lastid));
					}
				}
				if(count(Input::get('Promos'))){
					foreach(Input::get('Promos') as $promo){
						$this->_db->insert('vouchers_assoc',array('idpromo'=>$promo,'idvoucher'=>$this->_lastid));
					}
				}
				return true;
			}
		}
		

		return false;
	}

	public function getLastId(){
		return $this->_lastid;
	}

	public function data(){
		return $this->_data;
	}

	public function delete($id=0){
		if(!$this->_db->delete('vouchers',array('id','=',$id))){
			return false;
		}
		if(!$this->_db->delete('vouchers_codes',array('idvoucher','=',$id))){
			return false;
		}
		if(!$this->_db->delete('vouchers_assoc',array('idvoucher','=',$id))){
			return false;
		}
		if(!$this->_db->delete('vouchers_usage',array('idvoucher','=',$id))){
			return false;
		}
		return true;
	}

}