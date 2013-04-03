<?php

function populate_table_legislation($congress_number) {
    // Variables
    $apiKey = 'f0f6194b1134716d5978932ce003a4b31d5ea4d9';
    $XMLURL = "http://www.opencongress.org/api/bills?congress=$congress_number&key=$apiKey";
    // *******************

    echo "*** Populating legislation table for congress # $congress_number.\n";
    $mysql = new mysqli('localhost', 'democracy', 'd3m0cr4cymy5q1', 'democracy');

    if ($mysql->connect_errno) { die('error connecting to db.'); }

    echo "First, deleting sponsorship data...";
    if (!$mysql->query('DELETE FROM sponsorship')) {
        echo "[ERROR] Couldn't do it. ". $mysql->error .". Quitting.\n";
        die();
    }
    echo " done.\n";

    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $XMLURL);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); // get the response as a string from curl_exec(), rather than echoing it
    curl_setopt($c, CURLOPT_FRESH_CONNECT, 1); // don't use a cached version of the url
    if (!$result = curl_exec($c)) { die('[ERROR] Error getting XML. Ending.'); }
    curl_close($c);

    $indexXML = DOMDocument::loadXML($result);
    $xp = new DOMXPath($indexXML);

    echo "Got bill index XML. Parsing.\n";

    $bills = $xp->query("//bill");

    foreach ($bills as $bill) {

        $billType = $bill->getAttribute('bill-type');
        $billNumber = $bill->getAttribute('number');
        $billTitle = $bill->getAttribute('title-full-common');

        $billURL = "http://www.govtrack.us/data/us/$congress_number/bills/$billType$billNumber.xml";

        $billPlainSummary = $xp->query('//plain-language-summary', $bill)->item(0)->textContent;
        $billSummary = ($billPlainSummary? $billPlainSummary : $xp->query('/bill/summary')->item(0)->textContent);
        $billSponsor = (int)$xp->query('//sponsor-id', $bill)->item(0)->textContent;
        $billCosponsorIds = $xp->query('//cosponsor/id', $bill);
        $billSubjects = $xp->query('/bill/subjects', $bill)->item(0);
        $billState = $xp->query('/bill/state', $bill)->item(0);
        
        // ** Stuff we're not using at the moment: **
        //$billTitles = $xp->query('/bill/titles')->item(0);
        //$billActions = $xp->query('/bill/actions')->item(0);
        //$billAmendments = $xp->query('/bill/amendments')->item(0);

        $billStateDate = $billState->getAttribute('datetime');
        $billState = $billState->textContent;

        // getting all cosponsor ids
        $csIds = array(); // just a temp array
        $billCosponsors = $xp->query('./cosponsor', $billCosponsors);
        foreach ($billCosponsors as $cs) {
            $csIds[] = $cs->getAttribute('id');
        }
        $billCosponsors = $csIds;

        // gettings all keywords
        $billKeywords = array();
        $billSubjects = $xp->query('./term', $billSubjects);
        foreach ($billSubjects as $kw) {
            $billKeywords[] = $kw->getAttribute('name');
        }


        $billType = $mysql->real_escape_string($billType);
        $billTitle = $mysql->real_escape_string($billTitle);
        $billSummary = $mysql->real_escape_string($billSummary);
        $billState = $mysql->real_escape_string($billState);
        $billStateDate = $mysql->real_escape_string($billStateDate);

        $foundBill = true;
        $q = "INSERT INTO legislation (
            congress_number, 
            number, 
            type, 
            title, 
            summary,
            state, 
            state_date) VALUES (
                $congress_number, 
                $billNumber, 
                '$billType', 
                '$billTitle', 
                '$billSummary', 
                '$billState', 
                '$billStateDate'
                ) ON DUPLICATE KEY UPDATE title='$billTitle',
                                          summary='$billSummary',
                                          state='$billState',
                                          state_date = '$billStateDate'";

        echo "Inserting (or updating) row for $billType $billNumber\n";
        if (!$mysql->query($q)) { echo "[ERROR] ". $mysql->error .". Query was: $q"; }
                
        $billId = $mysql->query("SELECT id FROM legislation WHERE congress_number = $congress_number 
                                 AND number = $billNumber 
                                 AND type = '$billType'")->fetch_object()->id;

        echo "Now adding sponsorship data.\n";
        $insertq = "INSERT INTO sponsorship
            (congressmember_govtrack_id,
            article_id,
            is_primary_sponsor) VALUES 
            ($billSponsor, 
             $billId,
             TRUE) ";
        
        foreach ($billCosponsors as &$cs) {
            $insertq .= ", ($cs, $billId, FALSE) ";
        }

        if (!$mysql->query($insertq)) { echo '[ERROR] Error adding sponsorship data: '. $mysql->error .". Query was $insertq\n"; }
               
    }
    
    echo "Finished updating legislation table.\n";


}

?>
