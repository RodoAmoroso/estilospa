<?php 

if(!$User->logged()) Redirect::to('login');

$Favs->iduser = $User->data()->id;