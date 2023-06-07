<?php

$category = false;
if(!empty($_subsection)){
	list($categoryid) = explode('-',$_subsection);
	if(!$category = $PromosCategories->find($categoryid)) Redirect::to('404');
}

//show_array($category);

$Experiences = new Experiences;
$Experiences->filters = [
	'active'=>1,
	'gift'=>1,
	'visible'=>1
];
if($category){
	$Experiences->filters['category'] = $category->id;
}
if($_idsection){
	$Experiences->filters['city'] = $_idsection;
}

$total_experiences = $Experiences->get();
$limit = 50;
$page = 0;
$Experiences->limit = $page.','.$limit;
$experiences = $Experiences->get();


//// STORES
$arr_stores = array();
$stores = false;
if($total_experiences){
	foreach($total_experiences as $te){
		$st = explode(',',$te->stores);
		$arr_stores = array_merge($arr_stores,$st);
	}
	$arr_stores = array_unique($arr_stores);
}
if($arr_stores){
	$Stores->group = 'city';
	$Stores->ids = implode(',',$arr_stores);
	$Stores->search();
	$stores = $Stores->data();
}