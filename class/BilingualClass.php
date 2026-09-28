<?php
class BilingualClass {
    private $language;
    
    public function __construct() {
        $this->language = 'tc';
    }
    
    public function setLanguage($language) {
        $this->language = $language;
    }
    
    public function getText($key) {
        // Load language file
        require('languages/' . $this->language . '.php');
       
        return (isset($lang[$key])) ? $lang[$key] : $key;
    }
    
    public function getTimestamp() {
        // Return current timestamp in English
        return date('Y-m-d H:i:s');
    }

    public function showAll() {
        require('languages/' . $this->language . '.php');
        print_r($lang);
    }
    
}

