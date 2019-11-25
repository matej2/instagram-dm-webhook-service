<?php

use Dotenv\Dotenv;
use InstagramAPI\Instagram;

require ROOT . '/vendor/autoload.php';
require ROOT . '/src/DmWebhookMockData.php';
require ROOT . '/src/Logger.php';

class DmWebook
{
    protected $config = array();

    public function __construct()
    {

        $this->logger = new Logger();
        (new Dotenv(ROOT))->overload();

        $this->webhooks = [[
            "url"           => getenv("WEBHOOK_URL"),
            "method"        => getenv("WEBHOOK_METHOD"),
            "returnStatus"  => getenv("WEBHOOK_RETURN_STATUS"),
            "keywords"      => getenv("WEBHOOK_KEYWORDS"),
            "blacklist"     => getenv("WEBHOOK_BLACK_LIST"),
            "perMonthLimit" => getenv("WEBHOOK_PER_MONTH_LIMIT"),
            "perHourLimit"  => getenv("WEBHOOK_PER_HOUR_LIMIT"),
            "path"          => getenv("WEBHOOK_PATH"),
            "wait"          => getenv("WEBHOOK_WAIT")
        ]];
        $this->debug    = getenv("DEBUG");
        $this->username = getenv("USERNAME");
        $this->password = getenv("PASSWORD");

        if ($this->debug == "false") {
            $this->ig = new Instagram();
            $isLoggedIn = $this->ig->login($this->username, $this->password);
            if(!$isLoggedIn) {
            	$this->logger->log("Wrong email or password.");
            }
        }

        $this->currConfInd = 0;

        // accept all pending messages
        if(isset($this->ig) && isset($this->ig->direct) && $this->ig->direct->getPendingInbox() && $this->ig->direct->getPendingInbox()->getInbox() && $this->ig->direct->getPendingInbox()->getInbox()->getThreads()) {
          $pendingInbox = $this->ig->direct->getPendingInbox()->getInbox()->getThreads();
          if (sizeof($pendingInbox) > 0)
            $this->ig->direct->approvePendingThreads($pendingInbox);
        }
    }

    public function sendWebhook($message, $callback = null)
    {
        $config = $this->getCurrWebhook();
        $url = $config["url"];


        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header' => "Content-type: application/json",
                'method' => $config["method"],
                'content' => json_encode($message)
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        $path = explode(".", $config["path"]);
        $whResponse = json_decode($result, true);

        $val = $this->arrayPath($whResponse, $path);

        if ($callback) {
            $callback();
        }
        return array(
            "http_response_header" => $http_response_header,
            "result" => $val
        );
    }


    public function getWebhooks()
    {
        return $this->webhooks;
    }

    public function getCurrWebhook()
    {
        return $this->webhooks[$this->currConfInd] ? $this->webhooks[$this->currConfInd] : false;
    }

    public function getUser()
    {
        return $this->username;
    }


    public function isBlacklisted($input)
    {
        $isBlacklisted = false;
        foreach ($input as $word) {
            $isBlacklisted &= strpos($this->getCurrWebhook()->blacklist, $input);
        }
        return $isBlacklisted;
    }

    public function isHourlLimitExceded()
    {
        return false;
    }

    public function processMessage($input)
    {
        $config = $this->getCurrWebhook();
        if (!isset($config["keywords"])) {
            $this->logger->log("Keywords missing. Check config");
            return false;
        }

        $this->logger->log("Reading keywords");
        $words = explode(",", $config["keywords"]);

        foreach ($words as $word) {
            $word = trim($word);

            $this->logger->log("Checking message: " . $input["message"] . " for keywords: " . $word);
            $regex = "/(^|\W)" . $word . "($|\W)/i";

            if (isset($input["message"]) && preg_match($regex, $input["message"]) !== false && preg_match($regex, $input["message"]) !== 0) {
                $this->logger->log("Found match for " . $input["message"] . ", waitlisting...");
                //return $this->waitlistMessage($config,$input);
                $response = $this->sendWebhook($input);

                $this->logger->log("Response from chat bot: " . strval($response["result"]));

                if ($this->checkWebhookResponse($response)) {

                    sleep($config["wait"]);
                    $this->sendWebhookReply($input, $response);
                }
            }
        }
    }

    public function waitlistMessage($message)
    {
        $response = $this->sendWebhook($message);
        if ($response)
            return true;
    }

    public function getLastDM()
    {
        if ($this->debug == "true") {
            $this->logger->log("Debugger mode");
            return getMockMessages();
        } else {

            $maxId = null;
            $threads = array();
            do {
              if(isset($this->ig) && isset($this->ig->direct) && $this->ig->direct->getInbox($maxId)) {
                $direct = $this->ig->direct->getInbox($maxId);

                $threads = array_merge($threads, $direct->getInbox()->getThreads());
                $maxId = $direct->getInbox()->getOldestCursor();

              } else {
                $this->logger->log("\$direct->getInbox() is null, exiting");
                break;
              }
            } while ($maxId !== null);

            /*
            TODO: unseen message check

            if(!$this->ig->direct->isUnseenCount()) {
              return false;
            }
            */

            $inbox = array();
            $msg = array();

            foreach ($threads as $thread) {

                $lastMsg = $thread->getLastPermanentItem();

                $user = $this->getUser();
                $this->logger->log($lastMsg->getUserId() . " != " . $this->ig->people->getUserIdForName($user));
                if ($lastMsg->getUserId() != $this->ig->people->getUserIdForName($user)) {
                    $msg = array(
                        "message" => $lastMsg->getText(),
                        "timestamp" => $lastMsg->getTimestamp(),
                        "threadId" => $thread->getThreadId()
                    );
                    array_push($inbox, $msg);
                }
            }
            return $inbox;
        }

    }

    public function checkWebhookResponse($response)
    {
        $config = $this->getCurrWebhook();

        $this->getCurrWebhook();
        if (!strpos($response["http_response_header"][0], strval($config["returnStatus"]))) {
            $this->logger->log("Reponse status does not match target status from config. Expected " . $config["returnStatus"] . " but got " . $response["http_response_header"][0]);
            return false;
        }
        if (!$response["result"]) {
            $this->logger->log("Error: Webhook response is null. Path: " . $config["path"]);
            return false;
        }
        return true;
    }

    public function sendWebhookReply($input, $response)
    {

        if ($this->debug == "false")
            $this->ig->direct->sendText(array("thread" => $input["threadId"]), $response["result"]);
        //$this->logger->log("Sending reply: ".$response["result"]);
        else
            $this->logger->log("Test: Reply sent");
    }

    /**
     * set/return a nested array value
     *
     * @param array $array the array to modify
     * @param array $path the path to the value
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

    public function hasNextConf()
    {
        $this->currConfInd++;
        return ($this->currConfInd < sizeof($this->getWebhooks())) ? true : false;
    }
}
