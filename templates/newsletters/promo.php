<!-- Highlight Promo -->
<?php if($obj->promo): ?>
<h2 style="font-size:14pt">Aprovechá esta promo antes que finalice:</h2>
<table style="margin:16px 0;border:1px solid #5ba4c8" cellpadding="10" cellspacing="0">
	<tr>
		<td style="width:50%;vertical-align: top;">
			<img src="<?=$obj->promo->image?>" alt="" style="width:100%">
		</td>
		<td style="vertical-align: top;padding:16px;width:50%">
			<h2 style="font-size:14pt;margin:0"><?=$obj->promo->title?></h2>
			<small style="color:#999"><?=$obj->promo->subtitle?></small>
			<h5><a href="<?=ROOT.'centros/'.$obj->promo->permalink?>"><?=$obj->promo->clientname?></a></h5>

			
			<h2>$ <?=number_format($obj->promo->price-($obj->promo->price*$obj->promo->discount/100),2,',','.')?></h2>
			<p><a href="<?=ROOT.'promo/'.$obj->promo->permalink.'/'.$obj->promo->id.'-'.Permalink($obj->promo->title)?>" style="background-color: #5ba4c8;color:white;display:inline-block;text-decoration: none;padding:6px 16px;font-size: 11pt">LO QUIERO</a></p>
			
		</td>
	</tr>
</table>
<?php endif; ?>



<p>&nbsp;</p>



<?php if($obj->related): ?>
<!-- RELATED -->
<h2 style="font-size:12pt">Mirá estas promos que tenemos para vos:</h2>
<table cellpadding="0" cellspacing="0" style="background-color: #eee;width:100%">
	
	<?php 
	//foreach($obj->related as $related): 
	$loop = PageMaker(2,count($obj->related));
	for($i=1; $i<=$loop; $i++):		
	?>
	
	<tr>

		<?php 
		for($p=1; $p<=2; $p++):
			if(!empty($obj->related)): 
				$related = $obj->related[0];
				$img = json_decode($related->gallery);
		?>		
		<td style="width:50%;vertical-align: top;padding:8px">
			<div style="position:relative;height:180px;overflow:hidden;">
				<img src="<?=View::img('promos',$img[0]->photoname.'-t.'.$img[0]->extension)?>" alt="" style="width:100%;position:absolute;left:0;top:0">				
			</div>		
			<div style="padding:16px;background-color: white">
				<h2 style="font-size:11pt"><?=$related->title?></small>
				<?php if($related->sale): ?>
				<h4>$ <?=number_format($related->price-($related->price*$related->discount/100),2,',','.')?></h4>
				<?php endif; ?>
				<p><a href="<?=ROOT.'promo/'.$related->permalink.'/'.$related->id.'-'.Permalink($related->title)?>" style="background-color: #5ba4c8;color:white;display:inline-block;text-decoration: none;padding:6px 16px;font-size:10px">LO QUIERO</a></p>
			</div>
		</td>
		<?php array_shift($obj->related); else: ?>
		<td style="width: 50%"></td>
		<?php endif; endfor; ?>

	</tr>

	<?php endfor; ?>


</table>

<?php endif; ?>