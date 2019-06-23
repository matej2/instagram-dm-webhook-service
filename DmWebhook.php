<?php 
require __DIR__.'/vendor/autoload.php';

class DmWebook
{
  protected $config = array();

  public function __construct () {
    $this->config = json_decode(file_get_contents('./config.json'));
    $this->webhooks = $this->getAllConfig()->webhooks;
    $this->user = $this->getAllConfig()->user;
    $this->settings = $this->getAllConfig()->settings;

    if($this->settings->debug == false) {
      $this->ig = new \InstagramAPI\Instagram();
      $this->ig->login($this->user->username, $this->user->pass);
    }
  }
  
  public function send($config, $message) {
    $url = $config->url;


    // use key 'http' even if you send the request to https://...
    $options = array(
      'http' => array(
        'header'  => "Content-type: application/json",
        'method'  => $config->method,
        'content' => json_encode($message)
      )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return $http_response_header;
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

  public function getWebhooks() {
    return $this->webhooks;
  }

  public function getUser() {
    return $this->user;
  }

  public function getSettings() {
    return $this->settings;
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

  public function processMessage($config, $input) {
    if(!isset($config->keywords)) {
      echo "false;";
      return false;
    }
    $words = explode(",", $config->keywords);

    foreach($words as $word) {
      $word = trim($word);
      
      if(isset($input["message"]) && strpos($input["message"], $word) != false) {
        $this->log("Found match for ".$input["message"].", waitlisting...");
        return $this->waitlistMessage($config,$input);
      } else {
        return false;
      }
    }
  }

  public function waitlistMessage($config,$message) {
    //$this->log("Waitlisting message: ".$input["message"]);
    $response = $this->send($config, $message);
    if($response)
    return true;
  }

  public function getLastDM() {

    $direct = $this->ig->direct->getInbox();

    /*
    TODO: unseen message check

    if(!$this->ig->direct->isUnseenCount()) {
      return false;
    }
    */
    $threads = $direct->getInbox()->getThreads();
    $inbox = array();
    $msg = array();

    foreach($threads as $thread) {
      $threadItems = $thread->getItems();
      foreach($threadItems as $threadItem) {
        if ($threadItem->getText() !== null) {
          // TODO: add profile id
          $msg = array(
            "message" => $threadItem->getText(),
            "userId" => $threadItem->getUserId(),
            "timestamp" => $threadItem->getTimestamp()
          );
          array_push($inbox, $msg);
        }
      }
    }
    return $inbox;
  }
}