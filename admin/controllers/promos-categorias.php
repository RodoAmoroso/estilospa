<?php

$PromosCategories = new PromosCategories;

//show_array($categories);

if(!empty(Input::get('search'))){
	$PromosCategories->search = Input::get('search');
}
if(Input::get('visible')!==''){
	$PromosCategories->filters = [
		'visible'=>Input::get('visible')
	];
}
$categories = $PromosCategories->get();