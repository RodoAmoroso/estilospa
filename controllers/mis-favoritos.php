<?php 

if(!$_USER->logged()) Redirect::to('Home');

$_FAVS->iduser = $_USER->data()->id;