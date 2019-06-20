<?php 
class DmWebook
{
  protected $config = array();

  public function __construct () {
    $this->config = json_decode(file_get_contents('./config.json'));
  }
  
  public function send($config) {
    $url = $config->url;
    $data = array('key1' => 'value1', 'key2' => 'value2');

    var_dump($config);

    // use key 'http' even if you send the request to https://...
    $options = array(
      'http' => array(
        'header'  => "Content-type: application/json",
        'method'  => $config->method,
        'content' => json_encode($data)
      )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    var_dump($http_response_header);
    if ($result === FALSE) { 
      $result.="False";
    }
    //return $result.$http_response_header;
    return true;
  }

  public function getSingleConfig($index) {
    if(isset($index) && gettype($index) == "integer" && $index > -1 && isset($this->config[$index])) {
      return $this->config[$index];
    }
    else {
      return false;
    }
  }

  public function getAllConfig() {
    return $this->config;
  }

  public function log($str) {

    $log  = "[".date("j-n-Y G:i")."] ".$str."".PHP_EOL;
    
    echo $log;

    if(!file_exists("./logs")) {
      mkdir("./logs");
      chmod("./logs", 0750);
    }

    file_put_contents('logs/'.date("j-n-Y").'.log', $log, FILE_APPEND);
  }

  public function isBlacklisted($config, $input) {
    $isBlacklisted = false;
    foreach($input as $word) {
      $isBlacklisted &= strpos($config->blacklist, $input);
    }
    return $isBlacklisted;
  }

  public function isHourlLimitExceded($config) {
    return false;
  }

  public function checkKeywords($config, $input) {
    $this->log("Keywords: ".$config->keywords);
    if(!isset($config->keywords)) {
      return false;
    }
    $words = explode(",", $config->keywords);

    foreach($words as $word) {
      $word = trim($word);
      
      if(strpos($input, $word) >= 0 && gettype(strpos($input, $word)) == "integer") {
        $this->waitlistMessage($config,$input);
        
      } else {
        $this->log("Key not found. Skipping...");
      }
    }
  }

  public function waitlistMessage($config,$input) {
    $this->log(" Waitlisting message: ".$input);
    return true;
  }
}