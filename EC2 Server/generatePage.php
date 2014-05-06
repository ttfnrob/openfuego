<?php

date_default_timezone_set('Europe/London');

// Source and start OpenFuego
require(__DIR__ . '/openfuego/init.php');
use OpenFuego\app\Getter as Getter;
$fuego = new Getter();
$allItems = $fuego->getItems(20, 24, TRUE, TRUE); //Get up to 20 items from the past 24 hours, use frshness algorithm, use metadata
$fuegoCounts = $fuego->fuegoSize(); // Get counts of citizens and links

//Get HTML structure and other stuff tidied away in functions.php
require(__DIR__ . '/functions.php');

// Go through links and generate HTML
$html_links = "";
$count=0;
foreach ($allItems as $thisItem) {
  $count+=1;
  $this_html = makeProjectHTML($thisItem);
  $html_links = $html_links.$this_html;
}

// Set filename and contents
$filename = 'index.html';
$contents = $html1.$html_links.$html2;

// Save HTML file to S3 bucket
echo "Saving {$filename} to S3...\n";
$result = $client->putObject(array(
    'Bucket' => $bucket,
    'Key'    => $filename,
    'Body'   => $contents,
    'ContentType' => 'text/html'
));
echo "File saved - process complete.\n";

?>
