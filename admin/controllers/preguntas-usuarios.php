<?php

$Clients = new Clients;
$Clients->get();
$clients = $Clients->data();



$Users = new Users;
$Users->filters = ['has_questions'=>true];

$users = $Users->get();
//show_array($Users->core_query());