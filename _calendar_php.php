<?php 
require_once 'calendar/vendor/autoload.php';


$client = new Google_Client();
///$client->setApplicationName("My Application");
///$client->useApplicationDefaultCredentials();
///$client->setDeveloperKey("AIzaSyDRFWcsZymIpYPlmN8T_xJ5QO8CwJ1bCpc");

$client->setAuthConfig('calendar/client_secret.json');
$client->setAccessType("offline");        // offline access
//$client->setApprovalPrompt ("force");
$client->setIncludeGrantedScopes(true);   // incremental auth
$client->addScope(Google_Service_Calendar::CALENDAR);
//$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY); //https://developers.google.com/identity/protocols/googlescopes
$client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
$auth_url = $client->createAuthUrl();

////////$calendar = new Google_Service_Calendar($client);
///$args = array('calendarId'=>'primary');
///$results = $calendar->events;

///https://developers.google.com/api-client-library/php/auth/web-app#protectauthcode
/////http://localhost/oauth2callback.php?code=4/AABLJbPG1bKYKYyr8Q7xJCGb4s2XCMKZGF3SKf-KMPTjGYZTHh4Lq3wMPJdxRWFebT9aa8w4f40vQmkQI23b6pE&scope=https://www.googleapis.com/auth/drive.metadata.readonly+https://www.googleapis.com/auth/plus.me+https://www.googleapis.com/auth/userinfo.email+https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/calendar+https://www.googleapis.com/auth/calendar.readonly#
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
</head>
<body>

<a href="<?= $auth_url ?>"><?= $auth_url ?></a>
	
</body>
</html>