<?php

//require PATH.'site/controllers/main.php';
$User = new User;
$_userdata = null;
if($User->logged()){
	$User->update($User->data()->id,array('logged'=>date('Y-m-d H:i:s')));
	$_userdata = $User->data();
}
if(!$User->logged()) Redirect::to('login#admin');
if($User->data()->idtype != 1) Redirect::to('restricted');


$arrAdminMenu = [
	['name'=>'Inicio','permalink'=>'inicio'],
	['name'=>'Actividad','permalink'=>'actividad'],
	['name'=>'Home Slider','permalink'=>'home'],
	['name'=>'Centros','permalink'=>'centros'],
	['name'=>'Experiencias','permalink'=>'promos'],
	['name'=>'Categorías','permalink'=>'promos-categorias'],
	['name'=>'Usuarios','permalink'=>'usuarios'],
	['name'=>'Ventas','permalink'=>'ventas'],
	['name'=>'Etiquetas','permalink'=>'etiquetas'],
	['name'=>'Preguntas','permalink'=>'preguntas'],
	['name'=>'Reservas','permalink'=>'reservas'],
	['name'=>'Blog','permalink'=>'blog'],
	//['name'=>'Estadísticas','permalink'=>'estadisticas'],
	[
		'name'=>'GiftCards',
		'permalink'=>'giftcards',
		'active-links'=>['giftcards','giftcards-gallery','giftcards-uso'],
		'submenu'=>[
			[
				'name'=>'Listado',
				'permalink'=>'giftcards'
			],
			[
				'name'=>'Galería',
				'permalink'=>'giftcards-gallery'
			],
			[
				'name'=>'Uso',
				'permalink'=>'giftcards-uso'
			]
		]
	],

	['name'=>'Vouchers','permalink'=>'vouchers'],
	['name'=>'Subscriptores','permalink'=>'subscriptores'],
	['name'=>'Vinculaciones','permalink'=>'vinculaciones']
];