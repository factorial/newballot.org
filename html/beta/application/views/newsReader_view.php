<?php

  // include the xpath file
  require_once("xpath.php");

  // read the xml source as string
  $str = file_get_contents("http://www.stateline.org/live/RSS+Feeds/details?rssNodeId=72");

  // load the string as xml object
  $xml = simplexml_load_string($str);

  // initialize the return array
  $result = array();

  // parse the xml nodes
  foreach($user_status as $key => $xpath) {
    $values = $xml->xpath("{$xpath}");
    foreach($values as $value) {
      $result[$key][] = (string)$value;
    }
  }
  echo $result['title'][0];
  echo $result['description'][0];
  echo $result['link'][0];
  echo count($result['title']);

  for ( $counter = 0; $counter <= count($result['title']); $counter += 1) {
  	echo "<br>";
  	echo "Title: ",$result['title'][$counter];
  	echo "<br>";
  	echo "Description: ",$result['description'][$counter];
  	echo "<br>";
  	echo "Link: ",$result['link'][$counter];
  	echo "<br><br>";
  }

  // print the return array
  print_r($result);
?>