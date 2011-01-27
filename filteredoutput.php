<?php
session_start();
require_once("consumer-keys.php");
require_once 'twitteroauth.php';

// Check we have active session with access tokens from Twitter
if(!empty($_SESSION["access_token"]) && !empty($_SESSION["access_token"]["oauth_token"])) {

  // Create new TwitterOAuth object with access tokens
  $tOAuth = new TwitterOAuth($consumerKey, $consumerSecret, $_SESSION["access_token"]["oauth_token"], $_SESSION["access_token"]["oauth_token_secret"]);

  // Perform an API request
  $credentials = $tOAuth->get('account/verify_credentials');
//  echo "Logged in as @" . $credentials->screen_name;
}

// Set the feed to process.
if (!empty($_GET["listurl"]) && !empty($_GET["hashtag"])){
$listurl = $_GET["listurl"];
$hashtag = $_GET["hashtag"];
}
else{
  $listurl = "http://twitter.com/#!/conoro/general-election-2011";
  $hashtag = "#ge11";
}
// from
// http://twitter.com/#!/conoro/general-election-2011-10
// to
// http://api.twitter.com/1/conoro/lists/general-election-2011/statuses.atom
// http://api.twitter.com/1/conoro/lists/general-election-2011.json

$list1 = explode("http://twitter.com/#!/", $listurl);
$list2 = explode ("/", $list1[1]);
//$listfeedurl = 'http://api.twitter.com/1/'. $list2[0]. '/lists/' . $list2[1] . '/statuses.atom' . '?page=1&per_page=20';
//$listfeedurl = $list2[0]. '/lists/' . $list2[1] . '/statuses.atom';
$listfeedurl = $list2[0]. '/lists/' . $list2[1] . '/statuses';
$listinfourl = $list2[0]. '/lists/' . $list2[1];
$listinfo = $tOAuth->get($listinfourl);

// print_r ($json);

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
<br />
<br />
<form action="filteredoutput.php" method="get">
URL of Twitter List: <input type="text" name="listurl" size="55" value="<?php echo $listurl;?>" />
<br>
Hashtag/Keyword to find: <input type="text" name="hashtag" size="10" value="<?php echo $hashtag;?>"/>
<br>
<input type="submit" />
</form>

<div class="header">
<h3>List Description: <?php echo $listinfo->description;?></h3>

<?php
// number of rows to show per page
$rowsperpage = 20;
// find out total pages
$totalpages = 10;

// get the current page or set a default
if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
   // cast var as int
   $currentpage = (int) $_GET['currentpage'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if
// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;


// Twitter data
$json = $tOAuth->get($listfeedurl, array('per_page'=>'200', 'page'=>$currentpage));


/******  build the pagination links ******/
// range of num links to show
$range = 3;

// if not on page 1, don't show back links
if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1&listurl=".urlencode($listurl)."&hashtag=".urlencode($hashtag)."'><<</a> ";
   // get previous page num
   $prevpage = $currentpage - 1;
   // show < link to go back to 1 page
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage&listurl=".urlencode($listurl)."&hashtag=".urlencode($hashtag)."'><</a> ";
} // end if 

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x&listurl=".urlencode($listurl)."&hashtag=".urlencode($hashtag)."'>$x</a> ";
      } // end else
   } // end if 
} // end for
                 
// if not on last page, show forward and last page links        
if ($currentpage != $totalpages) {
   // get next page
   $nextpage = $currentpage + 1;
    // echo forward link for next page 
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage&listurl=".urlencode($listurl)."&hashtag=".urlencode($hashtag)."'>></a> ";
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages&listurl=".urlencode($listurl)."&hashtag=".urlencode($hashtag)."'>>></a> ";
} // end if

?>
<p></p>
</div>

  <?php
    foreach ($json as $value){
    if (strlen(stristr($value->text,$hashtag))>0) { ?>
              <div class="item">
                <?php echo "<a href=\"http://twitter.com/#!/".$value->user->screen_name."/status/".$value->id."\" target=\"_blank\">".$value->user->screen_name."</a>" . ": ". $value->text . "<br />";?>
                <p><small>Posted on <?php echo $value->created_at;?></small></p>
                </div>
              <?php } } ?>
              
              
              
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
