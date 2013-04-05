<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User {

    public $id;
    public $username;
    public $password;
    public $role_id;
    public $firstname;
    public $lastname;
    public $address;
    public $city;
    public $state;
    public $zip;
    public $email;
    public $district;
    public $is_verified;
    public $lastlogin;

    function __construct($params = array()) { 
        // set any legitimate variables found in the $params array
        $legit_params = array('id',
        					  'username',
        					  'password',
        					  'role_id',
                              'firstname',
                              'lastname',
                              'address',
                              'city',
                              'state',
                              'zip',
                              'email',
                              'district',
                              'is_verified',
                              'lastlogin');

        foreach ($params as $key => $val) {
            if (in_array($key, $legit_params)) {
                $this->$key = $val;
            }
        }
    }
    
    
    function get_id(){
         //return id
         return $this->$id;    
    }
    
    function get_username(){
         //return username
         return $this->$username;    
    }
    
    function get_password(){
         //return password
         return $this->$password;    
    }
    
    function get_role_id(){
         //return role_id
         return $this->$role_id;    
    }    
    
    function get_firstname(){
         //return firstname
         return $this->$firstname;  
    }
    
    function get_lastname(){
         //return lastname
         return $this->$lastname;
    }
    
    function get_address(){
         //return address
         return $this->get_address();
    }
    
    function get_city(){
         //returns city
         return $this->$city;
    }        
    
    function get_state(){
         //returns state
         return $this->$state;
    }   
    
    function get_zip(){
         //returns zip
         return $this->$zip;
    }        
    
    function get_email(){
         //returns email
         return $this->$email;
    }
    
    function get_district(){
         //returns user district
         return $this->$district;
    } 
    
    function get_is_verified(){
         //returns is_verified
         return $this->$is_verified;
    } 
    
    function get_lastlogin(){
         //returns lastlogin
         return $this->$lastlogin;
    }         
 
}

?>
