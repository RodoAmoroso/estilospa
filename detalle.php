<?php 
require 'config.php';
#type 1: celebrities
#type 2: novedades
#type 3: noticias
#type 4: resultados de búsqueda
#type 5: centros
#type 6: Promociones, Descuentos, Ofertas en Spa, Centros de estetica, Masajes, Tratamien
#type 7: día del amigo
#type 8: notas
#type 9: Promos MES DE LA MUJER
#type 10: compra online
#type 11: blog
#type 12: mapa de centros
#type 13: mailing
//echo 'Section type: '.$_REQUEST['t'].' - ID: '.$_REQUEST['d'];
//detalle.php?a=aria-estetica,-centro-de-estetica,-spa-urbano,-en-balvanera&t=5&d=92&re=1
//detalle.php?a=mariana-brey&t=1&d=886
if(!isset($_REQUEST['t']) || !isset($_REQUEST['d'])) Redirect::javascript('home');
$_CLIENTS = new Clients();
$_BLOG = new Blog();
$_DB = DB::getInstance();
if($_REQUEST['t'] == 1 || $_REQUEST['t'] == 2 || $_REQUEST['t'] == 3 || $_REQUEST['t'] == 8 || $_REQUEST['t'] == 11):		
	$_DB->get('blog',array('idref','=',intval($_REQUEST['d'])));
	if(!$_BLOG->find($_DB->first()->id)) Redirect::javascript('home');
	Redirect::javascript('blog-pagina/'.$_BLOG->data()->id.'-'.Permalink($_BLOG->data()->title));
elseif($_REQUEST['t'] == 5):
	$_DB->get('clients',array('idref','=',intval($_REQUEST['d'])));
	if(!$_CLIENTS->find($_DB->first()->id)) Redirect::javascript('home');		
	Redirect::javascript('centros/'.$_CLIENTS->data()->permalink);
elseif($_REQUEST['t'] == 4):
	Redirect::javascript('busqueda/'.$_REQUEST['a']);
else:
	//echo 'fail';
	Redirect::javascript('home');
endif;
