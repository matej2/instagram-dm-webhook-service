<?php
class Logger {
  public function __construct() {
    set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext) {
      $this->log($errno." ".$errstr." In file: ".$errfile." on line: ".$errline." | ".json_encode($errcontext));
    });
  }

  public function log($str) {

    $log  = $this->getTimestamp().$str."".PHP_EOL;
    
    echo $log;

    if(!file_exists(ROOT."/logs")) {
      mkdir(ROOT."/logs");
      chmod(ROOT."/logs", 0750);
    }

    file_put_contents($this->getFileName(), $log, FILE_APPEND);
  }

  public function getFileName() {
    return ROOT.'/logs/'.date("j-n-Y").'.log';
  }

  public function getTimestamp() {
    return "[".date("j-n-Y G:i")."] ";
  }
}