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
			if($arrstatus[0]){
				$start = "v.start<=NOW()";
			}else{
				$start = "v.start >= NOW()";
			}
			if($arrstatus[1]){
				$finish = "v.finish>=NOW()";
			}else{
				$finish = "v.finish <= NOW()";
			}
			$where = "WHERE {$start} AND {$finish}";
		}

		if($this->idpromo){
			///if(empty($where)){$where = "WHERE";}else{$where .= " AND";}
			$where .= empty($where) ? "WHERE" : " AND ";
			$where .= "a.idpromo={$this->idpromo}";
		}

		if(!empty($this->keywords)){
			///if(empty($where)){$where = "WHERE";}else{$where .= " AND";}
			$where .= empty($where) ? "WHERE" : " AND ";
			///$where .= "(c.code LIKE '%{$this->keywords}%' OR )";
			$where .= "v.id IN (
				SELECT vc.idvoucher
				FROM {vouchers_codes} vc
				WHERE vc.code LIKE '%{$this->keywords}%'
			) OR v.name LIKE '%{$this->keywords}%'";
		}
		#(SELECT COUNT(*) FROM {$this->_dbprefix}vouchers_codes vc WHERE vc.idvoucher=a.idvoucher) as totcodes
		$this->_db->query(
			"SELECT DISTINCT
				v.*, v.start<=NOW() statusstart, v.finish>=NOW() statusfinish, DATE_FORMAT(v.start, '%d/%m/%Y') start, DATE_FORMAT(v.finish, '%d/%m/%Y') finish, DATE_FORMAT(v.added, '%d/%m/%Y') creado,
				(
					SELECT COUNT(*)
					FROM {vouchers_assoc} va
					WHERE va.idvoucher=v.id
				) as totpromos
			FROM {vouchers} v
			{$where}
			ORDER BY v.added DESC"
		);
		//LEFT JOIN {vouchers_codes} c ON v.id=c.idvoucher
		//GROUP BY a.idvoucher, c.idvoucher
		//show_array($this->_db->getquery()->queryString);
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function find($id=0){
		$this->_db->query("
			SELECT v.id, v.name, v.isunique, v.ispercent, v.value, DATE_FORMAT(v.start, '%d/%m/%Y') start, DATE_FORMAT(v.finish, '%d/%m/%Y') finish FROM {vouchers} v
			WHERE v.id=?",
			[$id]
		);
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
		//$this->_db->get('vouchers_assoc',array('idvoucher','=',$idvoucher));

		$this->_db->query("SELECT * FROM {vouchers_assoc} WHERE idvoucher=? GROUP BY idpromo",[$idvoucher]);

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
			FROM {vouchers_codes} c
			LEFT JOIN {vouchers} v ON v.id=c.idvoucher
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
		$this->_db->query(
			"SELECT v.*, v.start<=NOW() statusstart, v.finish>=NOW() statusfinish
			FROM {vouchers_assoc} a
			LEFT JOIN {vouchers} v ON v.id=a.idvoucher
			{$where}
			ORDER BY v.finish ASC
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
				FROM {vouchers_usage} u
				LEFT JOIN {vouchers_codes} c ON c.id=u.idcode
				LEFT JOIN {vouchers} v ON v.id=u.idvoucher
				{$where}",
				$arrvalues
			);
			if($this->_db->count()){
				$this->_errors = 'Este código promocional ya fue usado.';
				return false;
			}
			#Finally get voucher info
			$this->_db->query(
				"SELECT c.id codeid, c.code, v.*
				FROM {vouchers_codes} c
				LEFT JOIN {vouchers} v ON v.id=c.idvoucher
				LEFT JOIN {vouchers_assoc} a ON a.idvoucher=v.id
				INNER JOIN {promos} p ON p.id=a.idpromo
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

			if(!$this->_db->update('vouchers',Input::get('ID'),$sql)) return false;

				$voucherid = Input::get('ID');
				$this->_db->delete('vouchers_assoc',['idvoucher','=',$voucherid]);

				if(is_array(Input::get('Promos'))){
					$promo_values = [];
					foreach(Input::get('Promos') as $promo){
						$promo_values[] = [
							$promo,$voucherid
						];
					}
					$this->_db->insertmultiple('vouchers_assoc',[
						'idpromo','idvoucher'
					],$promo_values);
				}

				if(is_array(Input::get('Promos'))){
					foreach(Input::get('Promos') as $promo){
						$this->_db->insert('vouchers_assoc',array('idpromo'=>$promo,'idvoucher'=>$voucherid));
					}
				}
		}else{ #insert

			$sql['added'] = date('Y-m-d H:i:s');
			if(!$this->_db->insert('vouchers',$sql)) return false;

			$voucherid = $this->_db->getLastId();
			$arrcodes = explode(',',Input::get('Codes'));

			if(is_array($arrcodes)){
				$code_values = [];
				foreach($arrcodes as $code){
					$code_values[] = [
						$code,$voucherid
					];
				}

				$this->_db->insertmultiple('vouchers_codes',[
					'code','idvoucher'
				],$code_values);

			}

			/*if(count($arrcodes)){
				foreach($arrcodes as $code){
					$this->_db->insert('vouchers_codes',array('code'=>$code,'idvoucher'=>$this->_lastid));
				}
			}*/
			if(is_array(Input::get('Promos'))){
				$promo_values = [];
				foreach(Input::get('Promos') as $promo){
					///$this->_db->insert('vouchers_assoc',array('idpromo'=>$promo,'idvoucher'=>$this->_lastid));
					$promo_values[] = [
						$promo,$voucherid
					];
				}
				$this->_db->insertmultiple('vouchers_assoc',[
					'idpromo','idvoucher'
				],$promo_values);
			}

		}

		return $voucherid;
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