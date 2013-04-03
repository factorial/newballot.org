<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 include('Legislation.php');
 include('Congressmember.php');
 include('User.php');


class Site_model extends Model {
    private $opencongress_apikey = 'f0f6194b1134716d5978932ce003a4b31d5ea4d9';

    function __construct() { parent::Model(); }


    function checkEmail($email)
    {
        // returns 1 (TRUE) if a match is found in the database (ie, not available for use)
        $sql = "SELECT email
                  FROM users
                 WHERE email = '$email'";

        $query = $this->db->query($sql);

        return $query->num_rows(); // should be 0 or 1
    }

    function checkUsername($username)
    {
        // returns 1 (TRUE) if a match is found in the database (ie, not available for use)
        $sql = "SELECT username
                  FROM users
                 WHERE username = '{$username}'
        ";

        $query = $this->db->query($sql);
        return $query->num_rows(); // should be 0 (username is good) or 1 (username is taken)
    }



    function get_bills_in_media($congress_number, $truncateSummary = true)
    {
        $logged_in = $this->erkanaauth->try_session_login();
        $sumlen = 600;  // number of chars to aim for when truncating summary

        $apiUrl = 'http://www.opencongress.org/api/bills_in_the_news_this_week?key='. $this->opencongress_apikey;
        
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $apiUrl);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_FRESH_CONNECT, 1);
        $result = curl_exec($c);
        curl_close($c);
        
        if (!$result) { return array(); }
    
        $xml = DOMDocument::loadXML($result);
        $xp = new DOMXPath($xml);
    
        $bills = $xp->query("//bill");
        $result = array();

        foreach ($bills as $bill) {
            $billId = $xp->query('./id', $bill)->item(0)->textContent;
            $billType = strtoupper($xp->query('./bill-type', $bill)->item(0)->textContent);
            $billNumber = $xp->query('./number', $bill)->item(0)->textContent;
            $billPlainSummary = $xp->query('./plain-language-summary', $bill)->item(0)->textContent;
            $billSummary = ($billPlainSummary? $billPlainSummary : $xp->query('./summary', $bill)->item(0)->textContent);
            $billSummary = nl2br($billSummary);
            $billTitle = "$billType.$billNumber";
            $billVote = null;
            $billUrl = "http://www.opencongress.org/bill/$congress_number-$billType$billNumber/show";

            if ($truncateSummary) {
                $strlen = strlen($billSummary);
                if ($sumlen < $strlen) { $billSummary = substr($billSummary, 0, strpos($billSummary, ' ', $sumlen)); }
            }
            
            if ($logged_in) {
                $user_id = $this->erkanaauth->getField('id');
                $sql = "SELECT vote FROM votes WHERE legislation_id = $billId AND user_id = $user_id";
            
                $query = $this->db->query($sql);
   
                if ($query->num_rows()) {
                    foreach ($query->result_array() as $row) {
                        if (!is_null($row['vote'])) { $billVote = (bool)$row['vote']; }
                    }
                }
            }
            
            $result[] = array(
                    'id' => $billId,
                    'type' => $billType,
                    'number' => $billNumber,
                    'summary' => $billSummary,
                    'title' => $billTitle,
                    'url' => $billUrl,
                    'vote' => $billVote
                );
        }

        return $result;

    }
    
    function get_bill($congress_number, $billType, $billNumber) {
        $logged_in = $this->erkanaauth->try_session_login();
        $sumlen = 600;  // number of chars to aim for when truncating summary

        $billType = strtolower($billType);
        $apiUrl = "http://www.opencongress.org/api/bills?congress=$congress_number&type=$billType&number=$billNumber&key=". $this->opencongress_apikey;
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $apiUrl);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_FRESH_CONNECT, 1);
        $result = curl_exec($c);
        curl_close($c);
        
        if (!$result) { return false; }
        
        $xml = DOMDocument::loadXML($result);
        $xp = new DOMXPath($xml);
    
        $bill = $xp->query("//bill")->item(0);
        if (!$bill) { return false; }
        $result = array();

        $billId = $xp->query('./id', $bill)->item(0)->textContent;
        $billType = strtoupper($xp->query('./bill-type', $bill)->item(0)->textContent);
        $billNumber = $xp->query('./number', $bill)->item(0)->textContent;
        $billPlainSummary = $xp->query('./plain-language-summary', $bill)->item(0)->textContent;
        $billSummary = ($billPlainSummary? $billPlainSummary : $xp->query('./summary', $bill)->item(0)->textContent);
        $billSummary = nl2br($billSummary);
        $billTitle = $xp->query('./title-full-common', $bill)->item(0)->textContent;
        $billVote = null;
        $billUrl = "http://www.opencongress.org/bill/$congress_number-$billType$billNumber/show";

        if ($logged_in) {
            $user_id = $this->erkanaauth->getField('id');
            $sql = "SELECT vote FROM votes WHERE legislation_id = $billId AND user_id = $user_id";
        
            $query = $this->db->query($sql);

            if ($query->num_rows()) {
                foreach ($query->result_array() as $row) {
                    if (!is_null($row['vote'])) { $billVote = (bool)$row['vote']; }
                }
            }
        }
        
        $result = array(
                'id' => $billId,
                'type' => $billType,
                'number' => $billNumber,
                'summary' => $billSummary,
                'title' => $billTitle,
                'url' => $billUrl,
                'vote' => $billVote
            );

        return $result;
    }

    function get_legislation_comments($leg_id) {
        // gets all the comments related to a legislation of id $leg_id

        $leg_id = (int)$leg_id;

        $q = 'SELECT users.username, comments.score, comments.comment, votes.vote '.
             'FROM comments '.
             'INNER JOIN users ON users.id = comments.user_id '.
             'INNER JOIN votes ON votes.user_id = users.id AND votes.legislation_id = comments.legislation_id '.
             "WHERE comments.legislation_id = $leg_id ".
             'ORDER BY votes.vote';
       
        $query = $this->db->query($q);

        $retval = array('yea' => array(), 'nay' => array());
        foreach ($query->result_array() as $row)
        {
            $voteStr = ($row['vote']? 'yea' : 'nay');
            $retval[$voteStr][] = $row;
        }

        return $retval;
    }

    // get_news() - this needs to be a db call, filler for now
    function get_news()
    {

        $user_status = array( 'title' => '../rss/channel/item/title',
		                      'description' => '../rss/channel/item/description',
		                      'link' => '../rss/channel/item/link');

		// read the xml source as string
		  $str = file_get_contents("http://www.stateline.org/live/RSS+Feeds/details?rssNodeId=87");

		  // load the string as xml object
		  $xml = simplexml_load_string($str);

		  // initialize the return array
		  $result = array();

		  // parse the xml nodes
		  foreach($user_status as $key => $xpath) {
		    $values = $xml->xpath("{$xpath}");
		    foreach($values as $value) {
		   	$value = str_replace("<p>","", $value );
		    $value = str_replace("</p>","", $value );
		    $result[$key][] = (string)$value;
		    }
          }

        return $result;
    }


    // ---- page methods ----


    // string getDefaultJsPostLoadUrls()
    function getDefaultJsPostLoadUrls()
    {
        return $this->config->item('default_js_post_load_urls');
    }



    // string getDefaultJsPreLoadUrls()
    function getDefaultJsPreLoadUrls()
    {
        return $this->config->item('default_js_pre_load_urls');
    }



    // string getDefaultStyleUrls()
    function getDefaultStyleUrls()
    {
        return $this->config->item('default_style_urls');
    }



    // string getDoctype()
    function getDoctype()
    {
        return $this->config->item('site_doctype');
    }



    // string getFavicon(string)
    function getFavicon($url = '')
    {
        if ($url === '') { $url = $this->config->item('favicon_url'); }
        return "<link rel='shortcut icon' href='{$url}'>";
    }



    // getGoogleAnalyticsTracking()
    function getGoogleAnalyticsTracking()
    {
        return <<<HTMLEND
        <script type="text/javascript">
        var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
        document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
        </script>
        <script type="text/javascript">
        try {
        var pageTracker = _gat._getTracker("UA-11712213-1");
        pageTracker._setDomainName(".newballot.org");
        pageTracker._trackPageview();
        } catch(err) {}</script>

HTMLEND;
    }



    function getHeadData(&$dataArray) {
        $dataArray['doctype'] = $this->getDoctype();
        $dataArray['favicon'] = $this->getFavicon();
        $dataArray['page_title'] = $this->getSiteTitle();

        $dataArray['logged_in'] = $this->erkanaauth->try_session_login();

        return $dataArray;
    }



    // string getJavascript(string)
    function getJavascript($urls = array())
    {
        $html = '';

        $count = count($urls);
        for ($i = 0; $i < $count; ++$i)
        {
            $html .= "<script src='{$urls[$i]}'></script>";
        }

        return $html;
    }



    // string getSiteTitle()
    function getSiteTitle()
    {
        return $this->config->item('site_title');
    }



    // string getStyleSheets(array)
    function getStyleSheets($urls = array())
    {
        $html = '';

        $count = count($urls);
        for ($i = 0; $i < $count; $i++)
        {
            $html .= "<link rel='stylesheet' type='text/css' href='{$urls[$i]}'>";
        }

        return $html;
    }



    // string getYUIPath()
    function getYUIPath()
    {
        return $this->config->item('yui_path');
    }



    //-----------------------------------------
    // Legislation and Congressmember functions
    //-----------------------------------------

    // string getCongressmember($data)
    // $data can contain: state, district_id, party_id, is_current, is_senator, id
    function get_congressmembers($data)
    {
        $where = '';
        $and = '';

        if (isset($data['state']))
        {
          $state = strtolower($data['state']);
          $where = " LOWER(state) = '". strtolower(substr($data['state'],0,2)) ."'";
          $and = ' AND ';
        }
        if (isset($data['district']))
        {
            // Note that if district is passed in, we still get senators!
            $where .= "$and (district = ". (int)$data['district'] ." OR is_senator = TRUE)";
            $and = ' AND ';
        }
        if (isset($data['party']))
        {
            $where .= "$and LOWER(party) = ". strtolower(substr($data['party'],0,1)) ." ";
            $and = ' AND ';
        }
        if (isset($data['is_current']))
        {
            $where .= "$and is_current = ". ($data['is_current']? 'TRUE' : 'FALSE');
            $and = ' AND ';
        }
        if (isset ($data['is_senator']))
        {
            $where .= "$and is_senator = ". ($data['is_senator']? 'TRUE' : 'FALSE');
            $and = ' AND ';
        }
        if (isset ($data['id']))
        {
            $where .= "$and id = ". (int)$data['$id']; 
            $and = ' AND ';
        }

        if (!$and) { return false; }

        $sql = "SELECT * FROM congressmembers WHERE $where ";

        $query = $this->db->query($sql);

        if ($query->num_rows())
        {
            $recordset = array();
            foreach($query->result_array() as $row)
            {
                $recordset[] = new Congressmember($row);
            }
            return $recordset;
        }
        return FALSE;
    }


    function login() {
        $user_id = (int)$this->erkanaauth->getField('id');
        $q = "UPDATE users SET lastlogin = '". date("Y-m-d H:i:s") ."' WHERE id = $user_id";
        return $this->db->query($q);
    }

    function save_feedback($feedback) {
        $feedbackEmail = 'feedback@newballot.org';
        $mailHeaders = 'From: NewBallot feedback <'. $feedbackEmail .'>' . "\r\n".
                             'MIME-Version: 1.0' . "\r\n".
                             'Content-type: text/html; charset=iso-8859-1' . "\r\n".
                             'Content-Transfer-Encoding: base64' . "\r\n";
        $mailParams = '-r '. $feedbackEmail;

        $logged_in = $this->erkanaauth->try_session_login();
        $user_id = ($logged_in? (int)$this->erkanaauth->getField('id') : 'NULL');

        $feedback = mysql_real_escape_string($feedback);
        $q = "INSERT INTO feedback (user_id, feedback_text) VALUES ( $user_id, '$feedback' )";
        
        $this->db->query($q);
        
        mail($feedbackEmail,
             "NewBallot Feedback",
             rtrim(chunk_split(base64_encode("From user id $user_id:\n$feedback"))),
             $mailHeaders,
             $mailParams);

        return;
    }

    function search_legislation($terms, $congress_number, $truncateSummary = true, $page = 1) {
        $terms = urlencode($terms);
        $page = (int)$page;

        $logged_in = $this->erkanaauth->try_session_login();
        $sumlen = 600;  // number of chars to aim for when truncating summary

        $apiUrl = "http://www.opencongress.org/api/bills_by_query?congress=$congress_number&q=$terms&page=$page&key=". $this->opencongress_apikey;
        
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $apiUrl);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_FRESH_CONNECT, 1);
        $result = curl_exec($c);
        curl_close($c);
        
        if (!$result) { return array(); }
    
        $xml = DOMDocument::loadXML($result);
        $xp = new DOMXPath($xml);
    
        $bills = $xp->query("//bill");
        $result = array();

        foreach ($bills as $bill) {
            $billId = $xp->query('./id', $bill)->item(0)->textContent;
            $billType = strtoupper($xp->query('./bill-type', $bill)->item(0)->textContent);
            $billNumber = $xp->query('./number', $bill)->item(0)->textContent;
            $billPlainSummary = $xp->query('./plain-language-summary', $bill)->item(0)->textContent;
            $billSummary = ($billPlainSummary? $billPlainSummary : $xp->query('./summary', $bill)->item(0)->textContent);
            $billSummary = nl2br($billSummary);
            $billTitle = "$billType.$billNumber";
            $billVote = null;
            $billUrl = "http://www.opencongress.org/bill/$congress_number-$billType$billNumber/show";

            if ($truncateSummary) {
                $strlen = strlen($billSummary);
                if ($sumlen < $strlen) { $billSummary = substr($billSummary, 0, strpos($billSummary, ' ', $sumlen)); }
            }
            
            if ($logged_in) {
                $user_id = $this->erkanaauth->getField('id');
                $sql = "SELECT vote FROM votes WHERE legislation_id = $billId AND user_id = $user_id";
            
                $query = $this->db->query($sql);
   
                if ($query->num_rows()) {
                    foreach ($query->result_array() as $row) {
                        if (!is_null($row['vote'])) { $billVote = (bool)$row['vote']; }
                    }
                }
            }
            
            $result[] = array(
                    'id' => $billId,
                    'type' => $billType,
                    'number' => $billNumber,
                    'summary' => $billSummary,
                    'title' => $billTitle,
                    'url' => $billUrl,
                    'vote' => $billVote
                );
        }

        return $result;

    }

    //-----------------------------------------
    // New User & User functions
    //-----------------------------------------

    //returns user info
    function get_user($id, $email)
    {
        //need either and id or an email
        if (!$id && !$email) { return false; }

        $where = '';

        //first use email else use id
        if ($email) { $where = 'WHERE email = '. $email; } 
        else { $where = 'WHERE id = '. $id; }

        //returns user by id or email
        $sql = "SELECT * FROM users ". $where;

        $query = $this->db->query($sql);

        if ($query->num_rows())
        {
            return new User($query->row());
        }
        return FALSE;
    }

    function update_user($data, $id=false)
    {
         //inserts or (if $id is provided) updates a users info
         //makes use of Code Igniter Active Record Class
        if (!is_array($data)) { return false; }

        $updatedAddress = false;

        //if the users address was set, make sure the district is updated too
        if ( isset($data['address']) || isset($data['city']) || isset($data['state']) || isset($data['zip']) )
        {
            $data['is_verified'] = false;
            $updatedAddress = true;
        }
        
        //foreach ($data as &$val) { $val = mysql_real_escape_string($val); }
        
        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('users', $data);
        } else {
            $data['creation_date'] = date('Y-m-d H:i:s');
            $this->db->insert('users', $data);
            $id = $this->db->insert_id();
        }

        if ($updatedAddress) {
            $this->update_user_district($id);
        }
    }

    function update_user_district($id)
    {
        //updates or sets the district of the user with id = $id

        $this->db->select('address, city, state, zip');
        $this->db->where('id', $id);
        $query = $this->db->get('users', 1);
        $result =  $query->result_array();
        $row = $result[0];
        
        $address = str_replace(' ', '+', implode(',', $row));

        $url = 'http://www.govtrack.us/perl/district-lookup.cgi?address='. $address;

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); // get the response as a string
        curl_setopt($c, CURLOPT_FRESH_CONNECT, 1); // don't use a cached version of the url
        $result = curl_exec($c);
        curl_close($c);
        @$doc = DOMDocument::loadXML($result);
        if (!$doc) { 
            // couldn't get district info, setting to NULL
            $district_num = null;
        } else {
            $districts = $doc->getElementsByTagName('district');
            $district_num = $districts->item(0)->textContent;
        }

        $this->db->where('id', $id);
        $this->db->update('users', array('district' => $district_num));

        return $district_num;
	}

    function vote($userId, $billId, $vote) {
        $billId = (int)$billId;
        $vote = ($vote? 'TRUE' : 'FALSE');
        $datetime = date('Y-m-d H:i:s');

        $q = "INSERT INTO votes (user_id, legislation_id, vote, cur_datetime) 
              VALUES ( $userId, $billId, $vote, '$datetime') 
              ON DUPLICATE KEY UPDATE vote = $vote";

        return $this->db->query($q);
    }
}
