<?php

class Favs {

	private $_db,
					$_data,
					$_dbprefix,
					$_lastid,
					$_isfav=0;

	public 	$iduser=0,
					$idclient=0,
					$idpromo=0;

	public function __construct(){
		$this->_dbprefix = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function addremove(){
		$where = '';
		$where = "AND idpromo=".$this->idpromo.' AND idclient='.$this->idclient;

		if($this->_db->query(
			"SELECT *
			FROM {favs}
			WHERE iduser=?
			{$where}",
			array($this->iduser)
		)){
			if(!$this->_db->count()){
				$this->_db->insert('favs',array(
					'iduser'=>$this->iduser,
					'idclient'=>$this->idclient,
					'idpromo'=>$this->idpromo,
					'added'=>date('Y-m-d H:i:s')
				));
				$this->_isfav = 1;
				return true;
			}else{
				$id = $this->_db->first()->id;
				if($this->delete($id)){
					$this->_isfav = 0;
					return true;
				}
				return false;
			}
		}
		return false;
	}

	public function find(){

		$this->_db->query(
			"SELECT *
			FROM {favs}
			WHERE iduser=? AND idpromo=? AND idclient=?",
			array($this->iduser,$this->idpromo,$this->idclient)
		);
		if(!$this->_db->count()) return false;

		return true;
	}

	public function getpromos($iduser=0){
		$this->_db->query(
			"SELECT
				f.id, f.idpromo, f.idclient, f.added,
				p.title, p.gallery, p.price, p.discount, p.sale, p.amount,
				c.name, c.permalink, c.logo, c.subtitle, c.name client_name
			FROM {favs} f
			INNER JOIN {promos} p ON p.id=f.idpromo AND p.start<=NOW() AND p.finish>=NOW()
			INNER JOIN {clients} c ON c.id=p.idclient AND c.visible=1
			WHERE f.iduser=? AND f.idpromo!=0
			ORDER BY f.added DESC",
			array($iduser)
		);
		if($this->_db->count()){
			$data = $this->_db->results();
			foreach($data as $k=>$row){
				$data[$k]->added_obj = new DateTime($row->added);
				$data[$k]->link = ROOT.'promo/'.$row->permalink.'/'.$row->idpromo.'-'.Permalink($row->title);
				$data[$k]->client_link = ROOT.'centros/'.$row->permalink;
			}
			$this->_data = $data;
			return true;
		}
		return false;
	}

	public function getclients($iduser=0){
		$this->_db->query(
			"SELECT f.id, f.idpromo, f.idclient, c.name, c.permalink, c.logo, c.subtitle, c.permalink
			FROM {favs} f
			INNER JOIN {clients} c ON c.id=f.idclient AND c.visible=1
			WHERE f.iduser=? AND f.idpromo=0
			ORDER BY f.added DESC",
			array($iduser)
		);
		if($this->_db->count()){
			$this->_data = $this->_db->results();
			return true;
		}
		return false;
	}

	public function data(){
		return $this->_data;
	}

	public function isfav(){
		return $this->_isfav;
	}

	public function delete($id=0){
		if($this->_db->delete('favs',array('id','=',$id))){
			return true;
		}
		return false;
	}

	public function deleteall($iduser=0){
		if($this->_db->delete('favs',array('iduser','=',$iduser))){
			return true;
		}
		return false;
	}


	public function get_latest(){

		$this->_db->query(
			"SELECT f1.*
			FROM {favs} f1
			INNER JOIN {promos} p ON p.id=f1.idpromo
			INNER JOIN
				(
					SELECT MAX(added) recent, iduser
					FROM {favs}
					WHERE DATEDIFF(NOW(),added) = 1
					GROUP BY iduser
				) f2
				ON f2.iduser=f1.iduser AND f2.recent=f1.added
			WHERE f1.idpromo != 0
				AND p.start<=NOW()
				AND p.finish>=NOW()
			LIMIT 0,100"
		);
		if(!$this->_db->count()) return false;

		return $this->_db->results();
	}


}