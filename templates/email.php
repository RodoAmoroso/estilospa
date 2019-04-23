
<!DOCTYPE html>
<html>
	<head>
		<title>EstiloSPA</title>
	</head>
	<body style="font-family:Helvetica,sans-serif;background-color:#eee;padding:26px 0">
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #5ba4c8;max-width:760px;margin:26px auto">
			<thead>
				<tr>
					<th style="background-color:#5ba4c8;text-align:left;border-bottom:2px solid #e7127c"><a href="<?=ROOT?>"><img src="<?=View::assets('logo-white.png')?>" alt="" style="max-width:180px;padding:20px;"></a></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="padding:20px;background-color: white" >

						<?=$obj?>

					</td>
				</tr>
			</tbody>	
			<tfoot>
				<tr>
					<td style="background-color:#215663;color:white;padding:20px;font-size:9pt;">
						<h3><a href="<?=ROOT?>" style="color:white">EstiloSPA</a></h3>
						<p>
							<a href="http://www.facebook.com/estilospa" style="color:white">Facebook</a> | <a href="https://www.instagram.com/estilospa/" style="color:white">Instragram</a> | <a href="http://www.twitter.com/estilospa" style="color:white">Twitter</a>
						</p>
						<small>© 2006 - <?=date('Y')?>. Spa. Estetica. Centros de Estética. Centros Integrales de Estética y Medicina, Tratamientos de Estetica. Estilo Spa. Todos los derechos reservados.</small>
					</td>
				</tr>
			</tfoot>		
		</table>
	</body>
</html>