<?php

class Notifications {

	private $_db,
					$_data,
					$_dbpx;

	public 	$range=5,
					$limit='0,10',
					$filters=array();

	public function __construct(){
		$this->_dbpx = Env::get('DB_PREFIX');
		$this->_db = DB::getInstance();
	}

	public function get_unrated(){
		$this->_db->query(
			"SELECT s.id idsale, s.iduser, s.idpromo, c.id idcomment, u.mail, CONCAT(u.name,' ',u.lastname) name, s.added, p.title promotitle, cl.permalink, s.collection_id
			FROM {sales} s
			LEFT JOIN {comments} c ON c.idsale=s.id
			LEFT JOIN {users} u ON u.id=s.iduser
			LEFT JOIN {promos} p ON p.id=s.idpromo
			LEFT JOIN {clients} cl ON cl.id=p.idclient
			WHERE c.id IS NULL
				AND p.id IS NOT NULL
				AND u.mail IS NOT NULL
				AND s.payment_status='approved'
				AND DATE(NOW()) = DATE(s.added) + INTERVAL ? DAY",
			array($this->range));

		if(!$this->_db->count()) return false;
		foreach($this->_db->results() as $unrated){
			$this->add(array(
				'name_from'=>"Estilo SPA",
				'email_from'=>"info@estilospa.com",
				'name_to'=>$unrated->name,
				'email_to'=>strtolower($unrated->mail),
				'subject'=>"¡No te olvides de calificar tu experiencia!",
				'body'=>Templates::template('promos/unrated',$unrated),
				'log'=>'Notificacion enviada a '.$unrated->name.' ('.$unrated->mail.') para calificar la promo <a href="'.ROOT.'promo/'.$unrated->permalink.'/'.$unrated->idpromo.'-'.Permalink($unrated->promotitle).'" target="_blank">'.$unrated->promotitle.'</a> - Nro. de comprobante. '.$unrated->collection_id,
				'type'=>'promo_rate',
				'added'=>date('Y-m-d H:i:s'),
			));
		}
		return true;

	}

	public function get_unstated(){
		$this->_db->query(
			"SELECT s.*, p.title promotitle, CONCAT(u.name,' ',u.lastname) username, u.mail usermail, c.name clientname, c.mail clientmail, c.permalink
			FROM {sales} s
			LEFT JOIN {promos} p ON p.id=s.idpromo
			LEFT JOIN {clients} c ON c.id=p.idclient
			LEFT JOIN {users} u ON u.id=s.iduser
			LEFT JOIN {vouchers_usage} vu ON vu.idsale=s.id
			WHERE s.status = 1
				AND p.id IS NOT NULL
				AND c.mail IS NOT NULL
				AND s.payment_status='approved'
				AND DATE(NOW()) = DATE(s.added) + INTERVAL ? DAY",
			array($this->range)
		);
		if(!$this->_db->count()) return false;
		foreach($this->_db->results() as $unstated){
			$this->add(array(
				'name_from'=>"Estilo SPA",
				'email_from'=>"info@estilospa.com",
				'name_to'=>$unstated->clientname,
				'email_to'=>strtolower($unstated->clientmail),
				'subject'=>"¡No te olvides de actualizar el estado de tu venta!",
				'body'=>Templates::template('promos/unstated',$unstated),
				'log'=>'Notificacion enviada a <a href="'.ROOT.'centros/'.$unstated->permalink.'" target="_blank">'.$unstated->clientname.'</a> para actualizar el estado de la promo <a href="'.ROOT.'promo/'.$unstated->permalink.'/'.$unstated->idpromo.'-'.Permalink($unstated->promotitle).'" target="_blank">'.$unstated->promotitle.'</a> comprada por '.$unstated->username.' ('.$unstated->usermail.') - Nro. de comprobante '.$unstated->collection_id,
				'type'=>'promo_status',
				'added'=>date('Y-m-d H:i:s'),
			));
		}
		return true;
	}

	public function add($sql=array()){
		if(!$this->_db->insert('notifications_queue',$sql)) return false;
		return true;
	}

	public function delete($obj=null){
		if(is_null($obj)) return false;
		///$this->_db->insert('notifications_log',array('log'=>));
		$this->add_log($obj->log,$obj->type);
		$this->_db->delete('notifications_queue',array('id','=',$obj->id));
		return true;
	}

	public function get($limit=15){
		$this->_db->query(
			"SELECT n.*
			FROM {notifications_queue} n
			ORDER BY n.added ASC
			LIMIT 0,{$limit}"
		);
		if(!$this->_db->count()) return false;

		$this->_data = $this->_db->results();
		return $this->_data;
	}

	public function data(){
		return $this->_data;
	}

	public function add_log($text='',$type=''){
		$this->_db->insert('notifications_log',array(
			'log'=>$text,
			'type'=>$type
		));
		return true;
	}

	public function get_log(){

		$where = '';
		$values = array();

		if(!empty($this->filters)){
			foreach($this->filters as $key=>$filter){
				switch ($key) {
					case 'type':
						$where .= empty($where) ? "WHERE " : " AND ";
						$where .= "nl.type=?";
						$values[] = $filter;
						break;

				}
			}
		}

		$this->_db->query(
			"SELECT nl.*
			FROM {notifications_log} nl
			{$where}
			ORDER BY nl.added DESC
			LIMIT {$this->limit}",
			$values
		);

		if(!$this->_db->count()) return false;

		return $this->_db->results();
	}


}