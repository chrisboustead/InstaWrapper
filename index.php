<?php 



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
print_r($insta->getToken());

// Show the user data
echo "<br />User Data: ";
print_r($insta->getUserData());

// Get some photos with "circle" tag
$circles = $insta->api('tags/circles/media/recent'); 
echo "<br />Circle Tags: ";
foreach($circles->data as $circle){
	$tags = $circle->tags; 						// Array of tags
	$location = $circle->location; 				// Location Data if available
	$comments = $circle->comments->data; 		// Array of comment objects
	$comments_count = $circle->comments->count; // Comments count
	$created =  date('m/d/Y', $circle->created_time); // Created Date/time
	$link =  $circle->link; 					// Created Date/time
	$likes = $circle->likes->data; 				// Array of likes objects
	$likes_count = $circle->likes->count; 		// Likes count
	$low_res = $circle->images->low_resolution->url; 		// Low res image
	$low_res_w = $circle->images->low_resolution->width; 	// Low res image width
	$low_res_h = $circle->images->low_resolution->height; 	// Low res image height
	$std_res = $circle->images->standard_resolution->url; 		// Standard res image
	$std_res_w = $circle->images->standard_resolution->width; 	// Standard res image width
	$std_res_h = $circle->images->standard_resolution->height; // Standard res image height
	
	
	print_r($tags);
	echo "<br />";
	
	echo "<img src='$std_res' />";
	echo "<br />";
	
	print_r($comments);
	echo "<br />";
	echo '<hr />';
	
}

//print_r($circle);

