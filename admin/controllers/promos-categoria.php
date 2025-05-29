<?php

$PromosCategories = new PromosCategories;
$category = false;
if(!empty($_subsection)){
	if(!$category = $PromosCategories->find($_subsection)) Redirect::to('404');
}


$MainCategories = new MainCategories;
$main_categories = $MainCategories->get();


$_arrjs[] = ['folder'=>'lib/','script'=>'upfile'];