<?php

namespace Rest\Service;

class ApiVtiger
{
    private $accessKey = '';
    private $user = '';
    private $url = '';
    private $token = '';
    private $login = '';
    private static $ch = null;

    public function __construct($url, $userName, $userAccessKey)
   {
      $this->url = $url;
      $this->user = $userName;
      $this->accessKey = $userAccessKey;
    
      $this->token = $this->getToken();
      $this->login = $this->getAccess();
   }

   private function getToken()
   {

    $params = ["operation" => "getchallenge", "username" => $this->user];
    $result = $this->doRequest($this->url, "GET", $params);
    
    // Ensure result is an array
    if (!is_array($result)) {
       throw new \Exception("Invalid response type in ");
    }

    return $result;
   }

   private function getAccess()
   {
      $tokenResponse = $this->token;

      // Check if tokenResponse is an array
      if (!is_array($tokenResponse)) {
         throw new \Exception("Invalid token response");
      }

      $challengeToken = $tokenResponse['result']['token'] ?? null;
      if (!$challengeToken) {
         throw new \Exception("Challenge token not found");
      }

      $generatedKey = md5($challengeToken . $this->accessKey);
      $params = ["operation" => "login", "username" => $this->user, "accessKey" => $generatedKey];
      $dataDetails = $this->doRequest($this->url, "POST", $params);
      $dataDetails['result']['crmurl']=$this->url;
      // Ensure DataDetails is an array
      if (!is_array($dataDetails)) {
         throw new \Exception("Invalid response type");
      }

      return $dataDetails;
   }

   public function createRecord($data, $module)
    {  
        $params = [
        "operation" => "create",
        "sessionName" => $this->login['result']['sessionName'],
        "element" => json_encode($data),
        "elementType" => $module
        ];

        $response = $this->doRequest($this->url, "POST", $params);
        return $response;
    }

   private function doRequest($url, $method = 'GET', $params = [])
   {
        if (self::$ch === null) {
            self::$ch = curl_init();
        }

        if ($method === "GET") {
        
            $url = $url . "?" . http_build_query($params);
            
        }

        curl_setopt(self::$ch, CURLOPT_URL, $url);

        if ($method === "POST") {
        
            curl_setopt(self::$ch, CURLOPT_POST, true);
            curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($params));
        
        } else {
            curl_setopt(self::$ch, CURLOPT_HTTPGET, true);
        }

        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, false);

        $data = curl_exec(self::$ch);

        if ($data === false) {
            throw new \Exception("Curl Error: " . curl_error(self::$ch));
        }

        $httpCode = curl_getinfo(self::$ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            throw new \Exception("Request returned HTTP code " . $httpCode);
        }

        return json_decode($data,true);
   } 
}