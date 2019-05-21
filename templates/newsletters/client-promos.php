
<?php if($obj->promos): ?>

<h2 style="font-size:12pt">Mirá estas promos de <?=$obj->client->name?> que tenemos para vos:</h2>
<table cellpadding="0" cellspacing="0" style="background-color: #eee;width:100%">
	
	<?php 
	$loop = PageMaker(2,count($obj->promos));
	for($i=1; $i<=$loop; $i++):		
	?>
	
	<tr>

		<?php 
		for($p=1; $p<=2; $p++):
			if(!empty($obj->promos)): 
				$promo = $obj->promos[0];
				$img = json_decode($promo->gallery);
		?>		
		<td style="width:50%;vertical-align: top;padding:8px">
			<div style="position:relative;height:180px;overflow:hidden;">
				<img src="<?=View::img('promos',$img[0]->photoname.'-t.'.$img[0]->extension)?>" alt="" style="width:100%;position:absolute;left:0;top:0">				
			</div>		
			<div style="padding:16px;background-color: white">
				<h2 style="font-size:11pt"><?=$promo->title?></small>
				<?php if($promo->sale): ?>
				<h4>$ <?=number_format($promo->price-($promo->price*$promo->discount/100),2,',','.')?></h4>
				<?php endif; ?>
				<p><a href="<?=ROOT.'promo/'.$promo->permalink.'/'.$promo->id.'-'.Permalink($promo->title)?>" style="background-color: #5ba4c8;color:white;display:inline-block;text-decoration: none;padding:6px 16px;font-size:10px">LO QUIERO</a></p>
			</div>
		</td>
		<?php array_shift($obj->promos); else: ?>
		<td style="width: 50%"></td>
		<?php endif; endfor; ?>

	</tr>

	<?php endfor; ?>


</table>

<?php endif; ?>