InstaWrapper
============

Instagram API PHP Wrapper

See index.php for working sample.

Quick Usage: 
<p><br />
  &lt;?php </p>
<p>// Change these to your Instagram App Details --&gt;<br />
  $client_id = 'CLIENT_ID';<br />
  $secret = 'CLIENT_SECRET';<br />
  $redirect_uri = 'REDIRECT_URI';<br />
  $scope = 'comments';</p>
<p>include('Instagram.php');</p>
<p>$insta = new Instagram(array(<br />
  'client_id' =&gt; $client_id,<br />
  'client_secret' =&gt; $secret,<br />
  'redirect_uri' =&gt; $redirect_uri,<br />
  ));</p>
<p>if(!$insta-&gt;isAuthenticated()){<br />
  // User needs to login or authenticate<br />
  $login_url = $insta-&gt;getLoginUrl(array(<br />
  'scope' =&gt; $scope,<br />
  ));<br />
  header('Location: '.$login_url);<br />
  }</p>
<p>// Show the token<br />
  echo &quot;&lt;br /&gt;Token: &quot;;<br />
  var_dump($insta-&gt;getToken());</p>
<p>// Show the user data<br />
  echo &quot;&lt;br /&gt;User Data: &quot;;<br />
  var_dump($insta-&gt;getUserData());</p>
<p>// Make a call to the API<br />
  $circles = $insta-&gt;api('tags/cool-tag/media/recent'); <br />
  var_dump($circles-&gt;data);</p>
<p>?&gt;</p>