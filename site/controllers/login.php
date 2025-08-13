<?php 

if($User->logged()) Redirect::to('home');

$arrsection = array();
echo '<script>var arrsection;</script>';
if(!empty($_subsection)){
	$arrsection = explode('-',$_subsection);
}

// Add Google OAuth JavaScript
$_arrjs[] = ['folder'=>'site/','script'=>'google-oauth'];

