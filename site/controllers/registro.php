<?php 

if($User->logged()) Redirect::to('home');

// Add Google OAuth JavaScript
$_arrjs[] = ['folder'=>'site/','script'=>'google-oauth'];