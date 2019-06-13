<?php 
class InstagramChatBot
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
}