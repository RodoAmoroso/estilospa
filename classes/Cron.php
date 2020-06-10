<?php

class Cron extends Core{

	protected $table='cron';


	public function add_log($type=''){
		$this->_db->insert($this->table,[
			'type'=>$type
		]);
		return true;
	}

}