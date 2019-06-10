<?php 
class InstagramChatBot
{
  protected $config = array();

  public function __construct () {
    $this->config = json_decode(file_get_contents('./config.json'));
  }
  public function send() {
      $url = $this->config->url;
      $data = array('key1' => 'value1', 'key2' => 'value2');

      // use key 'http' even if you send the request to https://...
      $options = array(
        'http' => array(
          'header'  => "Content-type: application/json",
          'method'  => $this->config->method,
          'content' => json_encode($data)
        )
      );
      $context  = stream_context_create($options);
      $result = file_get_contents($url, false, $context);
      var_dump($http_response_header);
      if ($result === FALSE) { 
        $result.="False";
      }
      return $result.$http_response_header;
    }
}