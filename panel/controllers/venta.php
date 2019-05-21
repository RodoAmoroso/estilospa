<?php 

$Sales = new Sales();
if(!$Sales->find($_subsection)) Redirect::to('panel/mi-cuenta');
$_sale = $Sales->data();

if($_userdata->idclient != $_sale->idclient) Redirect::to('restricted');

$img = json_decode($_sale->gallery);

$image = View::img('promos',$img[0]->photoname.'-t.'.$img[0]->extension );

$User = new User();
$User->find($_sale->iduser);
$_user = $User->data();

$userimg = empty($_user->image) ? '' : json_decode($_user->image);
$_userimage = empty($userimg) ? 'user-default.png' : $userimg->photoname.'-o.'.$userimg->extension;

