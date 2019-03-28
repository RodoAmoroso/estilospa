<?php 

class Notifications {

	private $_db,
					$_data,
					$_dbpx;

	public 	$range=5,
					$limit='0,10';

	public function __construct(){
		$this->_dbpx = Config::get('mysql/prefix');
		$this->_db = DB::getInstance();
	}

	public function getunrated(){
		$this->_db->query(
			"SELECT s.id idsale, s.iduser, s.idpromo, c.id idcomment, u.mail, u.name, s.added, p.title promotitle, cl.permalink
			FROM {$this->_dbpx}sales s
			LEFT JOIN {$this->_dbpx}comments c ON c.idsale=s.id 
			LEFT JOIN {$this->_dbpx}users u ON u.id=s.iduser
			LEFT JOIN {$this->_dbpx}promos p ON p.id=s.idpromo
			LEFT JOIN {$this->_dbpx}clients cl ON cl.id=p.idclient
			WHERE c.id IS NULL AND p.title IS NOT NULL AND u.mail IS NOT NULL AND s.collection_status='approved' AND DATE(NOW()) = DATE(s.added) + INTERVAL ? DAY",
			array($this->range));

		if(!$this->_db->count()) return false;
		foreach($this->_db->results() as $unrated){
			$this->add(array(
				'name_from'=>"Estilo SPA",
				'email_from'=>"info@estilospa.com",
				'name_to'=>$unrated->name,
				'email_to'=>strtolower($unrated->mail),
				'subject'=>"¡No te olvides de calificar tu experiencia!",
				'body'=>"<h4>Hola {$unrated->name}</h4><p>Si ya has vivido la experiencia de la promo <b><a href='".ROOTPATH."promo/{$unrated->permalink}/{$unrated->idpromo}-".Permalink($unrated->promotitle)."'>{$unrated->promotitle}</a></b>, por favor cuéntanos cómo fue. Con tu aporte podemos mejorar y ofrecer un mejor servicio día a día.</p><p>&nbsp;</p><p><a href='".ROOTPATH."calificar/{$unrated->idpromo}' style='background-color:#e7127c;border-color:#e7127c;color:#fff;padding:6px 12px;text-align:center;'>Calificar</a></p><hr><p>Gracias.<br />El equipo de EstiloSPA.com</p>",
				'added'=>date('Y-m-d H:i:s'),
			));

			///// Contanos como fue y sumá beneficios para tus próximas compras en EstiloSPA
		}
		return true;

	}

	public function getunstated(){
		$this->_db->query(
			"SELECT s.id, s.iduser, s.idpromo, s.status, s.collection_id, s.added, s.price, s.quantity, p.title promotitle, u.name username, u.mail usermail, c.name clientname, c.mail clientmail, c.permalink
			FROM {$this->_dbpx}sales s
			LEFT JOIN {$this->_dbpx}promos p ON p.id=s.idpromo 
			LEFT JOIN {$this->_dbpx}clients c ON c.id=p.idclient
			LEFT JOIN {$this->_dbpx}users u ON u.id=s.iduser
			LEFT JOIN {$this->_dbpx}vouchers_usage vu ON vu.idsale=s.id
			WHERE s.status = 1 AND p.title IS NOT NULL AND c.mail IS NOT NULL AND s.collection_status='approved' AND DATE(NOW()) = DATE(s.added) + INTERVAL ? DAY",
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
				'body'=>"<h4>Hola {$unstated->clientname}</h4><p>¿{$unstated->username} ({$unstated->usermail}) ya tomó el servicio de la promo <b><a href='".ROOTPATH."promo/{$unstated->permalink}/{$unstated->idpromo}-".Permalink($unstated->promotitle)."'>{$unstated->promotitle}</a> - Nro de Comprobante: {$unstated->collection_id}?</b>.<br /> Si es así, por favor ingresa al sitio de EstiloSPA y actualiza el estado del servicio como <b>Brindado</b>, (o <b>Cancelado</b> en caso de haberse cancelado el servicio). Con tu aporte podemos mejorar y ofrecer un mejor servicio día a día.</p><p>&nbsp;</p><p><a href='".ROOTPATH."cuenta/mi-cuenta' style='background-color:#e7127c;border-color:#e7127c;color:#fff;padding:6px 12px;text-align:center;'>Establecer Estado</a></p><hr><p>Gracias.<br />El equipo de EstiloSPA.com</p>",
				'added'=>date('Y-m-d H:i:s'),
			));
		}
		return true;
	}

	public function add($sql=array()){
		if(!$this->_db->insert('notifications_queue',$sql)) return false;
		return true;
	}

	public function delete($id=0){
		if(!$this->_db->delete('notifications_queue',array('id','=',$id))) return false;
		return true;
	}

	public function get(){
		$this->_db->query(
			"SELECT n.id, n.name_from, n.email_from, n.name_to, n.email_to, n.subject, n.body, n.added
			FROM {$this->_dbpx}notifications_queue n
			ORDER BY n.added ASC
			LIMIT {$this->limit}"
		);
		if(!$this->_db->count()) return false;

		$this->_data = $this->_db->results();
		return true;
	}

	public function data(){
		return $this->_data;
	}


}