<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Congressmember {

    public $id;
    public $last_name;
    public $first_name;
    public $state;
    public $district;
    public $party;
    public $is_current;
    public $is_senator;
    public $email;
    public $fax;
    public $website;
    public $photo;

    function __construct($params = array()) { 
        // set any legitimate variables found in the $params array
        $legit_params = array('id',
                              'last_name',
                              'first_name',
                              'state',
                              'district',
                              'party',
                              'is_current',
                              'is_senator',
                              'email',
                              'fax',
                              'website',
                              'photo');

        foreach ($params as $key => $val) {
            if (in_array($key, $legit_params)) {
                $this->$key = $val;
            }
        }
    }
    
    function get_firstname(){
         //return firstname
         return $this->$first_name;  
    }
    
    function get_lastname(){
         //return lastname
         return $this->$last_name;
    }
    
    function get_fullname(){
         //return fullname
         return $this->get_firstname() + ' ' + $this->get_lastname();
    }
    
    function get_state(){
         //returns congressmember state
         return $this->$state;
    }
    
    function get_district(){
         //returns congressmember district
         return $this->$district;
    }
    
    function get_party(){
         //returns congressmember party
         return $this->$party;
    }        
    
    function get_email(){
         //returns congressmember email
         return $this->$email;
    }
    
    function get_fax(){
         //returns congressmember fax
         return $this->$fax;
    }
    
    function get_website(){
         //returns congressmember website
         return $this->$website;
    }        
}

?>
