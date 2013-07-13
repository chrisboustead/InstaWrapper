InstaWrapper
============

Instagram API PHP Wrapper

See index.php for working sample.

Quick Usage: 
<pre>
$client_id = 'CLIENT_ID';
$secret = 'CLIENT_SECRET';
$redirect_uri = 'REDIRECT_URI';  //Must Match IG setup Redirect URI
$scope = 'comments';

require_once('Instagram.php');

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
echo "Token: ";
print_r($insta->getToken());

// Show the user data
echo "User Data: ";
print_r($insta->getUserData());

// Get the media tag to pull
$tag = (isset($_GET['tag'])) ? urldecode($_GET['tag']) : 'circles';

// Make API call
$circles = $insta->api('tags/'.$tag.'/media/recent'); 

echo "API Response: ";
var_dump($circles);
</pre>
