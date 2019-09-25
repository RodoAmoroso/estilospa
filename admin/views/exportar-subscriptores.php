<?php 

require_once '../../config.php';
require_once '../controllers/main.php';
$_subscribers = new Subscribers();

//if(!$_USER->logged() || $_USER->data()->idtype != 1) die(json_encode(array('status'=>'fail')));

$_data = $_subscribers->get();


$salida =

'<!DOCTYPE html>
<html>
<head>
	<title>Listado de Códigos</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>

<table style="border:1px solid black">
	<thead>
		<tr>
			<th>ID</th>
			<th>Email</th>
			<th>Creado</th>
		</tr>
	</thead>
	<tbody>';
		
		if($_data){
			foreach($_data as $k=>$subs){
				$salida .= '<tr>';
					$salida .= '<td style="border:1px solid black;padding:10px">'.$subs->id.'</td>';
					$salida .= '<td style="border:1px solid black;padding:10px">'.$subs->email.'</td>';
					$salida .= '<td style="border:1px solid black;padding:10px">'.$subs->creado.'</td>';
				$salida .= '</tr>';
			}
		}
	$salida .= '
	</tbody>
</table>

</body>
</html>';

$excelfile= "subscriptores_".date("Ymdhis").".xls"; //ruta del archivo a generar
$fpt = fopen("codestemp.xls","w");
fwrite($fpt,$salida);
fclose($fpt);
if(is_file("codestemp.xls")){
	header("Content-Type: application/xls");
	header("Content-Length: codestemp.xls");
	header('Content-Disposition: attachment; filename="'.$excelfile.'"');
	// upload the file to the user and quit
	readfile("codestemp.xls");
	exit;
	die();
}else{
	echo '<p >Hubo un error al procesar su solicitud. <br />Inténtalo nuevamente en unos minutos. <br /><br />Si el problema persiste contacta con el administrador del sitio.</p>';
}
?>