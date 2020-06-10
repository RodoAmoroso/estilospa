<?php

require 'config.php';


$Mailing = new Mailing();
$Newsletters = new Newsletters();
$Favs = new Favs();
$Questions = new Questions();

$Sales = new Sales();
$Subscribers = new Subscribers();

$Promos = new Promos();
$Clients = new Clients();
$Glossary = new Glossary();
$Banners = new Banners();

$MPConfig = new MPConfig();

/// SEND ALERTS - QUALIFY AND UPDATE STATUS ///
$Notifications = new Notifications();

$Notifications->range = 5;
$Notifications->get_unstated();
$Notifications->get_unrated();

$Notifications->range = 7;
$Notifications->get_unrated();
$Notifications->get_unstated();

$Notifications->range = 9;
$Notifications->get_unrated();
$Notifications->get_unstated();


//// RENEW TOKENS ///
$MPConfig->renewtoken();

//// CLEAN TEMP CART ///
$Sales->clean_temp();



//// NEWSLETTERS ///
$Promos->status = '1:1';
$Promos->limit = '0,10';
$Promos->visible = true;
$Promos->sort = 'position';

$latest_favs = $Favs->get_latest();

if($latest_favs){

	foreach($latest_favs as $fav){

		$check = array(
			'userid'=>$fav->iduser,
			'contextid'=>$fav->idpromo,
			'type'=>'promos'
		);

		if( $Newsletters->check_queue($fav->iduser) && $Newsletters->check_log($check) ){

			$obj = new stdClass();

			$Promos->find($fav->idpromo);
			$obj->promo = $Promos->data();

			$Promos->exclude = $Promos->data()->id;

			$Promos->get();
			$obj->related = $Promos->data();


			$Newsletters->add_queue(array(
				'userid'=>$fav->iduser,
				'subject'=>'Aprovechá esta promo en EstiloSPA.com',
				'body'=>Templates::template('newsletters/promo',$obj),
				'type'=>'promos',
				'contextid'=>$fav->idpromo
			));

		}


	}
}


$latest_question = $Questions->get_latest();
if($latest_question){

	foreach($latest_question as $question){

		$check = array(
			'userid'=>$question->userid,
			'contextid'=>$question->rowid,
			'type'=>$question->type
		);

		if( $Newsletters->check_queue($question->userid) && $Newsletters->check_log($check) ){

			$obj = new stdClass();

			switch ($question->type) {
				case 'promos':
					$Promos->find($question->rowid);
					$obj->promo = $Promos->data();

					$Promos->exclude = $Promos->data()->id;

					$Promos->get();
					$obj->related = $Promos->data();

					$Newsletters->add_queue(array(
						'userid'=>$question->userid,
						'subject'=>'Aprovechá estas promo en EstiloSPA.com',
						'body'=>Templates::template('newsletters/promo',$obj),
						'type'=>'promo',
						'contextid'=>$question->rowid
					));
					break;

				case 'clients':
					$Clients->find($question->rowid);
					$obj->client = $Clients->data();

					$Promos->exclude = 0;
					$Promos->idclient = $question->rowid;
					$Promos->get();
					$obj->promos = $Promos->data();
					if($obj->promos && count($obj->promos)>1){
						$Newsletters->add_queue(array(
							'userid'=>$question->userid,
							'subject'=>'Aprovechá estas promos que tenemos para vos en EstiloSPA.com',
							'body'=>Templates::template('newsletters/client-promos',$obj),
							'type'=>'clients',
							'contextid'=>$question->rowid
						));
					}


					break;

				case 'glossary':
					$Glossary->find($question->rowid);
					$obj->glossary = $Glossary->data();

					$Promos->exclude = 0;
					$Promos->keywords = $obj->glossary->name;
					$Promos->arrglossary = [$question->rowid];
					$Promos->get();
					$obj->promos = $Promos->data();

					if($obj->promos && count($obj->promos)>1){
						$Newsletters->add_queue(array(
							'userid'=>$question->userid,
							'subject'=>'Aprovechá estas promos que tenemos para vos en EstiloSPA.com',
							'body'=>Templates::template('newsletters/glossary-promos',$obj),
							'type'=>'glossary',
							'contextid'=>$question->rowid
						));
					}

					break;
			}



		}


	}
}


$latest_sales = $Sales->get_latest();

if($latest_sales){

	foreach($latest_sales as $sale){

		$check = array(
			'userid'=>$sale->iduser,
			'contextid'=>$sale->idpromo,
			'type'=>'sale'
		);

		if( $Newsletters->check_queue($sale->iduser) && $Newsletters->check_log($check) ){

			$obj = new stdClass();
			$Promos->exclude = $sale->idpromo;
			$Promos->get();
			$obj->promos = $Promos->data();

			$Banners->visible = 1;
			$Banners->type = 'main';
			$Banners->sort = 'position';
			$Banners->limit = '0,2';
			$Banners->get();
			$obj->banners = $Banners->data();

			$Newsletters->add_queue(array(
				'userid'=>$sale->iduser,
				'subject'=>'Aprovechá estas promos en EstiloSPA.com',
				'body'=>Templates::template('newsletters/sale-promos',$obj),
				'type'=>'sale',
				'contextid'=>$sale->idpromo
			));

		}


	}
}

$Cron = new Cron;
$Cron->add_log('daily');

http_response_code(200);