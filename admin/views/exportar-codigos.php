<?php 

$codes = isset($_POST['codes']) ? $_POST['codes'] : '';

$codes = explode(',',$codes);

/*echo '<pre>';
print_r(array_count_values($codes));
echo '</pre>';*/
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
			<th>Nro.</th>
			<th>Código</th>
		</tr>
	</thead>
	<tbody>';
		
		if(count($codes)){
			foreach($codes as $k=>$code){
				$salida .= '<tr>';
					$salida .= '<td style="border:1px solid black;padding:10px">'.($k+1).'</td>';
					$salida .= '<td style="border:1px solid black;padding:10px">'.$code.'</td>';
				$salida .= '</tr>';
			}
		}
	$salida .= '
	</tbody>
</table>

</body>
</html>';

$excelfile= "codigos_".date("Ymdhis").".xls"; //ruta del archivo a generar
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