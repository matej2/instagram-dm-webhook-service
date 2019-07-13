<?php 
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/DmWebhookMockData.php';
require __DIR__.'/Logger.php';

class DmWebook
{
  protected $config = array();

  public function __construct () {
    $this->logger = new Logger();

    $this->config = json_decode(file_get_contents('./config.json'));
    $this->webhooks = $this->getAllConfig()->webhooks;
    $this->user = $this->getAllConfig()->user;
    $this->settings = $this->getAllConfig()->settings;

    if($this->settings->debug == false) {
      $this->ig = new \InstagramAPI\Instagram();
      $this->ig->login($this->user->username, $this->user->pass);
    }
  }
  
  public function sendWebhook($config, $message, $callback = null) {
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

    $path = explode(".",$config->path);
    $whResponse = json_decode($result, true);

    $val = $this->arrayPath($whResponse, $path);
    
    if($callback) {
      $callback();
    }
    return array(
      "http_response_header" => $http_response_header,
      "result" => $val
    );
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
      return false;
    }
    $words = explode(",", $config->keywords);

    foreach($words as $word) {
      $word = trim($word);
      
      if(isset($input["message"]) && strpos($input["message"], $word) != false) {
        $this->logger->log("Found match for ".$input["message"].", waitlisting...");
        //return $this->waitlistMessage($config,$input);
        $response = $this->sendWebhook($config, $input);

        $this->logger->log("Response from chat bot: ".$response["result"]);

        if($this->checkWebhookResponse($config, $response)) {
          $this->sendWebhookReply($config, $input);
        }
      }
    }
  }

  public function waitlistMessage($config,$message) {
    //$this->log("Waitlisting message: ".$input["message"]);
    $response = $this->sendWebhook($config, $message);
    if($response)
    return true;
  }

  public function getLastDM() {
    if($this->getSettings()->debug == true) {
      return getMockMessages();
    } else {
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
  public function checkWebhookResponse($config, $response) {
    if(strpos($response["http_response_header"][0], $config->method)) {
      $this->logger->log( "Reponse status does not match target status from config. Expected " + $config->returnStatus + " but got " + $response[0]);
      return false;
    }
    if(!$response["result"]) {
      $this->logger->log("Error: Webhook response is null. Path: ".$config->path);
      return false;
    }
    return true;
  }
  public function sendWebhookReply($config, $input) {
    $reply = "Hello world";
    
    if(!$this->settings->debug)
      $this->ig->direct->sendWebhookText($input["userId"], $reply);
    else
      $this->logger->log("Test: Reply sent");
  }
  /**
   * set/return a nested array value
   *
   * @param array $array the array to modify
   * @param array $path  the path to the value
   * @param mixed $value (optional) value to set
   *
   * @return mixed previous value
   */
  function arrayPath(&$array, $path = array(), &$value = null)
  {
      $args = func_get_args();
      $ref = &$array;
      foreach ($path as $key) {
          if (!is_array($ref)) {
              $ref = array();
          }
          $ref = &$ref[$key];
      }
      $prev = $ref;
      if (array_key_exists(2, $args)) {
          // value param was passed -> we're setting
          $ref = $value;  // set the value
      }
      return $prev;
  }
}