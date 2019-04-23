<?php 

require '../config.php';

//$Mailing = new Mailing();
///$Templ = new Mailing();

//$obj->name = 'Rodo';
//$obj->email = 'rodosoft@gmail.com';
//$obj->id = 2;
//$obj->hash = 'fde2fd2313fdefeffefdcc';
/////$Mailing->register($obj);

$obj = new stdClass();
$obj->promo_link = 'https://www.estilospa.com';
$obj->promo_title = 'Promo nueva';
$obj->user_name = 'Rodo';
$obj->question = 'Cuánto vale?';
$obj->questionid = 2;
$obj->question_date = '01/02/2019';

$obj->response = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta aspernatur, magni, officiis laudantium quod similique voluptates impedit provident dolor dicta vero nobis nesciunt autem, facilis corporis optio at voluptate expedita.';
$obj->response_date = '01/02/2019';

$template = Templates::template('questions/response-promo',$obj);
echo Templates::template('email',$template);