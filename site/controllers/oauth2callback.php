<?php

//show_array(Input::get('code'),true);
//dd(Environment::get('GOOGLE_CLIENT_SECRET'));
//117325849715543711330
if($User->logged()) Redirect::to('home');

try{
	$response = Curl::action([
		'url'=>'https://oauth2.googleapis.com/token',
		'body'=>[
			'client_id'=>Env::get('GOOGLE_CLIENT_ID'),
			'client_secret'=>Env::get('GOOGLE_CLIENT_SECRET'),
			'code'=>Input::get('code'),
			'grant_type'=>'authorization_code',
			'redirect_uri'=>'http://localhost/estilospa/oauth2callback'
		],
		'body_json'=>false,
		'method'=>'POST',
		'header'=>[
			'Content-Type: application/x-www-form-urlencoded'
		]
	]);

	if(!$response) Redirect::to('google-auth-fail');
	
	$user_info = Curl::action([
		'url'=>'https://www.googleapis.com/oauth2/v2/userinfo',
		'header'=>[
			"Authorization: Bearer {$response->access_token}"
		]
	]);	
	
	if(!$user_info) Redirect::to('google-auth-fail');
	
	//img
	$imginfo = (object) pathinfo($user_info->picture);
	//dd($imginfo);

	copy($user_info->picture,IMG.'users'.DS.$imginfo->filename.'-o');
	copy($user_info->picture,IMG.'users'.DS.$imginfo->filename.'-t');
	$img_json = json_encode([
		'photoname'=>$imginfo->filename,
		'extension'=>''
	]);

	if($User->find($user_info->email)){
		$User->update($User->data()->id,[
			'google_id'=>$user_info->id,
			'image'=>$img_json
		]);
		if(!$User->login()) Redirect::to('404');
		Redirect::to('home');
	}
	
	$userid = $User->create([
		'mail'=>$user_info->email,
		'name'=>$user_info->given_name ?: $user_info->name,
		'lastname'=>$user_info->family_name ?: null,
		'image'=>$img_json,
		'idtype'=>2,
		'pass'=>password_hash(set_hash(),PASSWORD_DEFAULT),
		'created'=>date('Y-m-d H:i:s'),
		'hash'=>set_hash(),
		'google_id'=>$user_info->id
	]);


	

	$User->find($userid);
	if(!$User->login()) Redirect::to('404');
	Redirect::to('home');
	
	
}catch(Exception $e){

	Redirect::to('google-auth-fail');
}
