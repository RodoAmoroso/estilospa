<?php 

$arrsection = explode('-',$_SUBSECTION);
$idcategory = intval($arrsection[0]);
$_BLOG = new Blog();
$_BLOGCATEGORIES = new BlogCategories();

