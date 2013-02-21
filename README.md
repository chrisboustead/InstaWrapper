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

<h2>InstaBatch Examples</h2>

Call API and cache in DB: 
<pre>
$client_id = 'CLIENT_ID';
$secret = 'CLIENT_SECRET';
$redirect_uri = 'REDIRECT_URI';
$scope = 'comments';

require_once('Instagram.php');
require_once('InstaBatch.php');

$insta = new Instagram(array(
	'client_id' => $client_id,
	'client_secret' => $secret,
	'redirect_uri' => $redirect_uri,
));

$host = 'localhost';
$user = 'DB_USER';
$pass = 'DB_PASS';
$db = 'DB';

// To use InstaBatch to save retrieved details in DB, set DB details
$batch = new Instabatch(array(
	'db_server' => $host,
	'db_user' => $user,
	'db_pass' => $pass,
	'db_catalog' => $db,
));

// To use InstaBatch to save retrieved details in DB, 
// set only the properties that you want to save
$batch->record_fields = array('user','tags','comments','images');

// Set the number of records per searched tag to save in DB
// if not set, will be unlimited
$batch->record_store_count = 5;

if(!$insta->isAuthenticated()){
	// User needs to login or authenticate
	$login_url = $insta->getLoginUrl(array(
		'scope' => $scope,
	));
	header('Location: '.$login_url);
}

echo '<h1>InstaBatch</h1>';
echo '<h2>Information: </h2>';

// Show the token
echo "<br />Access Token: ";
print_r($insta->getToken());

// Show the user data
echo "<br />User Data: ";
print_r($insta->getUserData());

// Get some photos with a set tag or "circles" tag
$tag = (isset($_GET['tag'])) ? urldecode($_GET['tag']) : 'circles';

// Show InstaBatch Actions
echo "<br />InstaBatch will attempt to add the most recent Instagram media objects with tag `$tag`";
echo "<br />InstaBatch will record the details of `".implode(', ', $batch->record_fields )."` for `$tag` tagged objects.";

$objs = $insta->api('tags/'.$tag.'/media/recent'); 

$inserted = 0;
$prev_stored = 0;

foreach($objs->data as $obj){

	$created =  date('m/d/Y H:i', $obj->created_time); // Created Date/time
	$id = $obj->id; 							// Media ID 

	// Use InstaBatch to save retrieved details in DB 
	$last_id = $batch->insertItem(array(
		'id' => $id,
		'created' => date('Y-m-d H:i', strtotime($created)),
		'tag' => $tag
	));
	
	if($last_id){
		$batch->insertItemDetails($last_id,$obj);
		++$inserted;
	}else{
		++$prev_stored;
	}

}

echo "<br />Instabatch Added $inserted records to the DB.";
echo "<br />$prev_stored were already in the DB.";

// Perform DB Cleanup
$batch->batchCleanup($tag);
</pre>

Retrieve a cached tag From DB: 
<pre>
require_once('InstaBatch.php');

$host = 'localhost';
$user = 'dev';
$pass = 'Circle203';
$db = 'dev';

// To use InstaBatch to retrieve cached data, set DB details
$batch = new Instabatch(array(
	'db_server' => $host,
	'db_user' => $user,
	'db_pass' => $pass,
	'db_catalog' => $db,
));

$tag = (isset($_GET['tag'])) ? urldecode($_GET['tag']) : 'circles';

echo '<h1>InstaBatch</h1>';
echo "<h2>Object Query for `$tag` </h2>";

// Get cached Instagram data from DB for the tag
$objs = $batch->getItems($tag);

if($objs) {	
	echo count($objs) . " found.<br />";
	foreach($objs as $obj){

		$data = $obj['data'];
		$std_res = $data['images']->standard_resolution->url;
		echo "<img src='$std_res' />";
		echo "<br />";
		
	}
}else{
	echo "<br>No objects returned for tag `$tag`";
}
</pre>