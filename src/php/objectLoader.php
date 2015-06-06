<?php

$myVar = 'hey there';

// Interacts with database `appJSON` table `data`
class ObjectLoader {
    
    // Variables
    public $pubVar = "I'm public!";
    private $priVar = "I'm private!";
    
    // Constructer: set include path; require connection strings
    public function __construct() {
        set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
        require_once("cxn.php");
    }
    
    
    public function getPub(){
        return $this->pubVar;
    }
    
    public function setPub($x){
        $this->pubVar = $x;
    }
    
    public function getObject($key){
        // code
    }
    
    public function setObject($key){
        // code
    }
    
    private function getCXN(){
    }
    
}

$ObjectLoader = new ObjectLoader;

echo $ObjectLoader->pubVar;
echo "<br>";
echo $ObjectLoader->getPub();
echo "<br>";
$ObjectLoader->setPub(3);
echo $ObjectLoader->getPub();
