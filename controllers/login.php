<?php 

$arrsection = array();
echo '<script>var arrsection;</script>';
if(!empty($_SUBSECTION)){
	$arrsection = explode('-',$_SUBSECTION);
	///$_PROMOS->find($arrsection[1]);
	///$permalink = $_PROMOS->data()->permalink;
	///$promoname = Permalink($_PROMOS->data()->title);
	///echo '<script>arrsection = {section:"'.$arrsection[0].'",id:'.$arrsection[1].',permalink:"'.$permalink.'",promoname:"'.$promoname.'"}</script>';
}

