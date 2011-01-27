<?php 

session_start();
    
require_once("consumer-keys.php");
require_once 'twitteroauth.php';
    
// User has selected to DENY access 
if(!empty($_GET["denied"])) {
  // could re-direct or display cancelled view/template
  // we're just echoing out a message
  echo "No deal! <a href='index.php'>Try again?</a>";
  die();
}

// User has selected to ALLOW access for given token 
if($_GET["oauth_token"] == $_SESSION["oauth_token"]) {
  // Use generated request tokens (from session) to construct object
  $tOAuth = new TwitterOAuth($consumerKey, $consumerSecret, $_SESSION["oauth_token"], $_SESSION["oauth_token_secret"]);

  // Retrieve access token from Twitter
  $accessToken = $tOAuth->getAccessToken();

  // Check we have valid response
  if(is_numeric($accessToken["user_id"]))  {
    // Save the access tokens to a DB (we're using a session)
    $_SESSION["access_token"] = $accessToken;

    // Remove request token session variables
    unset($_SESSION["oauth_token"]);
    unset($_SESSION["oauth_token_secret"]);

    // Redirect to main page
    header("location: filteredoutput.php");
  } else {
    header("location: index.php");
  }
}

?>

