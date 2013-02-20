InstaWrapper
============

Instagram API PHP Wrapper

See index.php for working sample.

Quick Usage: 

<?php 

// Change these to your Instagram App Details -->
$client_id = 'CLIENT_ID';
$secret = 'CLIENT_SECRET';
$redirect_uri = 'REDIRECT_URI';
$scope = 'comments';

include('Instagram.php');

$insta = new Instagram(array(
	'client_id' => $client_id,
	'client_secret' => $secret,
	'redirect_uri' => $redirect_uri,
));

if(!$insta->isAuthenticated()){
	// User needs to login or authenticate
	$login_url = $insta->getLoginUrl(array(
		'scope' => $scope,
	));
	header('Location: '.$login_url);
}

// Show the token
echo "<br />Token: ";
var_dump($insta->getToken());

// Show the user data
echo "<br />User Data: ";
var_dump($insta->getUserData());

// Make a call to the API
$circles = $insta->api('tags/cool-tag/media/recent'); 
var_dump($circles->data);

?>