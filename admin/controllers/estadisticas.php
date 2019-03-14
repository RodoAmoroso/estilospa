<?php 

$_stats = new Stats();

$top_words = $_stats->get_top_words();
$top_locations = $_stats->get_top_locations();

$top_promos = $_stats->get_top_promos();
$top_clients = $_stats->get_top_clients();

$top_blog = $_stats->get_top_blog();
$top_glossary = $_stats->get_top_glossary();
