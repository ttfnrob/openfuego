<?php

/**
  * The Getter object has one method:
  *
  * getItems($quantity, $hours, $scoring, $metadata)
  *
  * $quantity (int): Number of links desired. Default 20.
  * $hours (int): How far back to look for links. Default 24.
  * $scoring (bool): TRUE to employ  "freshness vs. quality" algorithm
  *   or FALSE to simply return most frequently tweeted links. Default TRUE.
  * $metadata (bool): TRUE to hydrate URLs with Embed.ly metadata.
  *   An API key must be set in config.php. Default FALSE.
 */

function default_value(&$var, $default) {
    if (empty($var)) {
        $var = $default;
    }
}

require(__DIR__ . '/openfuego/init.php');
use OpenFuego\app\Getter as Getter;
$fuego = new Getter();

$html = '<html>
  <body>
    <h3>Recent Links</h3>
    <ul id="all-links">
';

$allItems = $fuego->getItems(20, 24, FALSE, TRUE); // quantity, hours, scoring, metadata
foreach ($allItems as $thisItem) {
	$thisUrl = $thisItem['url'];
	$thisMetadata = $thisItem['metadata'];
	$thisHeadline = $thisMetadata['title']; //$thisItem['url']
	default_value($thisHeadline, $thisItem['url']);
	$thisTweetLink = $thisItem['tw_tweet_url'];
	$thisTweeter = $thisItem['tw_screen_name'];

	$html = $html.'			<li><a href=' . $thisUrl . '>' . $thisHeadline . '</a> - <a href="' . $thisTweetLink . '">Tweeted</a> by <a href="http://www.twitter.com/' . $thisTweeter . '">' . $thisTweeter . '</a></li>';
	}

$html = $html. '</ul>
  </body>
</html>';

$file = 'example.html';
file_put_contents($file, $html);

?>
