<?php

$_arrjs[] = ['folder'=>'lib/','script'=>'upfile'];

$PromosCategories = new PromosCategories;
$category = false;
if(!empty($_subsection)){
	if(!$category = $PromosCategories->find($_subsection)) Redirect::to('404');
}