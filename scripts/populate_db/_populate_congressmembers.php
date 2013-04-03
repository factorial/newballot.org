<?php

function populate_table_congressmembers() {
    // Variables
    $sunlightAPI_key = '882d9fa7e1cf63bfb17fb3a9c6a3df19';
    $sunlightAPI_url = 'http://services.sunlightlabs.com/api';
    $sunlightAPI_function = 'legislators.getList.xml';
    // *******************

    echo "*** Populating congressmembers table.\n";
    $mysql = new mysqli('localhost', 'democracy', 'd3m0cr4cymy5q1', 'democracy');

    if ($mysql->connect_errno) { die('error connecting to db.'); }

    $XMLURL = "$sunlightAPI_url/$sunlightAPI_function?apikey=$sunlightAPI_key";
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $XMLURL);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); // get the response as a string from curl_exec(), rather than echoing it
    curl_setopt($c, CURLOPT_FRESH_CONNECT, 1); // don't use a cached version of the url
    if (!$result = curl_exec($c)) { die('[ERROR] Error getting legislator XML. Ending.'); }
    curl_close($c);

    $congressXMLDoc = DOMDocument::loadXML($result);
    $xp = new DOMXPath($congressXMLDoc);

    echo "Got legislator XML. Updating existing congressmember data.\n";

    $congressMembers = $xp->query("//legislator");

    foreach ($congressMembers as $c) {
      $updateq = "UPDATE congressmembers SET ";
      $insertq = "INSERT INTO congressmembers (govtrack_id, last_name, first_name, state, district, party, is_current, is_senator, email, fax, website) VALUES ";

      $govtrack_id = (int)$xp->query('./govtrack_id', $c)->item(0)->textContent;
      $last_name = $mysql->real_escape_string($xp->query('./lastname', $c)->item(0)->textContent);
      $first_name = $mysql->real_escape_string($xp->query('./firstname', $c)->item(0)->textContent);
      $state = $mysql->real_escape_string($xp->query('./state', $c)->item(0)->textContent);
      $district = $mysql->real_escape_string(strtolower($xp->query('./district', $c)->item(0)->textContent));
      $party = $mysql->real_escape_string($xp->query('./party', $c)->item(0)->textContent);
      $is_current = 'true';
      $is_senator = (($xp->query('./title', $c)->item(0)->textContent) == 'Sen')? 'true' : 'false';
      $email = "'". $mysql->real_escape_string($xp->query('./email', $c)->item(0)->textContent) ."'";
      $fax = "'". $mysql->real_escape_string( str_replace(array('-', ' ', '(', ')'), '', $xp->query('./fax', $c)->item(0)->textContent)) ."'";
      $website = "'". $mysql->real_escape_string($xp->query('./website', $c)->item(0)->textContent) ."'";
      if ($fax == '') { $fax = 'null'; }
      if ($email == '') { $email = 'null'; }
      if ($website == '') { $website = 'null'; }

      // note: the 'apostrophes' around these values are in the $variable so, if they're empty,
      // we can just put NULL in the $variable and still use it in the SQL below.
      $updateq .= "govtrack_id=$govtrack_id, ".
            "last_name='$last_name', ".
            "first_name='$first_name', ".
            "party='$party', ".
            "is_current=$is_current, ".
            "is_senator=$is_senator, ".
            "email=$email, ".
            "fax=$fax, ".
            "website=$website ".
            "WHERE state='$state' AND district='$district'";

      $insertq .= "($govtrack_id, ".
            "'$last_name', ".
            "'$first_name', ".
            "'$state', ".
            "'$district', ".
            "'$party', ".
            "$is_current, ".
            "$is_senator, ".
            "$email, ".
            "$fax, ".
            "$website )";

      echo "Updating congress member for $state $district to $first_name $last_name ...";
      if (!$mysql->query($updateq)) { echo "\n[ERROR] ". $mysql->error .". SQL query was: $updateq\n"; }
      else { 
        if (strpos($mysql->info, 'Rows matched: 0') !== false) {
          echo "\n[NOTICE] Update matched 0 rows, inserting new data... ";
          if (!$mysql->query($insertq)) { echo "\n[ERROR] ". $mysql->error .". SQL query was: $insertq\n"; }
        }
        echo "Updated.\n"; 
      }
    }

    echo "I'm finished populating congresmembers table.\n";
    return;
}



