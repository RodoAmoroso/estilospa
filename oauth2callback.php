<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>OAuth</title>
	<link rel="stylesheet" href="">
</head>
<body>
	


<pre>
	
<?php 
require_once 'calendar/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig('calendar/client_secret.json');
$client->setAccessType("offline"); 
$client->setIncludeGrantedScopes(true);
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
$client->authenticate($_GET['code']);
$access_token = $client->getAccessToken();
echo $_GET['code'];
$client->setAccessToken($access_token);


///$client->setAccessToken('ya29.Glt2BcMpHrM_LCFQIhYIaMI3vApS_5xuQ_1BuMzuTR9vqnVB3jfd8pc6_uBWe6cJnblpncv3TVd6O9BSIxLoNBENgKvh2hj4WrB97ms-fZ1r05yzLTjYO9N_aZGc');
//print_r($_GET);
////var_dump($access_token);
//echo '<br />';


$calendar = new Google_Service_Calendar($client);
////$events = $calendar->files->listFiles(array())->getItems();
$events = $calendar->events->listEvents('primary',['timeMin'=>'2018-01-01T00:00:00-03:00']);
///$events->getItems();
while(true){
	foreach($events->getItems() as $event) {
		print_r( $event->getSummary().' - '.date('d/m/Y H:i:s',strtotime($event->getStart()->getDateTime())).' - '.date('d/m/Y H:i:s',strtotime($event->getEnd()->getDateTime())).'<br />');
	}
	$pageToken = $events->getNextPageToken();
	if($pageToken) {
		$optParams = array('pageToken' => $pageToken);
		$events = $service->events->listEvents('primary', $optParams);
	}else {
		break;
	}
}

///https://developers.google.com/resources/api-libraries/documentation/calendar/v3/java/latest/com/google/api/services/calendar/CalendarRequest.html
?>
</pre>

</body>
</html>

<?php 

/*array(5) {
  ["access_token"]=>
  string(129) "ya29.Glt2BYkksodxM19NYdQ3jWNyQ006JchAgMuGd4_y31dvUAkwYPYG6tmMvOKo0WjsvBqprcz0LL8ZSg6cM2LGdkARl48G-h5V-lhLvMReYZLH-4mkyOoTTtd-aNFm"
  ["token_type"]=>
  string(6) "Bearer"
  ["expires_in"]=>
  int(3599)
  ["id_token"]=>
  string(1062) "eyJhbGciOiJSUzI1NiIsImtpZCI6ImFjMmI2M2ZhZWZjZjgzNjJmNGM1MjhlN2M3ODQzMzg3OTM4NzAxNmIifQ.eyJhenAiOiI5OTUzNDY4MzU4MzMuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiI5OTUzNDY4MzU4MzMuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDc3NDM3MjkyNjY4MDc0NDcwNzciLCJlbWFpbCI6InJvZG9zb2Z0QGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhdF9oYXNoIjoiY3Q0cG9HOVlLNUVjcDl2NjBFRVNiZyIsImV4cCI6MTUyMDM3MTM4NywiaXNzIjoiaHR0cHM6Ly9hY2NvdW50cy5nb29nbGUuY29tIiwiaWF0IjoxNTIwMzY3Nzg3LCJuYW1lIjoiUm9EbyBBbW9yb3NvIiwicGljdHVyZSI6Imh0dHBzOi8vbGg2Lmdvb2dsZXVzZXJjb250ZW50LmNvbS8tS3FsZF9JY2tJd0EvQUFBQUFBQUFBQUkvQUFBQUFBQUFBMTAvekZJakRabGt0SlEvczk2LWMvcGhvdG8uanBnIiwiZ2l2ZW5fbmFtZSI6IlJvRG8iLCJmYW1pbHlfbmFtZSI6IkFtb3Jvc28iLCJsb2NhbGUiOiJlbiJ9.dvNeAb-FQRSZBwlO1nWHZOYGWrzDKmD8iXTz4hnRw1WgThXFe5dUIGN8jgeWp4625E8JctZM4JUNRkD25Sj06YqDiWwW5iaAlV8wcN03FlBge89wAVxX0bFb4X1Sn-aBnJ_ypE3VccFQZtudbcZKRgiyD7XzDsuNBcX72807WiUu2X1ytrqEV-yYiSNFWF8wv-gaxNZrJHyhV41XnDTooyrBDziIjiGhKCX7S-Dcyuc-eGdUqKRXJsVDfq01pEuTBWtC7NyeocnTT9tJLGrAa02uQBSUir7jG9bhdRD9zQon9lsQQEja4FAHElGmmhmp0mJPHo-I72XD0hKrOkTZ0g"
  ["created"]=>
  int(1520367788)
}*/

?>