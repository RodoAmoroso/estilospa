<?php

require 'config.php';

$User = new User;
if(!$User->logged()) return false;

$logid = intval(Input::get('id'));

$Newsletters = new Newsletters;
if(!$newsletter = $Newsletters->find_log($logid)){
	echo 'Newsletter no encontrado';
	exit;
}

echo Templates::template('email',$newsletter->body);