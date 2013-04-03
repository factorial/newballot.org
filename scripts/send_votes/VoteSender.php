<?php

class VoteSender {
    private static $ownFaxNumber = 7707835017;
    private static $ownEmail = 'no-reply@newballot.org';
    private $messageHeader;
    private $messageFooter;
    private $mailHeaders;

    public function __construct() {
        $this->messageHeader = file_get_contents('messageHeader.html', FILE_USE_INCLUDE_PATH);
        $this->messageFooter = file_get_contents('messageFooter.html', FILE_USE_INCLUDE_PATH);
        
        $this->mailHeaders = 'From: NewBallot.org <'. self::$ownEmail .'>' . "\r\n".
                             'MIME-Version: 1.0' . "\r\n".
                             'Content-type: text/html; charset=iso-8859-1' . "\r\n".
                             'Content-Transfer-Encoding: base64' . "\r\n";
                             
        $this->mailParams = '-r '. self::$ownEmail;
    }

    public function sendVotes() {
        // might need to do senators first then reps instead of all at once
        // so we can specifically send HR bills to reps and S bills to senators.
       
        echo "Preparing to send votes.\n";
        
        $mysql = new mysqli('localhost', 'democracy', 'd3m0cr4cymy5q1', 'democracy');
        $mysql_loop = new mysqli('localhost', 'democracy', 'd3m0cr4cymy5q1', 'democracy');
        
        if ($mysql->connect_errno || $mysql_loop->connect_errno) { die('error connecting to db.'); }

        $q = 'SELECT first_name, last_name, state, district, fax, email, is_senator '.
             'FROM congressmembers';

        $result = $mysql->query($q);
        while ($row = $result->fetch_assoc()) {
            // looping through every congressmember we should fax

            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $state = $row['state'];
            $district = $row['district'];
            $fax = $row['fax'];
            $email = $row['email'];
            $is_senator = $row['is_senator'];
            $msgContent = '';

            echo "Finding votes to send to $first_name $last_name of $state.\n";

            $districtClause = (!$is_senator? "AND users.district = $district " : '');
            $q = "SELECT vote, legislation_id FROM votes ".
                "LEFT JOIN users ON votes.user_id = users.id ".
                "WHERE users.state = '$state' ".
                $districtClause .
                "ORDER BY legislation_id DESC";
            
            $result_loop = $mysql_loop->query($q); 

            $voteTallies = array();
            while ($row_loop = $result_loop->fetch_array() ) {
                $vote = (bool)$row_loop[0];
                $billId = (int)$row_loop[1];

                if ($vote) { @$voteTallies[$billId]['yea']++; }
                else { @$voteTallies[$billId]['nay']++; }
            }


            if (!empty($voteTallies)) {
                // at least one bill was counted, so send the report to this congressmember
                echo "Found some votes to count.\n";

                foreach ($voteTallies as $billId => &$v) {

                    $apiKey = 'f0f6194b1134716d5978932ce003a4b31d5ea4d9';
                    $apiUrl = "http://www.opencongress.org/api/bills?id=$billId&key=$apiKey";
      
                    $retries = 3;
                    for ($i=0, $success=false; $i < $retries && !$success; $i++) { 
                        // try three times before giving up on a bill
                        echo "Calling OpenCongress API for bill $billId, try ". ($i+1) ."... ";

                        $c = curl_init();
                        curl_setopt($c, CURLOPT_URL, $apiUrl);
                        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($c, CURLOPT_FRESH_CONNECT, 1);
                        $curl_result = curl_exec($c);
                        curl_close($c);
    
                        if (!$curl_result) { 
                            echo "FAILED! Couldnt fetch URL.\n";
                            continue;
                        }
    
                        $xml = DOMDocument::loadXML($curl_result);
                        $xp = new DOMXPath($xml);
    
                        $bill = $xp->query("//bill")->item(0);
                        if (!$bill) {
                            echo "FAILED! No <bill> in XML.\n";
                            continue;
                        }
    
                        echo "got XML... ";

                        $billType = $xp->query('./bill-type', $bill)->item(0)->textContent;
                        $billNumber = $xp->query('./number', $bill)->item(0)->textContent;
                        $billTitle = $xp->query('./title-full-common', $bill)->item(0)->textContent;
                        echo "got data: $billType-$billNumber\n";
                        $success = true;
                    }
                    if (!$success) { 
                        echo "Tries exceeded for $billId, moving on to next bill.\n";
                        continue;
                    }

                    // the following if statement ensures senators dont get votes
                    // about house resolutions and reps don't get senate resolutions
                    if ( ($billType != 'hr' && $billType != 'sr') ||
                         ($billType == 'hr' && !$is_senator) ||
                         ($billType == 'sr' && $is_senator) ) {
                            $article_title = $billTitle;
                            $article = strtoupper($billType) ."-". $billNumber;
                            $yeaCount = (isset($v['yea'])? $v['yea'] : 0);
                            $nayCount = (isset($v['nay'])? $v['nay'] : 0);
                            $msgContent .= "<tr><td>$article</td>".
                                           "<td>$article_title</td>".
                                           "<td>$yeaCount</td>".
                                           "<td>$nayCount</td></tr>";
                    }
                }

                if (!$msgContent) {
                    echo "There are votes to count, but none this legislator should be notified of. Moving on.\n";
                } else {

                    $name = ($is_senator? 'Senator' : 'Representative') ." ". $last_name;
                    $msgContent = str_replace('%NAME%', $name, $this->messageHeader, $tmp=1).
                            "<table><tr><td width=75>Article No</td><td>Title</td><td>Yeas</td><td>Nays</td></tr>".
                            $msgContent . 
                            "</table>".
                            $this->messageFooter;

                    $constituency = $state . ($is_senator? '' : " district $district");

                    $targetEmail = ($email? $email : "$fax@metrofax.com");

                    echo "Sending email for $constituency to $targetEmail now... ";
/*
                    // HERE IS THE DEBUG EMAIL:
                    // ******************************************************************************

                    // right here is the debug message to nb's fax number 
                    mail(self::$ownFaxNumber .'@metrofax.com, john@newballot.org', 
                    //mail('john@newballot.org', 
                         "NewBallot report for $constituency",
                         rtrim(chunk_split(base64_encode("This msg would be sent to $targetEmail:<br>". $msgContent))),
                         $this->mailHeaders,
                         $this->mailParams);
*/
                    // HERE IS THE REAL DEAL!
                    // ******************************************************************************
                    mail($targetEmail .", john@newballot.org",
                    "NewBallot report for $constituency", 
                    rtrim(chunk_split(base64_encode($msgContent))),
                    $this->mailHeaders,
                    $this->mailParams);
                    // ******************************************************************************
                    echo "Sent.\n";
                }
            } else {
                echo "Found no votes to count.\n";
            }


        } // end of congressmember loop
        $mysql->close();
        $mysql_loop->close();
    }
}

?>
