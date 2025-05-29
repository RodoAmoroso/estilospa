<?php


if(!Input::check(['class'])) die(Responses::response('required'));

$_class = Input::get('class');
$Class = new $_class;

switch($_action):

	case 'get':
		$Class->search = Input::get('search');
		$Class->sort = Input::get('sort');
		$Class->filters = Input::get('filters');
		$Class->range = Input::get('range');

		$display_results = empty(Input::get('display_results')) ? 500 : Input::get('display_results');

		$Class->limit = '0,500';
		$total = $Class->get_total();
		$total = !$total ? 0 : $total;
		$total_pages = page_maker($display_results,$total);

		$page = empty(Input::get('page')) ? 1 : intval(Input::get('page'));
		$Class->limit = (($page*$display_results)-$display_results).','.$display_results;
		$results = $Class->get();

		echo Responses::response('ok','',array(
			'q'=>$Class->core_query(),
			'results'=>$results,
			'total'=>$total,
			'total_pages'=>$total_pages,
			'page'=>$page,
			'limit'=>$Class->limit
		));
		break;


	case 'get-datatable':

		$total = $Class->get_total();
		$Class->search = Input::get('search')['value'];

		$Class->filters = Input::get('filters');
		$total_filtered = $Class->get_total();

		$response = [
			'draw'=>Input::get('draw','int'),
			'recordsTotal'=>(int) $total,
			'recordsFiltered'=>(int) $total_filtered,
			'data'=>[],
			'request'=>Input::get_all()
		];

		$Class->limit = Input::get('start','int').','.Input::get('length','int');

		if(Input::get('order')){
			$sort = Input::get('columns')[Input::get('order')[0]['column']]['name'].'_'.Input::get('order')[0]['dir'];
			$Class->sort = $sort;
		}


		if($results = $Class->get()){
			if( method_exists($Class, 'datatable') ){
				foreach($results as $result){
					$response['data'][] = $Class->datatable($result);
				}
			}else{
				$response['data'] = $results;
			}
		}
		$response['queries'] = $DB->get_queries();
		echo Responses::response('ok','',$response);
		break;


	case 'find':
		echo Responses::response('ok','',array('result'=>$Class->find($_id)));
		break;

	case 'delete':
		if(!$Class->delete($_id)) die(Responses::response('fail',$Class->response));
		echo Responses::response('ok','Borrado con éxito');
		break;

	case 'save':
		if(!Input::check(Input::get('required'))) die(Responses::response('required'));
		if(!$Class->save()) die(Responses::response('fail',$Class->response));
		echo Responses::response('ok','Los datos fueron guardados correctamente',array('id'=>$Class->core_lastid()));
		break;

	case 'reorder':
		$Class->reorder(Input::get('arrids'));
		echo Responses::response('ok');
		break;

	default:
		echo Responses::response('fail');
		break;

endswitch;