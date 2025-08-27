<?php

require_once 'config.php';

//dd(Input::get_all());
dd(Environment::get('GOOGLE_CLIENT_ID'));

use Google\Client as GoogleClient;
use Google\Service\Oauth2;

$User = new User();

// Initialize Google Client
$client = new GoogleClient();
$client->setAuthConfig('calendar/client_secret.json');
$client->setRedirectUri(ROOT.'oauth2callback.php');
$client->addScope('email');
$client->addScope('profile');

// Handle OAuth callback
if (Input::get('code')) {
	try {
		// Exchange authorization code for access token
		$token = $client->fetchAccessTokenWithAuthCode(Input::get('code'));
		$client->setAccessToken($token);

		// Get user info from Google
		$oauth2 = new Oauth2($client);
		$userInfo = $oauth2->userinfo->get();

		$googleEmail = $userInfo->email;
		$googleName = $userInfo->givenName;
		$googleLastName = $userInfo->familyName;
		$googlePicture = $userInfo->picture;

		// Check if user exists
		if ($User->find($googleEmail)) {
			// User exists, log them in
			if ($User->isActive($googleEmail)) {
				$User->login($googleEmail, null); // Login without password for OAuth users
				Redirect::to('home');
			} else {
				// Activate inactive user
				$User->update($User->data()->id, ['active' => 1]);
				$User->login($googleEmail, null);
				Redirect::to('home');
			}
		} else {
			// Create new user
			$hash = set_hash();
			$userId = $User->create([
				'name' => $googleName,
				'lastname' => $googleLastName,
				'mail' => strtolower($googleEmail),
				'pass' => password_hash(uniqid(), PASSWORD_DEFAULT), // Random password for OAuth users
				'created' => date('Y-m-d H:i:s'),
				'hash' => $hash,
				'idtype' => 2,
				'active' => 1, // Auto-activate OAuth users
				'google_id' => $userInfo->id,
				'image' => json_encode([
					'photoname' => 'google_' . $userInfo->id,
					'extension' => 'jpg',
					'url' => $googlePicture
				])
			]);

			if ($userId) {
				// Log in the new user
				$User->find($googleEmail);
				$User->login($googleEmail, null);
				Redirect::to('home');
			} else {
				Redirect::to('registro?error=oauth_failed');
			}
		}
	} catch (Exception $e) {
		Redirect::to('registro?error=oauth_failed');
	}
} else {
	// No authorization code, redirect to registration
	Redirect::to('registro');
}
