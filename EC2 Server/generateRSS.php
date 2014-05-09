<?php

date_default_timezone_set('Europe/London');

// Source and start OpenFuego
require(__DIR__ . '/openfuego/init.php');
use OpenFuego\app\Getter as Getter;
$fuego = new Getter();
$allItems = $fuego->getItems(20, 24, TRUE, TRUE); //Get up to 20 items from the past 24 hours, use frshness algorithm, use metadata
$fuegoCounts = $fuego->fuegoSize(); // Get counts of citizens and links

//Get HTML structure and other stuff tidied away in functions.php
require(__DIR__ . '/functionsRSS.php');

// Go through links and generate XML
$xml_links = "";
$count=0;
foreach ($allItems as $thisItem) {
  $count+=1;
  $this_xml = makeProjectHTML($thisItem);
  $xml_links = $xml_links.$this_xml;
}

// Set filename and contents
$filename = 'rss.xml';
$contents = $xml1.$xml_links.$xml2;

// Save HTML file to S3 bucket
echo "Saving {$filename} to S3...\n";
$result = $client->putObject(array(
    'Bucket' => $bucket,
    'Key'    => $filename,
    'Body'   => $contents,
    'ContentType' => 'text/xml'
));
echo "File saved - process complete.\n";

?>
