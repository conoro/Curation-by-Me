<?php
session_start();
require_once("consumer-keys.php");
require_once 'twitteroauth.php';

$connection = new TwitterOAuth($consumerKey, $consumerSecret);
$request_token = $connection->getRequestToken();
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
        "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Curation By Me - An experiment in Twitter Lists and filters</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

  <style type="text/css">
    body {
    font:12px/1.4em Verdana, sans-serif;
    color:#333;
    background-color:#fff;
    width:700px;
    margin:50px auto;
    padding:0;
    }

    a {
    color:#326EA1;
    text-decoration:underline;
    padding:0 1px;
    }

    a:hover {
    color:#000000;
    text-decoration:none;
    }

    div.header {
    border-bottom:1px solid #999;
    }

    div.item {
    padding:5px 0;
    border-bottom:1px solid #999;
    }
    
    img {
      border: none;
    }
    </style>

</head>
<body>
<a href="/"><img src="curationbyme_128x128.png" /></a>  
<h1>Curation By Me</h1>
<h2>An experiment in Twitter Lists and filters</h2>
<p>This site is just about scratching an itch. I wanted to be able to filter tweets from a list based on a hashtag or keyword so I built something simple to do that.</p>
<p>If you want to try it out, you need a Twitter account. Just login below</p>
<br />
<?php
// Display Twitter log in button with encoded link 
echo "<a href=\"". $connection->getAuthorizeURL($request_token["oauth_token"]) . "\">
<img src=\"http://a0.twimg.com/images/dev/buttons/sign-in-with-twitter-d.png\"></a>";
?>
<br />
<h3>Notes</h3>
<ol>
<li>The site defaults to a list and hashtag related to the Irish General Election in 2011</li>
<li>But you can use any list URL and any hashtag or keyword</li>
<li>Open multiple browsers tabs to track multiple things</li>
<li>Click on the link on the person's name to see that tweet on Twitter so you can reply/retweet/etc</li>
<li>It doesn't auto-refresh because that would use up your API accesses to Twitter</li>
<li>The pagination is screwy for a good reason</li>
</ol>
<h3>Coming Soon</h3>
<ol>
  <li>Fewer bugs</li>
  <li>AND/OR in the filter</li>
  <li>Some styling (maybe)</li>
</ol>
<h3>Bug Reports, Feature Requests, Awards</h3>
<p>Just <a href="http://twitter.com/#!/conoro">@conoro</a></p>
<br />
<br />
<br />
<p><a href="http://www.conoroneill.com">Conor</a> <a href="http://www.conoroneill.net">O'Neill</a>, 2011</p>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-88610-5']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
