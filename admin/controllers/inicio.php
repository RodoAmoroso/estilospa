<?php 

$Promos = new Promos();
$Clients = new Clients();

$Clients->sort = 'date';
$Clients->limit = '0,10';
$Clients->get();

$Promos->limit = '0,10';


//$Questions = new Questions();
//$questions = $Questions->get_unanswered();
//$questions_responses = $Questions->get_unanswered(null,true);



$_stats = new Stats();

$top_words = $_stats->get_top_words();
$top_locations = $_stats->get_top_locations();

$top_promos = $_stats->get_top_promos();
$top_clients = $_stats->get_top_clients();


$top_blog = $_stats->get_top_blog();
$top_glossary = $_stats->get_top_glossary();


$top_promos_questions = $_stats->get_top_promos_questions();
$top_clients_questions = $_stats->get_top_clients_questions();


function dif_labels($dif=''){
	switch ($dif) {
		case $dif<5:
			return 'danger';
			break;

		case $dif>=5 && $dif<10:
			return 'warning';
			break;
		
		default:
			return 'success';
			break;
	}
}