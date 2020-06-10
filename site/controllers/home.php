<?php

$Banners = new Banners;
$Blog = new Blog;


$PromosCategories->limit = "0,12";
$promo_categories = $PromosCategories->get();

$_arrjs[] = ['folder'=>'lib/','script'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.carousel.min'];
$_arrcss[] = ['folder'=>'lib/','style'=>'owl.theme.default.min'];

$_arrjs[] = ['folder'=>'lib/','script'=>'slider'];




$Banners->filters = [
	'visible'=>1,
	'type'=>'main'
];
$banners = $Banners->get();