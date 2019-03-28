<?php

$MailHead = '
<!DOCTYPE html>
<html>
	<head>
		<title>Bienvenido/a a EstiloSPA!!!</title>
	</head>
	<body style="font-family:Helvetica,sans-serif;background-color:#eee">
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #5ba4c8;max-width:760px;margin:26px auto">
			<thead>
				<tr>
					<th style="background-color:#5ba4c8;text-align:left;border-bottom:2px solid #e7127c"><a href="'.ROOTPATH.'"><img src="'.ROOTPATH.'assets/logo-white.png" alt="" style="max-width:180px;padding:20px;"></a></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="padding:20px" >';

$MailFoot = '
					</td>
				</tr>
			</tbody>	
			<tfoot>
				<tr>
					<td style="background-color:#215663;color:white;padding:20px;font-size:9pt;">
						<p>© 2006 - '.date('Y').' Spa. Estetica. Centros de Estética. Centros Integrales de Estética y Medicina, Tratamientos de Estetica. Estilo Spa. Todos los derechos reservados.</p>
					</td>
				</tr>
			</tfoot>		
		</table>
	</body>
</html>';