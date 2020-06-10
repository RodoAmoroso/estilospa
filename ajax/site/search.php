<?php

header("Content-Type: application/json; charset=utf-8", true);

$Search = new Search();
$Search->keywords = Input::get('keywords');
$Search->searchmixed = Input::get('search_mixed');
$Search->limit = '0,10';

$Experiences = new Experiences;
$Stores = new Stores;
$User = new User;
$Favs = new Favs;
$MPConfig = new MPConfig;

$dbprovinces = DB::getInstance()->get('provinces',array('id','!=',0));
foreach($dbprovinces->results() as $p){
	$Provinces[$p->id] = $p->name;
}

if(!Input::check(Input::get('required'))) die(Responses::response('fail'));

switch($_action){

	case 'locations':
		$Search->locations();
		echo Responses::response('ok','',array('results'=>$Search->data()));
		break;

	case 'main':
		$Search->main();
		echo Responses::response('ok','',array('results'=>$Search->data()));
		break;

	case 'load_more_promos':
		$limit = intval(Input::get('limit'));
		$page = intval(Input::get('page')+1)*$limit;
		$Experiences->limit = $page.','.$limit;
		$Experiences->filters = [
			'active'=>1,
			'category'=>Input::get('categoryid')
		];
		if(Input::get('gift')){
			$Experiences->filters['gift'] = 1;
		}

		if(Input::get('city')){
			$Experiences->filters['city'] = Input::get('city');
		}
		$experiences = $Experiences->get();

		$body = '';
		if($experiences){
			foreach($experiences as $kp=>$promo){
				$imgpromo = $promo->image;
				$Stores->get($promo->idclient);
				$promolink = $promo->link;

				$body .= '<div class="mod-promo mod-promo-5">';
				ob_start();
				include PATH.'mods/mod-promo.php';
				$body .= ob_get_contents();
				ob_end_clean();
				$body .= '</div>';
			}

		}
		echo Responses::response('ok','',[
			'results'=>$experiences,
			'page'=>$page,
			'body'=>$body
		]);
		break;

	default:
		echo Responses::response('fail');
		break;

}