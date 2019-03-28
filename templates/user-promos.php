<?php 
require '../config.php';


$Mailing = new Mailing();

ob_start();
?>


<!-- Banners -->
<table style="width:100%" cellpadding="0" cellspacing="0">
	<tr>
		<td style="width:50%;border:2px solid white">
			<a href="<?=ROOT?>" style="display:block;text-decoration: none">
				<img src="<?=View::img('home','banner-enamorados-compraonline-8455-o.png')?>" alt="" style="width:100%;margin:0;padding:0;border:0">
			</a>
		</td>
		<td style="width:50%;border:2px solid white">
			<a href="<?=ROOT?>" style="display:block;text-decoration: none">
				<img src="<?=View::img('home','banner-enamorados-promos-9317-o.png')?>" alt="" style="width:100%;margin:0;padding:0;border:0">
			</a>
		</td>
	</tr>
</table>

<p>&nbsp;</p>
<!-- Highlight Promo -->

<h2 style="font-size:14pt">Aprovechá esta promo antes que finalice:</h2>
<table style="margin:16px 0;border:1px solid #ccc" cellpadding="0" cellspacing="0">
	<tr>
		<td style="width:50%;">
			<img src="<?=View::img('promos','img-20171012-wa0027-4254-o.jpg')?>" alt="" style="width:100%">
		</td>
		<td style="vertical-align: top;padding:16px;">
			<h2 style="font-size:14pt">Enamorados: Miny Day Premium Dúo Parejas en Simultaneo</h2>
			<p>Circuito de Masajes Premium Duo Pareja en Simultaneo. Regalos originales que aportan salud. compra online</p>
			<h5>Validez</h5>
			<h5>Shiva Masajes: Palermo</h5>

			
			<h2>$ 3.390,00</h2>
			<a href="<?=ROOT?>" style="background-color: #5ba4c8;color:white;display:inline-block;text-decoration: none;padding:6px 16px">COMPRAR</a>
			
		</td>
	</tr>
</table>

<p>&nbsp;</p>

<!-- RELATED -->
<h2 style="font-size:12pt">Mirá estas promos que tenemos para vos:</h2>
<table cellpadding="0" cellspacing="0" style="background-color: #eee">
	<?php for($i=0;$i<=4;$i++): ?>
	<tr>
		<td style="width:50%;vertical-align: top;padding:8px">
			<div style="position:relative;height:180px;overflow:hidden;">
				<img src="<?=View::img('promos','img-20171012-wa0027-4254-o.jpg')?>" alt="" style="width:100%;position:absolute;left:0;top:0">				
			</div>		
			<div style="padding:16px;background-color: white">
				<h2 style="font-size:11pt">Enamorados: Miny Day Premium Dúo Parejas en Simultaneo</h2>
				<small>Shiva Masajes: Palermo</small>
				<h2>$ 3.390,00</h2>
				<a href="<?=ROOT?>" style="background-color: #5ba4c8;color:white;display:inline-block;text-decoration: none;padding:6px 16px">COMPRAR</a>
			</div>
		</td>
		<td style="width:50%;vertical-align: top;padding:8px">
			<div style="position:relative;height:180px;overflow:hidden;">
				<img src="<?=View::img('promos','34511522-10156441141153838-4083192710721175552-o-5622-o.jpg')?>" alt="" style="width:100%">				
			</div>			
			<div style="padding:16px;background-color: white">
				<h2 style="font-size:11pt">Enamorados: Miny Day Premium Dúo Parejas en Simultaneo</h2>
				<small>Shiva Masajes: Palermo</small>
				<h2>$ 3.390,00</h2>
				<a href="<?=ROOT?>" style="background-color: #5ba4c8;color:white;display:inline-block;text-decoration: none;padding:6px 16px">COMPRAR</a>
			</div>
		</td>
	</tr>
	<?php endfor; ?>


</table>


<?php

$body = ob_get_contents();
ob_end_clean();
echo $Mailing::template($body);