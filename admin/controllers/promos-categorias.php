<?php

$PromosCategories = new PromosCategories;

if(!empty(Input::get('search'))){
	$PromosCategories->search = Input::get('search');
}
if(Input::get('visible')!==''){
	$PromosCategories->filters = [
		'visible'=>Input::get('visible')
	];
}
if(Input::get('main_category')){
	$PromosCategories->filters['main_category'] = Input::get('main_category');
}
$categories = $PromosCategories->get();

$MainCategories = new MainCategories;
$main_categories = $MainCategories->get();