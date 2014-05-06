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
$html1 = '<!doctype html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8">

<title>Orbiting Links</title>
<meta name="description" content="Automatically extracted links from the astronomers of the Twittersphere. OpenFuego for Astronomy">
<meta name="author" content="Robert Simpson">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<meta name="twitter:widgets:link-color" content="#ce5b33">

<link rel="stylesheet" href="css/base.css">
<link rel="stylesheet" href="css/themes/type_06.css">
<link rel="stylesheet" href="css/themes/color_05.css">
<link rel="stylesheet" href="css/frog.css">

<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<link rel="shortcut icon" href="images/favicons/favicon.ico">
<link rel="apple-touch-icon" href="images/favicons/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="images/favicons/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="images/favicons/apple-touch-icon-114x114.png">

<script src="js/libs/modernizr.min.js"></script>
<script src="js/libs/jquery-1.8.3.min.js"></script>
<script src="js/libs/jquery.easing.1.3.min.js"></script>
<script src="js/libs/jquery.fitvids.js"></script>
<script src="js/script.js"></script>

</head>
<body>

<script type="text/javascript">
  document.write("<div id=\'sitePreloader\'><div id=\'preloaderImage\'><img src=\'images/site_preloader.gif\' alt=\'Preloader\' /></div></div>");
</script>

<div id="menu-above-header" class="menu-navigation navbar navbar-fixed-top">
  <div class="navbar-inner ">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".above-header-nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="nav-collapse above-header-nav-collapse">
        <div class="menu-header-container">
          <ul id="menu-top-menu" class="nav nav-menu menu">
            <li class=" menu-item menu-item-type-custom menu-item-object-custom current-menu-item">
              <a href="/" title="Home">&larr; Back to Orbiting Frog</a>
            </li>
            <li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item" id="workPage">Links</li>
            <li class="menu-item menu-item-type-custom menu-item-object-custom" id="aboutPage">About</li>
            </ul>
          </div>
      </div><!-- /.nav-collapse -->
    </div> <!-- /container -->
  </div><!-- /navbar-inner -->
</div>

<div class="container">

  <header class="sixteen columns">
    <div id="logo">
      <h1 id="site-title" >Orbiting Links</h1>
      <h2 id="site-description">Automatically extracted links from the astronomers of the Twittersphere.</h2>
      <br/>
      <h2 id="site-description">Last updated at '.date('Y/m/d H:i').'</h2>
    </div>
    <hr />
  </header>

  <div id="work">

    <div class="eight columns" id="col1">';

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

  $this_html = '
  <div class="project">

    <div class="projectThumbnail">
      <svg class="thumbnailMask"></svg>
      <div class="projectThumbnailHover">
        <h4>'.$thisHeadline.'</h4>
        <h5>First tweeted by @'.$thisTweeter.'</h5>
      </div>
      <div class="thumbnailImage" style="background-image: url(\''.$thisThumb.'\')" ></div>
    </div>

    <div class="projectInfo">
      <h4>'.$thisHeadline.'</h4>
      <div class="projectNavCounter"></div>
      <div class="projectNav">
        <div class="projectNavEnlarge"><button class="enlargeButton" title="'.$thisUrl.'" onClick="window.open(\''.$thisUrl.'\', \'_blank\')">Visit URL</button></div>
        <div class="projectNavClose"><button class="closeButton">Close</button></div>
        <div class="projectNavButtons"><button class="prev"></button><button class="next"></button></div>
      </div>
      <p>
        '.$thisDescription.'
      </p>
      <ul>
        <li>First seen in a tweet by <a href="http://twitter.com/'.$thisTweeter.'" target="_blank">@'.$thisTweeter.'</a>. Subsequently shared by '.$thisCount.' other tracked sources in the past 24 hours.<br>
          <blockquote class="twitter-tweet" data-conversation="none" data-cards="hidden">
            <p>'.$thisOriginalTweet.'</p> &mdash; '.$thisTweeterDisplay.' (@'.$thisTweeter.') <a href="'.$thisTweetLink.'" data-datetime="">'.date('F j, Y').'</a>
          </blockquote>
        </li>
      </ul>
    </div>

  </div>';
  return $this_html;
}

// End of HTML file and About page content
$html2 = '
</div>

<div class="eight columns" id="col2">
</div>

</div>
<div id="about">

<div class="eight columns">

  <h3>What Is This?</h3>
  <p>
    This is an automated set of links currently being tweeted by the astronomers of Twitter. I take a small set of my favourite astro-Tweeters, and follow their tweets, and the tweets they follow too. As links are shared, I store them and keep track of how often they are retweeted or posted elsewhere. Those that rise to the top in any 24-hour period are displayed here.
  </p>

  <h4>Who Am I Tracking?</h4>
  <p>
    I\'m tracking myself (seems like a good a place to start as any!) and a bunch of my favourite go-to astronomers on Twitter. The accounts they follow are also monitored, up to about 5,000 accounts. It isn\'t necessarily those people that will rise to the top here though - but more likley the sources of the links they share. I will continulously modify the list of source accounts, to maximise the useful of this page.
  </p>

  <h4>Why Do This?</h4>
  <p>
    To find interesting stuff! The topics will vary day-to-day, and sources of interesting links should rise to the top organically. I see this as an alternative news source, delivering material aligned with the interests of my peers on Twitter. It\'s an experiment too - and a coding project I\'ve been wanting to build for a while now.
  </p>

</div>

<div class="eight columns">

  <h3>Resources Used</h3>
  <p>
    This site has built on top of several other projects, many of which I have slightly modified. The back-end is written in PHP and the front-end is HTML+JavaScript.
  </p>

  <ul class="linedList">
    <li><strong>OpenFuego:</strong> Created by <a href="https://twitter.com/andrewphelps">Andrew Phelps</a> of the <a href="http://www.niemanlab.org/">Nieman Journalism Lab<a/>, <a href="https://github.com/niemanlab/openfuego/">OpenFuego</a> is the open-source version of Fuego, a Twitter bot created to track the future-of-journalism crowd and the links they’re sharing.</li>
    <li><strong>Type &amp; Grids:</strong> You can find many amazing website templates on <a href="http://www.typeandgrids.com" target="_blank">Type &amp; Grids</a>. All of them are responsive and well-commented, and many of them are free.</li>
    <li><strong>Twitter:</strong> Microblogging site <a href="http://twitter.com">Twitter</a> is still one of my favourite things about the web, even after all these years.</li>
  </ul>

  <h4>Future Development</h4>
  <p>
    The current to-do list for this project includes an RSS feed and a Twitter account, which will provide other ways to access the same set of links. If you have ideas for how this projects should evolve, please <a href="http://about.me/robertsimpson">get in touch</a>.
  </p>

</div>

</div>

<footer class="sixteen columns">
<hr />
<ul id="footerLinks">
  <li>Tracking '.number_format($fuegoCounts['citizen_count']).' users and '.number_format($fuegoCounts['link_count']).' links</li>
  <li>Curated and maintaned by <a href="http://about.me/robertsimpson">Robert Simpson</a></li>
  <li>Engine forked from <a href="https://github.com/niemanlab/openfuego/">OpenFuego</a></li>
  <li>Design powered by <a href="http://www.typeandgrids.com" target="_blank">Type &amp; Grids</a></li>
</ul>
</footer>

</div>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</body>
</html>';

?>
