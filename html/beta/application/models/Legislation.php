<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Legislation {

    public $id;
    public $number;
    public $type;
    public $congress_number;
    public $title;
    public $official_title;
    public $summary;
    public $state;
    public $state_date; 
    public $last_update;

    function __construct($params = array()) { 
        // set any legitimate variables found in the $params array
        $legit_params = array('id', 
                              'number',
                              'type',
                              'congress_number',
                              'title',
                              'official_title',
                              'summary',
                              'state',
                              'state_date',
                              'last_update');

        foreach ($params as $key => $val) {
            if (in_array($key, $legit_params)) {
                $this->$key = $val;
            }
        }
    }
    
}

?>
