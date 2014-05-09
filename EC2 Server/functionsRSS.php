<?php

// Handy function for setting a default value
function default_value(&$var, $default) {
    if (empty($var)) {
        $var = $default;
    }
}

// Use AWS-PHP class from AWK SDK. Define bucket to connect to.
require 'aws-php/vendor/autoload.php';
use Aws\S3\S3Client;
$client = S3Client::factory();
$bucket = 'links.orbitingfrog.com';
$client->waitUntilBucketExists(array('Bucket' => $bucket));

// Kerep track of the numbe rof times lorempixel is used
$loremCount = 1;

// HTML TEMPLATES AND FUNCTION
$xml1 = '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
  <title>Orbiting Links</title>
  <link>http://links.orbitingfrog.com</link>
  <atom:link href="http://feeds.feedburner.com/OrbitingLinks" rel="self" type="application/rss+xml" />
  <description>Automatically extracted links from the astronomers of the Twittersphere.</description>';

function makeProjectHTML($thisItem) {
  global $loremCount;

  $thisUrl = $thisItem['url'];
  $thisMetadata = $thisItem['metadata'];
  // print_r($thisMetadata);

  $thisHeadline = $thisMetadata['title'];
  default_value($thisHeadline, $thisItem['url']);

  $thisDescription = $thisMetadata['description'];
  default_value($thisDescription, 'Description not available.');

  if (isset($thisMetadata['thumbnail_url'])) {
    $thisThumb = $thisMetadata['thumbnail_url'];
  } else {
    $thisThumb = 'http://lorempixel.com/500/500/abstract/'.$loremCount.'/';
    $loremCount = $loremCount+1;
  }

  $thisTweetLink = $thisItem['tw_tweet_url'];
  $thisTweeter = $thisItem['tw_screen_name'];
  $thisTweeterDisplay = $thisItem['tw_display_name'];
  $thisTweeterAvatar = $thisItem['tw_profile_image_url'];
  $thisOriginalTweet = $thisItem['tw_text'];
  $thisCount = $thisItem['count'];

  $this_xml = '
  <item>
    <title>'.$thisHeadline.'</title>
    <link>'.$thisUrl.'</link>
    <description>
<![CDATA[
<img src="'.$thisThumb.'" style="max-width:100%;" />
<p>'.$thisDescription.'</p>
<p><small>First tweeted by <a href="http:/twitter.com/'.$thisTweeter.'">@'.$thisTweeter.'</a>. Subsequently shared by '.$thisCount.' other tracked sources in the past 24 hours.</small></p>
]]>
    </description>
    <guid>'.$thisUrl.'</guid>
    <source url="'.$thisTweetLink.'">@'.$thisTweeter.'</source>
  </item>';
  return $this_xml;
}

// End of HTML file and About page content
$xml2 = '
</channel>
</rss>';

?>
